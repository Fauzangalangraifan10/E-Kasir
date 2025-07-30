@extends('layouts.app') {{-- Asumsi ada layout dasar --}}

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-3xl font-bold mb-6">Dashboard Kasir</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-lg font-semibold text-gray-700">Penjualan Hari Ini</h2>
            <p class="text-3xl font-bold text-indigo-600">Rp{{ number_format($todaySales, 0, ',', '.') }}</p>
            <p class="text-sm text-gray-500">{{ $todayTransactionsCount }} Transaksi</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-lg font-semibold text-gray-700">Revenue Bulan Ini</h2>
            <p class="text-3xl font-bold text-green-600">Rp{{ number_format($monthlyRevenue, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-lg font-semibold text-gray-700">Produk Terlaris (Bulan Ini)</h2>
            <ul class="list-disc list-inside text-gray-800">
                @forelse ($topSellingProducts as $product)
                    <li>{{ $product->name }} ({{ $product->total_quantity_sold }} unit)</li>
                @empty
                    <li>Belum ada data produk terlaris.</li>
                @endforelse
            </ul>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-lg font-semibold text-gray-700">Stok Menipis</h2>
            <ul class="list-disc list-inside text-red-600">
                @forelse ($lowStockProducts as $product)
                    <li>{{ $product->name }} (Sisa: {{ $product->stock }} unit)</li>
                @empty
                    <li class="text-gray-800">Tidak ada produk dengan stok menipis.</li>
                @endforelse
            </ul>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md mb-8">
        <h2 class="text-lg font-semibold text-gray-700 mb-4">Grafik Penjualan Mingguan</h2>
        <canvas id="salesChart" width="400" height="150"></canvas>
    </div>
</div>

@push('scripts')
{{-- Pastikan Chart.js sudah di-load di layout Anda --}}
{{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('salesChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($salesChartData['labels']),
                datasets: [{
                    label: 'Penjualan Mingguan (Rp)',
                    data: @json($salesChartData['data']),
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1,
                    fill: false
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection