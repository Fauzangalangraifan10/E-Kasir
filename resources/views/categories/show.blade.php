@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow border-0">
        <div class="card-header bg-success bg-opacity-75 text-white d-flex justify-content-between align-items-center rounded-top">
            <h4 class="mb-0"><i class="fas fa-info-circle me-2"></i> Detail Kategori</h4>
            <a href="{{ route('categories.index') }}" class="btn btn-light btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <p class="mb-1 fw-bold">Nama Kategori:</p>
                    <p class="text-muted">{{ $category->name }}</p>
                </div>
                <div class="col-md-6">
                    <p class="mb-1 fw-bold">Dibuat pada:</p>
                    <p class="text-muted">{{ $category->created_at->format('d F Y, H:i') }}</p>
                </div>
            </div>

            <hr class="my-4">

            <h5><i class="fas fa-boxes me-2"></i> Produk dalam kategori ini:</h5>
            @if($category->products->count() > 0)
                <div class="table-responsive mt-3">
                    <table class="table table-hover table-striped table-bordered align-middle">
                        <thead class="table-success">
                            <tr>
                                <th scope="col" class="text-center">#</th>
                                <th scope="col">Nama Produk</th>
                                <th scope="col">Harga</th>
                                <th scope="col">Stok</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($category->products as $product)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $product->name }}</td>
                                    <td>Rp. {{ number_format($product->price, 0, ',', '.') }}</td>
                                    <td>{{ $product->stock }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info mt-3" role="alert">
                    <i class="fas fa-info-circle me-2"></i> Tidak ada produk dalam kategori ini.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection