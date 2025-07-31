@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold text-green-700 mb-4">Produk Stok Rendah</h1>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if ($products->count() > 0)
        <div class="overflow-x-auto bg-white rounded shadow">
            <table class="min-w-full table-auto border-collapse">
                <thead>
                    <tr class="bg-green-200 text-left">
                        <th class="p-3 border">#</th>
                        <th class="p-3 border">Nama Produk</th>
                        <th class="p-3 border">Kategori</th>
                        <th class="p-3 border">Harga</th>
                        <th class="p-3 border">Stok</th>
                        <th class="p-3 border">Min Stok</th>
                        <th class="p-3 border">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $index => $product)
                        <tr class="hover:bg-green-50">
                            <td class="p-3 border">{{ $loop->iteration }}</td>
                            <td class="p-3 border">{{ $product->name }}</td>
                            <td class="p-3 border">{{ $product->category ? $product->category->name : '-' }}</td>
                            <td class="p-3 border">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                            <td class="p-3 border">{{ $product->stock }}</td>
                            <td class="p-3 border">{{ $product->min_stock }}</td>
                            <td class="p-3 border">
                                @if ($product->stock <= $product->min_stock)
                                    <span class="text-red-600 font-semibold">Stok Rendah</span>
                                @else
                                    <span class="text-green-600 font-semibold">Normal</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $products->links() }}
        </div>
    @else
        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
            Tidak ada produk dengan stok rendah.
        </div>
    @endif
</div>
@endsection
