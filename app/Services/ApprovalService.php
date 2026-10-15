<?php

namespace App\Services;

use App\Models\ApprovalRequest;
use App\Models\AuditLog;
use App\Models\Category;
use App\Models\Expense;
use App\Models\Product;
use App\Models\StockIn;
use App\Models\Supplier;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use RuntimeException;

class ApprovalService
{
    public function submit(Request $request, Model $subject, string $action, array $payload = [], array $fileFields = []): ApprovalRequest
    {
        $alreadyPending = ApprovalRequest::query()
            ->where('requester_id', $request->user()->id)
            ->where('subject_type', $subject->getMorphClass())
            ->where('subject_id', $subject->getKey())
            ->where('action', $action)
            ->where('status', 'pending')
            ->exists();

        if ($alreadyPending) {
            throw ValidationException::withMessages([
                'approval' => 'Permintaan untuk data ini masih menunggu persetujuan admin.',
            ]);
        }

        $files = [];
        foreach ($fileFields as $field) {
            if (! $request->hasFile($field)) {
                continue;
            }

            $uploaded = $request->file($field);
            $files[$field] = [
                'path' => $uploaded->store('approval-requests'),
                'name' => $uploaded->getClientOriginalName(),
            ];
        }

        $approval = ApprovalRequest::create([
            'requester_id' => $request->user()->id,
            'subject_type' => $subject->getMorphClass(),
            'subject_id' => $subject->getKey(),
            'action' => $action,
            'payload' => $payload ?: null,
            'files' => $files ?: null,
            'status' => 'pending',
        ]);

        AuditLog::record('approval.requested', $approval, [
            'action' => $action,
            'subject_type' => class_basename($subject),
            'subject_id' => $subject->getKey(),
        ]);

        return $approval;
    }

    public function approve(ApprovalRequest $approval, User $reviewer, ?string $notes = null): void
    {
        abort_unless($reviewer->isAdmin(), 403);

        $processed = DB::transaction(function () use ($approval, $reviewer, $notes): ApprovalRequest {
            $lockedApproval = ApprovalRequest::lockForUpdate()->findOrFail($approval->id);
            $this->ensurePending($lockedApproval);
            $lockedApproval->load('subject');
            $subject = $lockedApproval->subject;
            if (! $subject) {
                throw ValidationException::withMessages(['approval' => 'Data yang diminta sudah tidak tersedia.']);
            }

            $this->execute($lockedApproval, $subject, $reviewer);
            $lockedApproval->update([
                'status' => 'approved',
                'reviewer_id' => $reviewer->id,
                'review_notes' => $notes,
                'reviewed_at' => now(),
            ]);

            AuditLog::record('approval.approved', $lockedApproval, [
                'action' => $lockedApproval->action,
                'subject_type' => class_basename($subject),
                'subject_id' => $subject->getKey(),
            ]);

            return $lockedApproval;
        });

        $this->cleanupFiles($processed);
    }

    public function reject(ApprovalRequest $approval, User $reviewer, ?string $notes = null): void
    {
        abort_unless($reviewer->isAdmin(), 403);

        $processed = DB::transaction(function () use ($approval, $reviewer, $notes): ApprovalRequest {
            $lockedApproval = ApprovalRequest::lockForUpdate()->findOrFail($approval->id);
            $this->ensurePending($lockedApproval);
            $lockedApproval->update([
                'status' => 'rejected',
                'reviewer_id' => $reviewer->id,
                'review_notes' => $notes,
                'reviewed_at' => now(),
            ]);

            AuditLog::record('approval.rejected', $lockedApproval, ['action' => $lockedApproval->action]);

            return $lockedApproval;
        });

        $this->cleanupFiles($processed);
    }

