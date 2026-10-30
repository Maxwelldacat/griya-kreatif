<x-guest-layout>
    <!-- Judul & Deskripsi Singkat -->
    <div class="text-center mb-4">
        <h4 class="fw-bold mb-2" style="color: #4a154b;">Lupa Password?</h4>
        <p class="text-muted small" style="line-height: 1.6;">
            Masukkan email Anda. Kami akan mengirimkan link untuk mengatur ulang password.
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4 text-success small" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Input -->
        <div class="mb-4">
            <label for="email" class="form-label fw-bold small">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="nama@email.com">
            @error('email')
                <small class="text-danger mt-1 d-block">{{ $message }}</small>
            @enderror
        </div>

        <!-- Tombol Submit -->
        <button type="submit" class="w-100">
            Kirim Link Reset
        </button>
        
        <!-- Link Kembali -->
        <div class="text-center mt-4">
            <a href="{{ route('login') }}" class="small text-muted text-decoration-none">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Login
            </a>
        </div>
    </form>
</x-guest-layout>