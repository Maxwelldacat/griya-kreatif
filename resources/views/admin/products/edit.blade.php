@extends('layouts.admin')

@section('title', 'Edit Produk')
@section('page-title', 'Edit Produk')

@section('content')
<style>
    .form-label { font-weight: 600; color: #4a154b; margin-bottom: 8px; }
    .form-control-custom { background-color: white; border: 1px solid #ddd; border-radius: 10px; padding: 12px 20px; }
    .form-control-custom:focus { border-color: #4a154b; box-shadow: 0 0 0 3px rgba(74, 21, 75, 0.1); }
    .upload-area { border: 2px dashed #ccc; border-radius: 15px; padding: 30px 20px; text-align: center; cursor: pointer; transition: 0.3s; background-color: #fafafa; }
    .upload-area:hover { border-color: #4a154b; background-color: #f8f4f8; }
</style>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('admin.products.index') }}" class="btn btn-light border rounded-circle me-3" style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;">
                <i class="bi bi-arrow-left fs-5"></i>
            </a>
            <h4 class="mb-0 fw-bold">Edit Produk: {{ $product->name }}</h4>
        </div>

        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <div class="row g-4">
                        <!-- KOLOM KIRI -->
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label">Nama Produk <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-custom @error('name') is-invalid @enderror" name="name" value="{{ old('name', $product->name) }}" required>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Stok <span class="text-danger">*</span></label>
                                <input type="number" class="form-control form-control-custom @error('stock') is-invalid @enderror" name="stock" value="{{ old('stock', $product->stock) }}" required min="0">
                                @error('stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Kategori <span class="text-danger">*</span></label>
                                <select class="form-select form-control-custom @error('category_id') is-invalid @enderror" name="category_id" required>
                                    <option value="">Pilih Kategori</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Deskripsi Produk</label>
                                <textarea class="form-control form-control-custom @error('description') is-invalid @enderror" name="description" rows="5">{{ old('description', $product->description) }}</textarea>
                                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <!-- KOLOM KANAN -->
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control form-control-custom @error('price') is-invalid @enderror" name="price" value="{{ old('price', $product->price) }}" required min="0">
                                @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Nomor P-IRT / Sertifikat</label>
                                <input type="text" class="form-control form-control-custom @error('pirt_number') is-invalid @enderror" name="pirt_number" value="{{ old('pirt_number', $product->pirt_number) }}">
                                @error('pirt_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Gambar Produk</label>
                                @if($product->image)
                                    <div class="mb-2">
                                        <img src="{{ $product->image_url }}" alt="Current Image" class="rounded shadow-sm" style="height: 100px; object-fit: cover;">
                                    </div>
                                @endif
                                <label for="image" class="upload-area d-block">
                                    <i class="bi bi-image display-6 text-muted mb-2"></i>
                                    <h6 class="fw-bold mb-0">Klik untuk {{ $product->image ? 'mengganti' : 'memilih' }} foto</h6>
                                    <p class="text-muted small mb-0">Biarkan kosong jika tidak ingin mengubah gambar</p>
                                    <input type="file" id="image" name="image" class="d-none" accept="image/*">
                                </label>
                                @error('image')<div class="text-danger mt-1 small">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-2 form-check">
                                <input class="form-check-input" type="checkbox" id="has_sizes" name="has_sizes" value="1" {{ old('has_sizes', $product->has_sizes) ? 'checked' : '' }}>
                                <label class="form-check-label fw-medium" for="has_sizes">Memiliki varian ukuran (S, M, L, XL)</label>
                            </div>

                            <div class="mb-3 form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label fw-medium" for="is_active">Tampilkan produk di katalog</label>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-end gap-3">
                        <a href="{{ route('admin.products.index') }}" class="btn btn-light border px-4 py-2" style="border-radius: 10px;">Batal</a>
                        <button type="submit" class="btn text-white px-4 py-2" style="background-color: #4a154b; border-radius: 10px;">
                            <i class="bi bi-save me-1"></i> Update Produk
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection