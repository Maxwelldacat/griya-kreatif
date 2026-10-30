@extends('layouts.admin')

@section('title', 'Detail Pesanan')
@section('page-title', 'Detail Pesanan')

@section('content')
<style>
    .form-control-custom { background-color: #fafafa; border: 1px solid #ddd; border-radius: 10px; padding: 12px 20px; }
    .card-custom { border: none; border-radius: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); margin-bottom: 1.5rem; }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex align-items-center">
        <a href="{{ route('admin.orders.index') }}" class="btn btn-light border rounded-circle me-3" style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;">
            <i class="bi bi-arrow-left fs-5"></i>
        </a>
        <h4 class="mb-0 fw-bold">Detail Pesanan</h4>
    </div>
    <span class="text-muted small"><i class="bi bi-clock me-1"></i> {{ $order->created_at->format('d M Y, H:i') }} WIB</span>
</div>

<div class="row g-4">
    <!-- KOLOM KIRI: Daftar Barang yang Dibeli -->
    <div class="col-lg-8">
        <!-- Header Nota -->
        <div class="card card-custom">
            <div class="card-body p-4 d-flex justify-content-between align-items-center border-bottom">
                <div>
                    <h5 class="fw-bold mb-1" style="color: #d32f2f;">{{ $order->order_number }}</h5>
                    <span class="text-muted">Status Pesanan:</span>
                    <span class="badge bg-{{ $order->display_status_badge }} rounded-pill px-3 py-1 ms-1">
                        {{ $order->display_status }}
                    </span>
                </div>
                <div class="text-end">
                    <small class="text-muted d-block">Metode Pengiriman</small>
                    <strong class="text-dark">{{ $order->shipping_method }}</strong>
                    <small class="text-muted d-block mt-1">Metode Pembayaran</small>
                    <strong class="text-dark">{{ $order->payment_method }}</strong>
                </div>
            </div>
            
            <!-- List Barang -->
            <div class="card-body p-0">
                @foreach($order->orderItems as $item)
                    <div class="p-4 border-bottom d-flex gap-3 align-items-center">
                        <img src="{{ $item->product->image_url }}" class="rounded shadow-sm" style="width: 80px; height: 80px; object-fit: cover;" onerror="this.src='https://placehold.co/80x80/eaeaea/888?text=No'">
                        <div class="flex-grow-1">
                            <h6 class="fw-bold mb-1">{{ $item->product->name }}</h6>
                            @if($item->size)
                                <span class="badge bg-light text-dark border me-2">Ukuran: {{ $item->size }}</span>
                            @endif
                            <span class="text-muted small">{{ $item->quantity }} x {{ $item->formatted_price }}</span>
                        </div>
                        <div class="text-end fw-bold text-dark fs-6">
                            {{ $item->formatted_subtotal }}
                        </div>
                    </div>
                @endforeach
                
                <!-- Total & Ongkir -->
                <div class="p-4 bg-light rounded-bottom">
                    <div class="d-flex justify-content-between text-muted mb-2">
                        <span>Subtotal Produk</span>
                        <span>Rp {{ number_format($order->total_amount - ($order->shipping_cost ?? 0), 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between text-muted mb-3">
                        <span>Ongkos Kirim ({{ $order->shipping_method }})</span>
                        <span>
                            @if($order->shipping_method === 'Pick-up')
                                Rp 0 (Gratis)
                            @elseif($order->shipping_cost !== null)
                                Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}
                            @else
                                <span class="text-danger fw-bold"><i class="bi bi-exclamation-circle"></i> Belum diinput</span>
                            @endif
                        </span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">Total Tagihan</h5>
                        <h3 class="mb-0 fw-bold text-success">{{ $order->formatted_total }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Catatan Pembeli (Jika Ada) -->
        @if($order->customer_notes)
        <div class="card card-custom" style="background-color: #fff8e1; border-left: 5px solid #ffc107;">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-2 text-dark"><i class="bi bi-chat-left-quote me-2"></i> Catatan dari Pembeli:</h6>
                <p class="text-muted fst-italic mb-0 fs-6">"{{ $order->customer_notes }}"</p>
            </div>
        </div>
        @endif
    </div>

    <!-- KOLOM KANAN: Info Pembeli & Aksi -->
    <div class="col-lg-4">
        
        <!-- FORM INPUT ONGKIR (Hanya muncul jika butuh diinput) -->
        @if($order->shipping_method !== 'Pick-up' && $order->shipping_cost === null)
        <div class="card card-custom" style="background-color: #e3f2fd; border-left: 5px solid #2196f3;">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-2 text-dark"><i class="bi bi-truck me-2"></i> Input Ongkos Kirim</h6>
                <p class="small text-muted mb-3">Pesanan ini menggunakan {{ $order->shipping_method }}. Silakan timbang barang dan masukkan biaya ongkirnya.</p>
                
                <form action="{{ route('admin.orders.update-shipping', $order) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-white border-end-0">Rp</span>
                        <input type="number" name="shipping_cost" class="form-control border-start-0" placeholder="Contoh: 15000" required min="0">
                    </div>
                    <button type="submit" class="btn btn-primary w-100 rounded-3 fw-bold">
                        <i class="bi bi-save me-1"></i> Simpan Ongkir
                    </button>
                </form>
            </div>
        </div>
        @endif

        <!-- Info Pembeli -->
        <div class="card card-custom">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3" style="color: #4a154b;"><i class="bi bi-person-circle me-2"></i> Informasi Pembeli</h6>
                
                <h6 class="fw-bold mb-1">{{ $order->user->name }}</h6>
                <p class="mb-1 text-muted small"><i class="bi bi-envelope me-2"></i> {{ $order->user->email }}</p>
                <p class="mb-3 text-muted small"><i class="bi bi-telephone me-2"></i> {{ $order->user->phone ?? 'Tidak ada no HP' }}</p>
                
                <div class="p-3 bg-light rounded-3 mb-3">
                    <small class="text-muted d-block mb-1">Alamat Pengiriman:</small>
                    <span class="fw-medium text-dark small">{{ $order->user->address ?? 'Belum mengisi alamat' }}</span>
                </div>

                <!-- TOMBOL WA -->
                @if($order->user->phone)
                    @php
                        $phone = preg_replace('/[^0-9]/', '', $order->user->phone);
                        if (str_starts_with($phone, '0')) {
                            $phone = '62' . substr($phone, 1);
                        }
                        $waText = urlencode("Halo Kak " . $order->user->name . ", saya Admin Griya Kreatif Deyalina. Kami ingin mengonfirmasi pesanan Anda dengan No. " . $order->order_number . ". Total tagihannya adalah " . $order->formatted_total . ".");
                    @endphp
                    <a href="https://wa.me/{{ $phone }}?text={{ $waText }}" target="_blank" class="btn w-100 text-white fw-bold py-2 rounded-3" style="background-color: #25D366;">
                        <i class="bi bi-whatsapp me-1"></i> Chat Pembeli via WA
                    </a>
                @endif
            </div>
        </div>

        <!-- Form Update Status -->
        <div class="card card-custom" style="background-color: #e8f5e9;">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3 text-dark"><i class="bi bi-arrow-repeat me-2"></i> Ubah Status Pesanan</h6>
                <p class="text-muted small mb-3">Ubah status menjadi "Selesai" jika pesanan sudah dibayar dan diterima.</p>
                
                <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <select name="status" class="form-select form-control-custom border-0 shadow-sm" required>
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Selesai (Pesanan Sukses)</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success w-100 py-2 fw-bold rounded-3">
                        Simpan Perubahan Status
                    </button>
                </form>
            </div>
        </div>
        
    </div>
</div>
@endsection