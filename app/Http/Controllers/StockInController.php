<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockIn;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockInController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'tanggal' => ['required', 'date'],
            'product_id' => ['required', 'exists:products,id'],
            'qty' => ['required', 'integer', 'min:1'],
            'supplier' => ['nullable', 'string', 'max:150'],
            'keterangan' => ['nullable', 'string', 'max:1000'],
            'nota_pembelian' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'surat_jalan' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ]);

        if ($request->hasFile('nota_pembelian')) {
            $data['nota_pembelian'] = $request->file('nota_pembelian')->store('stock_ins', 'public');
        }

        if ($request->hasFile('surat_jalan')) {
            $data['surat_jalan'] = $request->file('surat_jalan')->store('stock_ins', 'public');
        }

        DB::transaction(function () use ($data): void {
            StockIn::create($data);

            Product::whereKey($data['product_id'])->increment('stok', $data['qty']);
        });

        return redirect()->route('dashboard', ['page' => 'stockin'])->with('success', 'Barang masuk tersimpan dan stok bertambah.');
    }
}
