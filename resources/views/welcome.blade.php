<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Griya Kreatif Deyalina') }}</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-color: #90EE90;
            --primary-dark: #7AD87A;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .hero-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, #ffffff 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            color: #333;
            margin-bottom: 1.5rem;
        }

        .hero-subtitle {
            font-size: 1.3rem;
            color: #666;
            margin-bottom: 2rem;
        }

        .btn-hero {
            padding: 1rem 2.5rem;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 50px;
            margin: 0.5rem;
        }

        .btn-primary-hero {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
            color: white;
        }

        .btn-primary-hero:hover {
            background-color: #6ACE6A;
            border-color: #6ACE6A;
        }

        .feature-card {
            padding: 2rem;
            text-align: center;
            transition: transform 0.3s;
        }

        .feature-card:hover {
            transform: translateY(-10px);
        }

        .feature-icon {
            font-size: 3rem;
            color: var(--primary-dark);
            margin-bottom: 1rem;
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            .hero-subtitle {
                font-size: 1.1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="hero-title">Selamat Datang di<br>Griya Kreatif Deyalina</h1>
                    <p class="hero-subtitle">
                        Platform e-commerce UMKM untuk produk makanan berkualitas, pakaian, buku inspiratif, 
                        dan perlengkapan rumah tangga dengan harga terjangkau.
                    </p>
                    <div>
                        <a href="{{ route('register') }}" class="btn btn-primary-hero btn-hero">
                            <i class="bi bi-person-plus"></i> Daftar Sekarang
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-outline-dark btn-hero">
                            <i class="bi bi-box-arrow-in-right"></i> Masuk
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 text-center mt-5 mt-lg-0">
                    <img src="images/deyalina.png" alt="Deyalina UMKM">
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center fw-bold mb-5">Mengapa Memilih Kami?</h2>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="feature-card">
                        <i class="bi bi-shield-check feature-icon"></i>
                        <h4 class="fw-bold">Produk Bersertifikat</h4>
                        <p class="text-muted">Semua produk makanan memiliki sertifikat P-IRT dan Halal MUI</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-card">
                        <i class="bi bi-box-seam feature-icon"></i>
                        <h4 class="fw-bold">Beragam Kategori</h4>
                        <p class="text-muted">Dari makanan, pakaian, buku, hingga perlengkapan rumah tangga</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-card">
                        <i class="bi bi-cart-check feature-icon"></i>
                        <h4 class="fw-bold">Mudah & Aman</h4>
                        <p class="text-muted">Proses pembelian yang sederhana dan sistem pembayaran yang aman</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>