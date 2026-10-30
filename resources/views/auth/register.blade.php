@extends('layouts.guest')

@section('content')
<div class="auth-card mx-auto">
    <div class="auth-header">
        <i class="bi bi-person-plus fs-1"></i>
        <h3 class="mt-2">Daftar Akun Baru</h3>
        <p class="mb-0 text-muted">Bergabung dengan Griya Kreatif Deyalina</p>
    </div>
    
    <div class="auth-body">
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div class="mb-3">
                <label for="name" class="form-label">Nama Lengkap</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" 
                           name="name" value="{{ old('name') }}" required autofocus placeholder="Nama lengkap Anda">
                </div>
                @error('name')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <!-- Email Address -->
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                           name="email" value="{{ old('email') }}" required placeholder="nama@email.com">
                </div>
                @error('email')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <!-- Phone -->
            <div class="mb-3">
                <label for="phone" class="form-label">Nomor Telepon (Opsional)</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-phone"></i></span>
                    <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" 
                           name="phone" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx">
                </div>
                @error('phone')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <!-- Address -->
            <div class="mb-3">
                <label for="address" class="form-label">Alamat (Opsional)</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                    <textarea id="address" class="form-control @error('address') is-invalid @enderror" 
                              name="address" rows="2" placeholder="Alamat lengkap untuk pengiriman">{{ old('address') }}</textarea>
                </div>
                @error('address')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                           name="password" required placeholder="Minimal 8 karakter">
                </div>
                @error('password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                    <input id="password_confirmation" type="password" class="form-control" 
                           name="password_confirmation" required placeholder="Ulangi password">
                </div>
            </div>

            <!-- Register Button -->
            <button type="submit" class="btn btn-primary mb-3">
                <i class="bi bi-person-check"></i> Daftar
            </button>

            <!-- Login Link -->
            <div class="text-center">
                <span class="text-muted">Sudah punya akun?</span>
                <a href="{{ route('login') }}" class="auth-link">Masuk</a>
            </div>
        </form>
    </div>
</div>
@endsection