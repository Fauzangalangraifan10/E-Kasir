@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Produk</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
            </ul>
        </div>
    @endif

    {{-- Penting: tambahkan enctype="multipart/form-data" jika ada input file --}}
    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Nama Produk</label>
            <input type="text" name="name" id="name" class="form-control" required value="{{ old('name', $product->name) }}">
        </div>

        <div class="mb-3">
            <label for="category_id" class="form-label">Kategori</label>
            <select name="category_id" id="category_id" class="form-select" required>
                <option value="">Pilih Kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="barcode" class="form-label">Barcode</label>
            <input type="text" name="barcode" id="barcode" class="form-control" value="{{ old('barcode', $product->barcode) }}">
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Deskripsi</label>
            <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $product->description) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Gambar Produk</label>
            @if($product->image)
                <div class="mb-2">
                    <img src="{{ Storage::url($product->image) }}" alt="Gambar Produk" style="max-width: 150px; height: auto;">
                    <div class="form-check mt-1">
                        <input class="form-check-input" type="checkbox" name="remove_image" id="remove_image" value="1">
                        <label class="form-check-label" for="remove_image">
                            Hapus gambar yang sudah ada
                        </label>
                    </div>
                </div>
            @endif
            <input type="file" name="image" id="image" class="form-control">
            <small class="text-muted">Biarkan kosong jika tidak ingin mengubah gambar.</small>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Harga</label>
            <input type="number" name="price" id="price" class="form-control" required min="0" step="0.01" value="{{ old('price', $product->price) }}">
        </div>

        <div class="mb-3">
            <label for="stock" class="form-label">Stok</label>
            <input type="number" name="stock" id="stock" class="form-control" required min="0" value="{{ old('stock', $product->stock) }}">
        </div>

        <div class="mb-3">
            <label for="min_stock" class="form-label">Stok Minimal</label>
            <input type="number" name="min_stock" id="min_stock" class="form-control" required min="0" value="{{ old('min_stock', $product->min_stock) }}">
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">Aktif</label>
        </div>

        <button type="submit" class="btn btn-primary">Perbarui</button>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection