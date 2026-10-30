@extends('layouts.app')

@section('title', 'Halaman Tidak Ditemukan')

@section('content')
<div class="container d-flex align-items-center justify-content-center" style="min-height: 60vh;">
    <div class="text-center">
        <h1 class="fw-bold" style="font-size: 8rem; color: #4a154b; line-height: 1;">404</h1>
        <h3 class="fw-bold mb-3">Ups! Halaman Tidak Ditemukan</h3>
        <p class="text-muted mb-5">Maaf, halaman yang Anda cari mungkin sudah dihapus, diubah namanya, atau tidak pernah ada.</p>
        <a href="{{ route('home') }}" class="btn text-white fw-bold px-5 py-3" style="background-color: #4a154b; border-radius: 10px;">
            <i class="bi bi-house-door me-2"></i> Kembali ke Beranda
        </a>
    </div>
</div>
@endsection