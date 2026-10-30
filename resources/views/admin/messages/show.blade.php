@extends('layouts.admin')

@section('title', 'Detail Pesan')
@section('page-title', 'Detail Pesan')

@section('content')
<div class="row mb-3">
    <div class="col-12">
        <a href="{{ route('admin.messages.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Pesan
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-primary text-dark">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-envelope"></i> {{ $message->subject }}
                    </h5>
                    @if($message->hasReply())
                        <span class="badge bg-success">Sudah Dibalas</span>
                    @else
                        <span class="badge bg-warning text-dark">Belum Dibalas</span>
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
                            <small class="text-muted">{{ $message->user->email }}</small>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <small class="text-muted">Tanggal:</small>
                            <p class="mb-0">{{ $message->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Original Message -->
                <div class="mb-4">
                    <h6 class="fw-bold mb-2"><i class="bi bi-chat-left-text"></i> Pesan Pelanggan:</h6>
                    <div class="bg-light p-3 rounded">
                        <p class="mb-0" style="white-space: pre-line;">{{ $message->message }}</p>
                    </div>
                </div>

                <!-- Admin Reply Section -->
                @if($message->hasReply())
                    <div class="alert alert-success border-start border-5 border-success">
                        <h6 class="fw-bold mb-2">
                            <i class="bi bi-reply-fill"></i> Balasan Anda:
                        </h6>
                        <p class="mb-0" style="white-space: pre-line;">{{ $message->admin_reply }}</p>
                    </div>
                @endif

                <!-- Reply Form -->
                <hr>
                <div>
                    <h6 class="fw-bold mb-3">
                        <i class="bi bi-send"></i> {{ $message->hasReply() ? 'Ubah Balasan' : 'Balas Pesan' }}
                    </h6>
                    <form action="{{ route('admin.messages.reply', $message) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <textarea class="form-control @error('admin_reply') is-invalid @enderror" 
                                      name="admin_reply" rows="6" required 
                                      placeholder="Tulis balasan Anda di sini...">{{ old('admin_reply', $message->admin_reply) }}</textarea>
                            @error('admin_reply')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-send-check"></i> {{ $message->hasReply() ? 'Update Balasan' : 'Kirim Balasan' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer Info -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-primary text-dark">
                <h6 class="mb-0"><i class="bi bi-person"></i> Informasi Pelanggan</h6>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <strong>Nama:</strong>
                    <p class="mb-0">{{ $message->user->name }}</p>
                </div>
                <div class="mb-2">
                    <strong>Email:</strong>
                    <p class="mb-0">{{ $message->user->email }}</p>
                </div>
                <div class="mb-2">
                    <strong>Telepon:</strong>
                    <p class="mb-0">{{ $message->user->phone ?? 'Tidak tersedia' }}</p>
                </div>
                <div class="mb-0">
                    <strong>Total Pesanan:</strong>
                    <p class="mb-0 fw-bold">{{ $message->user->orders()->count() }} pesanan</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection