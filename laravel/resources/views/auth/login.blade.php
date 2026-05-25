@extends('layouts.auth')

@section('title', 'Login - Dalwa Water')

@section('content')
    <main class="auth">
        <section class="login">
            <div class="logo">
                <span class="drop">@include('partials.icon', ['name' => 'dashboard'])</span>
                <div><h1>Dalwa Water</h1><p>Management System</p></div>
            </div>

            @include('partials.alerts')

            <form method="POST" action="{{ route('login') }}" class="list">
                @csrf
                <div class="field">
                    <label for="username">Username</label>
                    <input id="username" name="username" value="{{ old('username', 'admin') }}" required autofocus>
                </div>
                <div class="field">
                    <label for="password">Password</label>
                    <input id="password" name="password" type="password" value="password" required>
                </div>
                <button class="btn" type="submit">@include('partials.icon', ['name' => 'check']) Login</button>
                <p class="sub">Demo awal: <strong>admin/password</strong> atau <strong>kasir/password</strong>.</p>
            </form>
        </section>
    </main>
@endsection
