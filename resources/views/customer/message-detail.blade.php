@extends('layouts.app')

@section('title', 'Detail Pesan')

@section('content')
<div class="container">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('messages.index') }}" class="text-decoration-none">Pesan</a></li>
            <li class="breadcrumb-item active">{{ $message->subject }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <!-- Message Card -->
            <div class="card mb-3">
                <div class="card-header bg-primary text-dark">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-envelope"></i> {{ $message->subject }}
                        </h5>
                        @if($message->hasReply())
                            <span class="badge bg-success">
                                <i class="bi bi-reply-fill"></i> Dibalas
                            </span>
                        @else
                            <span class="badge bg-warning text-dark">
                                <i class="bi bi-clock"></i> Menunggu Balasan
                            </span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <!-- Message Metadata -->
                    <div class="mb-3 pb-3 border-bottom">
                        <div class="row">
                            <div class="col-md-6">
                                <small class="text-muted">Dari:</small>
                                <p class="mb-0 fw-bold">{{ $message->user->name }}</p>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <small class="text-muted">Tanggal:</small>
                                <p class="mb-0">{{ $message->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Original Message -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-2"><i class="bi bi-chat-left-text"></i> Pesan Anda:</h6>
                        <div class="bg-light p-3 rounded">
                            <p class="mb-0" style="white-space: pre-line;">{{ $message->message }}</p>
                        </div>
                    </div>

                    <!-- Admin Reply -->
                    @if($message->hasReply())
                        <div class="alert alert-success border-start border-5 border-success">
                            <h6 class="fw-bold mb-2">
                                <i class="bi bi-reply-fill"></i> Balasan Admin:
                            </h6>
                            <p class="mb-0" style="white-space: pre-line;">{{ $message->admin_reply }}</p>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> 
                            Admin belum membalas pesan Anda. Harap tunggu, admin akan merespon secepatnya.
                        </div>
                    @endif
                </div>
                <div class="card-footer bg-light">
                    <div class="d-flex gap-2">
                        <a href="{{ route('messages.index') }}" class="btn btn-outline-primary">
                            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Pesan
                        </a>
                        <a href="{{ route('messages.create') }}" class="btn btn-success">
                            <i class="bi bi-plus-circle"></i> Kirim Pesan Baru
                        </a>
                    </div>
                </div>
            </div>

            <!-- Contact Info Card -->
            <div class="card">
                <div class="card-body">
                    <h6 class="fw-bold mb-3"><i class="bi bi-telephone"></i> Kontak Alternatif</h6>
                    <p class="small text-muted mb-2">
                        Jika pesan Anda urgent, Anda juga bisa menghubungi kami melalui:
                    </p>
                    <ul class="list-unstyled small">
                        <li class="mb-2">
                            <i class="bi bi-whatsapp text-success"></i> 
                            <strong>WhatsApp:</strong> +62 812-3456-7890
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-envelope text-primary"></i> 
                            <strong>Email:</strong> info@griya-deyalina.com
                        </li>
                        <li class="mb-0">
                            <i class="bi bi-clock text-warning"></i> 
                            <strong>Jam Operasional:</strong> Senin - Sabtu, 08:00 - 17:00 WIB
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection