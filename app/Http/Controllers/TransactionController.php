<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\CashierShift;
use App\Models\PaymentVerification;
use App\Models\Product;
use App\Models\Transaction;
use App\Services\ThermalReceiptPrinter;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Rap2hpoutre\FastExcel\FastExcel;
use Throwable;

class TransactionController extends Controller
{
    public function store(Request $request, ThermalReceiptPrinter $thermalPrinter): RedirectResponse
    {
        $data = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.qty' => ['required', 'integer', 'min:1'],
            'items.*.discount_type' => ['nullable', 'in:amount,percent'],
            'items.*.discount_value' => ['nullable', 'integer', 'min:0'],
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
            $totalBeforeDiscount = 0;
            $discountTotal = 0;
            $details = [];
            $discountPerProduct = (int) ($data['discount_per_product'] ?? 0);
            $usesLineDiscount = collect($data['items'])->contains(fn ($item) => array_key_exists('discount_value', $item) || array_key_exists('discount_type', $item));
            $cashierShift = $this->currentShiftForTransaction();

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

                $lineDiscountType = $item['discount_type'] ?? 'amount';
                $lineDiscountValue = array_key_exists('discount_value', $item) ? (int) $item['discount_value'] : $discountPerProduct;
                $lineDiscount = $lineDiscountType === 'percent'
                    ? (int) round($product->harga_jual * min($lineDiscountValue, 100) / 100)
                    : $lineDiscountValue;

                if ($lineDiscountValue > 100 && $lineDiscountType === 'percent') {
                    throw ValidationException::withMessages([
                        'items' => "Diskon persen {$product->nama_barang} tidak boleh lebih dari 100%.",
                    ]);
                }

                if ($lineDiscount > $product->harga_jual) {
                    throw ValidationException::withMessages([
                        'discount_per_product' => "Diskon tidak boleh melebihi harga jual {$product->nama_barang}.",
                    ]);
                }

                $hargaSetelahDiskon = $product->harga_jual - $lineDiscount;
                $subtotal = $hargaSetelahDiskon * $item['qty'];
                $totalBeforeDiscount += $product->harga_jual * $item['qty'];
                $discountTotal += $lineDiscount * $item['qty'];
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
                'cashier_shift_id' => $cashierShift?->id,
                'customer_name' => $data['customer_name'] ?? null,
                'total' => $total,
                'discount_per_product' => $usesLineDiscount ? 0 : $discountPerProduct,
                'discount_total' => $discountTotal,
                'payment_type' => $paymentType,
                'uang_diterima' => $uangDiterima,
                'kembalian' => max(0, $uangDiterima - $total),
                'bank_name' => $paymentType === 'transfer' ? ($data['bank_name'] ?? null) : null,
                'reference_number' => $paymentType === 'transfer' ? ($data['reference_number'] ?? null) : null,
                'payment_status' => $isPending ? 'pending' : 'paid',
                'status' => 'completed',
                'paid_at' => $isPending ? null : now(),
                'settled_by' => $isPending ? null : auth()->id(),
                'payment_verified_at' => $paymentType === 'transfer' && auth()->user()->isAdmin() ? now() : null,
                'payment_verified_by' => $paymentType === 'transfer' && auth()->user()->isAdmin() ? auth()->id() : null,
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

            AuditLog::record('transaction.created', $transaction, [
                'kode_transaksi' => $transaction->kode_transaksi,
                'total_before_discount' => $totalBeforeDiscount,
                'discount_total' => $discountTotal,
                'total' => $transaction->total,
                'payment_status' => $transaction->payment_status,
                'payment_type' => $transaction->payment_type,
            ]);

            return $transaction;
        });

        $redirect = redirect()
            ->route('dashboard', ['page' => 'transactions'])
            ->with('success', 'Transaksi berhasil disimpan.');

        if (config('thermal.auto_print')) {
            try {
                $thermalPrinter->print($transaction);
                $redirect->with('success', 'Transaksi berhasil disimpan dan struk thermal dicetak.');
            } catch (Throwable $exception) {
                Log::warning('Thermal receipt print failed.', [
                    'transaction_id' => $transaction->id,
                    'kode_transaksi' => $transaction->kode_transaksi,
                    'error' => $exception->getMessage(),
                ]);

                $redirect->with('warning', 'Transaksi tersimpan, tetapi struk thermal gagal dicetak. Cek koneksi printer X58MP II.');
            }
        }

        return $redirect;
    }

    public function receipt(Transaction $transaction): View
    {
        $this->authorizeReceipt($transaction);

        return view('transactions.receipt', [
            'transaction' => $transaction->load(['user', 'details.product']),
        ]);
    }

    public function update(Request $request, Transaction $transaction): RedirectResponse
    {
        $this->authorizeTransactionUpdate($transaction);

        $data = $request->validate([
            'created_at' => ['required', 'date'],
            'customer_name' => ['nullable', 'string', 'max:255'],
            'payment_type' => ['required', 'in:cash,transfer'],
            'uang_diterima' => ['nullable', 'integer', 'min:0'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.qty' => ['required', 'integer', 'min:1'],
            'items.*.discount_value' => ['nullable', 'integer', 'min:0'],
        ]);

        DB::transaction(function () use ($data, $transaction): void {
            $lockedTransaction = Transaction::with('details.product')->lockForUpdate()->findOrFail($transaction->id);

            $this->authorizeTransactionUpdate($lockedTransaction);

            if ($lockedTransaction->isVoid()) {
                throw ValidationException::withMessages([
                    'items' => 'Transaksi void tidak dapat diubah.',
                ]);
            }

            foreach ($lockedTransaction->details as $detail) {
                if ($detail->product) {
                    $detail->product()->lockForUpdate()->first()?->increment('stok', $detail->qty);
                }
            }

            $total = 0;
            $discountTotal = 0;
            $newDetails = [];

            foreach ($data['items'] as $item) {
                $product = Product::lockForUpdate()->findOrFail($item['product_id']);
                $qty = (int) $item['qty'];

                if (! $product->isActive()) {
                    throw ValidationException::withMessages([
                        'items' => "{$product->nama_barang} sedang nonaktif.",
                    ]);
                }

                if ($product->stok < $qty) {
                    throw ValidationException::withMessages([
                        'items' => "Stok {$product->nama_barang} tidak cukup. Tersedia {$product->stok} {$product->satuan}.",
                    ]);
                }

                $lineDiscount = min((int) ($item['discount_value'] ?? 0), $product->harga_jual);
                $subtotal = max(0, $product->harga_jual - $lineDiscount) * $qty;

                $discountTotal += $lineDiscount * $qty;
                $total += $subtotal;
                $newDetails[] = [
                    'product' => $product,
                    'qty' => $qty,
                    'harga' => $product->harga_jual,
                    'subtotal' => $subtotal,
                ];
            }

            $paymentType = $data['payment_type'];
            $uangDiterima = $lockedTransaction->payment_status === 'paid' && $paymentType === 'cash'
                ? (int) ($data['uang_diterima'] ?? 0)
                : ($lockedTransaction->payment_status === 'paid' ? $total : 0);

            if ($lockedTransaction->payment_status === 'paid' && $paymentType === 'cash' && $uangDiterima < $total) {
                throw ValidationException::withMessages([
                    'uang_diterima' => 'Uang diterima harus sama dengan atau lebih besar dari total transaksi.',
                ]);
            }

            $lockedTransaction->details()->delete();

            foreach ($newDetails as $detail) {
                $lockedTransaction->details()->create([
                    'product_id' => $detail['product']->id,
                    'qty' => $detail['qty'],
                    'harga' => $detail['harga'],
                    'subtotal' => $detail['subtotal'],
                ]);

                $detail['product']->decrement('stok', $detail['qty']);
            }

            $lockedTransaction->update([
                'created_at' => Carbon::parse($data['created_at']),
                'customer_name' => $data['customer_name'] ?? null,
                'payment_type' => $paymentType,
                'uang_diterima' => $uangDiterima,
                'total' => $total,
                'discount_total' => $discountTotal,
                'kembalian' => $paymentType === 'cash' ? max(0, $uangDiterima - $total) : 0,
            ]);

            AuditLog::record('transaction.updated', $lockedTransaction, [
                'kode_transaksi' => $lockedTransaction->kode_transaksi,
                'total' => $total,
                'discount_total' => $discountTotal,
            ]);
        });

        return redirect()
            ->route('dashboard', ['page' => 'transaction-history'])
            ->with('success', 'Transaksi berhasil diperbarui.');
    }

    public function printThermal(Transaction $transaction, ThermalReceiptPrinter $thermalPrinter): RedirectResponse
    {
        $this->authorizeReceipt($transaction);

        try {
            $thermalPrinter->print($transaction, force: true);

            return redirect()->back()->with('success', 'Struk thermal berhasil dikirim ke printer.');
        } catch (Throwable $exception) {
            Log::warning('Thermal receipt reprint failed.', [
                'transaction_id' => $transaction->id,
                'kode_transaksi' => $transaction->kode_transaksi,
                'error' => $exception->getMessage(),
            ]);

            return redirect()->back()->with('warning', 'Struk thermal gagal dicetak. Pastikan printer X58MP II aktif dan nama printer sesuai konfigurasi.');
        }
    }

    public function destroy(Transaction $transaction): RedirectResponse
    {
        if (! auth()->user()->isAdmin()) {
            abort(403);
        }

        $data = request()->validate([
            'void_reason' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($transaction, $data) {
            $lockedTransaction = Transaction::with('details.product')->lockForUpdate()->findOrFail($transaction->id);

            if ($lockedTransaction->isVoid()) {
                throw ValidationException::withMessages([
                    'void_reason' => 'Transaksi ini sudah dihapus.',
                ]);
            }

            foreach ($lockedTransaction->details as $detail) {
                if ($detail->product) {
                    $detail->product->increment('stok', $detail->qty);
                }
            }

            $lockedTransaction->update([
                'status' => 'void',
                'voided_at' => now(),
                'voided_by' => auth()->id(),
                'void_reason' => $data['void_reason'] ?? 'Dihapus oleh admin.',
            ]);

            AuditLog::record('transaction.voided', $lockedTransaction, [
                'kode_transaksi' => $lockedTransaction->kode_transaksi,
                'reason' => $lockedTransaction->void_reason,
                'total' => $lockedTransaction->total,
            ]);
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
                'payment_verified_at' => $data['payment_type'] === 'transfer' && auth()->user()->isAdmin() ? now() : null,
                'payment_verified_by' => $data['payment_type'] === 'transfer' && auth()->user()->isAdmin() ? auth()->id() : null,
            ]);

            AuditLog::record('transaction.settled', $lockedTransaction, [
                'payment_type' => $lockedTransaction->payment_type,
                'total' => $lockedTransaction->total,
            ]);
        });

        return redirect()
            ->route('dashboard', ['page' => 'history'])
            ->with('success', 'Pembayaran berhasil dilunasi.');
    }

    public function verifyTransfer(Request $request, Transaction $transaction): RedirectResponse
    {
        abort_unless($request->user()->isAdmin(), 403);

        $data = $request->validate([
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($transaction, $data, $request): void {
            $lockedTransaction = Transaction::lockForUpdate()->findOrFail($transaction->id);

            if ($lockedTransaction->payment_type !== 'transfer' || $lockedTransaction->payment_status !== 'paid') {
                throw ValidationException::withMessages([
                    'payment_type' => 'Hanya transaksi transfer lunas yang dapat diverifikasi.',
                ]);
            }

            if ($lockedTransaction->isVoid()) {
                throw ValidationException::withMessages([
                    'payment_type' => 'Transaksi void tidak dapat diverifikasi.',
                ]);
            }

            $lockedTransaction->update([
                'payment_verified_at' => now(),
                'payment_verified_by' => $request->user()->id,
            ]);

            PaymentVerification::create([
                'transaction_id' => $lockedTransaction->id,
                'user_id' => $request->user()->id,
                'verified_at' => now(),
                'notes' => $data['notes'] ?? null,
            ]);

            AuditLog::record('payment_transfer.verified', $lockedTransaction, [
                'kode_transaksi' => $lockedTransaction->kode_transaksi,
                'notes' => $data['notes'] ?? null,
            ]);
        });

        return redirect()->back()->with('success', 'Pembayaran transfer berhasil diverifikasi.');
    }

    private function nextCode(): string
    {
        $today = Carbon::today();
        $date = $today->toDateString();
        $prefix = 'TRX-'.$today->format('ymd').'-';
        $sequence = DB::table('transaction_sequences')
            ->where('date', $date)
            ->lockForUpdate()
            ->first();

        if (! $sequence) {
            $lastNumber = Transaction::whereDate('created_at', $today)
                ->where('kode_transaksi', 'like', $prefix.'%')
                ->pluck('kode_transaksi')
                ->map(fn (string $code): int => (int) substr(strrchr($code, '-'), 1))
                ->max() ?? 0;

            try {
                DB::table('transaction_sequences')->insert([
                    'date' => $date,
                    'last_number' => $lastNumber,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } catch (QueryException) {
                //
            }

            $sequence = DB::table('transaction_sequences')
                ->where('date', $date)
                ->lockForUpdate()
                ->first();
        }

        $nextNumber = ((int) $sequence->last_number) + 1;

        DB::table('transaction_sequences')
            ->where('date', $date)
            ->update([
                'last_number' => $nextNumber,
                'updated_at' => now(),
            ]);

        return $prefix.str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);
    }

    private function currentShiftForTransaction(): ?CashierShift
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return null;
        }

        $shift = CashierShift::where('user_id', $user->id)
            ->open()
            ->latest('opened_at')
            ->first();

        if (! $shift) {
            throw ValidationException::withMessages([
                'shift' => 'Buka shift kasir terlebih dahulu sebelum membuat transaksi.',
            ]);
        }

        return $shift;
    }

    private function authorizeReceipt(Transaction $transaction): void
    {
        $user = auth()->user();

        if (! $user->isAdmin() && $transaction->user_id !== $user->id) {
            abort(403);
        }
    }

    private function authorizeTransactionUpdate(Transaction $transaction): void
    {
        $user = auth()->user();

        if (! $user->isAdmin() && $transaction->user_id !== $user->id) {
            abort(403);
        }
    }

    private function getFilteredTransactions(Request $request)
    {
        $filters = $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'payment_status' => ['nullable', 'in:paid,pending'],
            'payment_type' => ['nullable', 'in:cash,transfer'],
        ]);

        $startDate = $filters['start_date'] ?? null;
        $endDate = $filters['end_date'] ?? null;
        $selectedUserId = $request->user()->isAdmin() ? ($filters['user_id'] ?? null) : null;
        $selectedPaymentStatus = $filters['payment_status'] ?? null;
        $selectedPaymentType = $filters['payment_type'] ?? null;

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

        if ($selectedPaymentType) {
            $query->where('payment_type', $selectedPaymentType);
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
                'Status Transaksi' => $transaction->status,
                'Diskon' => $transaction->discount_total,
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
