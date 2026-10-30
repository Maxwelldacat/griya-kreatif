@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<style>
    body { background-color: #faf8f5; }
    .header-gradient {
        background: linear-gradient(90deg, #4a154b 0%, #d33a3a 100%);
        color: white;
        padding: 20px 0;
        margin-bottom: 2rem;
    }
    .btn-back {
        background: transparent; border: 1px solid white; color: white;
        border-radius: 8px; width: 40px; height: 40px;
        display: flex; align-items: center; justify-content: center;
        text-decoration: none;
    }
    .card-custom {
        border: none; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        background-color: white; padding: 1.5rem; margin-bottom: 1.5rem;
    }
    .form-control-custom {
        background-color: #f8f9fa; border: 1px solid #eee; border-radius: 8px; padding: 10px 15px;
    }
    .form-control-custom:focus {
        border-color: #8a264a; box-shadow: 0 0 0 0.2rem rgba(138, 38, 74, 0.1); background-color: white;
    }
    .btn-primary-custom {
        background-color: #4a154b; color: white; border-radius: 8px; font-weight: 500;
        padding: 10px 20px; transition: 0.3s;
    }
    .btn-primary-custom:hover { background-color: #8a264a; color: white; }
    .btn-warning-custom {
        background-color: #ffc107; color: #333; border-radius: 8px; font-weight: 500;
        padding: 10px 20px; transition: 0.3s; border: none;
    }
    .btn-warning-custom:hover { background-color: #e0a800; color: #333; }
</style>

<!-- Header -->
<div class="header-gradient">
    <div class="container d-flex align-items-center gap-3">
        {{-- Cek Role: Jika Admin, kembali ke Dashboard Admin. Jika Customer, ke Home --}}
        @if(Auth::user()->role === 'admin')
            <a href="{{ route('admin.dashboard') }}" class="btn-back"><i class="bi bi-arrow-left fs-5"></i></a>
        @else
            <a href="{{ route('home') }}" class="btn-back"><i class="bi bi-arrow-left fs-5"></i></a>
        @endif
        
        <h4 class="mb-0 fw-normal">Profil Saya</h4>
    </div>
</div>

<div class="container pb-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            <!-- Update Profile Information -->
            <div class="card-custom">
                <h5 class="fw-bold mb-4" style="color: #4a154b;"><i class="bi bi-person-vcard me-2"></i> Informasi Profil</h5>
                
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PATCH')

                    <!-- Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold text-muted small">Nama Lengkap</label>
                        <input type="text" class="form-control form-control-custom @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label fw-bold text-muted small">Email</label>
                        <input type="email" class="form-control form-control-custom @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div class="mb-3">
                        <label for="phone" class="form-label fw-bold text-muted small">Nomor Telepon / WhatsApp</label>
                        <input type="text" class="form-control form-control-custom @error('phone') is-invalid @enderror" 
                               id="phone" name="phone" value="{{ old('phone', $user->phone) }}" 
                               placeholder="08xxxxxxxxxx">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Address -->
                    <div class="mb-4">
                        <label for="address" class="form-label fw-bold text-muted small">Alamat Lengkap Pengiriman</label>
                        <textarea class="form-control form-control-custom @error('address') is-invalid @enderror" 
                                  id="address" name="address" rows="3" 
                                  placeholder="Alamat untuk pengiriman">{{ old('address', $user->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary-custom w-100">
                        <i class="bi bi-save me-1"></i> Simpan Perubahan
                    </button>
                </form>
            </div>

            <!-- Update Password -->
            <div class="card-custom">
                <h5 class="fw-bold mb-4" style="color: #4a154b;"><i class="bi bi-key me-2"></i> Ubah Password</h5>
                
                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    @method('PUT')

                    <!-- Current Password -->
                    <div class="mb-3">
                        <label for="current_password" class="form-label fw-bold text-muted small">Password Saat Ini</label>
                        <input type="password" class="form-control form-control-custom @error('current_password') is-invalid @enderror" 
                               id="current_password" name="current_password" required>
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- New Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label fw-bold text-muted small">Password Baru</label>
                        <input type="password" class="form-control form-control-custom @error('password') is-invalid @enderror" 
                               id="password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label fw-bold text-muted small">Konfirmasi Password Baru</label>
                        <input type="password" class="form-control form-control-custom" 
                               id="password_confirmation" name="password_confirmation" required>
                    </div>

                    <button type="submit" class="btn btn-warning-custom w-100">
                        <i class="bi bi-shield-check me-1"></i> Ubah Password
                    </button>
                </form>
            </div>

            <!-- Delete Account -->
            <div class="card-custom" style="border: 1px solid #f5c2c7; background-color: #fff5f5;">
                <h5 class="fw-bold mb-3 text-danger"><i class="bi bi-exclamation-triangle me-2"></i> Hapus Akun</h5>
                <p class="text-muted small mb-4">
                    Setelah akun Anda dihapus, semua data akan dihapus secara permanen. 
                    Sebelum menghapus akun, silakan pastikan tidak ada pesanan yang sedang berjalan.
                </p>
                <button type="button" class="btn btn-danger w-100 rounded-3" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                    <i class="bi bi-trash me-1"></i> Hapus Akun Saya
                </button>
            </div>

        </div>
    </div>
</div>

<!-- Delete Account Modal (Tetap menggunakan Bootstrap Modal) -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
            <div class="modal-header bg-danger text-white border-0" style="border-radius: 15px 15px 0 0;">
                <h5 class="modal-title"><i class="bi bi-exclamation-triangle me-2"></i> Konfirmasi Hapus Akun</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('profile.destroy') }}">
                @csrf
                @method('DELETE')
                
                <div class="modal-body p-4">
                    <p class="text-muted">Apakah Anda yakin ingin menghapus akun? Tindakan ini tidak dapat dibatalkan.</p>
                    <div class="mb-3">
                        <label for="delete_password" class="form-label fw-bold text-muted small">Masukkan Password untuk Konfirmasi</label>
                        <input type="password" class="form-control form-control-custom" id="delete_password" name="password" required>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger rounded-3">Ya, Hapus Akun</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection