@extends('layouts.app')

@section('title', 'Pesanan Saya')

@section('content')
<style>
    body { background-color: #faf8f5; } /* Warna krem background */
    .header-gradient {
        background: linear-gradient(90deg, #4a154b 0%, #d33a3a 100%);
        color: white;
        padding: 20px 0;
        margin-bottom: 2rem;
    }
    .btn-back {
        background: transparent;
        border: 1px solid white;
        color: white;
        border-radius: 8px;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
    }
    .card-custom {
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }
    .text-pink { color: #d81b60; }
</style>

<!-- Header -->
<div class="header-gradient">
    <div class="container d-flex align-items-center gap-3">
        <a href="{{ route('home') }}" class="btn-back"><i class="bi bi-arrow-left fs-5"></i></a>
        <h4 class="mb-0 fw-normal">Pesanan Saya</h4>
    </div>
</div>

<div class="container pb-5">
    @if($orders->count() > 0)
        @foreach($orders as $order)
            <div class="card card-custom mb-4">
                <div class="card-body p-4">
                    <div class="row">
                        <!-- Kiri: Info Pesanan -->
                        <div class="col-md-8">
                            <h6 class="text-pink fw-bold mb-1">Nomor Pesanan</h6>
                            <h5 class="fw-bold mb-3">{{ $order->order_number }}</h5>
                            
                            <p class="text-muted mb-1">{{ $order->created_at->format('d M Y, H:i') }}</p>
                            <p class="mb-1 text-dark">
                                <strong>Total Produk:</strong> {{ $order->orderItems->sum('quantity') }} item
                            </p>
                            <h5 class="fw-bold text-dark mt-3">{{ $order->formatted_total }}</h5>
                            
                            @if($order->customer_notes)
                                <div class="mt-2 text-muted small">
                                    <i class="bi bi-chat-left-quote"></i> Catatan: {{ $order->customer_notes }}
                                </div>
                            @endif
                        </div>
                        
                        <!-- Kanan: Status & Aksi -->
                        <div class="col-md-4 d-flex flex-column justify-content-between align-items-end">
                            <!-- Status Pengiriman (Badge) -->
                            <span class="badge rounded-pill px-4 py-2" style="background-color: #e0e0e0; color: #333;">
                                @if($order->status === 'pending')
                                    Menunggu Konfirmasi
                                @elseif($order->status === 'completed')
                                    Pesanan Selesai
                                @else
                                    Dibatalkan
                                @endif
                            </span>
                            
                            <!-- Tombol Detail -->
                            <a href="{{ route('orders.show', $order) }}" class="btn px-4 py-2 mt-4" style="background-color: #b0b0b0; color: white; font-weight: 500;">
                                Detail
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $orders->links() }}
        </div>
    @else
        <!-- Jika Kosong -->
        <div class="card card-custom text-center py-5">
            <div class="card-body">
                <i class="bi bi-inbox display-1 text-muted mb-3"></i>
                <h4 class="fw-bold">Belum Ada Pesanan</h4>
                <p class="text-muted">Anda belum pernah melakukan pemesanan.</p>
                <a href="{{ route('home') }}" class="btn mt-3 px-4 py-2 text-white" style="background-color: #4a154b;">
                    Mulai Belanja
                </a>
            </div>
        </div>
    @endif
</div>
@endsection