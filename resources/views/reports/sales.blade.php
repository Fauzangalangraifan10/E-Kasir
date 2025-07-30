@extends('layouts.app') {{-- Asumsi ada layout dasar --}}

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-3xl font-bold mb-6">Laporan Penjualan</h1>

    <div class="bg-white p-6 rounded-lg shadow-md mb-6">
        <form action="{{ route('reports.sales') }}" method="GET" class="flex flex-wrap items-end gap-4">
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700">Dari Tanggal:</label>
                <input type="date" id="start_date" name="start_date" value="{{ $startDate }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>
            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700">Sampai Tanggal:</label>
                <input type="date" id="end_date" name="end_date" value="{{ $endDate }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">Filter</button>
            <button type="submit" name="export_pdf" value="1" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50">Export PDF</button>
            <button type="submit" name="export_excel" value="1" class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50">Export Excel</button>
        </form>
    </div>

    <div class="overflow-x-auto bg-white rounded-lg shadow-md">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Transaksi</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Amount</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Metode Pembayaran</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Detail Produk</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                {{-- Ubah $sales menjadi $transaction, dan saleItems menjadi details --}}
                @forelse ($sales as $transaction)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->id }}</td>
                    {{-- Ubah transaction_date menjadi created_at --}}
                    <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->created_at->format('Y-m-d H:i') }}</td>
                    {{-- Ubah total_amount menjadi total_price --}}
                    <td class="px-6 py-4 whitespace-nowrap">Rp{{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->payment_method ?? 'Cash' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        <ul>
                            {{-- Ubah saleItems menjadi details dan price_per_item menjadi price --}}
                            @foreach ($transaction->details as $item)
                                <li>{{ $item->product->name }} ({{ $item->quantity }}x @ Rp{{ number_format($item->price, 0, ',', '.') }})</li>
                            @endforeach
                        </ul>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">Tidak ada data penjualan pada periode ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection