<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{
    public function exportCsv(): Response
    {
        $filename = 'laporan-penjualan-'.Carbon::now()->format('Ymd-His').'.csv';
        $transactions = Transaction::with(['user', 'details.product'])->latest()->get();

        $rows = [
            ['No Transaksi', 'Tanggal', 'Kasir', 'Produk', 'Qty', 'Harga', 'Subtotal', 'Metode', 'Total'],
        ];

        foreach ($transactions as $transaction) {
            foreach ($transaction->details as $detail) {
                $rows[] = [
                    $transaction->kode_transaksi,
                    $transaction->created_at->format('Y-m-d H:i:s'),
                    $transaction->user->name,
                    $detail->product->nama_barang,
                    $detail->qty,
                    $detail->harga,
                    $detail->subtotal,
                    $transaction->payment_type,
                    $transaction->total,
                ];
            }
        }

        $content = collect($rows)->map(function (array $row): string {
            return implode(',', array_map(fn ($value) => '"'.str_replace('"', '""', (string) $value).'"', $row));
        })->implode("\n");

        return response($content, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
