@extends('layouts.auth')

@section('title', 'Lupa Password - Dalwa Water')

@section('content')
    <main class="auth">
        <section class="login">
            <div class="logo">
                <span class="drop">@include('partials.icon', ['name' => 'key'])</span>
                <div><h1>Lupa Password</h1><p>Hubungi admin untuk reset password kasir.</p></div>
            </div>
        </section>
    </main>
@endsection