    private function execute(ApprovalRequest $approval, Model $subject, User $reviewer): void
    {
        match (true) {
            $subject instanceof Product => $this->product($approval, $subject),
            $subject instanceof Category => $this->category($approval, $subject),
            $subject instanceof Supplier => $this->supplier($approval, $subject),
            $subject instanceof Expense => $this->expense($approval, $subject),
            $subject instanceof StockIn => $this->stockIn($approval, $subject),
            $subject instanceof Transaction => $this->transaction($approval, $subject, $reviewer),
            default => throw new RuntimeException('Jenis persetujuan tidak didukung.'),
        };
    }

    private function product(ApprovalRequest $approval, Product $product): void
    {
        if ($approval->action === 'delete') {
            if ($product->transactionDetails()->exists()) {
                $product->update(['status' => 'inactive']);
            } else {
                if ($product->foto_produk) {
                    Storage::disk('public')->delete($product->foto_produk);
                }
                $product->delete();
            }

            return;
        }

        $data = $approval->payload ?? [];
        if ($path = $this->promoteFile($approval, 'foto_produk', 'products', 'public')) {
            if ($product->foto_produk) {
                Storage::disk('public')->delete($product->foto_produk);
            }
            $data['foto_produk'] = $path;
        }
        $product->update($data);
    }

    private function category(ApprovalRequest $approval, Category $category): void
    {
        if ($approval->action === 'delete') {
            if (Product::where('kategori', $category->nama_kategori)->exists()) {
                throw ValidationException::withMessages(['approval' => 'Kategori masih dipakai produk.']);
            }
            $category->delete();

            return;
        }

        $oldName = $category->nama_kategori;
        $category->update($approval->payload ?? []);
        Product::where('kategori', $oldName)->update(['kategori' => $category->nama_kategori]);
    }

    private function supplier(ApprovalRequest $approval, Supplier $supplier): void
    {
        $approval->action === 'delete'
            ? $supplier->delete()
            : $supplier->update($approval->payload ?? []);
    }

    private function expense(ApprovalRequest $approval, Expense $expense): void
    {
        if ($approval->action === 'delete') {
            if ($expense->attachment) {
                Storage::disk('local')->delete($expense->attachment);
            }
            $expense->delete();

            return;
        }

        $data = $approval->payload ?? [];
        if ($path = $this->promoteFile($approval, 'attachment', 'expenses')) {
            if ($expense->attachment) {
                Storage::disk('local')->delete($expense->attachment);
            }
            $data['attachment'] = $path;
        }
        $expense->update($data);
    }

    private function stockIn(ApprovalRequest $approval, StockIn $stockIn): void
    {
        $oldProduct = Product::lockForUpdate()->find($stockIn->product_id);
        if ($oldProduct) {
            $oldProduct->update(['stok' => max(0, $oldProduct->stok - $stockIn->qty)]);
        }

        if ($approval->action === 'delete') {
            foreach (['nota_pembelian', 'surat_jalan'] as $field) {
                if ($stockIn->{$field}) {
                    Storage::disk('local')->delete($stockIn->{$field});
                }
            }
            $stockIn->delete();

            return;
        }

        $data = $approval->payload ?? [];
        foreach (['nota_pembelian', 'surat_jalan'] as $field) {
            if ($path = $this->promoteFile($approval, $field, 'stock_ins')) {
                if ($stockIn->{$field}) {
                    Storage::disk('local')->delete($stockIn->{$field});
                }
                $data[$field] = $path;
            }
        }

        $stockIn->update(collect($data)->only([
            'invoice_number', 'tanggal', 'product_id', 'qty', 'harga_beli', 'harga_jual',
            'discount_percent', 'discount_amount', 'subtotal', 'expired_at', 'barcode',
            'supplier', 'keterangan', 'nota_pembelian', 'surat_jalan',
        ])->all());

        $newProduct = Product::lockForUpdate()->findOrFail($data['product_id']);
        $updates = collect($data)->only(['harga_modal', 'harga_jual', 'kode_barang'])
            ->filter(fn ($value) => $value !== null && $value !== '')->all();
        if ($updates !== []) {
            $newProduct->update($updates);
        }
        $newProduct->increment('stok', (int) $data['qty']);
    }

