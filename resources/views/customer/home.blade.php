@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
<style>
    /* Hero Section */
    .hero-section { background: linear-gradient(135deg, #4a154b 0%, #d33a3a 100%); padding: 5rem 0; color: white; position: relative; }
    .hero-title { font-size: 3.5rem; font-weight: 800; line-height: 1.2; margin-bottom: 1.5rem; }
    .hero-title span { color: #f39c12; }
    .hero-subtitle { font-size: 1.1rem; margin-bottom: 2rem; opacity: 0.9; max-width: 500px; line-height: 1.6; }
    .btn-hero-primary { background-color: #f39c12; color: white; font-weight: 600; padding: 12px 30px; border-radius: 8px; border: none; transition: 0.3s; }
    .btn-hero-primary:hover { background-color: #e67e22; color: white; }
    
    /* Brand Section */
    .brand-section { background-color: #3b0f3a; padding: 1.5rem 0; border-bottom: 2px solid #4a154b; }
    .brand-pill { background-color: white; color: #4a154b; padding: 6px 20px; border-radius: 20px; font-weight: 600; font-size: 0.85rem; display: inline-block; margin-right: 10px; }

    /* Category Section */
    .category-section { background-color: white; padding: 1.5rem 0; border-bottom: 1px solid #eee; }
    .category-list { display: flex; gap: 10px; overflow-x: auto; padding-bottom: 5px; scrollbar-width: none; }
    .category-list::-webkit-scrollbar { display: none; }
    .category-item { padding: 8px 20px; border-radius: 30px; border: 1px solid #ddd; color: #555; text-decoration: none; font-weight: 500; white-space: nowrap; transition: 0.3s; cursor: pointer; }
    .category-item:hover, .category-item.active { background-color: #4a154b; color: white; border-color: #4a154b; }

    /* Product Section */
    .product-section { background-color: #faf8f5; padding: 4rem 0; min-height: 50vh; }
    .product-card { border: none; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); transition: transform 0.3s, box-shadow 0.3s; overflow: hidden; }
    .product-card:hover { transform: translateY(-5px); box-shadow: 0 8px 25px rgba(0,0,0,0.1); }
    .product-image { 
        height: 200px; 
        width: 100%; 
        object-fit: contain; /* <-- Diubah jadi contain */
        background-color: #ffffff; /* Background putih bersih */
        padding: 10px; /* Memberi jarak agar gambar tidak mepet pinggir */
    }
    .btn-cart { background: linear-gradient(to right, #f39c12, #d35400); color: white; border: none; border-radius: 8px; font-weight: 600; padding: 10px; transition: 0.3s; width: 100%; }
    .btn-cart:hover { opacity: 0.9; color: white; }
    .btn-disabled { background-color: #e0e0e0; color: #888; border: none; border-radius: 8px; font-weight: 600; padding: 10px; width: 100%; cursor: not-allowed; }

    .map-section { padding: 0 0 4rem 0; background-color: #faf8f5; }
    .features-section { background: linear-gradient(135deg, #f39c12 0%, #d35400 100%); padding: 3rem 0; color: white; }
    
    /* Loading Spinner */
    #loading-overlay { display: none; position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(250, 248, 245, 0.7); z-index: 10; justify-content: center; align-items: flex-start; padding-top: 100px; }
</style>

{{-- ==================== HERO SECTION ==================== --}}
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-5 mb-lg-0">
                <h1 class="hero-title">Semua kebutuhan,<br><span>lebih dekat</span><br>dari rumah.</h1>
                <p class="hero-subtitle">Belanja harian, produk lokal pilihan, dan pre-order favorit keluarga dalam satu tempat yang mudah digunakan.</p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="#produk" class="btn btn-hero-primary">Belanja Sekarang</a>
                </div>
            </div>
            <div class="col-lg-6 d-flex justify-content-center">
                <img src="{{ asset('images/deyalina.png') }}" alt="Griya Kreatif Deyalina" class="img-fluid" style="max-height: 400px; object-fit: contain;">
            </div>
        </div>
    </div>
</section>

{{-- ==================== BRAND SECTION ==================== --}}
<section class="brand-section">
    <div class="container d-flex align-items-center flex-wrap">
        <span class="text-white me-4 mb-2 mb-md-0 fw-bold">Brand kami :</span>
        <div>
            <span class="brand-pill">Deyalina</span>
            <span class="brand-pill">Sambal Pecel</span>
            <span class="brand-pill">Mbak Vy</span>
        </div>
    </div>
</section>

{{-- ==================== KATEGORI & PRODUK ==================== --}}
<!-- Bungkus dengan ID untuk AJAX -->
<div id="catalog-container" style="position: relative;">
    
    <!-- Overlay Loading -->
    <div id="loading-overlay"><div class="spinner-border text-warning" role="status"></div></div>

    <section class="category-section">
        <div class="container">
            <div class="category-list">
                <a href="{{ route('home') }}" class="category-item ajax-filter {{ !request('category') ? 'active' : '' }}">
                    <i class="bi bi-grid me-1"></i> Semua
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('home', ['category' => $cat->id]) }}" class="category-item ajax-filter {{ request('category') == $cat->id ? 'active' : '' }}">
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <section class="product-section" id="produk">
        <div class="container">
            <div class="d-flex justify-content-between align-items-end mb-4">
                <div>
                    <h3 class="fw-bold mb-1" style="color: #4a154b;">Produk Pilihan</h3>
                    <p class="text-muted mb-0">{{ $products->total() }} produk tersedia</p>
                </div>
            </div>

            @if($products->count() > 0)
                <div class="row g-4">
                    @foreach($products as $product)
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="card product-card h-100">
                                
                                <a href="{{ route('products.show', $product->id) }}" class="text-decoration-none">
                                    <img src="{{ $product->image_url }}" class="product-image" alt="{{ $product->name }}" 
                                         onerror="this.onerror=null; this.src='https://placehold.co/400x400/eaeaea/888?text=No+Image';">
                                </a>

                                <div class="card-body d-flex flex-column p-3">
                                    <a href="{{ route('products.show', $product->id) }}" class="text-decoration-none text-dark">
                                        <h6 class="fw-bold mb-1 text-truncate">{{ $product->name }}</h6>
                                    </a>
                                    <small class="text-muted mb-3 text-truncate">{{ Str::limit($product->description, 40) }}</small>
                                    <p class="fw-bold mb-3" style="color: #d32f2f; font-size: 1.1rem;">Rp {{ number_format($product->price, 0, ',', '.') }}</p>

                                    <div class="mt-auto">
                                        @if($product->stock > 0)
                                            {{-- JIKA STOK ADA --}}
                                            @auth
                                                @if(auth()->user()->role === 'customer')
                                                    <form action="{{ route('cart.store') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                        <input type="hidden" name="quantity" value="1">
                                                        <button type="submit" class="btn btn-cart"><i class="bi bi-cart-plus me-1"></i> Keranjang</button>
                                                    </form>
                                                @endif
                                            @else
                                                <a href="#" data-bs-toggle="modal" data-bs-target="#authModal" onclick="event.preventDefault(); document.getElementById('pills-login-tab').click();" class="btn btn-cart"><i class="bi bi-cart-plus me-1"></i> Keranjang</a>
                                            @endauth
                                        @else
                                            {{-- JIKA STOK HABIS --}}
                                            <button class="btn btn-disabled" disabled>Stok Habis</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-center mt-5">
                    {{ $products->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox display-1 text-muted"></i>
                    <p class="text-muted mt-3">Belum ada produk tersedia.</p>
                </div>
            @endif
        </div>
    </section>
</div>

{{-- ==================== MAPS & FITUR ==================== --}}
<section class="map-section">
    <div class="container">
        <div class="map-wrapper rounded-4 overflow-hidden shadow-sm">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3959.9805336!2d111.9!3d-7.6!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zN8KwMzYnMDAuMCJTIDExMcKwNTQnMDAuMCJF!5e0!3m2!1sid!2sid!4v1600000000000" width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>
    </div>
</section>

<section class="features-section">
    <div class="container">
        <div class="row text-center">
            <div class="col-6 col-md-3 mb-4 mb-md-0"><i class="bi bi-truck fs-1 mb-2 d-block"></i><h6 class="fw-bold mb-1">Pengiriman Cepat</h6></div>
            <div class="col-6 col-md-3 mb-4 mb-md-0"><i class="bi bi-shield-check fs-1 mb-2 d-block"></i><h6 class="fw-bold mb-1">Produk Bersertifikat</h6></div>
            <div class="col-6 col-md-3"><i class="bi bi-headset fs-1 mb-2 d-block"></i><h6 class="fw-bold mb-1">Layanan Ramah</h6></div>
            <div class="col-6 col-md-3"><i class="bi bi-star fs-1 mb-2 d-block"></i><h6 class="fw-bold mb-1">Kualitas Terjamin</h6></div>
        </div>
    </div>
</section>

{{-- SCRIPT AJAX UNTUK FILTER KATEGORI & SEARCH --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const catalogContainer = document.getElementById('catalog-container');
    const loadingOverlay = document.getElementById('loading-overlay');

    function loadCatalog(url) {
        loadingOverlay.style.display = 'flex';

        fetch(url)
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newCatalog = doc.getElementById('catalog-container').innerHTML;
                
                catalogContainer.innerHTML = newCatalog;
                window.history.pushState({}, '', url);
                loadingOverlay.style.display = 'none';
                attachFilterEvents();

                const produkSection = document.getElementById('produk');
                if (produkSection) {
                    produkSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                loadingOverlay.style.display = 'none';
                window.location.href = url;
            });
    }

    function attachFilterEvents() {
        const filterLinks = document.querySelectorAll('.ajax-filter');
        filterLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const url = this.getAttribute('href');
                loadCatalog(url);
            });
        });
    }
    attachFilterEvents();

    const searchForm = document.getElementById('navbar-search-form');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault(); 
            const searchInput = this.querySelector('input[name="search"]').value;
            const url = `{{ route('home') }}?search=${encodeURIComponent(searchInput)}`;
            loadCatalog(url);
        });
    }
});
</script>
@endsection