@extends('layouts.app')

@section('title', 'Kirim Pesan')

@section('content')
<div class="container">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('messages.index') }}" class="text-decoration-none">Pesan</a></li>
            <li class="breadcrumb-item active">Kirim Pesan Baru</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-chat-dots"></i> Kirim Pesan ke Admin</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('messages.store') }}" method="POST">
                        @csrf

                        <!-- Subject -->
                        <div class="mb-3">
                            <label for="subject" class="form-label fw-bold">
                                Subjek Pesan <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('subject') is-invalid @enderror" 
                                   id="subject" name="subject" value="{{ old('subject') }}" 
                                   placeholder="Contoh: Pertanyaan tentang produk" required maxlength="255">
                            @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Maksimal 255 karakter</small>
                        </div>

                        <!-- Message -->
                        <div class="mb-3">
                            <label for="message" class="form-label fw-bold">
                                Pesan <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('message') is-invalid @enderror" 
                                      id="message" name="message" rows="8" required maxlength="1000"
                                      placeholder="Tuliskan pesan Anda di sini...">{{ old('message') }}</textarea>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Maksimal 1000 karakter</small>
                        </div>

                        <!-- Info Alert -->
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> 
                            <strong>Tips:</strong> Jelaskan pertanyaan atau kebutuhan Anda dengan jelas. 
                            Admin akan merespon pesan Anda secepatnya.
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-send"></i> Kirim Pesan
                            </button>
                            <a href="{{ route('messages.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Help Card -->
            <div class="card mt-3">
                <div class="card-body">
                    <h6 class="fw-bold mb-3"><i class="bi bi-question-circle"></i> Bantuan</h6>
                    <p class="small mb-2"><strong>Kapan saya harus mengirim pesan?</strong></p>
                    <ul class="small text-muted">
                        <li>Pertanyaan tentang produk atau ketersediaan stok</li>
                        <li>Informasi detail pengiriman atau pembayaran</li>
                        <li>Keluhan atau masukan tentang pesanan</li>
                        <li>Permintaan khusus atau custom order</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Character counter for message
    document.getElementById('message').addEventListener('input', function() {
        const maxLength = 1000;
        const currentLength = this.value.length;
        const remaining = maxLength - currentLength;
        
        // You can add a character counter display here if needed
        console.log('Remaining characters:', remaining);
    });
</script>
@endpush