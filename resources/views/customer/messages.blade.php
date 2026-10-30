@extends('layouts.app')

@section('title', 'Pesan Saya')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="fw-bold"><i class="bi bi-chat-dots"></i> Pesan Saya</h2>
            <p class="text-muted">Komunikasi dengan admin toko</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('messages.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Kirim Pesan Baru
            </a>
        </div>
    </div>

    @if($messages->count() > 0)
        <div class="row">
            @foreach($messages as $message)
                <div class="col-12 mb-3">
                    <div class="card {{ $message->hasReply() ? 'border-success' : '' }}">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <!-- Message Subject -->
                                <div class="col-md-5">
                                    <h6 class="fw-bold mb-1">
                                        {{ $message->subject }}
                                        @if($message->hasReply())
                                            <span class="badge bg-success ms-2">
                                                <i class="bi bi-reply-fill"></i> Dibalas
                                            </span>
                                        @else
                                            <span class="badge bg-warning text-dark ms-2">
                                                <i class="bi bi-clock"></i> Menunggu
                                            </span>
                                        @endif
                                    </h6>
                                    <small class="text-muted">
                                        <i class="bi bi-calendar"></i> {{ $message->created_at->format('d M Y, H:i') }}
                                    </small>
                                </div>

                                <!-- Message Preview -->
                                <div class="col-md-5">
                                    <p class="mb-0 text-muted small">
                                        {{ Str::limit($message->message, 100) }}
                                    </p>
                                </div>

                                <!-- Action Button -->
                                <div class="col-md-2 text-end">
                                    <a href="{{ route('messages.show', $message) }}" class="btn btn-sm btn-primary">
                                        <i class="bi bi-eye"></i> Buka
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="row mt-4">
            <div class="col-12">
                {{ $messages->links() }}
            </div>
        </div>
    @else
        <!-- No Messages -->
        <div class="row">
            <div class="col-md-6 mx-auto">
                <div class="card text-center py-5">
                    <div class="card-body">
                        <i class="bi bi-chat-left-text display-1 text-muted"></i>
                        <h4 class="mt-3">Belum Ada Pesan</h4>
                        <p class="text-muted">Anda belum pernah mengirim pesan ke admin</p>
                        <a href="{{ route('messages.create') }}" class="btn btn-success">
                            <i class="bi bi-plus-circle"></i> Kirim Pesan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection