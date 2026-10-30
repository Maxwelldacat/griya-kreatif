@extends('layouts.admin')

@section('title', 'Manajemen Produk')
@section('page-title', 'Produk')

@section('content')
<style>
    .table-custom th { background-color: #e5e5e5; color: #555; font-weight: 600; border: none; padding: 15px; }
    .table-custom td { padding: 15px; vertical-align: middle; border-bottom: 1px solid #f0f0f0; }
    .table-custom tr:hover { background-color: #faf8f5; }
    .btn-action { font-size: 0.85rem; font-weight: 500; border-radius: 8px; padding: 6px 12px; }
    .form-control-custom, .form-select-custom { background-color: #e5e5e5; border: none; border-radius: 10px; padding: 12px 20px; }
    .form-control-custom:focus, .form-select-custom:focus { background-color: white; box-shadow: 0 0 0 3px rgba(74, 21, 75, 0.1); border: 1px solid #4a154b; }
</style>

<!-- Top Bar: Search & Add -->
<div class="row mb-4 align-items-center">
    <div class="col-md-8">
        <form action="{{ route('admin.products.index') }}" method="GET" class="d-flex">
            <input type="text" name="search" class="form-control form-control-custom" placeholder="Cari nama produk..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-light border ms-2 px-3"><i class="bi bi-search"></i></button>
        </form>
    </div>
    <div class="col-md-4 text-end">
        <a href="{{ route('admin.products.create') }}" class="btn text-white px-4 py-2" style="background-color: #8a264a; border-radius: 10px; font-weight: 500;">
            <i class="bi bi-plus-lg me-1"></i> Tambah Produk
        </a>
    </div>
</div>

<!-- Filter Bar -->
<div class="card mb-4 border-0 shadow-sm rounded-4">
    <div class="card-body p-3">
        <form action="{{ route('admin.products.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-4">
                <select name="category" class="form-select form-select-custom">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <select name="status" class="form-select form-select-custom">
                    <option value="">Semua Status</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100" style="background-color: #4a154b; border: none; border-radius: 10px;">Filter</button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-light border w-100" style="border-radius: 10px;">Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- Table -->
<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-body p-0">
        @if($products->count() > 0)
            <div class="table-responsive">
                <table class="table table-custom mb-0">
                    <thead>
                        <tr>
                            <th>Gambar</th>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                            <tr>
                                <td>
                                    <img src="{{ $product->image_url }}" class="rounded" style="width: 50px; height: 50px; object-fit: contain;" onerror="this.src='https://placehold.co/50x50/eaeaea/888?text=No'">
                                </td>
                                <td>
                                    <span class="fw-bold text-dark d-block">{{ $product->name }}</span>
                                    <small class="text-muted">{{ $product->pirt_number ?? '-' }}</small>
                                </td>
                                <td>{{ $product->category->name }}</td>
                                <td class="fw-bold" style="color: #d32f2f;">{{ $product->formatted_price }}</td>
                                <td>
                                    <span class="badge {{ $product->stock <= 0 ? 'bg-danger' : ($product->stock <= 10 ? 'bg-warning text-dark' : 'bg-success') }} rounded-pill px-3">
                                        {{ $product->stock }}
                                    </span>
                                </td>
                                <td>
                                    @if($product->is_active)
                                        <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-2">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-action btn-outline-primary">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus produk {{ $product->name }}?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-action btn-outline-danger">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4">{{ $products->links() }}</div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-inbox display-1 text-muted opacity-50"></i>
                <h5 class="mt-3 fw-bold">Tidak Ada Produk</h5>
                <p class="text-muted">Belum ada produk yang ditambahkan atau tidak cocok dengan filter.</p>
            </div>
        @endif
    </div>
</div>
@endsection