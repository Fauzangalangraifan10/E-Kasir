@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold text-green-700 mb-4">Bulk Import Produk</h1>

    @if (session('warning'))
        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4">
            {{ session('warning') }}
        </div>
    @endif

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if (session('import_errors'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <strong>Beberapa produk gagal diimport:</strong>
            <ul class="list-disc list-inside">
                @foreach (session('import_errors') as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white p-6 rounded shadow-md">
        <form action="{{ route('products.process-bulk-import') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label for="file" class="block text-sm font-medium text-gray-700">Pilih File CSV</label>
                <input type="file" name="file" id="file" accept=".csv" class="mt-2 block w-full border rounded p-2">
                @error('file')
                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="flex items-center gap-4">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                    Import
                </button>
                <a href="{{ route('products.download-template') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                    Download Template CSV
                </a>
                <a href="{{ route('products.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">
                    Kembali
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
