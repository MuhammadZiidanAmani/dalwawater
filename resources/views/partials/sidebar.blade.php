@php($page = request('page', 'dashboard'))

<aside class="side">
    <div class="brand">
        <span class="drop" style="background:rgba(255,255,255,.14);color:white">@include('partials.icon', ['name' => 'dashboard'])</span>
        <div><strong>Dalwa Water</strong><span>{{ auth()->user()?->role === 'admin' ? 'Admin Panel' : 'Kasir POS' }}</span></div>
    </div>
    <nav class="nav">
        <button class="{{ $page === 'dashboard' ? 'active' : '' }}" data-page-target="dashboard">@include('partials.icon', ['name' => 'dashboard']) Dashboard</button>
        <button class="{{ $page === 'inventory' ? 'active' : '' }}" data-page-target="inventory">@include('partials.icon', ['name' => 'box']) Inventory</button>
        @if (auth()->user()?->isAdmin())
            <button class="{{ $page === 'stockin' ? 'active' : '' }}" data-page-target="stockin">@include('partials.icon', ['name' => 'truck']) Barang Masuk</button>
        @endif
        <button class="{{ $page === 'pos' ? 'active' : '' }}" data-page-target="pos">@include('partials.icon', ['name' => 'cart']) POS Penjualan</button>
        @if (auth()->user()?->isAdmin())
            <button class="{{ $page === 'cashiers' ? 'active' : '' }}" data-page-target="cashiers">@include('partials.icon', ['name' => 'users']) Kasir</button>
            <button class="{{ $page === 'reports' ? 'active' : '' }}" data-page-target="reports">@include('partials.icon', ['name' => 'chart']) Laporan</button>
        @endif
        <button class="{{ $page === 'receipt' ? 'active' : '' }}" data-page-target="receipt">@include('partials.icon', ['name' => 'receipt']) Nota</button>
    </nav>
    <div class="side-foot">
        <strong>{{ auth()->user()?->name }}</strong><br><span style="color:rgba(255,255,255,.72)">{{ auth()->user()?->username }}</span>
        <form method="POST" action="{{ route('logout') }}" style="margin-top:12px">@csrf<button class="btn" type="submit" style="width:100%">Logout</button></form>
    </div>
</aside>
