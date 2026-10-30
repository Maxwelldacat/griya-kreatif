@extends('layouts.app')

@section('title', 'Detail Pesanan')

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
    .text-pink { color: #d81b60; }
    
    /* Timeline CSS */
    .timeline { list-style: none; padding-left: 0; margin: 0; position: relative; }
    .timeline::before { content: ''; position: absolute; left: 9px; top: 5px; bottom: 5px; width: 2px; background: #ddd; }
    .timeline li { position: relative; padding-left: 30px; margin-bottom: 20px; }
    .timeline li::before {
        content: ''; position: absolute; left: 0; top: 2px; width: 20px; height: 20px;
        border-radius: 50%; background-color: #8a264a;
    }
    .timeline li:last-child { margin-bottom: 0; }
</style>

<!-- Header -->
<div class="header-gradient">
    <div class="container d-flex align-items-center gap-3">
        <a href="{{ route('orders.index') }}" class="btn-back"><i class="bi bi-arrow-left fs-5"></i></a>
        <h4 class="mb-0 fw-normal">Detail Pesanan</h4>
    </div>
</div>

<div class="container pb-5">
    <div class="row g-4">
        
        <!-- KOLOM KIRI -->
        <div class="col-lg-8">
            
            <!-- Card 1: Detail Pesanan -->
            <div class="card-custom">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h5 class="fw-bold text-dark mb-0">Detail Pesanan</h5>
                    <span class="badge rounded-pill px-3 py-2 bg-{{ $order->display_status_badge }} bg-opacity-25 text-{{ $order->display_status_badge }}">
                        {{ $order->display_status }}
                    </span>
                </div>
                
                <h6 class="text-pink mb-3">Nomor Pesanan: {{ $order->order_number }}</h6>
                
                <div class="row text-muted mt-4">
                    <div class="col-md-6 mb-2">
                        <strong class="text-dark d-block">Tanggal Pesanan</strong>
                        {{ $order->created_at->format('d M Y, H:i') }}
                    </div>
                    <div class="col-md-6 mb-2">
                        <strong class="text-dark d-block">Metode Pengiriman</strong>
                        {{ $order->shipping_method }}
                    </div>
                    <div class="col-md-6 mb-2">
                        <strong class="text-dark d-block">Metode Pembayaran</strong>
                        {{ $order->payment_method }}
                    </div>
                    <div class="col-md-6 mb-2">
                        <strong class="text-dark d-block">Alamat Pengiriman</strong>
                        {{ $order->user->address ?? 'Tidak tersedia' }}
                    </div>
                </div>

                @if($order->customer_notes)
                    <div class="mt-4 p-3 rounded" style="background-color: #f8f9fa;">
                        <strong class="text-dark d-block mb-1">Catatan Anda:</strong>
                        <span class="text-muted">{{ $order->customer_notes }}</span>
                    </div>
                @endif
            </div>

            <!-- Card 2: Timeline Status Dinamis -->
            <div class="card-custom">
                <h5 class="fw-bold text-dark mb-4">Status Pesanan</h5>
                <ul class="timeline">
                    <!-- Step 1: Dibuat -->
                    <li>
                        <h6 class="fw-bold mb-1" style="color: #4a154b;">Pesanan Dibuat</h6>
                        <small class="text-muted">{{ $order->created_at->format('d M Y, H:i') }}</small>
                    </li>
                    
                    <!-- Step 2: Menunggu Ongkir (Hanya jika bukan Pick-up) -->
                    @if($order->shipping_method !== 'Pick-up')
                    <li>
                        <h6 class="fw-bold mb-1 {{ $order->shipping_cost === null ? 'text-danger' : 'text-muted' }}">
                            Konfirmasi Ongkos Kirim
                        </h6>
                        <small class="text-muted">
                            @if($order->shipping_cost === null)
                                Admin sedang menghitung ongkir.
                            @else
                                Ongkir disetujui: Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}
                            @endif
                        </small>
                    </li>
                    @endif

                    <!-- Step 3: Menunggu Pembayaran -->
                    <li>
                        <h6 class="fw-bold mb-1 {{ $order->display_status === 'Menunggu Pembayaran' ? 'text-warning text-dark' : 'text-muted' }}">
                            Pembayaran ({{ $order->payment_method }})
                        </h6>
                        <small class="text-muted">
                            @if($order->display_status === 'Menunggu Pembayaran')
                                Menunggu Anda menyelesaikan pembayaran.
                            @else
                                Pembayaran telah dikonfirmasi.
                            @endif
                        </small>
                    </li>

                    <!-- Step 4: Selesai -->
                    <li>
                        <h6 class="fw-bold mb-1 {{ $order->status === 'completed' ? 'text-success' : 'text-muted' }}">Selesai</h6>
                        <small class="text-muted">Pesanan diterima</small>
                    </li>
                </ul>
            </div>

        </div>

        <!-- KOLOM KANAN -->
        <div class="col-lg-4">
            
            <!-- Card 3: Produk yang Dipesan -->
            <div class="card-custom">
                <h5 class="fw-bold text-dark mb-4">Produk yang Dipesan</h5>
                
                @foreach($order->orderItems as $item)
                    <div class="d-flex gap-3 mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                        <div class="flex-grow-1">
                            <h6 class="fw-bold mb-1">{{ $item->product->name }}</h6>
                            @if($item->size)
                                <small class="text-muted d-block">Ukuran: {{ $item->size }}</small>
                            @endif
                            <div class="d-flex justify-content-between mt-1">
                                <small class="text-muted">{{ $item->quantity }} x {{ $item->formatted_price }}</small>
                                <span class="fw-bold text-dark">{{ $item->formatted_subtotal }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
                
                <!-- Rincian Total & Ongkir -->
                <div class="border-top pt-3 mt-4">
                    <div class="d-flex justify-content-between text-muted mb-2">
                        <span>Ongkos Kirim ({{ $order->shipping_method }})</span>
                        <span>
                            @if($order->shipping_method === 'Pick-up')
                                Rp 0 (Gratis)
                            @elseif($order->shipping_cost !== null)
                                Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}
                            @else
                                <span class="text-warning fw-bold">Menunggu Konfirmasi</span>
                            @endif
                        </span>
                    </div>
                    <div class="d-flex justify-content-between fw-bold fs-5 mt-3">
                        <span>Total Tagihan</span>
                        <span>{{ $order->formatted_total }}</span>
                    </div>
                </div>
            </div>

            <!-- Instruksi Pembayaran (Muncul HANYA jika status Menunggu Pembayaran atau lebih) -->
            @if($order->display_status === 'Menunggu Pembayaran' || $order->status === 'completed')
                <div class="alert mt-4 rounded-3 border-0" style="background-color: #e3f2fd; color: #0d47a1;">
                    <h6 class="fw-bold"><i class="bi bi-info-circle me-1"></i> Instruksi Pembayaran</h6>
                    @if($order->payment_method === 'Transfer')
                        <p class="small mb-1">Silakan transfer total tagihan ke rekening:</p>
                        <h6 class="fw-bold mb-1">BCA: 123456789 a/n Ibu Deyalina</h6>
                        <p class="small mb-0">Setelah transfer, silakan hubungi admin via WA untuk konfirmasi.</p>
                    @else
                        <p class="small mb-0">Silakan siapkan uang tunai sebesar <strong>{{ $order->formatted_total }}</strong>. Bayar saat mengambil/menerima barang.</p>
                    @endif
                </div>
            @endif

            <!-- Tombol Aksi -->
            <div class="d-grid gap-3 mt-4">
                <a href="https://wa.me/6282139788324?text=Halo%20Admin,%20saya%20ingin%20bertanya%20mengenai%20pesanan%20saya%20dengan%20nomor%20{{ $order->order_number }}." target="_blank" class="btn py-2 text-white" style="background-color: #8a264a; border-radius: 8px;">
                    <i class="bi bi-whatsapp me-1"></i> Hubungi Admin
                </a>
                <a href="{{ route('home') }}" class="btn py-2 text-dark border" style="border-radius: 8px;">
                    Lanjut Belanja
                </a>
            </div>

        </div>
    </div>
</div>
@endsection