@extends('layouts.admin')

@section('title', 'Riwayat Penjualan')
@section('page-title', 'Riwayat Penjualan')

@section('content')
<style>
    .form-control-custom { background-color: #f8f9fa; border: 1px solid #ddd; border-radius: 10px; padding: 12px 20px; font-weight: 500; }
    .form-control-custom:focus { background-color: white; border-color: #4a154b; box-shadow: 0 0 0 3px rgba(74, 21, 75, 0.1); }
    .stat-card { background-color: #e5e5e5; border: none; border-radius: 20px; padding: 25px; height: 100%; transition: transform 0.3s; }
    .stat-card:hover { transform: translateY(-5px); }
    .stat-value { font-size: 2rem; font-weight: 700; color: #333; line-height: 1.2; margin-bottom: 5px; }
    .stat-title { font-size: 0.9rem; color: #555; margin-bottom: 10px; }
    
    .table-custom th { background-color: #f8f9fa; color: #555; font-weight: 600; border: none; padding: 15px; }
    .table-custom td { padding: 15px; vertical-align: middle; border-bottom: 1px solid #f0f0f0; }
    .table-custom tr:hover { background-color: #faf8f5; }
</style>

<!-- Filter Tanggal -->
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-4">
        <form action="{{ route('admin.orders.sales-history') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-bold text-muted small">Dari Tanggal</label>
                <input type="date" name="date_from" class="form-control form-control-custom" value="{{ $dateFrom }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold text-muted small">Sampai Tanggal</label>
                <input type="date" name="date_to" class="form-control form-control-custom" value="{{ $dateTo }}" required>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn w-100 py-2 text-white fw-bold" style="background-color: #4a154b; border-radius: 10px;">
                    <i class="bi bi-search me-1"></i> Tampilkan Data
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Kartu Statistik -->
<div class="row g-4 mb-4">
    <!-- Total Pendapatan -->
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-title"><i class="bi bi-wallet2 text-success me-2"></i> Total Pendapatan</div>
            <div class="stat-value text-success">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
            <div class="text-muted small">Periode: {{ date('d M Y', strtotime($dateFrom)) }} - {{ date('d M Y', strtotime($dateTo)) }}</div>
        </div>
    </div>
    <!-- Total Pesanan -->
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-title"><i class="bi bi-check-circle text-primary me-2"></i> Total Pesanan Selesai</div>
            <div class="stat-value">{{ $totalOrders }} <span class="fs-6 fw-normal text-muted">Pesanan</span></div>
            <div class="text-muted small">Pesanan yang berhasil diproses</div>
        </div>
    </div>
    <!-- Total Item -->
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-title"><i class="bi bi-box-seam text-info me-2"></i> Total Item Terjual</div>
            <div class="stat-value">{{ $totalItemsSold }} <span class="fs-6 fw-normal text-muted">Unit</span></div>
            <div class="text-muted small">Produk yang keluar dari toko</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Tabel Penjualan per Produk -->
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-trophy text-warning me-2"></i> Penjualan per Produk</h5>
            </div>
            <div class="card-body p-0">
                @if($productSales->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-custom mb-0">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th class="text-center">Terjual</th>
                                    <th class="text-end">Pendapatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($productSales as $product)
                                    <tr>
                                        <td class="fw-bold text-dark">{{ $product->name }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-light text-dark border px-2 py-1">{{ $product->total_quantity }} unit</span>
                                        </td>
                                        <td class="text-end fw-bold text-success">Rp {{ number_format($product->total_revenue, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-inbox display-4 text-muted opacity-50"></i>
                        <p class="text-muted mt-3">Tidak ada data penjualan untuk periode ini.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Grafik Penjualan Harian -->
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-graph-up-arrow text-primary me-2"></i> Grafik Penjualan Harian</h5>
            </div>
            <div class="card-body p-4" style="position: relative; height: 350px; width: 100%;">
                <canvas id="dailySalesChart"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('dailySalesChart').getContext('2d');
        const dailySales = @json($dailySales);
        
        const labels = dailySales.map(item => {
            const date = new Date(item.date);
            return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
        });
        
        const revenue = dailySales.map(item => item.revenue);
        const orderCount = dailySales.map(item => item.order_count);
        
        // Gradient untuk Bar Chart
        let gradientBar = ctx.createLinearGradient(0, 0, 0, 400);
        gradientBar.addColorStop(0, '#4a154b'); // Ungu
        gradientBar.addColorStop(1, '#d33a3a'); // Merah

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: revenue,
                    backgroundColor: gradientBar,
                    borderRadius: 6,
                    yAxisID: 'y'
                }, {
                    label: 'Jumlah Pesanan',
                    data: orderCount,
                    type: 'line',
                    borderColor: '#f39c12', // Garis oranye
                    backgroundColor: '#f39c12',
                    borderWidth: 3,
                    tension: 0.4,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#f39c12',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    yAxisID: 'y1'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                plugins: {
                    legend: { position: 'top', labels: { usePointStyle: true, boxWidth: 8 } }
                },
                scales: {
                    x: { grid: { display: false } },
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        grid: { borderDash: [5, 5] },
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        grid: { drawOnChartArea: false },
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });
    });
</script>
@endpush