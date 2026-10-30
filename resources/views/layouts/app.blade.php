<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Griya Kreatif Deyalina') }} - @yield('title', 'Home')</title>

    <!-- Favicon (Ikon Tab Browser) -->
    <link rel="icon" type="image/png" href="{{ asset('images/deyalina.png') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-color: #4a154b;
            --primary-gradient: linear-gradient(135deg, #6B0F3A 0%, #C0392B 100%);
            --bg-gray: #F5F5F5;
        }
        
        body { 
            background-color: var(--bg-gray); 
            font-family: 'Poppins', sans-serif; 
            min-height: 100vh; 
            margin: 0;
        }

        /* Navbar Custom Style */
        .navbar { background-color: white !important; box-shadow: 0 2px 10px rgba(0,0,0,0.05); padding: 10px 0; }
        .navbar-brand { font-weight: 700; font-size: 1.3rem; color: var(--primary-color) !important; }
        
        /* Search Bar */
        .search-container { flex-grow: 1; max-width: 600px; margin: 0 2rem; }
        .search-input { 
            border-radius: 50px; 
            background-color: #f8f9fa; 
            border: 1px solid #e9ecef; 
            padding-left: 45px; 
            padding-right: 40px; 
            height: 45px; 
        }
        .search-input:focus { background-color: white; box-shadow: 0 0 0 0.25rem rgba(74, 21, 75, 0.1); border-color: var(--primary-color); }

        /* Tombol Clear (X) */
        .btn-clear-search {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            color: #999;
            padding: 0;
            display: none; 
            z-index: 10;
        }
        .btn-clear-search:hover { color: #d32f2f; }

        /* Buttons */
        .btn-wa { 
            background-color: #e8f5e9; 
            color: #2e7d32; 
            border-radius: 50px; 
            font-weight: 600; 
            padding: 8px 16px; 
            transition: 0.3s; 
        }
        .btn-wa:hover { background-color: #c8e6c9; color: #1b5e20; }
        
        .btn-masuk { 
            background-color: var(--primary-color); 
            color: white; 
            border-radius: 8px; 
            font-weight: 600; 
            padding: 8px 24px; 
        }
        .btn-masuk:hover { background-color: #3a103b; color: white; }

        .cart-icon { font-size: 1.5rem; color: #555; position: relative; transition: 0.3s; }
        .cart-icon:hover { color: var(--primary-color); }
        .cart-badge { 
            position: absolute; 
            top: -5px; 
            right: -10px; 
            background-color: #d32f2f; 
            color: white; 
            border-radius: 50%; 
            padding: 2px 6px; 
            font-size: 0.65rem; 
            font-weight: bold; 
        }

        /* Efek Blur untuk Semua Modal & Offcanvas */
        .modal-backdrop.show, .offcanvas-backdrop.show {
            opacity: 1 !important;
            background-color: rgba(0, 0, 0, 0.4) !important;
            backdrop-filter: blur(5px) !important;
            -webkit-backdrop-filter: blur(5px) !important;
        }

        /* Main Content */
        .main-content { 
            min-height: calc(100vh - 200px); 
            padding: 0; 
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container-fluid px-4">
            
            <!-- Logo -->
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
                <img src="{{ asset('images/deyalina.png') }}" alt="Logo" style="width: 40px; height: 40px; object-fit: contain;">
                <div style="line-height: 1;">
                    <span class="d-block" style="font-size: 1.2rem;">Deyalina</span>
                    <span class="d-block text-muted" style="font-size: 0.6rem; letter-spacing: 1px;">TOKO LENGKAP</span>
                </div>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Search Bar (Tengah) -->
                <form id="navbar-search-form" class="search-container d-none d-lg-block" action="{{ route('home') }}" method="GET">
                    <div class="position-relative">
                        <i class="bi bi-search position-absolute text-muted" style="left: 18px; top: 14px; z-index: 10;"></i>
                        
                        <input type="text" id="navbar-search-input" name="search" class="form-control search-input" placeholder="Cari beras, bumbu, kebutuhan rumah..." value="{{ request('search') }}">
                        
                        <!-- Tombol X (Clear) -->
                        <button type="button" id="clear-search-btn" class="btn-clear-search" style="{{ request('search') ? 'display: block;' : '' }}">
                            <i class="bi bi-x-circle-fill fs-5"></i>
                        </button>
                    </div>
                </form>

                <!-- Menu Kanan -->
                <ul class="navbar-nav ms-auto align-items-center gap-3">
                    <!-- Tombol WhatsApp -->
                    @if(!Auth::check() || Auth::user()->role === 'customer')
                    <li class="nav-item d-none d-md-block">
                        <a href="https://wa.me/6282139788324" target="_blank" class="btn btn-wa d-flex align-items-center gap-2">
                            <i class="bi bi-whatsapp"></i> WhatsApp
                        </a>
                    </li>
                    @endif

                    <!-- Icon Keranjang -->
                    @if(!Auth::check() || Auth::user()->role === 'customer')
                    <li class="nav-item">
                        <a class="nav-link cart-icon" href="#" data-bs-toggle="offcanvas" data-bs-target="#cartOffcanvas">
                            <i class="bi bi-cart2"></i>
                            @auth
                                @if(auth()->user()->role === 'customer' && auth()->user()->carts()->count() > 0)
                                    <span class="cart-badge">{{ auth()->user()->carts()->count() }}</span>
                                @endif
                            @endauth
                        </a>
                    </li>
                    @endif

                    <!-- Menu Auth -->
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle fw-bold" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                                @if(Auth::user()->role === 'admin')
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">Dashboard Admin</a></li>
                                @else
                                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profil Saya</a></li>
                                    <li><a class="dropdown-item" href="{{ route('orders.index') }}">Pesanan Saya</a></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">Keluar</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <!-- Satu Pintu Masuk -->
                        <li class="nav-item">
                            <a class="btn btn-masuk" href="#" data-bs-toggle="modal" data-bs-target="#authModal" onclick="document.getElementById('pills-login-tab').click()">
                                Masuk
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <main class="main-content">
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="footer" style="background-color: #2b0b2b; padding: 4rem 0 2rem 0; margin-top: 0; color: white;">
        <div class="container">
            <div class="row g-4">
                <!-- Kolom 1: Logo & Deskripsi -->
                <div class="col-lg-4 col-md-6">
                    <a class="navbar-brand d-flex align-items-center gap-2 mb-3" href="{{ route('home') }}">
                        <img src="{{ asset('images/deyalina.png') }}" alt="Logo" style="width: 45px; height: 45px; object-fit: contain;">
                        <div style="line-height: 1;">
                            <span class="d-block fw-bold text-white" style="font-size: 1.3rem;">Deyalina</span>
                            <span class="d-block text-white-50" style="font-size: 0.65rem; letter-spacing: 1px;">TOKO LENGKAP</span>
                        </div>
                    </a>
                    <p class="text-white-50 small" style="line-height: 1.8; max-width: 300px;">
                        Platform e-commerce UMKM untuk produk makanan, pakaian, buku, dan perlengkapan rumah tangga dengan harga terjangkau.
                    </p>
                </div>

                <!-- Kolom 2: Layanan -->
                <div class="col-lg-3 col-md-6">
                    <h6 class="fw-bold mb-3 text-white">Layanan</h6>
                    <ul class="list-unstyled small" style="line-height: 2.2;">
                        <li><a href="{{ route('home') }}" class="text-decoration-none text-white-50">Katalog Produk</a></li>
                        @auth
                            @if(auth()->user()->role === 'customer')
                                <li><a href="{{ route('cart.index') }}" class="text-decoration-none text-white-50">Keranjang Belanja</a></li>
                                <li><a href="{{ route('orders.index') }}" class="text-decoration-none text-white-50">Riwayat Pesanan</a></li>
                            @endif
                        @else
                            <li><a href="#" data-bs-toggle="modal" data-bs-target="#authModal" class="text-decoration-none text-white-50">Masuk / Daftar</a></li>
                        @endauth
                    </ul>
                </div>

                <!-- Kolom 3: Kontak -->
                <div class="col-lg-5 col-md-12">
                    <h6 class="fw-bold mb-3 text-white">Kontak Kami</h6>
                    <ul class="list-unstyled text-white-50 small" style="line-height: 2.2;">
                        <li class="d-flex gap-2">
                            <i class="bi bi-geo-alt-fill text-warning mt-1"></i> 
                            <span>Jl. Contoh Alamat UMKM No. 123, Kota Malang, Jawa Timur</span>
                        </li>
                        <li class="d-flex gap-2 align-items-center">
                            <i class="bi bi-whatsapp text-success"></i> 
                            <a href="https://wa.me/6282139788324" target="_blank" class="text-decoration-none text-white-50">+62 812-3456-7890</a>
                        </li>
                        <li class="d-flex gap-2 align-items-center">
                            <i class="bi bi-envelope-fill text-warning"></i> 
                            <a href="mailto:info@griya-deyalina.com" class="text-decoration-none text-white-50">info@griya-deyalina.com</a>
                        </li>
                    </ul>
                </div>
            </div>

            <hr class="mt-4 mb-3" style="border-color: rgba(255,255,255,0.1);">

            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <small class="text-white-50">&copy; {{ date('Y') }} Griya Kreatif Deyalina. All rights reserved.</small>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Komponen Modal & Sidebar -->
    <x-auth-modal />
    <x-cart-sidebar />

    <!-- SCRIPT: BUKA POP-UP OTOMATIS JIKA DILEMPAR DARI /login -->
    @if(session('open_login_modal'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var authModalElement = document.getElementById('authModal');
            if (authModalElement) {
                var authModal = new bootstrap.Modal(authModalElement);
                authModal.show();
            }
        });
    </script>
    @endif

    <!-- Script untuk Tombol Clear Search -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('navbar-search-input');
            const clearBtn = document.getElementById('clear-search-btn');

            if (searchInput && clearBtn) {
                searchInput.addEventListener('input', function() {
                    if (this.value.length > 0) {
                        clearBtn.style.display = 'block';
                    } else {
                        clearBtn.style.display = 'none';
                    }
                });

                clearBtn.addEventListener('click', function() {
                    searchInput.value = ''; 
                    clearBtn.style.display = 'none'; 
                    searchInput.focus(); 
                    
                    const searchForm = document.getElementById('navbar-search-form');
                    if (searchForm) {
                        searchForm.requestSubmit(); 
                    }
                });
            }
        });
    </script>

    @stack('scripts')
</body>
</html>