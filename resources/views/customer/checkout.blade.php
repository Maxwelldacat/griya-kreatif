@extends('layouts.app')

@section('title', 'Checkout')

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
        background-color: white; padding: 1.5rem;
    }
    .form-control-custom {
        background-color: #e0e0e0; border: none; border-radius: 8px; padding: 10px 15px;
        color: #555; cursor: not-allowed;
    }
    .btn-action {
        background-color: #8a264a; color: white; border-radius: 8px; font-weight: 500;
        transition: 0.3s;
    }
    .btn-action:hover { background-color: #4a154b; color: white; }
</style>

<!-- Header -->
<div class="header-gradient">
    <div class="container d-flex align-items-center gap-3">
        <a href="{{ route('cart.index') }}" class="btn-back"><i class="bi bi-arrow-left fs-5"></i></a>
        <h4 class="mb-0 fw-normal">Konfirmasi Pembayaran / Checkout</h4>
    </div>
</div>

<div class="container pb-5">
    <form action="{{ route('checkout.store') }}" method="POST">
        @csrf
        <div class="row g-4">
            
            <!-- KOLOM KIRI: Data & Pembayaran -->
            <div class="col-lg-8">
                
                <!-- Konfirmasi Data -->
                <div class="card-custom mb-4">
                    <h5 class="fw-bold mb-4 text-dark">Konfirmasi Data</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">Nama</label>
                            <input type="text" class="form-control form-control-custom" value="{{ Auth::user()->name }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">Telepon</label>
                            <input type="text" class="form-control form-control-custom" value="{{ Auth::user()->phone ?? 'Belum diisi' }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">Alamat</label>
                            <input type="text" class="form-control form-control-custom @if(empty(Auth::user()->address)) is-invalid @endif" value="{{ Auth::user()->address ?? 'Belum diisi' }}" readonly>
                            @if(empty(Auth::user()->address))
                                <div class="invalid-feedback">Alamat wajib diisi!</div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">E-mail</label>
                            <input type="email" class="form-control form-control-custom" value="{{ Auth::user()->email }}" readonly>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('profile.edit') }}" class="btn btn-sm px-4 py-2" style="background-color: #e0e0e0; color: #333; font-weight: 500; border-radius: 8px;">
                            Edit Profile
                        </a>
                    </div>
                </div>
                
                <!-- METODE PENGIRIMAN & PEMBAYARAN -->
                <div class="card-custom mb-4">
                    <h5 class="fw-bold mb-3 text-dark">Metode Pengiriman</h5>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="shipping_method" id="ship_pickup" value="Pick-up" required checked onchange="togglePayment()">
                        <label class="form-check-label text-dark" for="ship_pickup">Ambil di Toko (Gratis)</label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="shipping_method" id="ship_lokal" value="Kurir Lokal" onchange="togglePayment()">
                        <label class="form-check-label text-dark" for="ship_lokal">Diantar Kurir Lokal (Ongkir dihitung Admin)</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="shipping_method" id="ship_ekspedisi" value="Ekspedisi" onchange="togglePayment()">
                        <label class="form-check-label text-dark" for="ship_ekspedisi">Ekspedisi Luar Kota (Ongkir dihitung Admin)</label>
                    </div>

                    <hr class="my-4">

                    <h5 class="fw-bold mb-3 text-dark">Metode Pembayaran</h5>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="payment_method" id="pay_transfer" value="Transfer" required checked>
                        <label class="form-check-label text-dark" for="pay_transfer">Transfer Bank</label>
                    </div>
                    <div class="form-check" id="cod-container">
                        <input class="form-check-input" type="radio" name="payment_method" id="pay_cod" value="COD">
                        <label class="form-check-label text-dark" for="pay_cod">Bayar di Tempat (COD)</label>
                    </div>
                    <small class="text-muted d-block mt-2" id="cod-warning" style="display:none;">COD hanya tersedia untuk dalam kota / ambil di toko.</small>
                </div>
            
                <!-- Konfirmasi Pembayaran (Ringkasan Pesanan) -->
                <div class="card-custom">
                    <h5 class="fw-bold mb-4 text-dark">Konfirmasi Pembayaran</h5>
                    
                    @foreach($cartItems as $item)
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h6 class="mb-0 fw-bold">{{ $item->product->name }} <span class="text-muted fw-normal">({{ $item->quantity }}x)</span></h6>
                                @if($item->size)
                                    <small class="text-muted">Ukuran: {{ $item->size }}</small>
                                @endif
                            </div>
                            <span class="fw-bold text-dark">{{ $item->formatted_subtotal }}</span>
                        </div>
                    @endforeach

                    <hr class="my-4" style="border-color: #ddd;">
                    
                    <div class="d-flex justify-content-between text-muted mb-2">
                        <span>Ongkos Kirim</span>
                        <span>Dikonfirmasi Admin</span>
                    </div>
                    <div class="d-flex justify-content-between fw-bold fs-5 mt-4">
                        <span>Total Harga Barang</span>
                        <span>Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                    </div>
                </div>

            </div>

            <!-- KOLOM KANAN: Catatan & Aksi -->
            <div class="col-lg-4">
                
                <!-- Catatan Pesanan -->
                <div class="card-custom mb-4">
                    <h5 class="fw-bold mb-3 text-dark">Catatan Pesanan</h5>
                    <textarea class="form-control" name="customer_notes" rows="8" style="background-color: #e0e0e0; border: none; border-radius: 10px;" placeholder="Tambahkan catatan...">{{ old('customer_notes') }}</textarea>
                </div>

                <!-- Tombol Aksi (DENGAN VALIDASI ALAMAT) -->
                <div class="text-center">
                    <p class="mb-3 text-muted">Untuk info metode pembayaran, klik di bawah ini</p>
                    
                    <a href="https://wa.me/6282139788324?text=Halo%20Admin,%20saya%20ingin%20bertanya%20mengenai%20pesanan%20saya." target="_blank" class="btn btn-action w-100 py-2 mb-3">
                        <i class="bi bi-whatsapp me-1"></i> Hubungi Admin via WA
                    </a>
                    
                    @if(empty(Auth::user()->address))
                        <div class="alert alert-danger small border-0 rounded-3 mb-3 text-start">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i> 
                            Alamat pengiriman Anda masih kosong. Silakan klik <strong>Edit Profile</strong> di atas terlebih dahulu.
                        </div>
                        <button type="button" class="btn btn-secondary w-100 py-2" disabled>
                            Buat Pesanan (Alamat Kosong)
                        </button>
                    @else
                        <button type="submit" class="btn btn-action w-100 py-2">
                            Buat Pesanan
                        </button>
                    @endif
                </div>

            </div>
        </div>
    </form>
</div>

<script>
    function togglePayment() {
        const isEkspedisi = document.getElementById('ship_ekspedisi').checked;
        const codContainer = document.getElementById('cod-container');
        const codWarning = document.getElementById('cod-warning');
        const codRadio = document.getElementById('pay_cod');
        const transferRadio = document.getElementById('pay_transfer');

        if (isEkspedisi) {
            codContainer.style.opacity = '0.5';
            codRadio.disabled = true;
            if (codRadio.checked) transferRadio.checked = true;
            codWarning.style.display = 'block';
        } else {
            codContainer.style.opacity = '1';
            codRadio.disabled = false;
            codWarning.style.display = 'none';
        }
    }
</script>

@endsection