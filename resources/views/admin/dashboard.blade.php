@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard Admin')

@section('content')
<style>
    /* Styling Kartu Statistik */
    .stat-card {
        background-color: #e5e5e5; /* Warna abu-abu terang sesuai Figma */
        border: none; border-radius: 20px; padding: 20px;
        height: 100%; transition: transform 0.3s;
    }
    .stat-card:hover { transform: translateY(-5px); }
    .stat-icon { font-size: 1.5rem; margin-bottom: 15px; display: inline-block; }
    .stat-value { font-size: 1.8rem; font-weight: 700; color: #333; line-height: 1.2; }
    .stat-title { font-size: 0.9rem; color: #555; margin-bottom: 5px; }
    .stat-desc { font-size: 0.8rem; color: #777; }
    
    .chart-container { background: white; border-radius: 20px; padding: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); }
</style>

<!-- Welcome Message -->
<div class="mb-4">
    <h3 class="fw-bold mb-1" style="color: #333;">Selamat datang kembali, {{ Auth::user()->name }}!</h3>
    <p class="text-muted">Berikut ringkasan toko Deyalina hari ini.</p>
</div>

<!-- Statistics Cards -->
<div class="row g-3 mb-4">
    <!-- Total Revenue -->
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon text-success"><i class="bi bi-wallet2"></i></div>
            <div class="stat-title">Total Pendapatan</div>
            <div class="stat-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
            <div class="stat-desc text-success mt-2"><i class="bi bi-check-circle"></i> Dari {{ $completedOrders }} pesanan</div>
        </div>
    </div>

    <!-- Total Orders -->
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon text-primary"><i class="bi bi-box-seam"></i></div>
            <div class="stat-title">Total Pesanan</div>
            <div class="stat-value">{{ $totalOrders }}</div>
            <div class="stat-desc text-warning mt-2"><i class="bi bi-clock"></i> {{ $pendingOrders }} menunggu</div>
        </div>
    </div>

    <!-- Total Products -->
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon text-info"><i class="bi bi-tags"></i></div>
            <div class="stat-title">Total Produk</div>
            <div class="stat-value">{{ $totalProducts }}</div>
            <div class="stat-desc text-success mt-2"><i class="bi bi-check2-all"></i> {{ $activeProducts }} aktif</div>
        </div>
    </div>

    <!-- Stock Alerts -->
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon text-danger"><i class="bi bi-exclamation-triangle"></i></div>
            <div class="stat-title">Peringatan Stok</div>
            <div class="stat-value">{{ $lowStockProducts + $outOfStockProducts }}</div>
            <div class="stat-desc text-danger mt-2"><i class="bi bi-x-circle"></i> {{ $outOfStockProducts }} habis</div>
        </div>
    </div>
</div>

<!-- Chart Area -->
<div class="row mb-4">
    <div class="col-12">
        <div class="chart-container">
            <h5 class="fw-bold mb-3" style="color: #333;">Chart Pendapatan (7 Hari Terakhir)</h5>
            <canvas id="revenueChart" height="80"></canvas>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Recent Orders (Kiri) -->
    <div class="col-lg-7">
        <div class="card h-100" style="border-radius: 20px; overflow: hidden;">
            <div class="card-header d-flex justify-content-between align-items-center bg-white border-bottom py-3">
                <h5 class="mb-0 fw-bold" style="color: #333;">List pesanan diproses</h5>
                <a href="{{ route('admin.orders.index') }}" class="text-decoration-none text-muted small">Lihat Semua <i class="bi bi-arrow-right"></i></a>
            </div>
            <div class="card-body p-0">
                @if($recentOrders->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light text-muted small">
                                <tr>
                                    <th class="border-0 ps-4">Nomor Pesanan</th>
                                    <th class="border-0">Pelanggan</th>
                                    <th class="border-0">Total</th>
                                    <th class="border-0">Status</th>
                                    <th class="border-0 pe-4 text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentOrders as $order)
                                    <tr>
                                        <td class="ps-4 fw-bold" style="color: #d33a3a;">{{ $order->order_number }}</td>
                                        <td>{{ $order->user->name }}</td>
                                        <td class="fw-bold">{{ $order->formatted_total }}</td>
                                        <td>
                                            <span class="badge rounded-pill bg-{{ $order->status_badge }} bg-opacity-25 text-{{ $order->status_badge }} px-3">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td class="pe-4 text-end">
                                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-light border rounded-pill px-3">Detail</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-inbox display-4 text-muted opacity-50"></i>
                        <p class="text-muted mt-3">Belum ada pesanan</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Right Side: Messages & Top Products (Kanan) -->
    <div class="col-lg-5">
        <!-- Messages Alert -->
        <div class="card mb-4" style="border-radius: 20px;">
            <div class="card-body p-4 d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-muted mb-1">Pesan Belum Dibaca</h6>
                    <h3 class="fw-bold mb-0" style="color: #333;">{{ $unreadMessages }}</h3>
                </div>
                <div>
                    @if($unreadMessages > 0)
                        <a href="{{ route('admin.messages.index') }}" class="btn rounded-pill px-4 text-white" style="background-color: #4a154b;">Lihat Pesan</a>
                    @else
                        <span class="badge bg-light text-muted border px-3 py-2 rounded-pill">Aman</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Top Selling Products -->
        <div class="card" style="border-radius: 20px;">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 fw-bold" style="color: #333;">Stok Hampir Habis / Terlaris</h5>
            </div>
            <div class="card-body p-0">
                @if($topSellingProducts->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($topSellingProducts as $product)
                            <div class="list-group-item px-4 py-3 border-0 border-bottom">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1 fw-bold text-truncate" style="max-width: 200px;">{{ $product->name }}</h6>
                                        <small class="text-muted">Terjual: {{ $product->total_sold }} unit</small>
                                    </div>
                                    <span class="badge rounded-pill bg-light text-dark border">#{{ $loop->iteration }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <small class="text-muted">Belum ada data penjualan</small>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Revenue Chart
    const ctx = document.getElementById('revenueChart').getContext('2d');
    const dailyRevenue = @json($dailyRevenue);
    
    const labels = dailyRevenue.map(item => {
        const date = new Date(item.date);
        return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
    });
    
    const data = dailyRevenue.map(item => item.revenue);
    
    // Gradient untuk Chart
    let gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(74, 21, 75, 0.2)'); // Ungu transparan
    gradient.addColorStop(1, 'rgba(211, 58, 58, 0.0)'); // Merah transparan
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: data,
                borderColor: '#4a154b', // Warna ungu
                backgroundColor: gradient,
                borderWidth: 3,
                pointBackgroundColor: '#d33a3a', // Merah
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4,
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                x: { grid: { display: false } },
                y: {
                    beginAtZero: true,
                    grid: { borderDash: [5, 5] },
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
</script>
@endpush