    private function transaction(ApprovalRequest $approval, Transaction $transaction, User $reviewer): void
    {
        $transaction = Transaction::with('details.product')->lockForUpdate()->findOrFail($transaction->id);
        if ($transaction->isVoid()) {
            throw ValidationException::withMessages(['approval' => 'Transaksi sudah void.']);
        }

        if ($approval->action === 'delete') {
            foreach ($transaction->details as $detail) {
                $detail->product?->increment('stok', $detail->qty);
            }
            $transaction->update([
                'status' => 'void',
                'voided_at' => now(),
                'voided_by' => $reviewer->id,
                'void_reason' => ($approval->payload['void_reason'] ?? null) ?: 'Penghapusan disetujui admin.',
            ]);

            return;
        }

        $data = $approval->payload ?? [];
        foreach ($transaction->details as $detail) {
            $detail->product?->increment('stok', $detail->qty);
        }

        $total = 0;
        $discountTotal = 0;
        $newDetails = [];
        foreach ($data['items'] as $item) {
            $product = Product::lockForUpdate()->findOrFail($item['product_id']);
            $qty = (int) $item['qty'];
            if (! $product->isActive() || $product->stok < $qty) {
                throw ValidationException::withMessages(['approval' => "Stok {$product->nama_barang} tidak cukup atau produk nonaktif."]);
            }
            $lineDiscount = min((int) ($item['discount_value'] ?? 0), $product->harga_jual);
            $subtotal = max(0, $product->harga_jual - $lineDiscount) * $qty;
            $total += $subtotal;
            $discountTotal += $lineDiscount * $qty;
            $newDetails[] = compact('product', 'qty', 'subtotal') + ['harga' => $product->harga_jual];
        }

        $paymentType = $data['payment_type'];
        $received = $transaction->payment_status === 'paid' && $paymentType === 'cash'
            ? (int) ($data['uang_diterima'] ?? 0)
            : ($transaction->payment_status === 'paid' ? $total : 0);
        if ($transaction->payment_status === 'paid' && $paymentType === 'cash' && $received < $total) {
            throw ValidationException::withMessages(['approval' => 'Uang diterima kurang dari total transaksi.']);
        }

        $transaction->details()->delete();
        foreach ($newDetails as $detail) {
            $transaction->details()->create([
                'product_id' => $detail['product']->id,
                'qty' => $detail['qty'],
                'harga' => $detail['harga'],
                'subtotal' => $detail['subtotal'],
            ]);
            $detail['product']->decrement('stok', $detail['qty']);
        }
        $transaction->update([
            'created_at' => Carbon::parse($data['created_at']),
            'customer_name' => $data['customer_name'] ?? null,
            'payment_type' => $paymentType,
            'uang_diterima' => $received,
            'total' => $total,
            'discount_total' => $discountTotal,
            'kembalian' => $paymentType === 'cash' ? max(0, $received - $total) : 0,
        ]);
    }

    private function promoteFile(ApprovalRequest $approval, string $field, string $directory, string $disk = 'local'): ?string
    {
        $file = $approval->files[$field] ?? null;
        if (! $file || ! Storage::disk('local')->exists($file['path'])) {
            return null;
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $target = $directory.'/'.uniqid($field.'-', true).($extension ? '.'.$extension : '');
        Storage::disk($disk)->put($target, Storage::disk('local')->get($file['path']));

        return $target;
    }

    private function cleanupFiles(ApprovalRequest $approval): void
    {
        foreach ($approval->files ?? [] as $file) {
            if (isset($file['path'])) {
                Storage::disk('local')->delete($file['path']);
            }
        }
    }

    private function ensurePending(ApprovalRequest $approval): void
    {
        if (! $approval->isPending()) {
            throw ValidationException::withMessages(['approval' => 'Permintaan ini sudah diproses.']);
        }
    }
}
