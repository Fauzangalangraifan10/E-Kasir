@extends('layouts.app')

@section('content')
<div class="p-4 md:p-6 bg-light min-vh-100">
    <div class="container-fluid">
        <!-- Header Halaman -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
            <h1 class="h3 fw-bold text-dark mb-2">Laporan Penjualan</h1>
            <p class="text-muted mb-0">Ringkasan transaksi dan pendapatan.</p>
        </div>

        <!-- Panel Filter -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form action="{{ route('reports.sales') }}" method="GET" class="row g-3 align-items-end">
                    <!-- Filter Tanggal Mulai -->
                    <div class="col-md-3">
                        <label for="start_date" class="form-label fw-semibold">Dari Tanggal:</label>
                        <input type="date" id="start_date" name="start_date" value="{{ $startDate }}" 
                               class="form-control">
                    </div>

                    <!-- Filter Tanggal Akhir -->
                    <div class="col-md-3">
                        <label for="end_date" class="form-label fw-semibold">Sampai Tanggal:</label>
                        <input type="date" id="end_date" name="end_date" value="{{ $endDate }}" 
                               class="form-control">
                    </div>

                    <!-- Tombol Filter -->
                    <div class="col-md-2">
                        <button type="submit" 
                                class="btn btn-success w-100">
                            <i class="fas fa-filter me-1"></i> Filter
                        </button>
                    </div>

                    <!-- Tombol PDF -->
                    <div class="col-md-2">
                        <button type="submit" name="export_pdf" value="1"
                                class="btn btn-danger w-100">
                            <i class="fas fa-file-pdf me-1"></i> PDF
                        </button>
                    </div>

                    <!-- Tombol Excel -->
                    <div class="col-md-2">
                        <button type="submit" name="export_excel" value="1"
                                class="btn btn-success w-100">
                            <i class="fas fa-file-excel me-1"></i> Excel
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Ringkasan Laporan -->
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card shadow-sm border-0 text-white bg-primary">
                    <div class="card-body">
                        <h6 class="text-white-50 mb-1">Total Pendapatan</h6>
                        <h3 class="fw-bold mb-0">Rp{{ number_format($sales->sum('total_price'), 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-muted mb-1">Jumlah Transaksi</h6>
                        <h3 class="fw-bold mb-0">{{ $sales->count() }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-muted mb-1">Produk Terlaris</h6>
                        <h5 class="fw-bold mb-0">{{ $bestSellingProduct->name ?? 'N/A' }}</h5>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Laporan Penjualan -->
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>ID Transaksi</th>
                                <th>Tanggal</th>
                                <th>Total Amount</th>
                                <th>Metode Pembayaran</th>
                                <th>Detail Produk</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($sales as $transaction)
                            <tr>
                                <td>{{ $transaction->id }}</td>
                                <td>{{ $transaction->created_at->format('Y-m-d H:i') }}</td>
                                <td class="fw-semibold text-success">Rp{{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                                <td>{{ $transaction->payment_method ?? 'Cash' }}</td>
                                <td>
                                    <ul class="mb-0">
                                        @foreach ($transaction->details as $item)
                                            <li>{{ $item->product->name }} ({{ $item->quantity }}x @ Rp{{ number_format($item->price, 0, ',', '.') }})</li>
                                        @endforeach
                                    </ul>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    Tidak ada data penjualan pada periode ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
