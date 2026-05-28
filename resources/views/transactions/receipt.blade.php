@extends('layouts.print')

@section('title', 'Nota '.$transaction->kode_transaksi)

@section('content')
<main class="wrap">
    <div>
        <div class="actions">
            <a class="btn" href="{{ route('dashboard', ['page' => 'reports']) }}">Kembali</a>
            <button class="btn primary" onclick="window.print()">Print Nota</button>
        </div>
        <section class="receipt">
            <h3>Dalwa Water Tegal</h3>
            <p>Jl. Raya Tegalwangi, Rt. 13/05<br>Kec. Talang - Kab. Tegal<br>CP : 0813-9375-0612</p>
            <hr>
            <div class="line"><span>No</span><strong>{{ $transaction->kode_transaksi }}</strong></div>
            <div class="line"><span>Tanggal</span><span>{{ $transaction->created_at->format('d/m/Y H:i') }}</span></div>
            <div class="line"><span>Kasir</span><span>{{ $transaction->user->name }}</span></div>
            <hr>
            @php $totalDiscount = 0; @endphp
            @foreach ($transaction->details as $detail)
                @php
                    $normalSubtotal = $detail->harga * $detail->qty;
                    $discount = max(0, $normalSubtotal - $detail->subtotal);
                    $totalDiscount += $discount;
                @endphp
                <div class="line"><span>{{ $detail->product->nama_barang }}</span><span>{{ $detail->qty }} x {{ number_format($detail->harga, 0, ',', '.') }}</span></div>
                <div class="line"><span></span><strong>{{ number_format($detail->subtotal, 0, ',', '.') }}</strong></div>
            @endforeach
            <hr>
            <div class="line"><span>Total</span><strong>{{ number_format($transaction->total, 0, ',', '.') }}</strong></div>
            @if ($totalDiscount > 0)
                <div class="line"><span>Diskon</span><span>{{ number_format($totalDiscount, 0, ',', '.') }}</span></div>
            @endif
            <div class="line"><span>Metode</span><span>{{ $transaction->payment_type === 'cash' ? 'Tunai' : 'Transfer' }}</span></div>
            @if ($transaction->payment_type === 'transfer')
                <div class="line"><span>Bank</span><span>{{ $transaction->bank_name ?: '-' }}</span></div>
                <div class="line"><span>Ref</span><span>{{ $transaction->reference_number ?: '-' }}</span></div>
            @endif
            <div class="line"><span>Diterima</span><span>{{ number_format($transaction->uang_diterima, 0, ',', '.') }}</span></div>
            <div class="line"><span>Kembali</span><strong>{{ number_format($transaction->kembalian, 0, ',', '.') }}</strong></div>
            <hr>
            <p>Terima kasih<br>Barang yang sudah dibeli tidak dapat ditukar/dikembalikan</p>
        </section>
    </div>
</main>
@endsection
