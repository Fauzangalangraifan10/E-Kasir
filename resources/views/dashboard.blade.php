@extends('layouts.app')

@section('content')
<style>
    /* ======= WARNA TEMA ======= */
    .text-soft-green {
        color: #5DBB63 !important;
    }
    .border-soft-green {
        border-color: #5DBB63 !important;
    }
    .bg-soft-green {
        background-color: #5DBB63 !important;
        color: white !important;
    }
    .badge-soft-green {
        background-color: #A8E6A1 !important;
        color: #155724 !important;
    }

    /* ======= CARD STYLING ======= */
    .card {
        border-radius: 12px;
        transition: all 0.3s ease-in-out;
    }
    .card:hover {
        transform: translateY(-4px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
    }

    /* ======= HEADER STYLE ======= */
    .card-header {
        border-bottom: 1px solid #e9ecef;
        background-color: #f9fafc;
        border-radius: 12px 12px 0 0;
    }

    /* ======= BUTTON STYLE ======= */
    .btn-soft-green {
        background-color: #5DBB63;
        color: white;
        border: none;
    }
    .btn-soft-green:hover {
        background-color: #4ca957;
    }
</style>

<div class="row mb-4">
    <div class="col-12 d-flex align-items-center justify-content-between">
        <h1 class="h3 mb-0">
            <i class="fas fa-tachometer-alt text-soft-green"></i> Dashboard
        </h1>
        
    </div>
</div>

<!-- Statistik Ringkas -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-start border-primary border-4 shadow h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-xs fw-bold text-primary text-uppercase mb-1">Revenue Hari Ini</div>
                    <div class="h5 mb-0 fw-bold text-gray-800">
                        Rp {{ number_format($stats['total_revenue_today'], 0, ',', '.') }}
                    </div>
                </div>
                <i class="fas fa-calendar fa-2x text-secondary"></i>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-start border-soft-green border-4 shadow h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-xs fw-bold text-soft-green text-uppercase mb-1">Revenue Bulan Ini</div>
                    <div class="h5 mb-0 fw-bold text-gray-800">
                        Rp {{ number_format($stats['total_revenue_month'], 0, ',', '.') }}
                    </div>
                </div>
                <i class="fas fa-dollar-sign fa-2x text-soft-green"></i>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-start border-info border-4 shadow h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-xs fw-bold text-info text-uppercase mb-1">Transaksi Hari Ini</div>
                    <div class="h5 mb-0 fw-bold text-gray-800">{{ $stats['total_transactions_today'] }}</div>
                </div>
                <i class="fas fa-clipboard-list fa-2x text-info"></i>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-start border-warning border-4 shadow h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-xs fw-bold text-warning text-uppercase mb-1">Stok Menipis</div>
                    <div class="h5 mb-0 fw-bold text-gray-800">{{ $stats['low_stock_products_count'] }}</div>
                    @if($stats['low_stock_products_count'] > 0)
                        <small>
                            <a href="{{ route('products.low-stock') }}" class="text-danger text-decoration-none">
                                Lihat Detail
                            </a>
                        </small>
                    @endif
                </div>
                <i class="fas fa-exclamation-triangle fa-2x text-warning"></i>
            </div>
        </div>
    </div>
</div>

<!-- Grafik -->
<div class="row mb-4">
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">
            <div class="card-header">
                <h6 class="m-0 fw-bold text-primary">
                    <i class="fas fa-chart-line"></i> Penjualan 7 Hari Terakhir
                </h6>
            </div>
            <div class="card-body">
                <canvas id="salesChart" style="height: 300px;"></canvas>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4">
            <div class="card-header">
                <h6 class="m-0 fw-bold text-primary">
                    <i class="fas fa-chart-pie"></i> Penjualan per Kategori
                </h6>
            </div>
            <div class="card-body">
                <canvas id="categoryChart" style="height: 300px;"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Produk Terlaris & Stok Menipis -->
<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header">
                <h6 class="m-0 fw-bold text-primary">
                    <i class="fas fa-star"></i> Produk Terlaris
                </h6>
            </div>
            <div class="card-body">
                @if($topProducts->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Kategori</th>
                                    <th>Terjual</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topProducts as $product)
                                    <tr>
                                        <td>
                                            <strong>{{ $product->name }}</strong>
                                            <br><small class="text-muted">Rp {{ number_format($product->price, 0, ',', '.') }}</small>
                                        </td>
                                        <td>
                                            @if($product->category)
                                                <span class="badge bg-secondary">{{ $product->category->name }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td><span class="badge badge-soft-green">{{ $product->total_sold }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted">
                        <i class="fas fa-box-open fa-2x mb-2"></i>
                        <p>Belum ada data penjualan</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="m-0 fw-bold text-warning">
                    <i class="fas fa-exclamation-triangle"></i> Stok Menipis
                </h6>
                @if($lowStockProducts->count() > 5)
                    <a href="{{ route('products.low-stock') }}" class="btn btn-warning btn-sm">Lihat Semua</a>
                @endif
            </div>
            <div class="card-body">
                @if($lowStockProducts->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Kategori</th>
                                    <th>Stok</th>
                                    <th>Min. Stok</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lowStockProducts as $product)
                                    <tr>
                                        <td>
                                            <strong>{{ $product->name }}</strong>
                                            <br><small class="text-muted">Rp {{ number_format($product->price, 0, ',', '.') }}</small>
                                        </td>
                                        <td>
                                            @if($product->category)
                                                <span class="badge bg-secondary">{{ $product->category->name }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge {{ $product->stock == 0 ? 'bg-danger' : 'bg-warning' }}">
                                                {{ $product->stock }}
                                            </span>
                                        </td>
                                        <td>{{ $product->min_stock }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-success">
                        <i class="fas fa-check-circle fa-2x mb-2"></i>
                        <p>Semua stok aman!</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Transaksi Terbaru -->
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="m-0 fw-bold text-primary">
                    <i class="fas fa-history"></i> Transaksi Terbaru
                </h6>
                <a href="{{ route('transactions.index') }}" class="btn btn-soft-green btn-sm">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body">
                @if($recentTransactions->count() > 0)
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Kode Transaksi</th>
                                    <th>Kasir</th>
                                    <th>Total</th>
                                    <th>Metode Bayar</th>
                                    <th>Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentTransactions as $transaction)
                                    <tr>
                                        <td><code>{{ $transaction->transaction_code }}</code></td>
                                        <td>{{ $transaction->user->name ?? '-' }}</td>
                                        <td><strong>Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</strong></td>
                                        <td>
                                            @if($transaction->payment_method)
                                                <span class="badge bg-info">{{ ucfirst($transaction->payment_method) }}</span>
                                            @else
                                                <span class="badge bg-secondary">Cash</span>
                                            @endif
                                        </td>
                                        <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted">
                        <i class="fas fa-shopping-cart fa-2x mb-2"></i>
                        <p>Belum ada transaksi</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const salesCtx = document.getElementById('salesChart').getContext('2d');
new Chart(salesCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode(array_column($salesChart, 'date')) !!},
        datasets: [{
            label: 'Revenue (Rp)',
            data: {!! json_encode(array_column($salesChart, 'revenue')) !!},
            borderColor: '#5DBB63',
            backgroundColor: 'rgba(93, 187, 99, 0.2)',
            tension: 0.3,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: value => 'Rp ' + value.toLocaleString('id-ID')
                }
            }
        },
        plugins: {
            tooltip: {
                callbacks: {
                    label: ctx => 'Revenue: Rp ' + ctx.parsed.y.toLocaleString('id-ID')
                }
            }
        }
    }
});

const categoryCtx = document.getElementById('categoryChart').getContext('2d');
new Chart(categoryCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($categorySales->pluck('name')) !!},
        datasets: [{
            data: {!! json_encode($categorySales->pluck('total_sales')) !!},
            backgroundColor: [
                '#5DBB63',
                '#36A2EB',
                '#FFCE56',
                '#4BC0C0',
                '#9966FF',
                '#FF9F40'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { position: 'bottom' },
            tooltip: {
                callbacks: {
                    label: ctx => ctx.label + ': Rp ' + ctx.parsed.toLocaleString('id-ID')
                }
            }
        }
    }
});
</script>
@endpush
@endsection
