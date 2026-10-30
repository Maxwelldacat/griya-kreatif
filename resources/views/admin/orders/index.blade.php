@extends('layouts.admin')

@section('title', 'Manajemen Pesanan')
@section('page-title', 'Pesanan')

@section('content')
<style>
    .table-custom th { background-color: #e5e5e5; color: #555; font-weight: 600; border: none; padding: 15px; }
    .table-custom td { padding: 15px; vertical-align: middle; border-bottom: 1px solid #f0f0f0; }
    .table-custom tr:hover { background-color: #faf8f5; }
    .btn-action { font-size: 0.85rem; font-weight: 500; border-radius: 8px; padding: 6px 12px; }
    .form-control-custom { background-color: #e5e5e5; border: none; border-radius: 10px; padding: 12px 20px; }
    .form-control-custom:focus { background-color: white; box-shadow: 0 0 0 3px rgba(74, 21, 75, 0.1); border: 1px solid #4a154b; }
    
    /* Badge Colors (Pastel) */
    .badge-pending { background-color: #fff3cd; color: #856404; }
    .badge-completed { background-color: #d1e7dd; color: #0f5132; }
    .badge-cancelled { background-color: #f8d7da; color: #842029; }
</style>

<!-- Action Bar: Search & Sales History -->
<div class="row mb-4 align-items-center">
    <div class="col-md-6 mb-3 mb-md-0">
        <a href="{{ route('admin.orders.sales-history') }}" class="btn btn-light border fw-bold" style="border-radius: 10px;">
            <i class="bi bi-graph-up-arrow text-success me-2"></i> Lihat Riwayat Penjualan
        </a>
    </div>
    <div class="col-md-6">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="d-flex">
            <input type="text" name="search" class="form-control form-control-custom" placeholder="Cari no. pesanan atau nama pelanggan..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-light border ms-2 px-3"><i class="bi bi-search"></i></button>
        </form>
    </div>
</div>

<!-- Filter -->
<div class="card mb-4 border-0 shadow-sm rounded-4">
    <div class="card-body p-3">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-3">
                <select name="status" class="form-select form-control-custom">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="date" name="date_from" class="form-control form-control-custom" value="{{ request('date_from') }}" title="Dari Tanggal">
            </div>
            <div class="col-md-3">
                <input type="date" name="date_to" class="form-control form-control-custom" value="{{ request('date_to') }}" title="Sampai Tanggal">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100" style="background-color: #4a154b; border: none; border-radius: 10px;">Filter</button>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-light border w-100" style="border-radius: 10px;">Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- Table -->
<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-body p-0">
        @if($orders->count() > 0)
            <div class="table-responsive">
                <table class="table table-custom mb-0">
                    <thead>
                        <tr>
                            <th>No. Pesanan</th>
                            <th>Pelanggan</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td class="fw-bold" style="color: #d32f2f;">{{ $order->order_number }}</td>
                                <td>
                                    <span class="fw-bold text-dark">{{ $order->user->name }}</span><br>
                                    <small class="text-muted">{{ $order->user->email }}</small>
                                </td>
                                <td class="fw-bold">{{ $order->formatted_total }}</td>
                                <td>
                                    @if($order->status === 'pending')
                                        <span class="badge badge-pending rounded-pill px-3 py-2">Menunggu</span>
                                    @elseif($order->status === 'completed')
                                        <span class="badge badge-completed rounded-pill px-3 py-2">Selesai</span>
                                    @else
                                        <span class="badge badge-cancelled rounded-pill px-3 py-2">Dibatalkan</span>
                                    @endif
                                </td>
                                <td class="text-muted">{{ $order->created_at->format('d M Y, H:i') }}</td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-action btn-outline-primary">
                                            Detail
                                        </a>
                                        @if($order->status === 'cancelled')
                                            <!-- Tombol Hapus (Tidak langsung submit, tapi memicu modal) -->
                                            <button type="button" class="btn btn-action btn-outline-danger btn-delete" 
                                                    data-url="{{ route('admin.orders.destroy', $order) }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                            <!-- Form tersembunyi -->
                                            <form id="delete-form-{{ $order->id }}" action="{{ route('admin.orders.destroy', $order) }}" method="POST" class="d-none">
                                                @csrf 
                                                @method('DELETE')
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4">{{ $orders->links() }}</div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-inbox display-1 text-muted opacity-50"></i>
                <h5 class="mt-3 fw-bold">Tidak Ada Pesanan</h5>
                <p class="text-muted">Belum ada pesanan masuk atau tidak cocok dengan filter.</p>
            </div>
        @endif
    </div>
</div>

<!-- MODAL KONFIRMASI HAPUS (POP-UP CANTIK) -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
            <div class="modal-header bg-danger text-white border-0" style="border-radius: 15px 15px 0 0;">
                <h5 class="modal-title fw-bold"><i class="bi bi-exclamation-triangle me-2"></i> Konfirmasi Hapus</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <i class="bi bi-trash display-4 text-danger mb-3"></i>
                <h5 class="fw-bold text-dark">Apakah Anda yakin?</h5>
                <p class="text-muted mb-0">Data pesanan yang dibatalkan ini akan dihapus secara permanen dari sistem.</p>
            </div>
            <div class="modal-footer border-0 d-flex justify-content-center pb-4">
                <button type="button" class="btn btn-light border px-4 fw-bold" data-bs-dismiss="modal" style="border-radius: 8px;">Batal</button>
                <button type="button" class="btn btn-danger px-4 fw-bold" id="confirmDeleteBtn" style="border-radius: 8px;">Ya, Hapus</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let formToSubmit = null;

        // Tangkap semua tombol hapus
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function() {
                // Ambil ID dari URL (karena kita butuh tau form mana yang mau disubmit)
                // Trik: kita cari form tersembunyi berdasarkan URL action
                let actionUrl = this.getAttribute('data-url');
                
                // Cari form yang action-nya sama dengan URL tombol
                document.querySelectorAll('form').forEach(form => {
                    if(form.getAttribute('action') === actionUrl) {
                        formToSubmit = form;
                    }
                });

                // Tampilkan Modal
                var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
                deleteModal.show();
            });
        });

        // Saat tombol "Ya, Hapus" di dalam modal diklik
        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            if(formToSubmit) {
                formToSubmit.submit();
            }
        });
    });
</script>
@endpush