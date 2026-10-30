@extends('layouts.admin')

@section('title', 'Tambah Kategori')
@section('page-title', 'Tambah Kategori Baru')

@section('content')
<style>
    .form-label { font-weight: 600; color: #4a154b; margin-bottom: 8px; }
    .form-control-custom { background-color: #fafafa; border: 1px solid #ddd; border-radius: 10px; padding: 12px 20px; }
    .form-control-custom:focus { background-color: white; border-color: #4a154b; box-shadow: 0 0 0 3px rgba(74, 21, 75, 0.1); }
</style>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('admin.categories.index') }}" class="btn btn-light border rounded-circle me-3" style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;">
                <i class="bi bi-arrow-left fs-5"></i>
            </a>
            <h4 class="mb-0 fw-bold">Tambah Kategori Baru</h4>
        </div>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <form action="{{ route('admin.categories.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="name" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-custom @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" 
                               placeholder="Contoh: Kue Kering dan Bakery" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <small class="text-muted mt-2 d-block">Slug URL akan dibuat otomatis dari nama kategori ini.</small>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label">Deskripsi Singkat</label>
                        <textarea class="form-control form-control-custom @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="4" 
                                  placeholder="Tuliskan penjelasan singkat mengenai kategori ini...">{{ old('description') }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-end gap-3">
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-light border px-4 py-2" style="border-radius: 10px;">Batal</a>
                        <button type="submit" class="btn text-white px-4 py-2" style="background-color: #4a154b; border-radius: 10px;">
                            <i class="bi bi-save me-1"></i> Simpan Kategori
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection