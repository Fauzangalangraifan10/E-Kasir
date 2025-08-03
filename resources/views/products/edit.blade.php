@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow border-0">
        <div class="card-header bg-success bg-opacity-75 text-white d-flex justify-content-between align-items-center rounded-top">
            <h4 class="mb-0"><i class="fas fa-edit me-2"></i> Edit Produk</h4>
            <a href="{{ route('products.index') }}" class="btn btn-light btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <div class="col-md-6">
                        <label for="name" class="form-label fw-semibold">Nama Produk</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $product->name) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="category_id" class="form-label fw-semibold">Kategori</label>
                        <select name="category_id" id="category_id" class="form-select" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="barcode" class="form-label fw-semibold">Barcode</label>
                        <input type="text" name="barcode" id="barcode" class="form-control" value="{{ old('barcode', $product->barcode) }}">
                    </div>

                    <div class="col-md-6">
                        <label for="price" class="form-label fw-semibold">Harga</label>
                        <input type="number" name="price" id="price" class="form-control" value="{{ old('price', $product->price) }}" required min="0" step="0.01">
                    </div>

                    <div class="col-md-6">
                        <label for="stock" class="form-label fw-semibold">Stok</label>
                        <input type="number" name="stock" id="stock" class="form-control" value="{{ old('stock', $product->stock) }}" required min="0">
                    </div>

                    <div class="col-md-6">
                        <label for="min_stock" class="form-label fw-semibold">Stok Minimal</label>
                        <input type="number" name="min_stock" id="min_stock" class="form-control" value="{{ old('min_stock', $product->min_stock) }}" required min="0">
                    </div>

                    <div class="col-12">
                        <label for="description" class="form-label fw-semibold">Deskripsi</label>
                        <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $product->description) }}</textarea>
                    </div>

                    <div class="col-md-6">
                        <label for="image" class="form-label fw-semibold">Gambar Produk</label>
                        @if($product->image && file_exists(public_path('storage/products/' . $product->image)))
                            <div class="mb-2">
                                <img src="{{ asset('storage/products/' . $product->image) }}" alt="{{ $product->name }}" class="img-thumbnail shadow-sm" style="max-width: 150px;">
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" name="remove_image" id="remove_image" value="1">
                                    <label class="form-check-label" for="remove_image">Hapus gambar lama</label>
                                </div>
                            </div>
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center mb-2" style="width: 150px; height: 150px;">
                                <i class="fas fa-image text-muted"></i>
                            </div>
                        @endif
                        <input type="file" name="image" id="image" class="form-control">
                        <small class="text-muted">Biarkan kosong jika tidak ingin mengganti gambar.</small>
                    </div>

                    <div class="col-md-6 d-flex align-items-end">
                        <div class="form-check mt-3">
                            <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="is_active">Aktif</label>
                        </div>
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-end gap-2">
                    <a href="{{ route('products.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-1"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-1"></i> Perbarui
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
