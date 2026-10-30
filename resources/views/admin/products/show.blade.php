@extends('layouts.admin')

@section('title', 'Detail Produk')
@section('page-title', 'Detail Produk')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex align-items-center">
        <a href="{{ route('admin.products.index') }}" class="btn btn-light border rounded-circle me-3" style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;">
            <i class="bi bi-arrow-left fs-5"></i>
        </a>
        <h4 class="mb-0 fw-bold">Detail Produk</h4>
    </div>
    <a href="{{ route('admin.products.edit', $product) }}" class="btn text-white px-4 py-2" style="background-color: #8a264a; border-radius: 10px;">
        <i class="bi bi-pencil me-1"></i> Edit Produk
    </a>
</div>

<div class="row g-4">
    <!-- KIRI: Info Produk -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-4 mb-3 mb-md-0">
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="img-fluid rounded-3 shadow-sm w-100" style="object-fit: contain; max-height: 300px;" onerror="this.src='https://placehold.co/400x400/eaeaea/888?text=No+Image'">
                    </div>
                    <div class="col-md-8">
                        <span class="badge bg-light text-dark border mb-2">{{ $product->category->name }}</span>
                        <h3 class="fw-bold mb-3">{{ $product->name }}</h3>
                        
                        <div class="d-flex align-items-center gap-4 mb-4">
                            <div>
                                <small class="text-muted d-block">Harga</small>
                                <h4 class="fw-bold mb-0" style="color: #d32f2f;">{{ $product->formatted_price }}</h4>
                            </div>
                            <div>
                                <small class="text-muted d-block">Stok</small>
                                <h5 class="fw-bold mb-0 {{ $product->stock <= 5 ? 'text-danger' : 'text-success' }}">{{ $product->stock }} unit</h5>
                            </div>
                        </div>

                        <h6 class="fw-bold text-muted mb-2">Deskripsi:</h6>
                        <p class="text-muted" style="line-height: 1.6;">{{ $product->description ?? 'Tidak ada deskripsi.' }}</p>

                        <hr>
                        
                        <div class="row mt-3">
                            <div class="col-sm-6 mb-2">
                                <small class="text-muted d-block">Nomor P-IRT</small>
                                <span class="fw-bold">{{ $product->pirt_number ?? '-' }}</span>
                            </div>
                            <div class="col-sm-6 mb-2">
                                <small class="text-muted d-block">Status Katalog</small>
                                @if($product->is_active)
                                    <span class="badge bg-success rounded-pill px-3">Aktif</span>
                                @else
                                    <span class="badge bg-secondary rounded-pill px-3">Disembunyikan</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- KANAN: Statistik & Update Stok Cepat -->
    <div class="col-lg-4">
        <!-- Statistik -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3"><i class="bi bi-graph-up text-success me-2"></i> Statistik Penjualan</h5>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Total Terjual:</span>
                    <span class="fw-bold">{{ $totalSold }} unit</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Total Pendapatan:</span>
                    <span class="fw-bold text-success">Rp {{ number_format($totalSold * $product->price, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Update Stok Cepat -->
        <div class="card border-0 shadow-sm rounded-4" style="background-color: #fff3cd;">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3 text-dark"><i class="bi bi-box-seam me-2"></i> Update Stok Cepat</h6>
                <form action="{{ route('admin.products.update-stock', $product) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <select class="form-select border-0 shadow-sm" name="action" required>
                            <option value="increase">Tambah Stok (+)</option>
                            <option value="decrease">Kurangi Stok (-)</option>
                            <option value="set">Set Stok Menjadi (=)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <input type="number" class="form-control border-0 shadow-sm" name="amount" min="0" placeholder="Jumlah..." required>
                    </div>
                    <button type="submit" class="btn w-100 fw-bold" style="background-color: #ffc107; color: #333;">
                        <i class="bi bi-arrow-repeat"></i> Update Stok Sekarang
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection