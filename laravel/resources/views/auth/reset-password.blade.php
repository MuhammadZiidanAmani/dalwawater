@extends('layouts.auth')

@section('title', 'Reset Password - Dalwa Water')

@section('content')
    <main class="auth">
        <section class="login">
            <div class="logo">
                <span class="drop">@include('partials.icon', ['name' => 'key'])</span>
                <div><h1>Reset Password</h1><p>Reset password dilakukan melalui menu Manajemen Kasir.</p></div>
            </div>
        </section>
    </main>
@endsection
