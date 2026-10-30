@extends('layouts.admin')

@section('title', 'Pesan Pelanggan')
@section('page-title', 'Pesan Pelanggan')

@section('content')
<!-- Filters -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.messages.index') }}" method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Status Baca</label>
                        <select name="read_status" class="form-select">
                            <option value="">Semua</option>
                            <option value="unread" {{ request('read_status') == 'unread' ? 'selected' : '' }}>Belum Dibaca</option>
                            <option value="read" {{ request('read_status') == 'read' ? 'selected' : '' }}>Sudah Dibaca</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Status Balasan</label>
                        <select name="reply_status" class="form-select">
                            <option value="">Semua</option>
                            <option value="unanswered" {{ request('reply_status') == 'unanswered' ? 'selected' : '' }}>Belum Dibalas</option>
                            <option value="answered" {{ request('reply_status') == 'answered' ? 'selected' : '' }}>Sudah Dibalas</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Cari</label>
                        <input type="text" name="search" class="form-control" 
                               placeholder="Subjek atau nama pelanggan..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-funnel"></i> Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Messages List -->
<div class="card">
    <div class="card-body">
        @if($messages->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px;"></th>
                            <th>Subjek</th>
                            <th>Pelanggan</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th style="width: 120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($messages as $message)
                            <tr class="{{ !$message->is_read ? 'table-warning' : '' }}">
                                <td class="text-center">
                                    @if(!$message->is_read)
                                        <i class="bi bi-envelope-fill text-warning"></i>
                                    @else
                                        <i class="bi bi-envelope-open text-muted"></i>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $message->subject }}</strong><br>
                                    <small class="text-muted">{{ Str::limit($message->message, 60) }}</small>
                                </td>
                                <td>{{ $message->user->name }}</td>
                                <td>{{ $message->created_at->format('d M Y, H:i') }}</td>
                                <td>
                                    @if($message->hasReply())
                                        <span class="badge bg-success">Dibalas</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Belum Dibalas</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="table-actions">
                                        <a href="{{ route('admin.messages.show', $message) }}" 
                                           class="btn btn-sm btn-primary" title="Lihat & Balas">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <form action="{{ route('admin.messages.destroy', $message) }}" 
                                              method="POST" class="d-inline" 
                                              onsubmit="return confirm('Hapus pesan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $messages->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-inbox display-1 text-muted"></i>
                <h5 class="mt-3">Tidak Ada Pesan</h5>
                <p class="text-muted">Belum ada pesan dari pelanggan atau tidak ada hasil yang cocok dengan filter.</p>
            </div>
        @endif
    </div>
</div>
@endsection