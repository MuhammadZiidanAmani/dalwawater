<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Riwayat Transaksi</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <h2>Laporan Riwayat Transaksi</h2>
    <p>Tanggal Cetak: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
    
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Tanggal</th>
                <th>Kasir</th>
                <th>Metode</th>
                <th>Status</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotal = 0; @endphp
            @foreach($transactions as $index => $transaction)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $transaction->kode_transaksi }}</td>
                <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $transaction->user?->name ?? 'Unknown' }}</td>
                <td>{{ $transaction->payment_type }}</td>
                <td>{{ $transaction->payment_status ?? 'paid' }}</td>
                <td class="text-right">Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
            </tr>
            @php $grandTotal += $transaction->total; @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="6" class="text-right">Grand Total</th>
                <th class="text-right">Rp {{ number_format($grandTotal, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>
</body>
</html>
