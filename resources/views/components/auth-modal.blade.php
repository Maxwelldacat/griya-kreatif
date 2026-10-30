<div class="modal fade" id="authModal" tabindex="-1" aria-labelledby="authModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px; overflow: hidden;">
            
            <!-- Header dengan Gradien (Mirip Figma) -->
            <div class="modal-header border-0 d-flex flex-column align-items-center pb-3" style="background: linear-gradient(135deg, #5a163f 0%, #8a264a 50%, #d33a3a 100%);">
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
                
                <div class="text-white mt-2 mb-3 d-flex align-items-center gap-2">
                    <i class="bi bi-house-door-fill fs-3 text-warning"></i>
                    <span class="fs-4 fw-bold">Deyalina</span>
                </div>

                <!-- Tab Menu Bootstrap -->
                <ul class="nav nav-pills w-100 bg-white bg-opacity-25 p-1 rounded-3" id="pills-tab" role="tablist">
                    <li class="nav-item w-50" role="presentation">
                        <button class="nav-link w-100 rounded-2 fw-semibold active bg-white text-dark shadow-sm" id="pills-login-tab" data-bs-toggle="pill" data-bs-target="#pills-login" type="button" role="tab">Masuk</button>
                    </li>
                    <li class="nav-item w-50" role="presentation">
                        <button class="nav-link w-100 rounded-2 fw-semibold text-white" id="pills-register-tab" data-bs-toggle="pill" data-bs-target="#pills-register" type="button" role="tab">Daftar</button>
                    </li>
                </ul>
            </div>

            <!-- Body Form -->
            <div class="modal-body p-4">
                <div class="tab-content" id="pills-tabContent">
                    
                    <!-- ================= FORM LOGIN ================= -->
                    <div class="tab-pane fade show active" id="pills-login" role="tabpanel">
                        @if (session('status'))
                            <div class="alert alert-success py-2 small">{{ session('status') }}</div>
                        @endif
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label text-muted small">Email / No. Telepon</label>
                                <input type="email" name="email" value="{{ old('email') }}" required class="form-control bg-light" placeholder="email@gmail.com">
                                @error('email')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted small">Kata Sandi</label>
                                <input type="password" name="password" required class="form-control bg-light" placeholder="********">
                                @error('password')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                            <div class="d-flex justify-content-between mb-4 align-items-center">
                                <div class="form-check">
                                    <input type="checkbox" name="remember" class="form-check-input" id="remember">
                                    <label class="form-check-label small text-muted" for="remember">Ingat saya</label>
                                </div>
                                <a href="{{ route('password.request') }}" class="text-danger small text-decoration-none">Lupa sandi?</a>
                            </div>
                            <button type="submit" class="btn w-100 text-white fw-bold py-2" style="background: linear-gradient(135deg, #5a163f 0%, #d33a3a 100%);">Masuk</button>
                            
                            <!-- Tombol Pindah ke Daftar (Diperbaiki) -->
                            <div class="text-center mt-3 small text-muted">
                                Belum punya akun? 
                                <a href="#" onclick="event.preventDefault(); document.getElementById('pills-register-tab').click();" class="text-decoration-none fw-bold" style="color: #5a163f;">Daftar Sekarang</a>
                            </div>
                        </form>
                    </div>

                    <!-- ================= FORM DAFTAR ================= -->
                    <div class="tab-pane fade" id="pills-register" role="tabpanel">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label text-muted small">Nama Lengkap</label>
                                <input type="text" name="name" value="{{ old('name') }}" required class="form-control bg-light" placeholder="Nama lengkap Anda">
                                @error('name')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted small">Email</label>
                                <input type="email" name="email" value="{{ old('email') }}" required class="form-control bg-light" placeholder="email@contoh.com">
                                @error('email')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted small">No. Whatsapp</label>
                                <input type="text" name="phone" value="{{ old('phone') }}" class="form-control bg-light" placeholder="08xx-xxxx-xxxx">
                                @error('phone')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                            
                            {{-- Alamat disembunyikan sesuai desain, tapi tetap dikirim ke backend --}}
                            <input type="hidden" name="address" value="{{ old('address') }}">
                            
                            <div class="mb-3">
                                <label class="form-label text-muted small">Kata Sandi</label>
                                <input type="password" name="password" required class="form-control bg-light" placeholder="Min. 8 Karakter">
                                @error('password')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                            <div class="mb-4">
                                <label class="form-label text-muted small">Konfirmasi Sandi</label>
                                <input type="password" name="password_confirmation" required class="form-control bg-light" placeholder="Ulangi kata sandi">
                            </div>
                            <button type="submit" class="btn w-100 text-white fw-bold py-2" style="background: linear-gradient(135deg, #5a163f 0%, #d33a3a 100%);">Buat Akun</button>
                            
                            <!-- Tombol Pindah ke Masuk (Diperbaiki) -->
                            <div class="text-center mt-3 small text-muted">
                                Sudah punya akun? 
                                <a href="#" onclick="event.preventDefault(); document.getElementById('pills-login-tab').click();" class="text-decoration-none fw-bold" style="color: #5a163f;">Masuk</a>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>