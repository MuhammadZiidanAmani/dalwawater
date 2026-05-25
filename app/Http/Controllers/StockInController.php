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
        ]);

        DB::transaction(function () use ($data): void {
            StockIn::create($data);

            Product::whereKey($data['product_id'])->increment('stok', $data['qty']);
        });

        return redirect()->route('dashboard', ['page' => 'stockin'])->with('success', 'Barang masuk tersimpan dan stok bertambah.');
    }
}
