@extends('layouts.guest')

@section('content')
<div class="auth-card mx-auto">
    <div class="auth-header">
        <i class="bi bi-shop fs-1"></i>
        <h3 class="mt-2">Griya Kreatif Deyalina</h3>
        <p class="mb-0 text-muted">Masuk ke Akun Anda</p>
    </div>
    
    <div class="auth-body">
        <!-- Session Status -->
        @if (session('status'))
            <div class="alert alert-success mb-3">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                           name="email" value="{{ old('email') }}" required autofocus placeholder="nama@email.com">
                </div>
                @error('email')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                           name="password" required placeholder="Masukkan password">
                </div>
                @error('password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
                <label class="form-check-label" for="remember_me">Ingat Saya</label>
            </div>

            <!-- Forgot Password Link -->
            <div class="mb-3">
                @if (Route::has('password.request'))
                    <a class="auth-link small" href="{{ route('password.request') }}">
                        Lupa Password?
                    </a>
                @endif
            </div>

            <!-- Login Button -->
            <button type="submit" class="btn btn-primary mb-3">
                <i class="bi bi-box-arrow-in-right"></i> Masuk
            </button>

            <!-- Register Link -->
            <div class="text-center">
                <span class="text-muted">Belum punya akun?</span>
                <a href="{{ route('register') }}" class="auth-link">Daftar Sekarang</a>
            </div>
        </form>
    </div>
</div>
@endsection