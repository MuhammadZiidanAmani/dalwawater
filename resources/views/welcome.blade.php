<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dalwa Water Management System</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        :root{--bg:#f8f9fa;--surface:#fff;--soft:#f3f4f6;--line:#e5e7eb;--text:#4b5563;--text-dark:#1f2937;--muted:#6b7280;--primary:#3b82f6;--primary-dark:#1e293b;--sidebar-bg:#1e293b;--sidebar-text:#94a3b8;--sidebar-hover:#f8fafc;--psoft:#eff6ff;--green:#10b981;--gsoft:#d1fae5;--amber:#f59e0b;--asoft:#fef3c7;--red:#ef4444;--rsoft:#fee2e2;font-family:'Inter',ui-sans-serif,system-ui,sans-serif}
        *{box-sizing:border-box}body{margin:0;background:var(--bg);color:var(--text)}button,input,select,textarea{font:inherit}button{cursor:pointer}
        .auth{min-height:100vh;display:grid;place-items:center;padding:24px}.login{width:min(430px,100%);background:var(--surface);border:1px solid var(--line);border-radius:8px;padding:28px;box-shadow:0 4px 6px -1px rgba(0,0,0,0.1)}.logo{display:flex;align-items:center;gap:12px;margin-bottom:22px}.drop{width:42px;height:42px;border-radius:8px;background:var(--psoft);color:var(--primary);display:grid;place-items:center}.logo h1{font-size:22px;margin:0;color:var(--text-dark)}.logo p{margin:2px 0 0;color:var(--muted)}
        .shell{min-height:100vh;display:flex}.side{position:sticky;top:0;height:100vh;width:250px;background:var(--sidebar-bg);color:var(--sidebar-text);display:flex;flex-direction:column;font-size:14px;flex-shrink:0}.brand{height:70px;display:flex;align-items:center;padding:0 24px;color:white;font-size:20px;font-weight:700;letter-spacing:0.5px;border-bottom:1px solid rgba(255,255,255,0.05)}.nav-section{margin-top:24px}.nav-title{font-size:11px;color:#64748b;text-transform:uppercase;font-weight:600;margin-bottom:8px;letter-spacing:0.05em;padding:0 24px}.nav{display:flex;flex-direction:column;gap:4px;overflow-y:auto;flex:1;padding-bottom:24px}.nav::-webkit-scrollbar{width:4px}.nav::-webkit-scrollbar-thumb{background:rgba(255,255,255,0.1);border-radius:4px}.nav button{border:0;background:transparent;color:var(--sidebar-text);display:flex;align-items:center;gap:12px;padding:10px 24px;text-align:left;font-weight:500;width:100%;border-radius:0;transition:all 0.2s}.nav button:hover,.nav button.active{color:var(--sidebar-hover);background:rgba(255,255,255,0.05)}.nav button svg{width:18px;height:18px;fill:currentColor}.submenu{display:none;flex-direction:column}.submenu.show{display:flex}.submenu button{padding:8px 24px 8px 54px;font-size:13px;color:#94a3b8}.submenu button:hover,.submenu button.active{color:var(--sidebar-hover);background:transparent}
        .main-wrapper{display:flex;flex-direction:column;min-width:0;flex:1}.topbar{height:70px;background:var(--surface);display:flex;align-items:center;justify-content:space-between;padding:0 24px;box-shadow:0 1px 2px rgba(0,0,0,.05);position:sticky;top:0;z-index:10}.hamburger{background:transparent;border:0;color:var(--text-dark);padding:4px;display:flex;align-items:center}.account-menu{position:relative}.account-menu summary{list-style:none;display:flex;align-items:center;gap:12px;color:var(--text-dark);font-weight:500;font-size:14px;cursor:pointer;padding:6px 12px;border-radius:6px}.account-menu summary::-webkit-details-marker{display:none}.account-menu summary:hover{background:var(--soft)}.account-menu .avatar{width:32px;height:32px;background:#e2e8f0;border-radius:6px;display:grid;place-items:center;color:#64748b}.account-dropdown{position:absolute;right:0;top:calc(100% + 8px);width:230px;background:var(--surface);border:1px solid var(--line);border-radius:6px;box-shadow:0 12px 28px rgba(15,23,42,.16);padding:8px;z-index:40}.account-dropdown button{border:0;background:transparent;color:var(--text-dark);display:flex;align-items:center;gap:10px;width:100%;padding:10px 12px;border-radius:6px;text-align:left;font-weight:500}.account-dropdown button:hover{background:var(--soft)}.account-dropdown .divider{height:1px;background:var(--line);margin:8px -8px}.account-dropdown form{margin:0}.main{padding:24px}.page{display:none}.page.active{display:block}
        h1{font-size:24px;line-height:32px;margin:0 0 8px;color:var(--text-dark);font-weight:600}h2{font-size:16px;margin:0 0 16px;color:var(--text-dark);font-weight:600}h3{font-size:14px;margin:0 0 4px;font-weight:600;color:var(--text-dark)}.sub{color:var(--muted);margin:0;line-height:20px;font-size:14px}.eyebrow{margin:0 0 4px;color:var(--primary);font-size:12px;text-transform:uppercase;letter-spacing:.05em;font-weight:600;display:none}
        .top,.row,.panel-head,.toolbar{display:flex;align-items:center;justify-content:space-between;gap:16px}.top{margin-bottom:24px}.actions{display:flex;gap:8px;flex-wrap:wrap}.btn{min-height:38px;border:1px solid var(--line);border-radius:6px;background:var(--surface);color:var(--text-dark);display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:8px 16px;font-weight:500;text-decoration:none;font-size:14px;transition:all 0.2s;box-shadow:0 1px 2px rgba(0,0,0,0.05)}.btn:hover{background:var(--soft);border-color:#d1d5db}.btn.primary{background:var(--primary);border-color:var(--primary);color:white}.btn.primary:hover{background:#2563eb;border-color:#2563eb}.btn.soft{background:var(--psoft);border-color:transparent;color:var(--primary);box-shadow:none}.btn.soft:hover{background:#dbeafe}.btn.danger{background:var(--rsoft);border-color:transparent;color:var(--red);box-shadow:none}.btn.danger:hover{background:#fecaca}
        .notice{padding:12px 16px;border-radius:6px;margin-bottom:24px;font-size:14px;font-weight:500}.notice.ok{background:var(--gsoft);color:var(--green);border:1px solid #a7f3d0}.notice.err{background:var(--rsoft);color:var(--red);border:1px solid #fecaca}.grid4{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:24px;margin-bottom:24px}.card,.panel{background:var(--surface);border:1px solid var(--line);border-radius:8px;box-shadow:0 1px 3px 0 rgba(0,0,0,0.1),0 1px 2px 0 rgba(0,0,0,0.06)}.card{padding:24px}.panel{padding:24px;margin-bottom:24px}.metric{display:flex;justify-content:space-between;align-items:flex-start}.metric-info h3{font-size:14px;color:var(--text-dark);font-weight:600;margin:0 0 12px}.metric-info .value{font-size:28px;font-weight:600;color:var(--text-dark);line-height:1}.metric-info .value small{font-size:14px;color:var(--muted);font-weight:500}.metric-icon{width:48px;height:48px;border-radius:12px;background:var(--psoft);color:var(--primary);display:grid;place-items:center}.metric-icon svg{width:24px;height:24px;fill:currentColor}
        .split{display:grid;grid-template-columns:minmax(0,1fr)380px;gap:24px}.dash{display:grid;grid-template-columns:2fr 1fr;gap:24px}.bars{height:240px;display:grid;grid-template-columns:repeat(7,1fr);gap:12px;align-items:end;border-bottom:1px solid var(--line);padding-top:18px}.bar-wrap{display:grid;gap:8px;text-align:center;color:var(--muted);font-size:12px;font-weight:500;height:100%}.bar{align-self:end;background:var(--psoft);border-radius:4px 4px 0 0;min-height:12px;transition:height 0.3s ease}.bar.current{background:var(--primary)}
        .list{display:grid;gap:12px}.product,.cart-row,.activity{display:flex;align-items:center;gap:16px;border:1px solid var(--line);border-radius:8px;padding:12px 16px;background:var(--surface);transition:border-color 0.2s}.product:hover,.cart-row{border-color:var(--primary)}.thumb{width:48px;height:48px;border-radius:8px;background:var(--soft);color:var(--primary);display:grid;place-items:center;flex:0 0 auto}.grow{min-width:0;flex:1}.grow strong{display:block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;color:var(--text-dark);font-size:14px;margin-bottom:2px}.muted{color:var(--muted);font-size:13px}.price{color:var(--text-dark);font-weight:600;white-space:nowrap}.badge{display:inline-flex;align-items:center;border-radius:9999px;padding:4px 10px;font-size:12px;font-weight:500}.badge.green{background:var(--gsoft);color:var(--green)}.badge.amber{background:var(--asoft);color:var(--amber)}.badge.red{background:var(--rsoft);color:var(--red)}.badge.gray{background:var(--soft);color:var(--text-dark)}
        .table{overflow-x:auto}table{width:100%;border-collapse:collapse;min-width:600px}th,td{padding:16px;text-align:left;border-bottom:1px solid var(--line);vertical-align:middle;font-size:14px}th{font-weight:600;color:var(--muted);text-transform:none;letter-spacing:0;background:transparent;border-bottom:1px solid var(--line)}tr:last-child td{border-bottom:0}
        .form-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:20px}.field{display:grid;gap:8px}label{font-size:14px;font-weight:500;color:var(--text-dark)}input,select,textarea{width:100%;min-height:42px;border:1px solid var(--line);border-radius:6px;background:var(--surface);padding:8px 16px;outline:none;font-size:14px;color:var(--text-dark);transition:border-color 0.2s,box-shadow 0.2s}textarea{min-height:88px;resize:vertical}input:focus,select:focus,textarea:focus{border-color:var(--primary);box-shadow:0 0 0 3px rgba(59,130,246,0.1)}.dashboard-filter{display:grid;grid-template-columns:1fr 1fr 1.12fr 1.12fr auto;margin-bottom:24px;max-width:1154px}.dashboard-filter input,.dashboard-filter select{min-height:48px;border-radius:0;border-right:0;font-size:16px;background:white}.dashboard-filter input[name=start_date]{border-radius:4px 0 0 4px}.dashboard-filter button{min-height:48px;border:1px solid #1f2937;border-radius:0 4px 4px 0;background:#20242b;color:white;display:inline-flex;align-items:center;gap:8px;padding:0 20px;font-size:16px;font-weight:600}.dashboard-filter button:hover{background:#111827}
        .catalog{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:16px}.catalog button{border:1px solid var(--line);border-radius:8px;background:var(--surface);text-align:left;padding:16px;display:grid;gap:12px;transition:all 0.2s}.catalog button:hover{border-color:var(--primary);box-shadow:0 4px 6px -1px rgba(0,0,0,0.1)}.summary{position:sticky;top:94px;align-self:start}.total{display:flex;justify-content:space-between;padding:12px 0;border-bottom:1px solid var(--line)}.total.big{font-size:18px;font-weight:600;border-bottom:0;color:var(--text-dark);padding-top:16px}.qty{display:flex;gap:8px;align-items:center;margin-top:10px}.qty button{width:28px;height:28px;border:1px solid var(--line);border-radius:6px;background:var(--surface);font-weight:600;color:var(--text-dark)}.tabs{display:grid;grid-template-columns:1fr 1fr;gap:8px;margin:16px 0}.tabs button{border:1px solid var(--line);background:var(--surface);border-radius:6px;min-height:42px;font-weight:500;color:var(--text-dark)}.tabs button.active{border-color:var(--primary);background:var(--psoft);color:var(--primary)}
        .mobile-nav{display:none}.inline-form{display:flex;gap:8px;align-items:center;flex-wrap:wrap}.inline-form input,.inline-form select{width:auto;min-width:112px}.receipt{width:80mm;max-width:100%;margin:auto;background:white;border:1px dashed var(--muted);padding:18px;font-family:"Courier New",monospace}.receipt h3{text-align:center;margin:0 0 4px}.receipt p{text-align:center;margin:0 0 8px}.receipt hr{border:0;border-top:1px dashed #9ca3af;margin:10px 0}.rline{display:flex;justify-content:space-between;gap:12px;margin:5px 0}
        .modal-overlay{display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center;padding:16px;}.modal-overlay.active{display:flex;}.modal-content{background:white;border-radius:8px;width:100%;max-width:650px;max-height:90vh;overflow-y:auto;box-shadow:0 10px 15px -3px rgba(0,0,0,0.1);}.modal-header{display:flex;justify-content:space-between;align-items:center;padding:16px 24px;border-bottom:1px solid var(--line);}.modal-header h2{margin:0;font-size:16px;font-weight:600;}.modal-body{padding:24px;display:flex;flex-direction:column;gap:16px;}.modal-footer{padding:16px 24px;border-top:1px solid var(--line);display:flex;justify-content:flex-end;gap:12px;}.success-modal{display:flex;position:fixed;inset:0;background:rgba(15,23,42,.08);z-index:1200;align-items:center;justify-content:center;padding:24px}.success-box{position:relative;width:min(365px,100%);background:white;padding:52px 32px 48px;text-align:center;box-shadow:0 1px 2px rgba(15,23,42,.04)}.success-close{position:absolute;right:18px;top:16px;border:0;background:transparent;color:#cbd5e1;font-size:28px;line-height:1;padding:0;cursor:pointer}.success-icon{width:40px;height:40px;border:3px solid #10b981;border-radius:50%;color:#10b981;display:grid;place-items:center;margin:0 auto 22px}.success-box h2{font-size:24px;margin:0 0 12px;color:var(--text-dark);font-weight:700}.success-box p{margin:0 0 34px;color:#475569;font-size:13px}.success-box .btn{background:#0d6efd;color:white;border-color:#0d6efd;border-radius:0;min-width:98px;box-shadow:none}.horizontal-field{display:flex;align-items:center;gap:16px;}.horizontal-field label{flex:0 0 140px;text-align:right;color:var(--text-dark);font-weight:500;}.horizontal-field .input-wrap{flex:1;}.horizontal-field input,.horizontal-field select,.horizontal-field textarea{width:100%;}.horizontal-field .required{color:var(--red);}
        @media(max-width:1180px){.grid4{grid-template-columns:repeat(2,1fr)}.dash,.split{grid-template-columns:1fr}.summary{position:static}.catalog{grid-template-columns:repeat(2,1fr)}.form-grid{grid-template-columns:repeat(2,1fr)}}@media(max-width:820px){.shell{display:block}.side{display:none}.main-wrapper{display:block}.main{padding:16px 16px 92px}.top,.toolbar,.panel-head{align-items:flex-start;flex-direction:column;gap:12px}.grid4,.form-grid,.catalog{grid-template-columns:1fr}.dashboard-filter{grid-template-columns:1fr;gap:8px}.dashboard-filter input,.dashboard-filter select,.dashboard-filter button{border:1px solid var(--line);border-radius:6px}.horizontal-field{flex-direction:column;align-items:flex-start;gap:8px;}.horizontal-field label{text-align:left;flex:none;}.mobile-nav{position:fixed;left:0;right:0;bottom:0;background:var(--surface);border-top:1px solid var(--line);display:grid;grid-template-columns:repeat(5,1fr);padding:8px;gap:4px;z-index:10;box-shadow:0 -1px 3px rgba(0,0,0,0.05)}.mobile-nav button{border:0;border-radius:6px;background:var(--surface);color:var(--muted);font-size:11px;font-weight:500;display:grid;place-items:center;gap:4px}.mobile-nav button.active{background:var(--psoft);color:var(--primary)}}@media print{.side,.mobile-nav,.topbar,.top,.no-print{display:none!important}.shell,.main,.page{display:block;padding:0}.page{display:none}.page.print{display:block}.panel{border:0;padding:0;box-shadow:none}.receipt{border:0}}
    </style>
</head>
<body>
@php
    $rupiah = fn ($value) => 'Rp '.number_format((int) $value, 0, ',', '.');
    $page = request('page', auth()->check() ? 'dashboard' : 'login');
    $maxChart = max(1, ($salesChart ?? collect())->max('count') ?: 1);
@endphp

@guest
    <main class="auth">
        <section class="login">
            <div class="logo">
                <span class="drop">@include('partials.icon', ['name' => 'dashboard'])</span>
                <div><h1>Dalwa Water</h1><p>Management System</p></div>
            </div>
            @if (isset($errors) && $errors->any()) <div class="notice err">{{ $errors->first() }}</div> @endif
            @if (session('success')) <div class="notice ok">{{ session('success') }}</div> @endif
            <form method="POST" action="{{ route('login') }}" class="list">
                @csrf
                <div class="field"><label for="username">Username</label><input id="username" name="username" value="{{ old('username', 'admin') }}" required autofocus></div>
                <div class="field"><label for="password">Password</label><input id="password" name="password" type="password" value="password" required></div>
                <button class="btn primary" type="submit">@include('partials.icon', ['name' => 'check']) Login</button>
                <p class="sub">Demo awal: <strong>admin/password</strong> atau <strong>kasir/password</strong>.</p>
            </form>
        </section>
        <div style="position: absolute; bottom: 24px; left: 0; right: 0; text-align: center; font-size: 13px; color: var(--muted);">
            CEO Restu Gusti - 2026 &copy;
        </div>
    </main>
@else
<div class="shell">
    <aside class="side">
        <div class="brand">
            Dalwa Water Tegal
        </div>
        <nav class="nav">
            <div class="nav-section">
                <div class="nav-title">Halaman</div>
                <button class="{{ $page === 'dashboard' ? 'active' : '' }}" data-page-target="dashboard">@include('partials.icon', ['name' => 'dashboard']) Beranda</button>
                <button class="{{ $page === 'transactions' ? 'active' : '' }}" data-page-target="transactions">@include('partials.icon', ['name' => 'wallet']) Transaksi</button>
            </div>
            
            <div class="nav-section">
                <div class="nav-title">Manajemen Barang</div>
                <button class="{{ $page === 'kategori' ? 'active' : '' }}" data-page-target="kategori">@include('partials.icon', ['name' => 'box']) Kategori Produk</button>
                <button class="{{ $page === 'produk' || $page === 'inventory' ? 'active' : '' }}" data-page-target="produk">@include('partials.icon', ['name' => 'box']) Data Produk</button>
                <button class="{{ $page === 'stok' ? 'active' : '' }}" data-page-target="stok">@include('partials.icon', ['name' => 'box']) Data Stok</button>
                @if (auth()->user()->isAdmin())
                    <button class="{{ $page === 'stockin' ? 'active' : '' }}" data-page-target="stockin">@include('partials.icon', ['name' => 'truck']) Barang Masuk</button>
                @endif
            </div>

            @if (auth()->user()->isAdmin())
            <div class="nav-section">
                <div class="nav-title">Laporan</div>
                <button class="{{ $page === 'history' ? 'active' : '' }}" data-page-target="history">@include('partials.icon', ['name' => 'receipt']) Riwayat Transaksi</button>
                <button class="{{ $page === 'reports' ? 'active' : '' }}" data-page-target="reports">@include('partials.icon', ['name' => 'chart']) Rekap Penjualan</button>
            </div>

            <div class="nav-section">
                <div class="nav-title">Pengaturan</div>
                <button class="{{ $page === 'settings-general' ? 'active' : '' }}" data-page-target="settings-general">@include('partials.icon', ['name' => 'settings']) Umum</button>
                <button class="{{ $page === 'settings-profile' ? 'active' : '' }}" data-page-target="settings-profile">@include('partials.icon', ['name' => 'profile']) Edit Profil</button>
                <button class="{{ $page === 'settings-password' ? 'active' : '' }}" data-page-target="settings-password">@include('partials.icon', ['name' => 'lock']) Ganti Password</button>
            </div>
            @endif
        </nav>
    </aside>

    <div class="main-wrapper">
        <header class="topbar">
            <button class="hamburger" onclick="document.querySelector('.side').style.display = document.querySelector('.side').style.display === 'none' ? 'flex' : 'none'">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
            </button>
            <details class="account-menu">
                <summary>
                    <div class="avatar">@include('partials.icon', ['name' => 'profile'])</div>
                    <span>{{ auth()->user()->isAdmin() ? 'Admin' : 'Kasir' }}</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </summary>
                <div class="account-dropdown">
                    <button type="button" data-page-target="settings-profile">@include('partials.icon', ['name' => 'profile']) Profil</button>
                    <button type="button" data-page-target="settings-password">@include('partials.icon', ['name' => 'settings']) Ubah Password</button>
                    <div class="divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">@include('partials.icon', ['name' => 'lock']) Log out</button>
                    </form>
                </div>
            </details>
        </header>

        <main class="main">
        @if (session('success'))
            <div class="success-modal" id="success-modal">
                <div class="success-box">
                    <button class="success-close" type="button" onclick="document.getElementById('success-modal').style.display='none'">&times;</button>
                    <div class="success-icon">@include('partials.icon', ['name' => 'check'])</div>
                    <h2>Success!</h2>
                    <p>{{ session('success') }}</p>
                    <button class="btn" type="button" onclick="document.getElementById('success-modal').style.display='none'">Awesome!</button>
                </div>
            </div>
        @endif
        @if (isset($errors) && $errors->any()) <div class="notice err">{{ $errors->first() }}</div> @endif

        <section id="dashboard" class="page {{ $page === 'dashboard' ? 'active' : '' }}">
            <div class="top" style="flex-direction: row; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--line); padding-bottom: 16px; margin-bottom: 24px;">
                <h1 style="margin:0;">Beranda</h1>
            </div>
            
            <form id="date-filter-form" class="dashboard-filter" method="GET" action="/">
                <input type="hidden" name="page" value="dashboard">
                <input type="date" name="start_date" value="{{ $startDate }}" required>
                <input type="date" name="end_date" value="{{ $endDate }}" required>
                <select name="user_id">
                    <option value="">Semua user</option>
                    @foreach ($cashiers as $cashier)
                        <option value="{{ $cashier->id }}" @selected((string) $selectedUserId === (string) $cashier->id)>{{ $cashier->name }}</option>
                    @endforeach
                </select>
                <select name="payment_status">
                    <option value="">Semua status bayar</option>
                    <option value="paid" @selected($selectedPaymentStatus === 'paid')>Lunas</option>
                    <option value="pending" @selected($selectedPaymentStatus === 'pending')>Pending</option>
                </select>
                <button type="submit">@include('partials.icon', ['name' => 'search']) Cari</button>
            </form>

            <div class="grid4" style="grid-template-columns:repeat(3,minmax(0,1fr))">
                <article class="card">
                    <div class="metric">
                        <div class="metric-info">
                            <h3>Jumlah Transaksi</h3>
                            <div class="value">{{ $stats['today_transactions'] }}</div>
                        </div>
                        <div class="metric-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                        </div>
                    </div>
                </article>
                <article class="card">
                    <div class="metric">
                        <div class="metric-info">
                            <h3>Total Transaksi</h3>
                            <div class="value" style="font-size:22px">{{ $rupiah($stats['today_income']) }}</div>
                        </div>
                        <div class="metric-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                        </div>
                    </div>
                </article>
                <article class="card">
                    <div class="metric">
                        <div class="metric-info">
                            <h3>Pendapatan</h3>
                            <div class="value" style="font-size:22px">{{ $rupiah($stats['today_income']) }}</div>
                        </div>
                        <div class="metric-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                        </div>
                    </div>
                </article>
            </div>
            <div class="dash">
                <section class="panel"><div class="panel-head"><div><h2>Tren Penjualan Mingguan</h2><p class="sub">Jumlah transaksi per hari.</p></div></div><div class="bars">@foreach ($salesChart as $i => $item)<div class="bar-wrap"><div class="bar {{ $loop->last ? 'current' : '' }}" style="height:{{ max(8, $item['count'] / $maxChart * 100) }}%"></div><span>{{ $item['label'] }}<br>{{ $item['count'] }}</span></div>@endforeach</div></section>
                <section class="panel"><h2>Produk Terlaris</h2><div class="list">@forelse ($topProducts as $product)<div class="product"><span class="thumb">@include('partials.product-icon')</span><div class="grow"><strong>{{ $product->nama_barang }}</strong><span class="muted">{{ (int) $product->sold_qty }} terjual</span></div><span class="price">{{ $rupiah($product->harga_jual) }}</span></div>@empty<p class="sub">Belum ada transaksi.</p>@endforelse</div></section>
            </div>
        </section>

        <section id="kategori" class="page {{ $page === 'kategori' ? 'active' : '' }}">
            <div style="margin-bottom: 24px;">
                <h1 style="font-size: 24px; color: var(--text-dark); margin: 0 0 4px; font-weight: 500;">Kategori Produk</h1>
                <p style="color: var(--muted); margin: 0; font-size: 14px;">Daftar Kategori Produk</p>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
                <div style="display: flex; gap: 0; align-items: stretch; border: 1px solid #bfdbfe; border-radius: 4px; overflow: hidden; background: #eff6ff;">
                    <select style="width: 140px; padding: 8px 12px; border: none; background: transparent; color: var(--text-dark); font-size: 14px; outline: none;">
                        <option>Aktif</option>
                        <option>Nonaktif</option>
                    </select>
                    <button style="padding: 8px 16px; background: #1e293b; color: white; border: none; display: flex; align-items: center; gap: 8px; font-weight: 500; font-size: 14px;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg> Cari
                    </button>
                </div>
                
                @if (auth()->user()->isAdmin())
                <div style="display: flex; gap: 12px; align-items: center;">
                    <button type="button" class="btn primary" style="background: #3b82f6; border: none; border-radius: 4px; padding: 8px 16px; font-weight: 500; font-size: 14px; box-shadow: none;" onclick="document.getElementById('add-category-modal').classList.add('active')">
                        + Tambah Kategori
                    </button>
                </div>
                @endif
            </div>

            @if (auth()->user()->isAdmin())
            <div id="add-category-modal" class="modal-overlay">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2>Form Tambah Kategori Produk</h2>
                        <button type="button" style="background:transparent; border:none; color:var(--muted); cursor:pointer; padding:0; display:flex; align-items:center; justify-content:center;" onclick="document.getElementById('add-category-modal').classList.remove('active')">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </button>
                    </div>
                    <form method="POST" action="{{ route('categories.store') }}">
                        @csrf
                        <div class="modal-body">
                            <div class="horizontal-field">
                                <label>Nama <span class="required">*</span></label>
                                <div class="input-wrap"><input name="nama_kategori" placeholder="Masukan nama kategori" required></div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn" style="background:#64748b; color:white; border:none;" onclick="document.getElementById('add-category-modal').classList.remove('active')">Close</button>
                            <button type="submit" class="btn primary" style="background:#3b82f6; border:none;">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
            @endif

            <section class="panel" style="padding: 24px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border: 1px solid var(--line); background: white;">
                <h2 style="font-size: 16px; font-weight: 600; margin: 0 0 24px; color: var(--text-dark);">Tabel Kategori Produk</h2>
                
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                    <div style="color: var(--muted); font-size: 14px; display: flex; align-items: center; gap: 6px;">
                        Show 
                        <select style="padding: 4px 8px; border: 1px solid var(--line); border-radius: 4px; outline: none; background: white; color: var(--text-dark);">
                            <option>10</option>
                            <option>25</option>
                            <option>50</option>
                        </select> 
                        entries
                    </div>
                    <div style="color: var(--muted); font-size: 14px; display: flex; align-items: center; gap: 8px;">
                        Search: <input type="text" style="padding: 4px 8px; border: 1px solid var(--line); border-radius: 4px; width: 200px; outline: none; min-height: 32px;">
                    </div>
                </div>

                <div class="table" style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; min-width: 800px;">
                        <thead>
                            <tr>
                                <th style="padding: 12px 16px; border-bottom: 1px solid var(--line); background: #f8fafc; color: var(--text-dark); font-weight: 600; font-size: 14px; text-align: left; width: 50px;">No <span style="float: right; color: #cbd5e1; font-size: 10px; padding-top:4px;">▲▼</span></th>
                                <th style="padding: 12px 16px; border-bottom: 1px solid var(--line); background: #f8fafc; color: var(--text-dark); font-weight: 600; font-size: 14px; text-align: left;">Nama <span style="float: right; color: #cbd5e1; font-size: 10px; padding-top:4px;">▲▼</span></th>
                                <th style="padding: 12px 16px; border-bottom: 1px solid var(--line); background: #f8fafc; color: var(--text-dark); font-weight: 600; font-size: 14px; text-align: left;">Tanggal Buat <span style="float: right; color: #cbd5e1; font-size: 10px; padding-top:4px;">▲▼</span></th>
                                <th style="padding: 12px 16px; border-bottom: 1px solid var(--line); background: #f8fafc; color: var(--text-dark); font-weight: 600; font-size: 14px; text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $index => $cat)
                            <tr style="border-bottom: 1px solid var(--line);">
                                <td style="padding: 16px; color: var(--muted); font-size: 14px;">{{ $index + 1 }}</td>
                                <td style="padding: 16px; color: var(--muted); font-size: 14px;">{{ $cat->nama_kategori }}</td>
                                <td style="padding: 16px; color: var(--muted); font-size: 14px;">{{ $cat->created_at ? $cat->created_at->locale('id')->diffForHumans() : '-' }}</td>
                                <td style="padding: 16px; text-align: center; white-space: nowrap;">
                                    @if(auth()->user()->isAdmin())
                                    <button type="button" style="background: transparent; border: none; color: #3b82f6; cursor: pointer; padding: 0 4px; display:inline-flex; align-items:center;" onclick="document.getElementById('edit-category-modal-{{ $cat->id }}').classList.add('active')" title="Edit">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                    </button>
                                    <form action="{{ route('categories.destroy', $cat) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin ingin menghapus kategori ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="background: transparent; border: none; color: #ef4444; cursor: pointer; padding: 0 4px; display:inline-flex; align-items:center;" title="Hapus">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                        </button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            
                            @if(auth()->user()->isAdmin())
                            <div id="edit-category-modal-{{ $cat->id }}" class="modal-overlay">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h2>Edit Kategori Produk</h2>
                                        <button type="button" style="background:transparent; border:none; color:var(--muted); cursor:pointer; padding:0; display:flex; align-items:center; justify-content:center;" onclick="document.getElementById('edit-category-modal-{{ $cat->id }}').classList.remove('active')">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                        </button>
                                    </div>
                                    <form method="POST" action="{{ route('categories.update', $cat) }}">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="horizontal-field">
                                                <label>Nama <span class="required">*</span></label>
                                                <div class="input-wrap"><input name="nama_kategori" value="{{ $cat->nama_kategori }}" placeholder="Masukan nama kategori" required></div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn" style="background:#64748b; color:white; border:none;" onclick="document.getElementById('edit-category-modal-{{ $cat->id }}').classList.remove('active')">Batal</button>
                                            <button type="submit" class="btn primary" style="background:#3b82f6; border:none;">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        </section>

        <section id="produk" class="page {{ $page === 'produk' || $page === 'inventory' ? 'active' : '' }}">
            <div style="margin-bottom: 24px;">
                <h1 style="font-size: 24px; color: var(--text-dark); margin: 0 0 4px; font-weight: 500;">Produk</h1>
                <p style="color: var(--muted); margin: 0; font-size: 14px;">Daftar Data Produk</p>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
                <div style="display: flex; gap: 12px; align-items: center;">
                    <select style="width: 180px; padding: 8px 12px; border-radius: 4px; border: 1px solid var(--line); background: white; color: var(--text-dark); font-size: 14px; outline: none;">
                        <option>Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->nama_kategori }}">{{ $cat->nama_kategori }}</option>
                        @endforeach
                    </select>
                    <select style="width: 180px; padding: 8px 12px; border-radius: 4px; border: 1px solid var(--line); background: white; color: var(--text-dark); font-size: 14px; outline: none;">
                        <option>Aktif</option>
                        <option>Nonaktif</option>
                    </select>
                    <button style="padding: 8px 16px; background: #1e293b; color: white; border: none; border-radius: 4px; display: flex; align-items: center; gap: 8px; font-weight: 500; font-size: 14px;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg> Cari
                    </button>
                </div>
                
                @if (auth()->user()->isAdmin())
                <div style="display: flex; gap: 12px; align-items: center;">
                    <button type="button" class="btn primary" style="background: #3b82f6; border: none; border-radius: 4px; padding: 8px 16px; font-weight: 500; font-size: 14px; box-shadow: none;" onclick="document.getElementById('add-product-modal').classList.add('active')">
                        + Tambah Produk
                    </button>
                    <button class="btn" style="background: #06b6d4; color: white; border: none; border-radius: 4px; padding: 8px 16px; font-weight: 500; font-size: 14px; box-shadow: none;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg> Cetak
                    </button>
                </div>
                @endif
            </div>

            @if (auth()->user()->isAdmin())
            <div id="add-product-modal" class="modal-overlay">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2>Form Tambah Produk</h2>
                        <button type="button" style="background:transparent; border:none; color:var(--muted); cursor:pointer; padding:0; display:flex; align-items:center; justify-content:center;" onclick="document.getElementById('add-product-modal').classList.remove('active')">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </button>
                    </div>
                    <form method="POST" action="{{ route('products.store') }}">
                        @csrf
                        <div class="modal-body">
                            <div class="horizontal-field">
                                <label>Nama <span class="required">*</span></label>
                                <div class="input-wrap"><input name="nama_barang" placeholder="Masukan nama produk" required></div>
                            </div>
                            <div class="horizontal-field">
                                <label>Kategori <span class="required">*</span></label>
                                <div class="input-wrap">
                                    <select name="kategori" required>
                                        <option value="">Pilih Kategori Produk</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->nama_kategori }}">{{ $cat->nama_kategori }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="horizontal-field">
                                <label>Satuan <span class="required">*</span></label>
                                <div class="input-wrap"><input name="satuan" placeholder="Masukan satuan produk" required></div>
                            </div>
                            <div class="horizontal-field">
                                <label>Harga Modal <span class="required">*</span></label>
                                <div class="input-wrap"><input name="harga_modal" type="number" min="0" placeholder="0" required></div>
                            </div>
                            <div class="horizontal-field">
                                <label>Harga Jual <span class="required">*</span></label>
                                <div class="input-wrap"><input name="harga_jual" type="number" min="0" placeholder="0" required></div>
                            </div>
                            <div class="horizontal-field">
                                <label>Stok Awal <span class="required">*</span></label>
                                <div class="input-wrap"><input name="stok" type="number" min="0" value="0" required></div>
                            </div>
                            <div class="horizontal-field">
                                <label>Thumbnail</label>
                                <div class="input-wrap">
                                    <div style="display:flex; border:1px solid var(--line); border-radius:6px; overflow:hidden; background:var(--surface);">
                                        <label for="thumbnail_upload" style="background:var(--soft); padding:8px 16px; border-right:1px solid var(--line); cursor:pointer; font-weight:500; font-size:14px; text-align:center; flex:none;">Choose File</label>
                                        <input type="file" id="thumbnail_upload" style="display:none;" accept="image/*" onchange="document.getElementById('thumbnail_text').innerText = this.files[0] ? this.files[0].name : 'No file chosen'">
                                        <div id="thumbnail_text" style="padding:8px 16px; color:var(--muted); font-size:14px; flex:1; display:flex; align-items:center;">No file chosen</div>
                                    </div>
                                </div>
                            </div>
                            <div class="horizontal-field">
                                <label>Barcode <span class="required">*</span></label>
                                <div class="input-wrap" style="display:flex; gap:8px;">
                                    <input name="kode_barang" id="input_kode_barang" placeholder="Masukan barcode" required>
                                    <button type="button" class="btn" style="background:#1e293b; color:white; border:none;" onclick="document.getElementById('input_kode_barang').value = 'BRG' + Math.floor(Math.random() * 1000000)">Generate</button>
                                </div>
                            </div>
                            <div class="horizontal-field" style="align-items:flex-start;">
                                <label style="padding-top:10px;">Deskripsi</label>
                                <div class="input-wrap"><textarea name="deskripsi" placeholder="Masukan deskripsi"></textarea></div>
                            </div>
                            <input type="hidden" name="status" value="active">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn" style="background:#64748b; color:white; border:none;" onclick="document.getElementById('add-product-modal').classList.remove('active')">Close</button>
                            <button type="submit" class="btn primary" style="background:#3b82f6; border:none;">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
            @endif

            <section class="panel" style="padding: 24px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border: 1px solid var(--line); background: white;">
                <h2 style="font-size: 16px; font-weight: 600; margin: 0 0 24px; color: var(--text-dark);">Tabel Produk</h2>
                
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                    <div style="color: var(--muted); font-size: 14px; display: flex; align-items: center; gap: 6px;">
                        Show 
                        <select style="padding: 4px 8px; border: 1px solid var(--line); border-radius: 4px; outline: none; background: white; color: var(--text-dark);">
                            <option>10</option>
                            <option>25</option>
                            <option>50</option>
                        </select> 
                        entries
                    </div>
                    <div style="color: var(--muted); font-size: 14px; display: flex; align-items: center; gap: 8px;">
                        Search: <input type="text" id="search-product" style="padding: 4px 8px; border: 1px solid var(--line); border-radius: 4px; width: 200px; outline: none; min-height: 32px;">
                    </div>
                </div>

                <div class="table" style="overflow-x: auto;">
                    <table id="product-table" style="width: 100%; border-collapse: collapse; min-width: 800px;">
                        <thead>
                            <tr>
                                <th style="padding: 12px 16px; border-bottom: 1px solid var(--line); background: #f8fafc; color: var(--text-dark); font-weight: 600; font-size: 14px; text-align: left; width: 50px;">No <span style="float: right; color: #cbd5e1; font-size: 10px; padding-top:4px;">▲▼</span></th>
                                <th style="padding: 12px 16px; border-bottom: 1px solid var(--line); background: #f8fafc; color: var(--text-dark); font-weight: 600; font-size: 14px; text-align: left;">Nama Produk <span style="float: right; color: #cbd5e1; font-size: 10px; padding-top:4px;">▲▼</span></th>
                                <th style="padding: 12px 16px; border-bottom: 1px solid var(--line); background: #f8fafc; color: var(--text-dark); font-weight: 600; font-size: 14px; text-align: center;">Foto Produk</th>
                                <th style="padding: 12px 16px; border-bottom: 1px solid var(--line); background: #f8fafc; color: var(--text-dark); font-weight: 600; font-size: 14px; text-align: left;">Kategori <span style="float: right; color: #cbd5e1; font-size: 10px; padding-top:4px;">▲▼</span></th>
                                <th style="padding: 12px 16px; border-bottom: 1px solid var(--line); background: #f8fafc; color: var(--text-dark); font-weight: 600; font-size: 14px; text-align: left;">Satuan <span style="float: right; color: #cbd5e1; font-size: 10px; padding-top:4px;">▲▼</span></th>
                                <th style="padding: 12px 16px; border-bottom: 1px solid var(--line); background: #f8fafc; color: var(--text-dark); font-weight: 600; font-size: 14px; text-align: left;">Barcode <span style="float: right; color: #cbd5e1; font-size: 10px; padding-top:4px;">▲▼</span></th>
                                <th style="padding: 12px 16px; border-bottom: 1px solid var(--line); background: #f8fafc; color: var(--text-dark); font-weight: 600; font-size: 14px; text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $index => $product)
                            <tr style="border-bottom: 1px solid var(--line);">
                                <td style="padding: 16px; color: var(--muted); font-size: 14px;">{{ $index + 1 }}</td>
                                <td style="padding: 16px; color: var(--muted); font-size: 14px;">{{ $product->nama_barang }}</td>
                                <td style="padding: 16px; text-align: center;">
                                    <div style="width: 48px; height: 48px; background: white; border-radius: 4px; display: inline-flex; align-items: center; justify-content: center; color: #9ca3af; border: 2px solid var(--text-dark);">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                                    </div>
                                </td>
                                <td style="padding: 16px; color: var(--muted); font-size: 14px;">{{ $product->kategori }}</td>
                                <td style="padding: 16px; color: var(--muted); font-size: 14px;">{{ $product->satuan }}</td>
                                <td style="padding: 16px; color: var(--muted); font-size: 14px;">{{ $product->kode_barang }}</td>
                                <td style="padding: 16px; text-align: center; white-space: nowrap;">
                                    @if(auth()->user()->isAdmin())
                                    <button type="button" style="background: transparent; border: none; color: #3b82f6; cursor: pointer; padding: 0 4px; display:inline-flex; align-items:center;" onclick="document.getElementById('edit-product-modal-{{ $product->id }}').classList.add('active')" title="Edit">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                    </button>
                                    <div id="edit-product-modal-{{ $product->id }}" class="modal-overlay">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h2>Form Edit Produk</h2>
                                                <button type="button" style="background:transparent; border:none; color:var(--muted); cursor:pointer; padding:0; display:flex; align-items:center; justify-content:center;" onclick="document.getElementById('edit-product-modal-{{ $product->id }}').classList.remove('active')">
                                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                                </button>
                                            </div>
                                            <form method="POST" action="{{ route('products.update', $product) }}">
                                                @csrf @method('PUT')
                                                <div class="modal-body">
                                                    <div class="horizontal-field">
                                                        <label>Nama <span class="required">*</span></label>
                                                        <div class="input-wrap"><input name="nama_barang" value="{{ $product->nama_barang }}" placeholder="Masukan nama produk" required></div>
                                                    </div>
                                                    <div class="horizontal-field">
                                                        <label>Kategori <span class="required">*</span></label>
                                                        <div class="input-wrap">
                                                            <select name="kategori" required>
                                                                <option value="">Pilih Kategori Produk</option>
                                                                @foreach($categories as $cat)
                                                                    <option value="{{ $cat->nama_kategori }}" @selected($product->kategori === $cat->nama_kategori)>{{ $cat->nama_kategori }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="horizontal-field">
                                                        <label>Satuan <span class="required">*</span></label>
                                                        <div class="input-wrap"><input name="satuan" value="{{ $product->satuan }}" placeholder="Masukan satuan produk" required></div>
                                                    </div>
                                                    <div class="horizontal-field">
                                                        <label>Harga Modal <span class="required">*</span></label>
                                                        <div class="input-wrap"><input name="harga_modal" type="number" min="0" value="{{ $product->harga_modal }}" placeholder="0" required></div>
                                                    </div>
                                                    <div class="horizontal-field">
                                                        <label>Harga Jual <span class="required">*</span></label>
                                                        <div class="input-wrap"><input name="harga_jual" type="number" min="0" value="{{ $product->harga_jual }}" placeholder="0" required></div>
                                                    </div>
                                                    <div class="horizontal-field">
                                                        <label>Stok Awal <span class="required">*</span></label>
                                                        <div class="input-wrap"><input name="stok" type="number" min="0" value="{{ $product->stok }}" required></div>
                                                    </div>
                                                    <div class="horizontal-field">
                                                        <label>Thumbnail</label>
                                                        <div class="input-wrap">
                                                            <div style="display:flex; border:1px solid var(--line); border-radius:6px; overflow:hidden; background:var(--surface);">
                                                                <label for="edit_thumbnail_upload_{{ $product->id }}" style="background:var(--soft); padding:8px 16px; border-right:1px solid var(--line); cursor:pointer; font-weight:500; font-size:14px; text-align:center; flex:none;">Choose File</label>
                                                                <input type="file" id="edit_thumbnail_upload_{{ $product->id }}" style="display:none;" accept="image/*" onchange="document.getElementById('edit_thumbnail_text_{{ $product->id }}').innerText = this.files[0] ? this.files[0].name : 'No file chosen'">
                                                                <div id="edit_thumbnail_text_{{ $product->id }}" style="padding:8px 16px; color:var(--muted); font-size:14px; flex:1; display:flex; align-items:center;">No file chosen</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="horizontal-field">
                                                        <label>Barcode <span class="required">*</span></label>
                                                        <div class="input-wrap" style="display:flex; gap:8px;">
                                                            <input name="kode_barang" id="edit_kode_barang_{{ $product->id }}" value="{{ $product->kode_barang }}" placeholder="Masukan barcode" required>
                                                            <button type="button" class="btn" style="background:#1e293b; color:white; border:none;" onclick="document.getElementById('edit_kode_barang_{{ $product->id }}').value = 'BRG' + Math.floor(Math.random() * 1000000)">Generate</button>
                                                        </div>
                                                    </div>
                                                    <div class="horizontal-field" style="align-items:flex-start;">
                                                        <label style="padding-top:10px;">Deskripsi</label>
                                                        <div class="input-wrap"><textarea name="deskripsi" placeholder="Masukan deskripsi"></textarea></div>
                                                    </div>
                                                    <input type="hidden" name="status" value="{{ $product->status }}">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn" style="background:#64748b; color:white; border:none;" onclick="document.getElementById('edit-product-modal-{{ $product->id }}').classList.remove('active')">Close</button>
                                                    <button type="submit" class="btn primary" style="background:#3b82f6; border:none;">Simpan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <form method="POST" action="{{ route('products.destroy', $product) }}" style="display: inline;" onsubmit="return confirm('Hapus produk ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" style="background: transparent; border: none; color: #ef4444; cursor: pointer; padding: 0; display:inline-flex; align-items:center;">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                        </button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        </section>

        <section id="stok" class="page {{ $page === 'stok' ? 'active' : '' }}">
            <div class="top"><div><p class="eyebrow">Master data</p><h1>Data Stok</h1><p class="sub">Pantau sisa stok barang saat ini.</p></div></div>
            <section class="panel">
                <h2>Data Stok</h2>
                <div class="table">
                    <table>
                        <thead><tr><th>Kode</th><th>Nama</th><th>Kategori</th><th>Stok Tersedia</th><th>Satuan</th></tr></thead>
                        <tbody>
                            @foreach ($products as $product)
                            <tr><td><strong>{{ $product->kode_barang }}</strong></td><td>{{ $product->nama_barang }}</td><td>{{ $product->kategori }}</td><td><strong style="font-size:16px;">{{ $product->stok }}</strong></td><td>{{ $product->satuan }}</td></tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        </section>

        @if (auth()->user()->isAdmin())
        <section id="stockin" class="page {{ $page === 'stockin' ? 'active' : '' }}">
            <div class="top"><div><p class="eyebrow">Penambahan stok</p><h1>Barang Masuk</h1><p class="sub">Simpan barang masuk, stok otomatis bertambah.</p></div></div>
            <section class="panel"><form method="POST" action="{{ route('stock-ins.store') }}" enctype="multipart/form-data" class="form-grid">@csrf
                <div class="field"><label>Tanggal</label><input name="tanggal" type="date" value="{{ now()->toDateString() }}" required></div><div class="field"><label>Barang</label><select name="product_id">@foreach($products as $product)<option value="{{ $product->id }}">{{ $product->nama_barang }}</option>@endforeach</select></div><div class="field"><label>Jumlah Masuk</label><input name="qty" type="number" min="1" required></div><div class="field"><label>Supplier</label><input name="supplier" value="Gudang Dalwa"></div>
                <div class="field" style="grid-column: span 2"><label>Nota Pembelian</label><input type="file" name="nota_pembelian" accept=".jpg,.jpeg,.png,.pdf" style="padding: 6px;"></div>
                <div class="field" style="grid-column: span 2"><label>Surat Jalan</label><input type="file" name="surat_jalan" accept=".jpg,.jpeg,.png,.pdf" style="padding: 6px;"></div>
                <div class="field" style="grid-column:1/-1"><label>Keterangan</label><textarea name="keterangan"></textarea></div><button class="btn primary" type="submit">@include('partials.icon', ['name' => 'check']) Simpan</button>
            </form></section>
            <section class="panel"><h2>Riwayat Barang Masuk</h2><div class="list">@foreach($stockIns as $stock)<div class="activity"><span class="tile">@include('partials.icon', ['name' => 'truck'])</span><div class="grow"><strong>{{ $stock->product->nama_barang }} +{{ $stock->qty }} {{ $stock->product->satuan }}</strong><span class="muted">{{ $stock->supplier }} - {{ $stock->tanggal->format('d/m/Y') }}</span>
                <div style="display:flex; gap:8px; margin-top:4px;">
                    @if($stock->nota_pembelian)<a href="{{ asset('storage/' . $stock->nota_pembelian) }}" target="_blank" class="badge gray" style="text-decoration:none">@include('partials.icon', ['name' => 'receipt']) Lihat Nota</a>@endif
                    @if($stock->surat_jalan)<a href="{{ asset('storage/' . $stock->surat_jalan) }}" target="_blank" class="badge gray" style="text-decoration:none">@include('partials.icon', ['name' => 'box']) Lihat Surat Jalan</a>@endif
                </div>
            </div><span class="badge green">Masuk</span></div>@endforeach</div></section>
        </section>
        @endif

        <section id="transactions" class="page {{ $page === 'transactions' ? 'active' : '' }}">
            <div class="top"><div><p class="eyebrow">Kasir & Data</p><h1>Transaksi Penjualan</h1><p class="sub">Pilih barang, input qty, pembayaran, lalu simpan nota.</p></div></div>
            <form method="POST" action="{{ route('transactions.store') }}" id="sale-form" class="split" style="margin-bottom:16px">@csrf
                <section class="panel"><div class="toolbar" style="margin-bottom:14px"><h2>Katalog Produk</h2><input id="search-sale" placeholder="Cari produk" style="max-width:320px"></div><div class="catalog" id="catalog">@foreach($activeProducts as $product)<button type="button" data-add-item data-id="{{ $product->id }}" data-name="{{ $product->nama_barang }}" data-price="{{ $product->harga_jual }}" data-stock="{{ $product->stok }}" data-unit="{{ $product->satuan }}"><span class="thumb">@include('partials.product-icon')</span><div><h3>{{ $product->nama_barang }}</h3><span class="muted">{{ $product->kode_barang }} - stok {{ $product->stok }} {{ $product->satuan }}</span></div><span class="price">{{ $rupiah($product->harga_jual) }}</span></button>@endforeach</div></section>
                <aside class="panel summary"><div class="panel-head"><div><h2>Transaksi baru</h2></div><span class="badge green">{{ auth()->user()->name }}</span></div><div id="cart-list" class="list" style="margin:14px 0"></div><div id="cart-empty" class="notice err">Transaksi masih kosong.</div><div id="cart-inputs"></div><div class="total big"><span>Total</span><strong id="grand-total">Rp 0</strong></div><div class="field" style="margin-top:12px"><label>Diskon Per Produk</label><input id="discount-per-product" name="discount_per_product" type="number" value="0" min="0"></div><div class="tabs"><button class="active" type="button" data-payment-tab="cash">Tunai</button><button type="button" data-payment-tab="transfer">Transfer</button></div><input type="hidden" name="payment_type" id="payment-type" value="cash"><div id="cash-fields" class="field"><label>Uang Diterima</label><input id="paid" name="uang_diterima" type="number" value="0" min="0"><div class="total big"><span>Kembalian</span><strong id="change">Rp 0</strong></div></div><div id="transfer-fields" class="form-grid" style="grid-template-columns:1fr;display:none"><div class="field"><label>Nama Bank</label><input name="bank_name" value="BSI"></div><div class="field"><label>No Referensi</label><input name="reference_number"></div><div class="field"><label>Status</label><select name="payment_status"><option value="paid">Lunas</option><option value="pending">Pending</option></select></div></div><button class="btn primary" style="width:100%;margin-top:14px" type="submit">@include('partials.icon', ['name' => 'check']) Simpan & Cetak Nota</button></aside>
            </form>
        </section>

        @if (auth()->user()->isAdmin())
        <section id="history" class="page {{ $page === 'history' ? 'active' : '' }}">
            <div class="top"><div><p class="eyebrow">Laporan</p><h1>Riwayat Transaksi</h1><p class="sub">Daftar seluruh riwayat transaksi penjualan.</p></div></div>
            <section class="panel">
                <div class="table">
                    <table>
                        <thead><tr><th>No</th><th>Tanggal</th><th>Kasir</th><th>Metode</th><th>Total</th><th>Aksi</th></tr></thead>
                        <tbody>
                            @foreach($transactions as $transaction)
                            <tr>
                                <td>{{ $transaction->kode_transaksi }}</td>
                                <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ $transaction->user->name }}</td>
                                <td>{{ $transaction->payment_type }}</td>
                                <td class="price">{{ $rupiah($transaction->total) }}</td>
                                <td>
                                    <div style="display:flex;gap:8px;align-items:center">
                                        <a class="btn soft" href="{{ route('transactions.receipt', $transaction) }}">Nota</a>
                                        @if(auth()->user()->isAdmin())
                                        <form method="POST" action="{{ route('transactions.destroy', $transaction) }}" onsubmit="return confirm('Hapus transaksi ini? Stok barang akan dikembalikan.')" style="margin:0">
                                            @csrf @method('DELETE')
                                            <button class="btn danger" type="submit">Hapus</button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        </section>

        <section id="cashiers" class="page {{ $page === 'cashiers' ? 'active' : '' }}">
            <div class="top"><div><p class="eyebrow">User role</p><h1>Manajemen Kasir</h1><p class="sub">Tambah kasir/admin, aktifkan, dan reset password.</p></div></div>
            <section class="panel"><h2>Tambah User</h2><form method="POST" action="{{ route('cashiers.store') }}" class="form-grid">@csrf <div class="field"><label>Nama</label><input name="name" required></div><div class="field"><label>Username</label><input name="username" required></div><div class="field"><label>Password</label><input name="password" type="password" required></div><div class="field"><label>Role</label><select name="role"><option value="cashier">Kasir</option><option value="admin">Admin</option></select></div><div class="field"><label>Status</label><select name="status"><option value="active">Aktif</option><option value="inactive">Nonaktif</option></select></div><button class="btn primary" type="submit">@include('partials.icon', ['name' => 'plus']) Simpan User</button></form></section>
            <section class="panel"><h2>Daftar User</h2><div class="table"><table><thead><tr><th>Nama</th><th>Username</th><th>Role</th><th>Status</th><th>Penjualan Hari Ini</th><th>Aksi</th></tr></thead><tbody>@foreach($cashiers as $cashier)<tr><td><strong>{{ $cashier->name }}</strong></td><td>{{ $cashier->username }}</td><td>{{ ucfirst($cashier->role) }}</td><td><span class="badge {{ $cashier->status === 'active' ? 'green' : 'red' }}">{{ $cashier->status }}</span></td><td>{{ $rupiah($cashier->today_sales ?? 0) }}</td><td><details><summary class="btn soft">@include('partials.icon', ['name' => 'edit']) Edit</summary><form class="inline-form" method="POST" action="{{ route('cashiers.update', $cashier) }}" style="margin-top:8px">@csrf @method('PUT')<input name="name" value="{{ $cashier->name }}" required><input name="username" value="{{ $cashier->username }}" required><input name="password" type="password" placeholder="Kosongkan jika tetap"><select name="role"><option value="cashier" @selected($cashier->role === 'cashier')>Kasir</option><option value="admin" @selected($cashier->role === 'admin')>Admin</option></select><select name="status"><option value="active" @selected($cashier->status === 'active')>Aktif</option><option value="inactive" @selected($cashier->status === 'inactive')>Nonaktif</option></select><button class="btn primary" type="submit">Update</button></form></details><form class="inline-form" method="POST" action="{{ route('cashiers.reset-password', $cashier) }}" style="margin-top:8px">@csrf @method('PUT')<input name="password" type="password" placeholder="Password baru" required><button class="btn soft" type="submit">@include('partials.icon', ['name' => 'key']) Reset</button></form></td></tr>@endforeach</tbody></table></div></section>
        </section>

        <section id="settings-general" class="page {{ $page === 'settings-general' ? 'active' : '' }}">
            <div class="top"><div><p class="eyebrow">Pengaturan</p><h1>Umum</h1><p class="sub">Ringkasan konfigurasi aplikasi dan akun yang sedang aktif.</p></div></div>
            <div class="grid4">
                <article class="card"><p class="sub">Nama Aplikasi</p><div class="value"><small>Dalwa Water Management System</small></div></article>
                <article class="card"><p class="sub">Cabang</p><div class="value"><small>Dalwa Water Tegal</small></div></article>
                <article class="card"><p class="sub">Pengguna Aktif</p><div class="value"><small>{{ auth()->user()->name }}</small></div></article>
                <article class="card"><p class="sub">Role</p><div class="value"><small>{{ ucfirst(auth()->user()->role) }}</small></div></article>
            </div>
            <section class="panel">
                <h2>Informasi Sistem</h2>
                <div class="table">
                    <table>
                        <tbody>
                            <tr><th>Status Akun</th><td><span class="badge {{ auth()->user()->status === 'active' ? 'green' : 'red' }}">{{ auth()->user()->status }}</span></td></tr>
                            <tr><th>Username</th><td>{{ auth()->user()->username }}</td></tr>
                            <tr><th>Email</th><td>{{ auth()->user()->email }}</td></tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </section>

        <section id="settings-profile" class="page {{ $page === 'settings-profile' ? 'active' : '' }}">
            <div class="top"><div><p class="eyebrow">Pengaturan</p><h1>Edit Profil</h1><p class="sub">Perbarui nama, username, dan email akun.</p></div></div>
            <section class="panel">
                <form method="POST" action="{{ route('profile.update') }}" class="form-grid">
                    @csrf @method('PUT')
                    <div class="field"><label>Nama</label><input name="name" value="{{ old('name', auth()->user()->name) }}" required></div>
                    <div class="field"><label>Username</label><input name="username" value="{{ old('username', auth()->user()->username) }}" required></div>
                    <div class="field"><label>Email</label><input name="email" type="email" value="{{ old('email', auth()->user()->email) }}" required></div>
                    <div class="field"><label>Role</label><input value="{{ ucfirst(auth()->user()->role) }}" disabled></div>
                    <button class="btn primary" type="submit">@include('partials.icon', ['name' => 'check']) Simpan Profil</button>
                </form>
            </section>
        </section>

        <section id="settings-password" class="page {{ $page === 'settings-password' ? 'active' : '' }}">
            <div class="top"><div><p class="eyebrow">Pengaturan</p><h1>Ganti Password</h1><p class="sub">Gunakan password minimal 8 karakter.</p></div></div>
            <section class="panel">
                <form method="POST" action="{{ route('password.update') }}" class="form-grid">
                    @csrf @method('PUT')
                    <div class="field"><label>Password Lama</label><input name="current_password" type="password" required autocomplete="current-password"></div>
                    <div class="field"><label>Password Baru</label><input name="password" type="password" required autocomplete="new-password"></div>
                    <div class="field"><label>Konfirmasi Password</label><input name="password_confirmation" type="password" required autocomplete="new-password"></div>
                    <button class="btn primary" type="submit">@include('partials.icon', ['name' => 'lock']) Ganti Password</button>
                </form>
            </section>
        </section>

        <section id="reports" class="page {{ $page === 'reports' ? 'active' : '' }}">
            <div class="top" style="flex-direction: column; align-items: flex-start; gap: 20px;">
                <div style="display: flex; align-items: flex-start; justify-content: space-between; width: 100%;">
                    <div><p class="eyebrow">Laporan</p><h1>Rekap Penjualan</h1><p class="sub">Laporan harian, bulanan, metode pembayaran, dan export.</p></div>
                    <a class="btn primary" href="{{ route('reports.export-csv', ['start_date' => $startDate ?? '', 'end_date' => $endDate ?? '']) }}">@include('partials.icon', ['name' => 'download']) Export Excel CSV</a>
                </div>
                <div class="actions" style="width: 100%;">
                    <form method="GET" action="/" style="display: flex; align-items: center; gap: 18px; width: 100%; padding-top: 8px; border-top: 1px solid var(--line);">
                        <input type="hidden" name="page" value="reports">
                        <span style="font-size: 16px; font-weight: 500; color: var(--text);">Waktu</span>
                        <div style="display: flex; align-items: center; gap: 14px;">
                            <input type="date" name="start_date" value="{{ $startDate }}" required style="padding: 10px 12px; border: 1px solid var(--soft); border-radius: 6px; background: white; outline: none; font-family: inherit; font-size: 14px; color: var(--muted); cursor: pointer; min-width: 135px; box-shadow: 0 1px 2px rgba(0,0,0,0.03);" onchange="this.form.submit()">
                            <span style="font-weight: 500; color: var(--text);">-</span>
                            <input type="date" name="end_date" value="{{ $endDate }}" required style="padding: 10px 12px; border: 1px solid var(--soft); border-radius: 6px; background: white; outline: none; font-family: inherit; font-size: 14px; color: var(--muted); cursor: pointer; min-width: 135px; box-shadow: 0 1px 2px rgba(0,0,0,0.03);" onchange="this.form.submit()">
                        </div>
                    </form>
                </div>
            </div>
            <div class="grid4">
                <article class="card"><p class="sub">Total transaksi</p><div class="value">{{ $reportStats['transactions'] }}</div></article>
                <article class="card"><p class="sub">Pendapatan</p><div class="value">{{ $rupiah($reportStats['income']) }}</div></article>
                <article class="card"><p class="sub">Produk terjual</p><div class="value">{{ $reportStats['items'] ?? 0 }}</div></article>
                <article class="card"><p class="sub">Tunai / Transfer</p><div class="value"><small>{{ $rupiah($reportStats['cash_income']) }} / {{ $rupiah($reportStats['transfer_income']) }}</small></div></article>
            </div>
            <section class="panel"><h2>Transaksi Terbaru</h2><div class="table"><table><thead><tr><th>No</th><th>Tanggal</th><th>Kasir</th><th>Metode</th><th>Total</th><th>Nota</th></tr></thead><tbody>@foreach($reportTransactions as $transaction)<tr><td>{{ $transaction->kode_transaksi }}</td><td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td><td>{{ $transaction->user->name }}</td><td>{{ $transaction->payment_type }}</td><td class="price">{{ $rupiah($transaction->total) }}</td><td><a class="btn soft" href="{{ route('transactions.receipt', $transaction) }}">Nota</a></td></tr>@endforeach</tbody></table></div></section>
        </section>
        @endif

        </main>

        <footer class="no-print" style="text-align: center; padding: 24px; font-size: 13px; color: var(--muted); border-top: 1px solid var(--line); margin-top: auto;">
            CEO Restu Gusti - 2026 &copy;
        </footer>
    </div>

    <nav class="mobile-nav">
        <button class="{{ $page === 'dashboard' ? 'active' : '' }}" data-page-target="dashboard">@include('partials.icon', ['name' => 'dashboard']) Dash</button>
        <button class="{{ in_array($page, ['kategori', 'produk', 'stok', 'stockin', 'inventory']) ? 'active' : '' }}" data-page-target="produk">@include('partials.icon', ['name' => 'box']) Barang</button>
        <button class="{{ $page === 'transactions' ? 'active' : '' }}" data-page-target="transactions">@include('partials.icon', ['name' => 'wallet']) Transaksi</button>
        @if(auth()->user()->isAdmin())<button class="{{ in_array($page, ['history', 'reports']) ? 'active' : '' }}" data-page-target="reports">@include('partials.icon', ['name' => 'chart']) Laporan</button>@endif
        @if(auth()->user()->isAdmin())<button class="{{ in_array($page, ['settings-general', 'settings-profile', 'settings-password']) ? 'active' : '' }}" data-page-target="settings-general">@include('partials.icon', ['name' => 'settings']) Atur</button>@endif
    </nav>
</div>

<script>
const rupiah=value=>new Intl.NumberFormat('id-ID',{style:'currency',currency:'IDR',maximumFractionDigits:0}).format(value).replace(/\s/g,' ');
const activate=id=>{document.querySelectorAll('.page').forEach(p=>{p.classList.toggle('active',p.id===id);p.classList.toggle('print',p.id===id&&id==='receipt')});document.querySelectorAll('[data-page-target]').forEach(b=>{b.classList.toggle('active',b.dataset.pageTarget===id);if(b.dataset.pageTarget===id&&b.closest('.submenu'))b.closest('.submenu').classList.add('show');});history.replaceState(null,'','?page='+id);scrollTo({top:0,behavior:'smooth'})};
document.querySelectorAll('[data-page-target],[data-page-jump]').forEach(b=>b.addEventListener('click',()=>activate(b.dataset.pageTarget||b.dataset.pageJump)));
const cart=new Map,cartList=document.getElementById('cart-list'),cartInputs=document.getElementById('cart-inputs'),empty=document.getElementById('cart-empty'),grand=document.getElementById('grand-total'),paid=document.getElementById('paid'),discountPerProduct=document.getElementById('discount-per-product'),change=document.getElementById('change');
function renderCart(){if(!cartList)return;cartList.innerHTML='';cartInputs.innerHTML='';let total=0,index=0;const discount=Math.max(0,Number(discountPerProduct?.value||0));cart.forEach(item=>{const discountedPrice=Math.max(0,item.price-discount),line=item.qty*discountedPrice;total+=line;cartList.insertAdjacentHTML('beforeend',`<div class="cart-row"><span class="thumb">@include('partials.product-icon')</span><div class="grow"><strong>${item.name}</strong><span class="muted">${rupiah(item.price)} / ${item.unit} | stok ${item.stock}</span><div class="qty"><button type="button" data-minus="${item.id}">-</button><strong>${item.qty}</strong><button type="button" data-plus="${item.id}">+</button></div></div><span class="price">${rupiah(line)}</span></div>`);cartInputs.insertAdjacentHTML('beforeend',`<input type="hidden" name="items[${index}][product_id]" value="${item.id}"><input type="hidden" name="items[${index}][qty]" value="${item.qty}">`);index++});empty.style.display=cart.size?'none':'block';grand.textContent=rupiah(total);change.textContent=rupiah(Math.max(0,Number(paid.value||0)-total))}
document.addEventListener('click',e=>{const add=e.target.closest('[data-add-item]'),plus=e.target.closest('[data-plus]'),minus=e.target.closest('[data-minus]');if(add){const id=add.dataset.id,item=cart.get(id)||{id,name:add.dataset.name,price:Number(add.dataset.price),stock:Number(add.dataset.stock),unit:add.dataset.unit,qty:0};if(item.qty<item.stock)item.qty++;cart.set(id,item);renderCart()}if(plus){const item=cart.get(plus.dataset.plus);if(item&&item.qty<item.stock)item.qty++;renderCart()}if(minus){const item=cart.get(minus.dataset.minus);if(item){item.qty--;if(item.qty<1)cart.delete(item.id)}renderCart()}});
paid?.addEventListener('input',renderCart);
discountPerProduct?.addEventListener('input',renderCart);
document.querySelectorAll('[data-payment-tab]').forEach(tab=>tab.addEventListener('click',()=>{const cash=tab.dataset.paymentTab==='cash';document.querySelectorAll('[data-payment-tab]').forEach(t=>t.classList.toggle('active',t===tab));document.getElementById('payment-type').value=tab.dataset.paymentTab;document.getElementById('cash-fields').style.display=cash?'grid':'none';document.getElementById('transfer-fields').style.display=cash?'none':'grid';if(!cash)paid.value=0;renderCart()}));
document.getElementById('sale-form')?.addEventListener('submit',e=>{if(!cart.size){e.preventDefault();empty.style.display='block'}});
document.getElementById('search-product')?.addEventListener('input',e=>{const q=e.target.value.toLowerCase();document.querySelectorAll('#product-table tbody tr').forEach(r=>r.style.display=r.innerText.toLowerCase().includes(q)?'':'none')});
document.getElementById('search-sale')?.addEventListener('input',e=>{const q=e.target.value.toLowerCase();document.querySelectorAll('#catalog [data-add-item]').forEach(r=>r.style.display=r.innerText.toLowerCase().includes(q)?'grid':'none')});
renderCart();
</script>
@endguest
</body>
</html>
