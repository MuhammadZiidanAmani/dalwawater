<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        Product::create($this->validated($request));

        return redirect()->route('dashboard', ['page' => 'inventory'])->with('success', 'Barang berhasil ditambahkan.');
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $product->update($this->validated($request, $product));

        return redirect()->route('dashboard', ['page' => 'inventory'])->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        if ($product->transactionDetails()->exists()) {
            $product->update(['status' => 'inactive']);

            return redirect()->route('dashboard', ['page' => 'inventory'])
                ->with('success', 'Barang memiliki riwayat transaksi, status diubah menjadi nonaktif.');
        }

        $product->delete();

        return redirect()->route('dashboard', ['page' => 'inventory'])->with('success', 'Barang berhasil dihapus.');
    }

    private function validated(Request $request, ?Product $product = null): array
    {
        return $request->validate([
            'kode_barang' => [
                'required',
                'string',
                'max:50',
                Rule::unique('products', 'kode_barang')->ignore($product),
            ],
            'nama_barang' => ['required', 'string', 'max:150'],
            'kategori' => ['required', 'string', 'max:100'],
            'harga_modal' => ['required', 'integer', 'min:0'],
            'harga_jual' => ['required', 'integer', 'min:0'],
            'stok' => ['required', 'integer', 'min:0'],
            'satuan' => ['required', 'string', 'max:50'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);
    }
}
