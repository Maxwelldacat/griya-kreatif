@extends('layouts.admin')

@section('title', 'Manajemen Kategori')
@section('page-title', 'Kategori')

@section('content')
<style>
    .table-custom th { background-color: #e5e5e5; color: #555; font-weight: 600; border: none; padding: 15px; }
    .table-custom td { padding: 15px; vertical-align: middle; border-bottom: 1px solid #f0f0f0; }
    .btn-action { font-size: 0.85rem; font-weight: 500; border-radius: 8px; padding: 6px 12px; }
</style>

<div class="row mb-4 align-items-center">
    <div class="col-md-6">
        <h5 class="fw-bold mb-0">Daftar Kategori</h5>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('admin.categories.create') }}" class="btn text-white px-4 py-2" style="background-color: #8a264a; border-radius: 10px; font-weight: 500;">
            <i class="bi bi-plus-lg me-1"></i> Tambah Kategori
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-body p-0">
        @if($categories->count() > 0)
            <div class="table-responsive">
                <table class="table table-custom mb-0">
                    <thead>
                        <tr>
                            <th>Nama Kategori</th>
                            <th>Deskripsi</th>
                            <th>Jumlah Produk</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                            <tr>
                                <td class="fw-bold">{{ $category->name }}</td>
                                <td class="text-muted">{{ Str::limit($category->description, 50) ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-light text-dark border px-3 py-2 rounded-pill fw-bold">{{ $category->products_count }} Produk</span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-action btn-outline-primary">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
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
        @else
            <div class="text-center py-5">
                <i class="bi bi-inbox display-1 text-muted opacity-50"></i>
                <h5 class="mt-3 fw-bold">Tidak Ada Kategori</h5>
                <p class="text-muted">Belum ada kategori yang ditambahkan.</p>
            </div>
        @endif
    </div>
</div>
@endsection