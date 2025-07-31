@extends('layouts.app')

@section('content')
<style>
    .pagination svg {
        width: 1em !important;
        height: 1em !important;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Daftar Produk</h3>
                    <div>
                        @if(!auth()->user()->role || auth()->user()->role !== 'kasir')
                            <a href="{{ route('products.low-stock') }}" class="btn btn-warning btn-sm me-2">
                                <i class="fas fa-exclamation-triangle"></i> Stok Menipis
                            </a>
                            <a href="{{ route('products.bulk-import') }}" class="btn btn-info btn-sm me-2">
                                <i class="fas fa-upload"></i> Import CSV
                            </a>
                            <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Tambah Produk
                            </a>
                        @endif
                    </div>
                </div>

                <div class="card-body">
                    <form method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <input type="text"
                                       name="search"
                                       class="form-control"
                                       placeholder="Cari nama atau barcode..."
                                       value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <select name="category_id" class="form-select">
                                    <option value="">Semua Kategori</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="stock_filter" class="form-select">
                                    <option value="">Semua Stok</option>
                                    <option value="low" {{ request('stock_filter') == 'low' ? 'selected' : '' }}>Stok Menipis</option>
                                    <option value="out" {{ request('stock_filter') == 'out' ? 'selected' : '' }}>Stok Habis</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-secondary w-100">
                                    <i class="fas fa-search"></i> Filter
                                </button>
                            </div>
                        </div>
                    </form>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('import_errors'))
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong>Peringatan!</strong> Beberapa produk gagal diimport:
                            <ul>
                                @foreach(session('import_errors') as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Gambar</th>
                                    <th>Nama Produk</th>
                                    <th>Kategori</th>
                                    <th>Barcode</th>
                                    <th>Harga</th>
                                    <th>Stok</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                    <tr>
                                        <td>
                                            @if($product->image && file_exists(public_path('storage/products/' . $product->image)))
                                                <img src="{{ asset('storage/products/' . $product->image) }}"
                                                     alt="{{ $product->name }}"
                                                     class="img-thumbnail"
                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <div class="bg-light d-flex align-items-center justify-content-center"
                                                     style="width: 50px; height: 50px;">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $product->name }}</strong>
                                            @if($product->description)
                                                <br><small class="text-muted">{{ Str::limit($product->description, 50) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($product->category)
                                                <span class="badge bg-secondary">{{ $product->category->name }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td><code>{{ $product->barcode ?: '-' }}</code></td>
                                        <td>{{ $product->formatted_price }}</td>
                                        <td>
                                            <span class="badge {{ $product->stock == 0 ? 'bg-dark' : ($product->is_low_stock ? 'bg-danger' : 'bg-success') }}">
                                                {{ $product->stock }}
                                            </span>
                                            @if($product->is_low_stock)
                                                <i class="fas fa-exclamation-triangle text-warning ms-1"
                                                   title="Stok menipis (minimal: {{ $product->min_stock }})"></i>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge {{ $product->is_active ? 'bg-success' : 'bg-danger' }}">
                                                {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                                @if(!auth()->user()->role || auth()->user()->role !== 'kasir')
                                                    <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-warning">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline"
                                                          onsubmit="return confirm('Yakin ingin menghapus produk ini? Tindakan ini tidak dapat dibatalkan.')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Belum ada produk</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $products->withQueryString()->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
