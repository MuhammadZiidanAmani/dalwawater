<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dalwa Water Tegal</title>
    <script>
        (() => {
            const stored = window.localStorage.getItem('dalwa-theme');
            const dark = stored ? stored === 'dark' : window.matchMedia('(prefers-color-scheme: dark)').matches;
            document.documentElement.classList.toggle('dark', dark);
        })();
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.tsx'])
</head>
<body>
@php
    $rupiah = fn ($value) => 'Rp '.number_format((int) $value, 0, ',', '.');
    $page = request('page', auth()->check() ? 'dashboard' : 'login');
    if (auth()->check() && ! auth()->user()->isAdmin() && ! in_array($page, ['dashboard', 'transactions', 'inventory', 'history', 'operations', 'account'], true)) {
        $page = 'dashboard';
    }
    if (auth()->check() && auth()->user()->isAdmin() && $page === 'settings-general') {
        $page = 'dashboard';
    }
    $maxChart = max(1, ($salesChart ?? collect())->max('count') ?: 1);
    $transactionExportFilters = array_filter([
        'start_date' => $startDate ?? null,
        'end_date' => $endDate ?? null,
        'user_id' => $selectedUserId ?? null,
        'payment_status' => $selectedPaymentStatus ?? null,
        'payment_type' => $selectedPaymentType ?? null,
    ], fn ($value) => $value !== null && $value !== '');
        $receiptSettings = auth()->check() ? \App\Models\AppSetting::receiptSettings() : [];
@endphp

@guest
    <main class="auth">
        <section class="login">
            <div class="logo">
                <span class="drop">@include('partials.icon', ['name' => 'dashboard'])</span>
                <div><h1>Dalwa Water</h1><p>Sistem Manajemen</p></div>
            </div>
            @if (isset($errors) && $errors->any()) <div class="notice err">{{ $errors->first() }}</div> @endif
            @if (session('success')) <div class="notice ok">{{ session('success') }}</div> @endif
            <form method="POST" action="{{ route('login') }}" class="list">
                @csrf
                <div class="field"><label for="username">Nama pengguna</label><input id="username" name="username" value="{{ old('username') }}" required autofocus></div>
                <div class="field"><label for="password">Kata sandi</label><input id="password" name="password" type="password" required></div>
                <button class="btn primary" type="submit">@include('partials.icon', ['name' => 'check']) Masuk</button>
            </form>
        </section>
        <div style="position: absolute; bottom: 24px; left: 0; right: 0; text-align: center; font-size: 13px; color: var(--muted);">
            @ 2026 Dalwa Water Tegal
        </div>
    </main>
