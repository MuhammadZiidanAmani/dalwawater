<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Rap2hpoutre\FastExcel\FastExcel;

class TransactionController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.qty' => ['required', 'integer', 'min:1'],
            'payment_type' => ['nullable', 'required_unless:payment_status,pending', 'in:cash,transfer'],
            'discount_per_product' => ['nullable', 'integer', 'min:0'],
            'uang_diterima' => ['nullable', 'integer', 'min:0'],
            'bank_name' => ['nullable', 'string', 'max:100'],
            'reference_number' => ['nullable', 'string', 'max:150'],
            'payment_status' => ['required', 'in:paid,pending'],
            'customer_name' => ['nullable', 'string', 'max:255'],
        ]);

        $transaction = DB::transaction(function () use ($data) {
            $total = 0;
            $details = [];
            $discountPerProduct = (int) ($data['discount_per_product'] ?? 0);

            foreach ($data['items'] as $item) {
                $product = Product::lockForUpdate()->findOrFail($item['product_id']);

                if (! $product->isActive()) {
                    throw ValidationException::withMessages([
                        'items' => "{$product->nama_barang} sedang nonaktif.",
                    ]);
                }

                if ($product->stok < $item['qty']) {
                    throw ValidationException::withMessages([
                        'items' => "Stok {$product->nama_barang} tidak cukup. Tersedia {$product->stok} {$product->satuan}.",
                    ]);
                }

                $hargaSetelahDiskon = max(0, $product->harga_jual - $discountPerProduct);
                $subtotal = $hargaSetelahDiskon * $item['qty'];
                $total += $subtotal;
                $details[] = [
                    'product' => $product,
                    'qty' => $item['qty'],
                    'harga' => $product->harga_jual,
                    'subtotal' => $subtotal,
                ];
            }

            $isPending = $data['payment_status'] === 'pending';
            $paymentType = $isPending ? null : $data['payment_type'];
            $uangDiterima = $paymentType === 'cash' ? (int) ($data['uang_diterima'] ?? 0) : ($isPending ? 0 : $total);

            if ($paymentType === 'cash' && $uangDiterima < $total) {
                throw ValidationException::withMessages([
                    'uang_diterima' => 'Uang diterima harus sama dengan atau lebih besar dari total transaksi.',
                ]);
            }

            $transaction = Transaction::create([
                'kode_transaksi' => $this->nextCode(),
                'user_id' => auth()->id(),
                'customer_name' => $data['customer_name'] ?? null,
                'total' => $total,
                'payment_type' => $paymentType,
                'uang_diterima' => $uangDiterima,
                'kembalian' => max(0, $uangDiterima - $total),
                'bank_name' => $paymentType === 'transfer' ? ($data['bank_name'] ?? null) : null,
                'reference_number' => $paymentType === 'transfer' ? ($data['reference_number'] ?? null) : null,
                'payment_status' => $isPending ? 'pending' : 'paid',
                'paid_at' => $isPending ? null : now(),
                'settled_by' => $isPending ? null : auth()->id(),
            ]);

            foreach ($details as $detail) {
                $transaction->details()->create([
                    'product_id' => $detail['product']->id,
                    'qty' => $detail['qty'],
                    'harga' => $detail['harga'],
                    'subtotal' => $detail['subtotal'],
                ]);

                $detail['product']->decrement('stok', $detail['qty']);
            }

            return $transaction;
        });

        return redirect()->route('transactions.receipt', $transaction)->with('success', 'Transaksi berhasil disimpan.');
    }

    public function receipt(Transaction $transaction): View
    {
        $this->authorizeReceipt($transaction);

        return view('transactions.receipt', [
            'transaction' => $transaction->load(['user', 'details.product']),
        ]);
    }

    public function destroy(Transaction $transaction): RedirectResponse
    {
        if (! auth()->user()->isAdmin()) {
            abort(403);
        }

        DB::transaction(function () use ($transaction) {
            // Restore stock
            foreach ($transaction->details as $detail) {
                if ($detail->product) {
                    $detail->product->increment('stok', $detail->qty);
                }
            }

            // Delete details and transaction
            $transaction->details()->delete();
            $transaction->delete();
        });

        return redirect()->back()->with('success', 'Transaksi berhasil dihapus dan stok dikembalikan.');
    }

    public function settle(Request $request, Transaction $transaction): RedirectResponse
    {
        $this->authorizeReceipt($transaction);

        $data = $request->validate([
            'payment_type' => ['required', 'in:cash,transfer'],
            'uang_diterima' => ['nullable', 'integer', 'min:0'],
            'bank_name' => ['nullable', 'string', 'max:100'],
            'reference_number' => ['nullable', 'string', 'max:150'],
        ]);

        DB::transaction(function () use ($data, $transaction): void {
            $lockedTransaction = Transaction::lockForUpdate()->findOrFail($transaction->id);

            if ($lockedTransaction->payment_status !== 'pending') {
                throw ValidationException::withMessages([
                    'payment_status' => 'Transaksi ini sudah lunas dan tidak dapat diubah kembali.',
                ]);
            }

            $uangDiterima = $data['payment_type'] === 'cash'
                ? (int) ($data['uang_diterima'] ?? 0)
                : $lockedTransaction->total;

            if ($data['payment_type'] === 'cash' && $uangDiterima < $lockedTransaction->total) {
                throw ValidationException::withMessages([
                    'uang_diterima' => 'Uang diterima harus sama dengan atau lebih besar dari total transaksi.',
                ]);
            }

            $lockedTransaction->update([
                'payment_type' => $data['payment_type'],
                'payment_status' => 'paid',
                'uang_diterima' => $uangDiterima,
                'kembalian' => max(0, $uangDiterima - $lockedTransaction->total),
                'bank_name' => $data['payment_type'] === 'transfer' ? ($data['bank_name'] ?? null) : null,
                'reference_number' => $data['payment_type'] === 'transfer' ? ($data['reference_number'] ?? null) : null,
                'paid_at' => now(),
                'settled_by' => auth()->id(),
            ]);
        });

        return redirect()
            ->route('dashboard', ['page' => 'history'])
            ->with('success', 'Pembayaran berhasil dilunasi.');
    }

    private function nextCode(): string
    {
        $prefix = 'TRX-'.Carbon::now()->format('ymd').'-';
        $countToday = Transaction::whereDate('created_at', Carbon::today())->lockForUpdate()->count() + 1;

        return $prefix.str_pad((string) $countToday, 4, '0', STR_PAD_LEFT);
    }

    private function authorizeReceipt(Transaction $transaction): void
    {
        $user = auth()->user();

        if (! $user->isAdmin() && $transaction->user_id !== $user->id) {
            abort(403);
        }
    }

    private function getFilteredTransactions(Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $selectedUserId = $request->user()->isAdmin() ? $request->query('user_id') : null;
        $selectedPaymentStatus = $request->query('payment_status');

        $query = Transaction::visibleTo($request->user())->with(['user', 'details.product'])->latest();

        if ($startDate && $endDate) {
            $start = Carbon::parse($startDate)->startOfDay();
            $end = Carbon::parse($endDate)->endOfDay();
            $query->whereBetween('created_at', [$start, $end]);
        }

        if ($selectedUserId) {
            $query->where('user_id', $selectedUserId);
        }

        if ($selectedPaymentStatus) {
            $query->where('payment_status', $selectedPaymentStatus);
        }

        return $query->get();
    }

    public function exportExcel(Request $request)
    {
        $transactions = $this->getFilteredTransactions($request);
        $filename = 'riwayat-transaksi-'.Carbon::now()->format('Ymd-His').'.xlsx';

        $rows = [];
        foreach ($transactions as $transaction) {
            $details = $transaction->details->map(fn ($d) => $d->product?->nama_barang.' ('.$d->qty.')')->implode('; ');
            $rows[] = [
                'Kode Transaksi' => $transaction->kode_transaksi,
                'Tanggal' => $transaction->created_at->format('Y-m-d H:i:s'),
                'Kasir' => $transaction->user?->name ?? 'Unknown',
                'Pelanggan' => $transaction->customer_name ?? '-',
                'Metode' => $transaction->payment_type,
                'Total' => $transaction->total,
                'Status' => $transaction->payment_status,
                'Item Detail' => $details,
            ];
        }

        return (new FastExcel(collect($rows)))->download($filename);
    }

    public function exportPdf(Request $request)
    {
        $transactions = $this->getFilteredTransactions($request);
        $pdf = Pdf::loadView('transactions.pdf', ['transactions' => $transactions]);

        return $pdf->download('riwayat-transaksi-'.Carbon::now()->format('Ymd-His').'.pdf');
    }
}
