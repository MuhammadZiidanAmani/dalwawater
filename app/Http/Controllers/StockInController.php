<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Product;
use App\Models\StockIn;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class StockInController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $this->stockData($request);

        if ($request->hasFile('nota_pembelian')) {
            $data['nota_pembelian'] = $request->file('nota_pembelian')->store('stock_ins');
        }

        if ($request->hasFile('surat_jalan')) {
            $data['surat_jalan'] = $request->file('surat_jalan')->store('stock_ins');
        }

        DB::transaction(function () use ($data): void {
            $stockIn = StockIn::create(collect($data)->only([
                'invoice_number',
                'tanggal',
                'product_id',
                'qty',
                'harga_beli',
                'harga_jual',
                'discount_percent',
                'discount_amount',
                'subtotal',
                'expired_at',
                'barcode',
                'supplier',
                'keterangan',
                'nota_pembelian',
                'surat_jalan',
            ])->all());

            $productUpdates = collect($data)->only([
                'harga_modal',
                'harga_jual',
                'kode_barang',
            ])->filter(fn ($value) => $value !== null && $value !== '')->all();

            $product = Product::lockForUpdate()->findOrFail($data['product_id']);

            if ($productUpdates !== []) {
                $product->update($productUpdates);
            }

            $product->increment('stok', $data['qty']);

            AuditLog::record('stock_in.created', $stockIn, [
                'product_id' => $data['product_id'],
                'qty' => $data['qty'],
                'product_updates' => array_keys($productUpdates),
            ]);
        });

        return redirect()->route('dashboard', ['page' => 'stock'])->with('success', 'Barang masuk tersimpan dan stok bertambah.');
    }

    public function update(Request $request, StockIn $stockIn): RedirectResponse
    {
        $data = $this->stockData($request, $stockIn);

        if ($request->hasFile('nota_pembelian')) {
            if ($stockIn->nota_pembelian) {
                Storage::disk('local')->delete($stockIn->nota_pembelian);
            }

            $data['nota_pembelian'] = $request->file('nota_pembelian')->store('stock_ins');
        }

        if ($request->hasFile('surat_jalan')) {
            if ($stockIn->surat_jalan) {
                Storage::disk('local')->delete($stockIn->surat_jalan);
            }

            $data['surat_jalan'] = $request->file('surat_jalan')->store('stock_ins');
        }

        DB::transaction(function () use ($data, $stockIn): void {
            $oldProduct = Product::lockForUpdate()->findOrFail($stockIn->product_id);
            $newProduct = Product::lockForUpdate()->findOrFail($data['product_id']);
            $oldProduct->update(['stok' => max(0, $oldProduct->stok - $stockIn->qty)]);

            $stockIn->update(collect($data)->only([
                'invoice_number',
                'tanggal',
                'product_id',
                'qty',
                'harga_beli',
                'harga_jual',
                'discount_percent',
                'discount_amount',
                'subtotal',
                'expired_at',
                'barcode',
                'supplier',
                'keterangan',
                'nota_pembelian',
                'surat_jalan',
            ])->all());

            $productUpdates = collect($data)->only([
                'harga_modal',
                'harga_jual',
                'kode_barang',
            ])->filter(fn ($value) => $value !== null && $value !== '')->all();

            if ($productUpdates !== []) {
                $newProduct->update($productUpdates);
            }

            $newProduct->increment('stok', $data['qty']);

            AuditLog::record('stock_in.updated', $stockIn, [
                'product_id' => $data['product_id'],
                'qty' => $data['qty'],
            ]);
        });

        return redirect()->route('dashboard', ['page' => 'stock'])->with('success', 'Pembelian berhasil diperbarui.');
    }

    public function destroy(StockIn $stockIn): RedirectResponse
    {
        DB::transaction(function () use ($stockIn): void {
            $product = Product::lockForUpdate()->find($stockIn->product_id);

            if ($product) {
                $product->update(['stok' => max(0, $product->stok - $stockIn->qty)]);
            }

            foreach (['nota_pembelian', 'surat_jalan'] as $document) {
                if ($stockIn->{$document}) {
                    Storage::disk('local')->delete($stockIn->{$document});
                }
            }

            AuditLog::record('stock_in.deleted', $stockIn, [
                'product_id' => $stockIn->product_id,
                'qty' => $stockIn->qty,
            ]);

            $stockIn->delete();
        });

        return redirect()->route('dashboard', ['page' => 'stock'])->with('success', 'Pembelian berhasil dihapus.');
    }

    public function document(StockIn $stockIn, string $document): BinaryFileResponse
    {
        abort_unless(in_array($document, ['nota_pembelian', 'surat_jalan'], true), 404);

        $path = $stockIn->{$document};
        abort_unless($path, 404);

        $disk = Storage::disk('local');
        if ($disk->exists($path)) {
            AuditLog::record('stock_in.document_viewed', $stockIn, [
                'document' => $document,
            ]);

            return response()->file($disk->path($path));
        }

        $publicDisk = Storage::disk('public');
        abort_unless($publicDisk->exists($path), 404);

        AuditLog::record('stock_in.document_viewed', $stockIn, [
            'document' => $document,
            'legacy_public_storage' => true,
        ]);

        return response()->file($publicDisk->path($path));
    }

    private function stockData(Request $request, ?StockIn $stockIn = null): array
    {
        $data = $request->validate([
            'invoice_number' => ['nullable', 'string', 'max:100'],
            'tanggal' => ['required', 'date'],
            'product_id' => ['required', 'exists:products,id'],
            'qty' => ['required', 'integer', 'min:1'],
            'supplier' => ['nullable', 'string', 'max:150'],
            'keterangan' => ['nullable', 'string', 'max:1000'],
            'harga_modal' => ['nullable', 'integer', 'min:0'],
            'harga_beli' => ['nullable', 'integer', 'min:0'],
            'harga_jual' => ['nullable', 'integer', 'min:0'],
            'discount_percent' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'discount_amount' => ['nullable', 'integer', 'min:0'],
            'subtotal' => ['nullable', 'integer', 'min:0'],
            'kode_barang' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('products', 'kode_barang')->ignore($request->input('product_id')),
            ],
            'barcode' => ['nullable', 'string', 'max:50'],
            'stock_unlimited' => ['nullable', Rule::in(['yes', 'no'])],
            'expired_at' => ['nullable', 'date'],
            'nota_pembelian' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'surat_jalan' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ]);

        $data['harga_beli'] = (int) ($data['harga_beli'] ?? $data['harga_modal'] ?? 0);
        $data['harga_modal'] = (int) ($data['harga_modal'] ?? $data['harga_beli']);
        $data['harga_jual'] = (int) ($data['harga_jual'] ?? 0);
        $data['discount_percent'] = (float) ($data['discount_percent'] ?? 0);
        $data['discount_amount'] = (int) ($data['discount_amount'] ?? 0);
        $data['subtotal'] = (int) ($data['subtotal'] ?? max(0, ($data['harga_beli'] * $data['qty']) - $data['discount_amount']));
        $data['barcode'] = $data['barcode'] ?? $data['kode_barang'] ?? null;

        return $data;
    }
}