@else
    @php
        $formatDateTime = fn ($date) => optional($date)->format('d/m/Y H:i');
        $formatDate = fn ($date) => optional($date)->format('d/m/Y');
        $mapProduct = fn ($product) => [
            'id' => $product->id,
            'kode_barang' => $product->kode_barang,
            'nama_barang' => $product->nama_barang,
            'kategori' => $product->kategori,
            'harga_modal' => (int) $product->harga_modal,
            'harga_jual' => (int) $product->harga_jual,
            'stok' => (int) $product->stok,
            'stok_minimal' => (int) ($product->stok_minimal ?? 20),
            'satuan' => $product->satuan,
            'status' => $product->status,
            'foto_produk_url' => $product->foto_produk ? asset('storage/'.$product->foto_produk) : null,
            'deskripsi' => $product->deskripsi,
            'update_url' => auth()->user()->isAdmin() ? route('products.update', $product) : null,
            'destroy_url' => auth()->user()->isAdmin() ? route('products.destroy', $product) : null,
        ];
        $mapTransaction = fn ($transaction) => [
            'id' => $transaction->id,
            'kode_transaksi' => $transaction->kode_transaksi,
            'created_at' => $formatDateTime($transaction->created_at),
            'created_at_input' => optional($transaction->created_at)->format('Y-m-d\TH:i'),
            'cashier' => $transaction->user?->name ?? 'Tidak diketahui',
            'customer_name' => $transaction->customer_name,
            'payment_type' => $transaction->payment_type,
            'payment_status' => $transaction->payment_status,
            'status' => $transaction->status,
            'discount_total' => (int) $transaction->discount_total,
            'void_reason' => $transaction->void_reason,
            'payment_verified_at' => $formatDateTime($transaction->payment_verified_at),
            'total' => (int) $transaction->total,
            'uang_diterima' => (int) $transaction->uang_diterima,
            'kembalian' => (int) $transaction->kembalian,
            'receipt_url' => route('transactions.receipt', $transaction),
            'receipt_pdf_url' => route('transactions.receipt-pdf', $transaction),
            'receipt_jpeg_url' => route('transactions.receipt-jpeg', $transaction),
            'thermal_receipt_url' => route('transactions.thermal-receipt', $transaction),
            'thermal_payload_url' => route('transactions.thermal-payload', $transaction),
            'update_url' => (auth()->user()->isAdmin() || auth()->id() === $transaction->user_id) ? route('transactions.update', $transaction) : null,
            'settle_url' => route('transactions.settle', $transaction),
            'destroy_url' => route('transactions.destroy', $transaction),
            'verify_transfer_url' => auth()->user()->isAdmin() ? route('transactions.verify-transfer', $transaction) : null,
            'details' => $transaction->details->map(fn ($detail) => [
                'id' => $detail->id,
                'product_id' => $detail->product_id,
                'product' => $detail->product?->nama_barang ?? 'Produk dihapus',
                'qty' => (int) $detail->qty,
                'harga' => (int) $detail->harga,
                'subtotal' => (int) $detail->subtotal,
            ])->values(),
        ];
        $mapStockIn = fn ($stock) => [
            'id' => $stock->id,
            'invoice_number' => $stock->invoice_number,
            'tanggal' => $formatDate($stock->tanggal),
            'tanggal_input' => optional($stock->tanggal)->format('Y-m-d'),
            'supplier' => $stock->supplier,
            'product_id' => $stock->product_id,
            'product' => $stock->product?->nama_barang ?? 'Produk dihapus',
            'product_code' => $stock->product?->kode_barang,
            'product_category' => $stock->product?->kategori,
            'qty' => (int) $stock->qty,
            'satuan' => $stock->product?->satuan,
            'harga_beli' => (int) ($stock->harga_beli ?? $stock->product?->harga_modal ?? 0),
            'harga_jual' => (int) ($stock->harga_jual ?? $stock->product?->harga_jual ?? 0),
            'discount_percent' => (float) ($stock->discount_percent ?? 0),
            'discount_amount' => (int) ($stock->discount_amount ?? 0),
            'subtotal' => (int) ($stock->subtotal ?? (($stock->harga_beli ?? $stock->product?->harga_modal ?? 0) * $stock->qty)),
            'expired_at' => $formatDate($stock->expired_at),
            'expired_at_input' => optional($stock->expired_at)->format('Y-m-d'),
            'barcode' => $stock->barcode ?? $stock->product?->kode_barang,
            'amount' => (int) (($stock->harga_beli ?? $stock->product?->harga_modal ?? 0) * $stock->qty),
            'keterangan' => $stock->keterangan,
            'nota_url' => $stock->nota_pembelian ? route('stock-ins.document', [$stock, 'nota_pembelian']) : null,
            'surat_jalan_url' => $stock->surat_jalan ? route('stock-ins.document', [$stock, 'surat_jalan']) : null,
            'update_url' => auth()->user()->isAdmin() ? route('stock-ins.update', $stock) : null,
            'destroy_url' => auth()->user()->isAdmin() ? route('stock-ins.destroy', $stock) : null,
        ];
        $mapExpense = fn ($expense) => [
            'id' => $expense->id,
            'spent_at' => $formatDate($expense->spent_at),
            'spent_at_input' => optional($expense->spent_at)->format('Y-m-d'),
            'category' => $expense->category,
            'uraian' => $expense->uraian ?? $expense->description,
            'description' => $expense->description,
            'volume' => (float) ($expense->volume ?? 1),
            'unit' => $expense->unit ?? 'Unit',
            'price' => (int) ($expense->price ?? $expense->amount),
            'amount' => (int) $expense->amount,
            'notes' => $expense->notes,
            'user' => $expense->user?->name ?? 'Tidak diketahui',
            'attachment_url' => $expense->attachment ? route('expenses.attachment', $expense) : null,
            'update_url' => auth()->user()->isAdmin() ? route('expenses.update', $expense) : null,
            'destroy_url' => auth()->user()->isAdmin() ? route('expenses.destroy', $expense) : null,
        ];
        $mapShift = fn ($shift) => [
            'id' => $shift->id,
            'cashier' => $shift->user?->name ?? auth()->user()->name,
            'opened_at' => $formatDateTime($shift->opened_at),
            'closed_at' => $formatDateTime($shift->closed_at),
            'opening_cash' => (int) $shift->opening_cash,
            'closing_cash' => $shift->closing_cash === null ? null : (int) $shift->closing_cash,
            'expected_cash' => (int) $shift->expected_cash,
            'cash_sales' => (int) $shift->cash_sales,
            'transfer_sales' => (int) $shift->transfer_sales,
            'pending_sales' => (int) $shift->pending_sales,
            'void_total' => (int) $shift->void_total,
            'difference' => (int) $shift->difference,
            'notes' => $shift->notes,
            'close_url' => route('cashier-shifts.close', $shift),
        ];
        $mapOpname = fn ($opname) => [
            'id' => $opname->id,
            'date' => $formatDate($opname->opname_date),
            'user' => $opname->user?->name ?? 'Tidak diketahui',
            'notes' => $opname->notes,
            'adjustments' => $opname->adjustments->map(fn ($adjustment) => [
                'product' => $adjustment->product?->nama_barang ?? 'Produk dihapus',
                'system_stock' => (int) $adjustment->system_stock,
                'actual_stock' => (int) $adjustment->actual_stock,
                'difference' => (int) $adjustment->difference,
                'reason' => $adjustment->reason,
            ])->values(),
        ];
        $mapAudit = fn ($log) => [
            'id' => $log->id,
            'time' => $formatDateTime($log->created_at),
            'user' => $log->user?->name ?? 'System',
            'action' => $log->action,
        ];
        $dalwaReactProps = [
            'csrf' => csrf_token(),
            'user' => [
                'id' => auth()->id(),
                'name' => auth()->user()->name,
                'username' => auth()->user()->username,
                'email' => auth()->user()->email,
                'role' => auth()->user()->role,
                'status' => auth()->user()->status,
                'isAdmin' => auth()->user()->isAdmin(),
            ],
            'routes' => [
                'dashboard' => route('dashboard'),
                'logout' => route('logout'),
                'transactionsStore' => route('transactions.store'),
                'transactionsExportExcel' => auth()->user()->isAdmin() ? route('transactions.export-excel', $transactionExportFilters) : null,
                'transactionsExportPdf' => auth()->user()->isAdmin() ? route('transactions.export-pdf', $transactionExportFilters) : null,
                'productsStore' => route('products.store'),
                'categoriesStore' => auth()->user()->isAdmin() ? route('categories.store') : null,
                'suppliersStore' => auth()->user()->isAdmin() ? route('suppliers.store') : null,
                'stockInsStore' => route('stock-ins.store'),
                'stockOpnamesStore' => auth()->user()->isAdmin() ? route('stock-opnames.store') : null,
                'expensesStore' => auth()->user()->isAdmin() ? route('expenses.store') : null,
                'expensesPrint' => auth()->user()->isAdmin() ? route('expenses.print', [
                    'start_date' => $startDate ?? null,
                    'end_date' => $endDate ?? null,
                ]) : null,
                'cashiersStore' => route('cashiers.store'),
                'cashierShiftsOpen' => route('cashier-shifts.open'),
                'profileUpdate' => route('profile.update'),
                'passwordUpdate' => route('password.update'),
                'receiptSettingsUpdate' => auth()->user()->isAdmin() ? route('settings.receipt.update') : null,
            ],
            'settingsTab' => request('settings_tab'),
            'receiptSettings' => $receiptSettings,
            'stats' => array_map('intval', $stats ?? []),
            'reportStats' => array_map('intval', $reportStats ?? []),
            'products' => ($products ?? collect())->map($mapProduct)->values(),
            'activeProducts' => ($activeProducts ?? collect())->map($mapProduct)->values(),
            'lowStockProducts' => ($lowStockProducts ?? collect())->map($mapProduct)->values(),
            'transactions' => ($transactions ?? collect())->map($mapTransaction)->values(),
            'dashboardTransactions' => ($dashboardTransactions ?? collect())->map($mapTransaction)->values(),
            'historyTransactions' => ($historyTransactions ?? collect())->map($mapTransaction)->values(),
            'stockEntries' => ($stockEntries ?? collect())->map($mapStockIn)->values(),
            'purchaseHistory' => ($purchaseHistory ?? collect())->map($mapStockIn)->values(),
            'expenses' => ($expenses ?? collect())->map($mapExpense)->values(),
            'categories' => ($categories ?? collect())->map(fn ($category) => [
                'id' => $category->id,
                'nama_kategori' => $category->nama_kategori,
                'update_url' => auth()->user()->isAdmin() ? route('categories.update', $category) : null,
                'destroy_url' => auth()->user()->isAdmin() ? route('categories.destroy', $category) : null,
            ])->values(),
            'suppliers' => ($suppliers ?? collect())->map(fn ($supplier) => [
                'id' => $supplier->id,
                'nama' => $supplier->nama,
                'nomor_telepon' => $supplier->nomor_telepon,
                'email' => $supplier->email,
                'alamat' => $supplier->alamat,
                'update_url' => auth()->user()->isAdmin() ? route('suppliers.update', $supplier) : null,
                'destroy_url' => auth()->user()->isAdmin() ? route('suppliers.destroy', $supplier) : null,
            ])->values(),
            'cashiers' => ($cashiers ?? collect())->map(fn ($cashier) => [
                'id' => $cashier->id,
                'name' => $cashier->name,
                'username' => $cashier->username,
                'role' => $cashier->role,
                'status' => $cashier->status,
                'today_sales' => (int) ($cashier->today_sales ?? 0),
                'update_url' => auth()->user()->isAdmin() ? route('cashiers.update', $cashier) : null,
                'reset_password_url' => auth()->user()->isAdmin() ? route('cashiers.reset-password', $cashier) : null,
            ])->values(),
            'currentShift' => $currentShift ? $mapShift($currentShift) : null,
            'recentShifts' => ($recentShifts ?? collect())->map($mapShift)->values(),
            'stockOpnames' => ($stockOpnames ?? collect())->map($mapOpname)->values(),
            'auditLogs' => ($auditLogs ?? collect())->map($mapAudit)->values(),
            'salesChart' => ($salesChart ?? collect())->map(fn ($point) => [
                'label' => $point['label'],
                'count' => (int) $point['count'],
            ])->values(),
            'netProfitChart' => ($netProfitChart ?? collect())->map(fn ($point) => [
                'label' => $point['label'],
                'count' => (int) $point['count'],
            ])->values(),
            'financialChart' => ($financialChart ?? collect())->map(fn ($point) => [
                'label' => $point['label'],
                'income' => (int) $point['income'],
                'expense' => (int) $point['expense'],
                'capital' => (int) $point['capital'],
                'gross_profit' => (int) $point['gross_profit'],
                'net_profit' => (int) $point['net_profit'],
            ])->values(),
            'monthlyProductSales' => ($monthlyProductSales ?? collect())->map(fn ($product) => [
                'name' => $product->nama_barang,
                'qty' => (int) $product->qty,
                'total' => (int) ($product->total ?? 0),
            ])->values(),
            'filters' => [
                'period_month' => $selectedPeriodMonth ?? null,
                'report_scope' => $selectedReportScope ?? null,
                'start_date' => $startDate ?? null,
                'end_date' => $endDate ?? null,
                'user_id' => $selectedUserId ?? null,
                'payment_status' => $selectedPaymentStatus ?? null,
                'payment_type' => $selectedPaymentType ?? null,
            ],
            'flash' => [
                'success' => session('success'),
                'warning' => session('warning'),
            ],
        ];
    @endphp
    <script>
        window.__DALWA_APP__ = {!! Illuminate\Support\Js::from($dalwaReactProps) !!};
    </script>
    <div id="dalwa-react-root"></div>
@endguest
</body>
</html>
