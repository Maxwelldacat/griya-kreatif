@extends('layouts.app')

@section('title', 'Keranjang Belanja')

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
    .btn-checkout {
        background-color: #4a154b; color: white; border-radius: 8px; font-weight: 500;
        transition: 0.3s; width: 100%; padding: 12px;
    }
    .btn-checkout:hover { background-color: #8a264a; color: white; }
</style>

<!-- Header -->
<div class="header-gradient">
    <div class="container d-flex align-items-center gap-3">
        <a href="{{ route('home') }}" class="btn-back"><i class="bi bi-arrow-left fs-5"></i></a>
        <h4 class="mb-0 fw-normal">Keranjang Belanja</h4>
    </div>
</div>

<div class="container pb-5">
    @if($cartItems->count() > 0)
        <div class="row g-4">
            
            <!-- Kolom Kiri: Daftar Barang -->
            <div class="col-lg-8">
                <div class="card-custom p-0 overflow-hidden">
                    @foreach($cartItems as $item)
                        <div class="p-4 border-bottom {{ $loop->last ? 'border-0' : '' }}">
                            <div class="row align-items-center">
                                <!-- Gambar -->
                                <div class="col-md-2 col-4 mb-3 mb-md-0">
                                    <img src="{{ $item->product->image_url }}" class="img-fluid rounded-3" alt="{{ $item->product->name }}" style="width: 100%; height: 80px; object-fit: contain;">
                                </div>
                                
                                <!-- Info Produk -->
                                <div class="col-md-6 col-8 mb-3 mb-md-0">
                                    <h6 class="fw-bold mb-1">
                                        <a href="{{ route('products.show', $item->product) }}" class="text-decoration-none text-dark">
                                            {{ $item->product->name }}
                                        </a>
                                    </h6>
                                    <small class="text-muted d-block mb-1">{{ $item->product->category->name }}</small>
                                    
                                    @if($item->size)
                                        <span class="badge bg-secondary mb-2">Ukuran: {{ $item->size }}</span>
                                    @endif
                                    
                                    <p class="text-danger fw-bold mb-0">{{ $item->product->formatted_price }}</p>
                                    
                                    @if(!$item->isAvailable())
                                        <div class="text-danger small mt-1"><i class="bi bi-exclamation-triangle"></i> Stok tidak mencukupi</div>
                                    @endif
                                </div>
                                
                                <!-- Kuantitas & Subtotal & Hapus -->
                                <div class="col-md-4 text-md-end">
                                    <div class="d-flex flex-column align-items-md-end">
                                        
                                        <!-- Form Tambah/Kurang (Memakai logika asli) -->
                                        <form action="{{ route('cart.update', $item) }}" method="POST" class="mb-2">
                                            @csrf
                                            @method('PATCH')
                                            <div class="input-group input-group-sm" style="width: 120px;">
                                                <button type="submit" name="quantity" value="{{ max(1, $item->quantity - 1) }}" class="btn btn-light border" {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                                    <i class="bi bi-dash"></i>
                                                </button>
                                                <input type="text" class="form-control text-center border bg-white" value="{{ $item->quantity }}" readonly>
                                                <button type="submit" name="quantity" value="{{ $item->quantity + 1 }}" class="btn btn-light border" {{ $item->quantity >= $item->product->stock ? 'disabled' : '' }}>
                                                    <i class="bi bi-plus"></i>
                                                </button>
                                            </div>
                                        </form>
                                        
                                        <!-- Subtotal -->
                                        <h6 class="fw-bold text-dark mb-2">{{ $item->formatted_subtotal }}</h6>
                                        
                                        <!-- Form Hapus -->
                                        <form action="{{ route('cart.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus produk dari keranjang?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-link text-danger p-0 text-decoration-none">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Tombol Kosongkan Keranjang -->
                <div class="text-end mt-3">
                    <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('Kosongkan seluruh keranjang?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm rounded-3">
                            <i class="bi bi-trash"></i> Kosongkan Keranjang
                        </button>
                    </form>
                </div>
            </div>

            <!-- Kolom Kanan: Ringkasan Pesanan -->
            <div class="col-lg-4">
                <div class="card-custom">
                    <h5 class="fw-bold mb-4 text-dark">Ringkasan Pesanan</h5>
                    
                    <div class="d-flex justify-content-between text-muted mb-2">
                        <span>Total Item</span>
                        <span>{{ $cartItems->sum('quantity') }} produk</span>
                    </div>
                    <div class="d-flex justify-content-between text-muted mb-3 pb-3 border-bottom">
                        <span>Subtotal</span>
                        <span>Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between fw-bold fs-5 mb-4">
                        <span>Total</span>
                        <span>Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                    </div>

                    <!-- Tombol Checkout -->
                    <a href="{{ route('checkout.index') }}" class="btn btn-checkout mb-3">
                        Lanjut ke Pembayaran <i class="bi bi-arrow-right"></i>
                    </a>
                    
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary w-100 rounded-3">
                        Lanjut Belanja
                    </a>
                </div>

                <!-- Info Tambahan -->
                <div class="card-custom mt-4" style="background-color: #f0f0f0;">
                    <h6 class="fw-bold mb-2"><i class="bi bi-info-circle"></i> Informasi</h6>
                    <ul class="small text-muted mb-0 ps-3">
                        <li>Pastikan jumlah dan ukuran produk sudah sesuai</li>
                        <li>Stok produk dapat berubah sewaktu-waktu</li>
                        <li>Hubungi admin jika ada pertanyaan</li>
                    </ul>
                </div>
            </div>
        </div>
    @else
        <!-- Tampilan Jika Keranjang Kosong -->
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card-custom text-center py-5">
                    <i class="bi bi-cart-x display-1 text-muted mb-3"></i>
                    <h4 class="fw-bold">Keranjang Kosong</h4>
                    <p class="text-muted mb-4">Anda belum menambahkan produk ke keranjang.</p>
                    <a href="{{ route('home') }}" class="btn btn-checkout" style="width: auto; padding: 10px 30px;">
                        Mulai Belanja
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection