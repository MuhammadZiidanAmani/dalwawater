<?php

namespace App\Http\Controllers;

use App\Http\Requests\StockOpnameRequest;
use App\Models\AuditLog;
use App\Models\Product;
use App\Models\StockAdjustment;
use App\Models\StockOpname;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class StockOpnameController extends Controller
{
    public function store(StockOpnameRequest $request): RedirectResponse
    {
        $data = $request->validated();

        DB::transaction(function () use ($data, $request): void {
            $product = Product::lockForUpdate()->findOrFail($data['product_id']);
            $systemStock = $product->stok;
            $actualStock = (int) $data['actual_stock'];

            $opname = StockOpname::create([
                'user_id' => $request->user()->id,
                'opname_date' => $data['opname_date'] ?? now()->toDateString(),
                'status' => 'completed',
                'notes' => $data['reason'],
                'completed_at' => now(),
            ]);

            $adjustment = StockAdjustment::create([
                'stock_opname_id' => $opname->id,
                'product_id' => $product->id,
                'user_id' => $request->user()->id,
                'system_stock' => $systemStock,
                'actual_stock' => $actualStock,
                'difference' => $actualStock - $systemStock,
                'reason' => $data['reason'],
            ]);

            $product->update(['stok' => $actualStock]);

            AuditLog::record('stock_opname.completed', $opname, [
                'product_id' => $product->id,
                'adjustment_id' => $adjustment->id,
                'system_stock' => $systemStock,
                'actual_stock' => $actualStock,
            ]);
        });

        return redirect()->route('dashboard', ['page' => 'stock-opname'])->with('success', 'Stok opname tersimpan dan stok disesuaikan.');
    }
}
