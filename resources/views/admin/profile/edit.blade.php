@extends('layouts.admin')

@section('title', 'Profil Admin')
@section('page-title', 'Pengaturan Profil')

@section('content')
<style>
    .card-custom { border: none; border-radius: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); margin-bottom: 1.5rem; background: white; }
    .form-control-custom { background-color: #fafafa; border: 1px solid #ddd; border-radius: 10px; padding: 12px 20px; }
    .form-control-custom:focus { border-color: #4a154b; box-shadow: 0 0 0 3px rgba(74, 21, 75, 0.1); }
    .btn-primary-custom { background-color: #4a154b; color: white; border-radius: 8px; font-weight: 500; padding: 10px 20px; transition: 0.3s; }
    .btn-primary-custom:hover { background-color: #8a264a; color: white; }
</style>

<div class="row justify-content-center">
    <div class="col-lg-8">
        
        <!-- Update Profile Information -->
        <div class="card card-custom">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4" style="color: #4a154b;"><i class="bi bi-person-vcard me-2"></i> Informasi Profil</h5>
                
                <form method="POST" action="{{ route('admin.profile.update') }}">
                    @csrf
                    @method('PATCH')

                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold text-muted small">Nama Lengkap</label>
                        <input type="text" class="form-control form-control-custom @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label fw-bold text-muted small">Email</label>
                        <input type="email" class="form-control form-control-custom @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label for="phone" class="form-label fw-bold text-muted small">Nomor Telepon</label>
                        <input type="text" class="form-control form-control-custom @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="08xxxxxxxxxx">
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <button type="submit" class="btn btn-primary-custom w-100">
                        <i class="bi bi-save me-1"></i> Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>

        <!-- Update Password -->
        <div class="card card-custom">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4" style="color: #4a154b;"><i class="bi bi-key me-2"></i> Ubah Password</h5>
                
                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="current_password" class="form-label fw-bold text-muted small">Password Saat Ini</label>
                        <input type="password" class="form-control form-control-custom @error('current_password') is-invalid @enderror" id="current_password" name="current_password" required>
                        @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label fw-bold text-muted small">Password Baru</label>
                        <input type="password" class="form-control form-control-custom @error('password') is-invalid @enderror" id="password" name="password" required>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label fw-bold text-muted small">Konfirmasi Password Baru</label>
                        <input type="password" class="form-control form-control-custom" id="password_confirmation" name="password_confirmation" required>
                    </div>

                    <button type="submit" class="btn btn-warning w-100 fw-bold" style="border-radius: 8px; padding: 10px;">
                        <i class="bi bi-shield-check me-1"></i> Ubah Password
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection