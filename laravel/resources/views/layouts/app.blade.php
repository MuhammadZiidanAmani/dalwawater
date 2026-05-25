<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dalwa Water Management System')</title>
    @stack('styles')
</head>
<body>
    <div class="shell">
        @include('partials.sidebar')

        <main class="main">
            @include('partials.alerts')
            @yield('content')
        </main>
    </div>

    @include('partials.footer')
    @include('partials.scripts')
    @stack('scripts')
</body>
</html>
