<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin - {{ config('app.name', 'Griya Kreatif Deyalina') }} - @yield('title', 'Dashboard')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --bg-cream: #faf8f5;
            --sidebar-bg: #260b26; /* Ungu sangat gelap sesuai Figma */
            --sidebar-hover: #3d143d;
            --sidebar-active: #541d54;
            --gradient-top: linear-gradient(90deg, #4a154b 0%, #d33a3a 100%);
            --text-dark: #333333;
        }

        body {
            background-color: var(--bg-cream);
            font-family: 'Poppins', sans-serif;
            margin: 0;
            overflow-x: hidden;
        }

        /* Sidebar */
        .sidebar {
            position: fixed; top: 0; left: 0; height: 100vh; width: 260px;
            background-color: var(--sidebar-bg); color: white;
            overflow-y: auto; transition: all 0.3s; z-index: 1050;
            display: flex; flex-direction: column;
        }

        .sidebar-brand {
            padding: 1.5rem; font-size: 1.2rem; font-weight: 700;
            display: flex; align-items: center; gap: 10px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        
        .sidebar-brand img { width: 35px; border-radius: 8px; }

        .sidebar-nav { padding: 1rem 0; flex-grow: 1; }
        .sidebar-nav .nav-link {
            color: rgba(255,255,255,0.7); padding: 12px 24px;
            display: flex; align-items: center; gap: 15px;
            font-weight: 400; transition: all 0.2s;
            margin: 4px 15px; border-radius: 10px;
        }

        .sidebar-nav .nav-link:hover {
            color: white; background-color: var(--sidebar-hover);
        }

        .sidebar-nav .nav-link.active {
            background-color: var(--sidebar-active);
            color: white; font-weight: 500;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .sidebar-nav .nav-link i { font-size: 1.2rem; }

        /* Main Content */
        .main-wrapper { margin-left: 260px; min-height: 100vh; transition: all 0.3s; }

        .top-navbar {
            background: var(--gradient-top);
            color: white; padding: 15px 30px;
            display: flex; justify-content: space-between; align-items: center;
            position: sticky; top: 0; z-index: 1040;
        }

        .top-navbar h5 { margin: 0; font-weight: 600; font-size: 1.1rem; }
        
        .admin-profile {
            display: flex; align-items: center; gap: 15px; font-weight: 500;
        }
        .profile-icon {
            background-color: white; color: var(--text-dark);
            border-radius: 50px; padding: 5px 15px; font-size: 0.85rem; font-weight: 600;
        }

        .content-area { padding: 30px; }

        /* Card Customization */
        .card { border: none; border-radius: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); margin-bottom: 20px; }
        .card-header { background-color: white; border-bottom: 1px solid #f0f0f0; border-radius: 15px 15px 0 0 !important; padding: 20px; font-weight: 600; color: var(--text-dark); }

        /* Mobile */
        @media (max-width: 991px) {
            .sidebar { margin-left: -260px; }
            .sidebar.active { margin-left: 0; }
            .main-wrapper { margin-left: 0; }
            .mobile-toggle { display: block !important; }
        }
        .mobile-toggle { display: none; background: transparent; border: none; color: white; font-size: 1.5rem; padding: 0; margin-right: 15px; }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <img src="https://ui-avatars.com/api/?name=D&background=d33a3a&color=fff&rounded=true&bold=true" alt="Logo">
            <div>
                <div style="line-height: 1.1;">Deyalina</div>
                <small style="font-size: 0.65rem; color: #aaa; font-weight: 400;">ADMIN PANEL</small>
            </div>
        </div>
        
        <ul class="sidebar-nav nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-grid-1x2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}">
                    <i class="bi bi-box-seam"></i> Produk
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
                    <i class="bi bi-tag"></i> Kategori
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.orders.index') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">
                    <i class="bi bi-bag"></i> Pesanan
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.orders.sales-history') ? 'active' : '' }}" href="{{ route('admin.orders.sales-history') }}">
                    <i class="bi bi-graph-up-arrow"></i> Riwayat Penjualan
                </a>
            </li>
            {{-- <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.messages.*') ? 'active' : '' }}" href="{{ route('admin.messages.index') }}">
                    <i class="bi bi-chat-left-dots"></i> Pesan Pelanggan
                    @if(\App\Models\Message::unread()->count() > 0)
                        <span class="badge bg-danger ms-auto rounded-pill">{{ \App\Models\Message::unread()->count() }}</span>
                    @endif
                </a>
            </li> --}}
        </ul>
    </div>

    <!-- Main Content Wrapper -->
    <div class="main-wrapper">
        <!-- Top Navbar -->
        <div class="top-navbar">
            <div class="d-flex align-items-center">
                <button class="mobile-toggle" id="sidebarToggle"><i class="bi bi-list"></i></button>
                <h5>@yield('page-title', 'Dashboard')</h5>
            </div>
            
            <!-- Menu Dropdown Admin (Kanan Atas) -->
            <div class="admin-profile dropdown">
                <a href="#" class="d-flex align-items-center gap-2 text-white text-decoration-none dropdown-toggle" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="outline: none;">
                    <span class="d-none d-md-inline fw-medium">{{ Auth::user()->name }}</span>
                    <span class="profile-icon">Profil</span>
                </a>
                
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-3" aria-labelledby="adminDropdown" style="border-radius: 10px;">
                    <li><a class="dropdown-item py-2" href="{{ route('admin.profile.edit') }}"><i class="bi bi-person me-2"></i> Pengaturan Profil</a></li>
                    <li><a class="dropdown-item py-2" href="{{ route('home') }}" target="_blank"><i class="bi bi-shop me-2"></i> Lihat Toko (Tab Baru)</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item py-2 text-danger"><i class="bi bi-box-arrow-right me-2"></i> Keluar</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Flash Messages -->
        <div class="px-4 pt-3">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        </div>

        <!-- Main Content -->
        <div class="content-area">
            @yield('content')
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Sidebar Toggle Script -->
    <script>
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
        });
    </script>

    @stack('scripts')
</body>
</html>