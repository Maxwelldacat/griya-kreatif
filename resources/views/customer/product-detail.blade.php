@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="container py-4">

    <!-- Header / Tombol Kembali -->
    <div class="d-flex align-items-center mb-4 pb-2 border-bottom">
        <a href="{{ url()->previous() }}" class="btn btn-light border rounded-circle me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h5 class="mb-0 fw-bold" style="color: #4a154b;">Detail Produk</h5>
    </div>

    <div class="row g-4">
        <!-- KOLOM KIRI: Gambar & Info Utama -->
        <div class="col-lg-7">
            <!-- Gambar Produk -->
            <div class="mb-4">
                <img src="{{ $product->image_url }}" class="img-fluid w-100 rounded-4 shadow-sm" alt="{{ $product->name }}" 
                     style="max-height: 400px; min-height: 250px; object-fit: contain; background-color: #fff;"
                     onerror="this.onerror=null; this.src='https://placehold.co/800x600/eaeaea/888?text=No+Image';">
            </div>

            <!-- Info Harga & Stok -->
            <div class="bg-white rounded-4 p-4 shadow-sm">
                <h2 class="fw-bold mb-3" style="color: #4a154b;">{{ $product->name }}</h2>
                
                <div class="d-flex align-items-center gap-3 mb-3 flex-wrap">
                    <h3 class="fw-bold mb-0" style="color: #d32f2f;">{{ $product->formatted_price }}</h3>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <span class="text-muted">Stok:</span>
                    @if($product->stock > 0)
                        <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill">
                            <i class="bi bi-check-circle-fill me-1"></i> {{ $product->stock }} tersisa
                        </span>
                    @else
                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2 rounded-pill">
                            <i class="bi bi-x-circle-fill me-1"></i> Stok Habis
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- KOLOM KANAN: Deskripsi & Tanya -->
        <div class="col-lg-5">
            <div class="bg-white rounded-4 p-4 shadow-sm h-100 d-flex flex-column">
                <h5 class="fw-bold mb-3" style="color: #4a154b;">Deskripsi Produk</h5>
                <p class="text-muted" style="line-height: 1.8; flex-grow: 1;">
                    {{ $product->description }}
                </p>
                
                @if($product->pirt_number)
                    <hr>
                    <p class="text-muted mb-4 small">
                        <i class="bi bi-award text-warning"></i> <strong>Sertifikat:</strong> {{ $product->pirt_number }}
                    </p>
                @endif

                <!-- Tombol Tanya Produk (Hanya untuk Guest & Customer) -->
                @if(!Auth::check() || Auth::user()->role === 'customer')
                <a href="https://wa.me/6282139788324?text=Halo%20Admin,%20saya%20mau%20tanya%20tentang%20produk%20{{ $product->name }}" target="_blank" class="btn btn-outline-dark rounded-3 py-2 w-100 mt-auto">
                    <i class="bi bi-whatsapp text-success"></i> Tanya via WhatsApp
                </a>
                @endif
            </div>
        </div>
    </div>

    <!-- BAGIAN BAWAH: Form Keranjang (Membentang Penuh) -->
    @if($product->stock > 0)
        <div class="bg-white rounded-4 p-4 shadow-sm mt-4">
            
            @if(Auth::check() && Auth::user()->role === 'admin')
                {{-- TAMPILAN KHUSUS ADMIN --}}
                <div class="alert alert-warning mb-0 border-0 rounded-3 text-center" style="background-color: #fff3cd; color: #856404;">
                    <i class="bi bi-info-circle-fill me-2"></i> Anda sedang login sebagai <strong>Admin</strong>. Fitur keranjang dinonaktifkan.
                </div>
            @else
                {{-- TAMPILAN UNTUK CUSTOMER & GUEST --}}
                <form action="{{ route('cart.store') }}" method="POST" id="addToCartForm">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    <div class="row align-items-end">
                        
                        @if($product->has_sizes)
                            <!-- KONDISI 1: PRODUK PUNYA UKURAN (3 Kolom) -->
                            
                            <!-- Pilihan Ukuran -->
                            <div class="col-md-4 mb-3 mb-md-0">
                                <label class="fw-bold mb-2" style="color: #4a154b;">Pilih Ukuran</label>
                                <div class="d-flex gap-2">
                                    <input type="radio" class="btn-check" name="size" id="size_s" value="S" required>
                                    <label class="btn btn-outline-secondary rounded-3 px-3" for="size_s">S</label>

                                    <input type="radio" class="btn-check" name="size" id="size_m" value="M">
                                    <label class="btn btn-outline-secondary rounded-3 px-3" for="size_m">M</label>

                                    <input type="radio" class="btn-check" name="size" id="size_l" value="L">
                                    <label class="btn btn-outline-secondary rounded-3 px-3" for="size_l">L</label>

                                    <input type="radio" class="btn-check" name="size" id="size_xl" value="XL">
                                    <label class="btn btn-outline-secondary rounded-3 px-3" for="size_xl">XL</label>
                                </div>
                            </div>

                            <!-- Input Jumlah -->
                            <div class="col-md-3 mb-3 mb-md-0">
                                <label class="fw-bold mb-2" style="color: #4a154b;">Jumlah</label>
                                <div class="input-group">
                                    <button type="button" class="btn btn-light border" id="decreaseQty">
                                        <i class="bi bi-dash"></i>
                                    </button>
                                    <input type="number" class="form-control text-center border" id="quantity" name="quantity" 
                                           value="1" min="1" max="{{ $product->stock }}" required readonly>
                                    <button type="button" class="btn btn-light border" id="increaseQty">
                                        <i class="bi bi-plus"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Subtotal -->
                            <div class="col-md-5 mb-3 mb-md-0 text-md-end">
                                <span class="text-muted d-block mb-1">Subtotal</span>
                                <h4 class="fw-bold mb-0" style="color: #d32f2f;" id="subtotalDisplay">{{ $product->formatted_price }}</h4>
                            </div>

                        @else
                            <!-- KONDISI 2: PRODUK TIDAK PUNYA UKURAN (2 Kolom) -->
                            
                            <!-- Harga di Kiri -->
                            <div class="col-md-6 mb-3 mb-md-0">
                                <span class="text-muted d-block mb-1">Subtotal</span>
                                <h4 class="fw-bold mb-0" style="color: #d32f2f;" id="subtotalDisplay">{{ $product->formatted_price }}</h4>
                            </div>

                            <!-- Input Jumlah di Kanan -->
                            <div class="col-md-6 mb-3 mb-md-0 text-md-end">
                                <label class="fw-bold mb-2" style="color: #4a154b;">Jumlah</label>
                                <div class="input-group ms-md-auto" style="max-width: 150px;">
                                    <button type="button" class="btn btn-light border" id="decreaseQty">
                                        <i class="bi bi-dash"></i>
                                    </button>
                                    <input type="number" class="form-control text-center border" id="quantity" name="quantity" 
                                           value="1" min="1" max="{{ $product->stock }}" required readonly>
                                    <button type="button" class="btn btn-light border" id="increaseQty">
                                        <i class="bi bi-plus"></i>
                                    </button>
                                </div>
                            </div>

                        @endif
                    </div>

                    <hr class="my-4">

                    <!-- Tombol Tambah -->
                    <button type="submit" class="btn w-100 py-3 text-white fw-bold rounded-3 fs-5" style="background: linear-gradient(to right, #ff6b6b, #e65100);">
                        + Tambah ke Keranjang
                    </button>
                </form>
            @endif
            
        </div>
    @else
        <div class="alert alert-warning rounded-4 mt-4 p-4" style="background-color: #fff3cd; border: none;">
            <i class="bi bi-exclamation-triangle-fill fs-4 me-2"></i> Produk ini sedang habis. Silakan cek kembali nanti.
        </div>
    @endif

    <!-- PRODUK TERKAIT -->
    @if($relatedProducts->count() > 0)
        <div class="mt-5">
            <h4 class="fw-bold mb-4" style="color: #4a154b;">Produk Terkait</h4>
            <div class="row g-3">
                @foreach($relatedProducts as $relatedProduct)
                    <div class="col-6 col-md-3">
                        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                            <img src="{{ $relatedProduct->image_url }}" class="card-img-top" alt="{{ $relatedProduct->name }}" 
                                 style="height: 150px; object-fit: cover;" onerror="this.onerror=null; this.src='https://placehold.co/400x400/eaeaea/888?text=No+Image';">
                            <div class="card-body p-3">
                                <h6 class="card-title fw-bold text-truncate mb-1">{{ $relatedProduct->name }}</h6>
                                <p class="text-danger fw-bold mb-3">{{ $relatedProduct->formatted_price }}</p>
                                <a href="{{ route('products.show', $relatedProduct) }}" class="btn btn-sm btn-outline-secondary w-100 rounded-3">
                                    Detail
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const qtyInput = document.getElementById('quantity');
        const increaseBtn = document.getElementById('increaseQty');
        const decreaseBtn = document.getElementById('decreaseQty');
        const subtotalDisplay = document.getElementById('subtotalDisplay');
        
        if(qtyInput && subtotalDisplay) {
            const priceString = "{{ $product->price }}";
            const productPrice = parseInt(priceString);

            const formatRupiah = (number) => {
                return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number).replace("Rp", "Rp ");
            };

            if(increaseBtn && decreaseBtn) {
                increaseBtn.addEventListener('click', function() {
                    let currentValue = parseInt(qtyInput.value);
                    let maxValue = parseInt(qtyInput.max);
                    if (currentValue < maxValue) {
                        qtyInput.value = currentValue + 1;
                        updateSubtotal();
                    }
                });

                decreaseBtn.addEventListener('click', function() {
                    let currentValue = parseInt(qtyInput.value);
                    let minValue = parseInt(qtyInput.min);
                    if (currentValue > minValue) {
                        qtyInput.value = currentValue - 1;
                        updateSubtotal();
                    }
                });
            }

            function updateSubtotal() {
                let qty = parseInt(qtyInput.value);
                let total = productPrice * qty;
                subtotalDisplay.innerText = formatRupiah(total);
            }
        }
    });
</script>
@endpush