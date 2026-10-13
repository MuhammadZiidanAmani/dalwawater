@extends('layouts.print')

@section('title', 'Nota '.$transaction->kode_transaksi)

@section('content')
@php
    $pdfMode = $pdfMode ?? false;
    $selectedSize = $receiptLayout['size'] ?? '58mm';
    $isThermalSafe = in_array($selectedSize, ['58mm', '80mm'], true);
    $thermalWidth = $selectedSize === '80mm' ? 42 : 32;
    $money = fn ($value) => number_format((int) $value, 0, ',', '.');
    $lineText = function (string $label, string $value) use ($thermalWidth): string {
        $label = trim($label);
        $value = trim($value);
        $gap = max(1, $thermalWidth - mb_strlen($label) - mb_strlen($value));

        return $label.str_repeat(' ', $gap).$value;
    };
    $centerText = function (string $value) use ($thermalWidth): string {
        $value = trim($value);
        $left = max(0, intdiv($thermalWidth - mb_strlen($value), 2));

        return str_repeat(' ', $left).$value;
    };
    $separator = str_repeat('-', $thermalWidth);
    $wrapText = fn (?string $value) => explode("\n", wordwrap(trim((string) $value), $thermalWidth, "\n", true));
    $receiptSettings = \App\Models\AppSetting::receiptSettings();
    $enabled = fn (string $key) => \App\Models\AppSetting::enabled($receiptSettings, $key);
    $headerLines = array_filter([
        $receiptSettings['receipt_address_line_1'] ?? '',
        $receiptSettings['receipt_address_line_2'] ?? '',
        $receiptSettings['receipt_phone'] ?? '',
    ]);
    $footerLines = array_filter([
        $receiptSettings['receipt_footer_line_1'] ?? '',
        $receiptSettings['receipt_footer_line_2'] ?? '',
        $receiptSettings['receipt_footer_line_3'] ?? '',
    ]);
    $thermalContent = '';
    if ($isThermalSafe) {
        $thermalLines = [''];
        foreach ($wrapText($receiptSettings['receipt_store_name']) as $wrappedLine) {
            $thermalLines[] = $centerText($wrappedLine);
        }
        foreach ($headerLines as $line) {
            foreach ($wrapText($line) as $wrappedLine) {
                $thermalLines[] = $centerText($wrappedLine);
            }
        }
        $thermalLines[] = $separator;
        $thermalLines[] = $lineText('Nomor', $transaction->kode_transaksi);
        $thermalLines[] = $lineText('Tanggal', $transaction->created_at->format('d/m/Y H:i'));
        if ($enabled('receipt_show_cashier')) {
            $thermalLines[] = $lineText('Kasir', $transaction->user->name);
        }
        if ($transaction->status === 'void') {
            $thermalLines[] = $lineText('Status', 'VOID');
            foreach ($wrapText($transaction->void_reason ?: '-') as $wrappedLine) {
                $thermalLines[] = $wrappedLine;
            }
        }
        if ($enabled('receipt_show_customer') && $transaction->customer_name) {
            foreach ($wrapText('Pelanggan '.$transaction->customer_name) as $wrappedLine) {
                $thermalLines[] = $wrappedLine;
            }
        }
        $thermalLines[] = $separator;
        $thermalSubtotal = 0;
        $thermalDiscount = 0;
        foreach ($transaction->details as $detail) {
            $normalSubtotal = $detail->harga * $detail->qty;
            $discount = max(0, $normalSubtotal - $detail->subtotal);
            $thermalSubtotal += $normalSubtotal;
            $thermalDiscount += $discount;
            foreach ($wrapText($detail->product?->nama_barang ?? 'Produk dihapus') as $wrappedLine) {
                $thermalLines[] = $wrappedLine;
            }
            $thermalLines[] = $lineText($detail->qty.' x '.$money($detail->harga), $money($detail->subtotal));
        }
        $thermalLines[] = $separator;
        $thermalLines[] = $lineText('Subtotal', $money($thermalSubtotal));
        if ($thermalDiscount > 0) {
            $thermalLines[] = $lineText('Diskon', $money($thermalDiscount));
        }
        $thermalLines[] = $lineText('Total', $money($transaction->total));
        if ($enabled('receipt_show_payment')) {
            $thermalLines[] = $lineText('Status', $transaction->payment_status === 'paid' ? 'LUNAS' : 'BELUM BAYAR');
            $thermalLines[] = $lineText('Metode', $transaction->payment_type === 'cash' ? 'Tunai' : ($transaction->payment_type === 'transfer' ? 'Transfer' : '-'));
            if ($transaction->payment_type === 'transfer') {
                $thermalLines[] = $lineText('Bank', $transaction->bank_name ?: '-');
                $thermalLines[] = $lineText('Ref', $transaction->reference_number ?: '-');
                $thermalLines[] = $lineText('Verifikasi', $transaction->payment_verified_at ? 'Terverifikasi' : 'Belum');
            }
            if ($transaction->payment_status === 'paid') {
                $thermalLines[] = $lineText('Diterima', $money($transaction->uang_diterima));
                $thermalLines[] = $lineText('Kembali', $money($transaction->kembalian));
            }
        }
        $thermalLines[] = $separator;
        foreach ($footerLines as $line) {
            foreach ($wrapText($line) as $wrappedLine) {
                $thermalLines[] = $centerText($wrappedLine);
            }
        }
        $thermalLines = array_merge($thermalLines, array_fill(0, 10, ''));
        $thermalContent = implode("\n", $thermalLines);
    }
@endphp
<main class="wrap">
    <div class="{{ $pdfMode ? '' : 'preview-shell' }}">
        @unless($pdfMode)
            <aside class="panel">
                <div>
                    <h1>Preview Nota</h1>
                    <p>{{ $transaction->kode_transaksi }}</p>
                </div>

                <div class="meta">
                    <div class="meta-row"><span>Ukuran</span><strong>{{ $receiptLayout['label'] ?? strtoupper($selectedSize) }}</strong></div>
                    <div class="meta-row"><span>Tanggal</span><strong>{{ $transaction->created_at->format('d/m/Y H:i') }}</strong></div>
                    <div class="meta-row"><span>Total</span><strong>Rp {{ number_format($transaction->total, 0, ',', '.') }}</strong></div>
                </div>

                <div class="size-grid" aria-label="Pilihan ukuran nota">
                    @foreach($receiptSizes as $value => $option)
                        <a class="size-link {{ $selectedSize === $value ? 'active' : '' }}" href="{{ route('transactions.receipt', ['transaction' => $transaction, 'size' => $value]) }}">{{ $option['label'] }}</a>
                    @endforeach
                </div>

                <form method="GET" action="{{ route('transactions.receipt', $transaction) }}" style="display:none">
                    <label>
                        Ukuran nota
                        <select name="size" aria-label="Ukuran nota">
                        @foreach($receiptSizes as $value => $option)
                            <option value="{{ $value }}" @selected($selectedSize === $value)>{{ $option['label'] }}</option>
                        @endforeach
                        </select>
                    </label>
                </form>

                @if(session('success'))
                    <div class="notice ok">{{ session('success') }}</div>
                @endif
                @if(session('warning'))
                    <div class="notice warn">{{ session('warning') }}</div>
                @endif

                <div class="actions">
                    <button class="btn primary full" onclick="window.print()">Cetak Nota</button>
                    <a class="btn" href="{{ route('dashboard', ['page' => 'transaction-history']) }}">Kembali</a>
                    <a class="btn" href="{{ route('transactions.receipt-pdf', ['transaction' => $transaction, 'size' => $selectedSize]) }}" target="_blank">Buka PDF</a>
                    <a class="btn full" href="{{ route('transactions.receipt-pdf', ['transaction' => $transaction, 'size' => $selectedSize, 'download' => 1]) }}">Download PDF</a>
                </div>

                <div class="hint">Cetak memakai printer yang terhubung ke perangkat ini. Pilih ukuran sesuai kertas sebelum menekan Cetak Nota.</div>
            </aside>
        @endif
        <div class="{{ $pdfMode ? '' : 'paper-stage' }}">
        <section class="receipt {{ $isThermalSafe ? 'thermal-receipt' : '' }}">
            @if($isThermalSafe)
                <pre class="thermal-pre">{{ $thermalContent }}</pre>
            @else
            <h3>{{ $receiptSettings['receipt_store_name'] }}</h3>
            @if($headerLines)
                <p>{!! collect($headerLines)->map(fn ($line) => e($line))->implode('<br>') !!}</p>
            @endif
            <hr>
            <div class="line"><span>Nomor</span><strong>{{ $transaction->kode_transaksi }}</strong></div>
            <div class="line"><span>Tanggal</span><span>{{ $transaction->created_at->format('d/m/Y H:i') }}</span></div>
            @if($enabled('receipt_show_cashier'))
                <div class="line"><span>Kasir</span><span>{{ $transaction->user->name }}</span></div>
            @endif
            @if($transaction->status === 'void')
                <div class="line"><span>Status Transaksi</span><strong>VOID</strong></div>
                <div class="line"><span>Alasan</span><span>{{ $transaction->void_reason ?: '-' }}</span></div>
            @endif
            @if($enabled('receipt_show_customer') && $transaction->customer_name)
                <div class="line"><span>Pelanggan</span><span>{{ $transaction->customer_name }}</span></div>
            @endif
            <hr>
            @php $subtotal = 0; @endphp
            @php $totalDiscount = 0; @endphp
            @foreach ($transaction->details as $detail)
                @php
                    $normalSubtotal = $detail->harga * $detail->qty;
                    $discount = max(0, $normalSubtotal - $detail->subtotal);
                    $subtotal += $normalSubtotal;
                    $totalDiscount += $discount;
                @endphp
                <div class="line"><span>{{ $detail->product?->nama_barang ?? 'Produk dihapus' }}</span><span>{{ $detail->qty }} x {{ number_format($detail->harga, 0, ',', '.') }}</span></div>
                <div class="line"><span></span><strong>{{ number_format($detail->subtotal, 0, ',', '.') }}</strong></div>
            @endforeach
            <hr>
            <div class="line"><span>Subtotal</span><span>{{ number_format($subtotal, 0, ',', '.') }}</span></div>
            @if ($totalDiscount > 0)
                <div class="line"><span>Diskon</span><span>{{ number_format($totalDiscount, 0, ',', '.') }}</span></div>
            @endif
            <div class="line"><span>Total</span><strong>{{ number_format($transaction->total, 0, ',', '.') }}</strong></div>
            @if($enabled('receipt_show_payment'))
                <div class="line"><span>Status</span><strong>{{ $transaction->payment_status === 'paid' ? 'LUNAS' : 'BELUM BAYAR' }}</strong></div>
                <div class="line"><span>Metode</span><span>{{ $transaction->payment_type === 'cash' ? 'Tunai' : ($transaction->payment_type === 'transfer' ? 'Transfer' : '-') }}</span></div>
                @if ($transaction->payment_type === 'transfer')
                    <div class="line"><span>Bank</span><span>{{ $transaction->bank_name ?: '-' }}</span></div>
                    <div class="line"><span>Ref</span><span>{{ $transaction->reference_number ?: '-' }}</span></div>
                    <div class="line"><span>Verifikasi</span><span>{{ $transaction->payment_verified_at ? 'Terverifikasi' : 'Belum diverifikasi' }}</span></div>
                @endif
                @if ($transaction->payment_status === 'paid')
                    <div class="line"><span>Diterima</span><span>{{ number_format($transaction->uang_diterima, 0, ',', '.') }}</span></div>
                    <div class="line"><span>Kembali</span><strong>{{ number_format($transaction->kembalian, 0, ',', '.') }}</strong></div>
                @endif
            @endif
            <hr>
            @if($footerLines)
                <p>{!! collect($footerLines)->map(fn ($line) => e($line))->implode('<br>') !!}</p>
            @endif
            @endif
        </section>
        </div>
    </div>
</main>
@endsection
