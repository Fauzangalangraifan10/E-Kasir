@extends('layouts.app') {{-- Memanggil master layout 'layouts/app.blade.php' --}}

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Detail Produk: {{ $product->name }}</h3>
                    <div>
                        {{-- Tombol Edit Produk --}}
                        <a href="{{ route('products.edit', $product) }}" class="btn btn-warning btn-sm me-2">
                            <i class="fas fa-edit"></i> Edit Produk
                        </a>
                        {{-- Tombol Kembali ke Daftar Produk --}}
                        <a href="{{ route('products.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            {{-- Menampilkan gambar produk jika ada --}}
                            @if($product->image)
                                <img src="{{ Storage::url('products/' . $product->image) }}"
                                     alt="{{ $product->name }}"
                                     class="img-fluid rounded"
                                     style="max-width: 200px; height: auto;">
                            @else
                                {{-- Placeholder jika tidak ada gambar --}}
                                <div class="bg-light d-flex align-items-center justify-content-center rounded"
                                     style="width: 200px; height: 200px; margin: 0 auto;">
                                    <i class="fas fa-image fa-5x text-muted"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <dl class="row">
                                <dt class="col-sm-4">Nama Produk:</dt>
                                <dd class="col-sm-8">{{ $product->name }}</dd>

                                <dt class="col-sm-4">Kategori:</dt>
                                <dd class="col-sm-8">
                                    @if($product->category)
                                        <span class="badge bg-primary">{{ $product->category->name }}</span>
                                    @else
                                        <span class="text-muted">- Tidak Ada Kategori -</span>
                                    @endif
                                </dd>

                                <dt class="col-sm-4">Barcode:</dt>
                                <dd class="col-sm-8"><code>{{ $product->barcode ?: '-' }}</code></dd>

                                <dt class="col-sm-4">Harga:</dt>
                                {{-- Menggunakan accessor formatted_price (jika ada) atau format manual --}}
                                <dd class="col-sm-8">{{ $product->formatted_price ?? 'Rp ' . number_format($product->price, 0, ',', '.') }}</dd>

                                <dt class="col-sm-4">Stok:</dt>
                                <dd class="col-sm-8">
                                    {{-- Menampilkan badge stok berdasarkan kondisi --}}
                                    <span class="badge {{ $product->is_low_stock ? 'bg-danger' : ($product->stock == 0 ? 'bg-dark' : 'bg-success') }}">
                                        {{ $product->stock }}
                                    </span>
                                    @if($product->is_low_stock)
                                        <i class="fas fa-exclamation-triangle text-warning ms-1"
                                           title="Stok menipis (minimal: {{ $product->min_stock }})"></i>
                                    @endif
                                </dd>

                                <dt class="col-sm-4">Min Stok:</dt>
                                <dd class="col-sm-8">{{ $product->min_stock }}</dd>

                                <dt class="col-sm-4">Status:</dt>
                                <dd class="col-sm-8">
                                    <span class="badge {{ $product->is_active ? 'bg-success' : 'bg-danger' }}">
                                        {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </dd>

                                <dt class="col-sm-4">Deskripsi:</dt>
                                <dd class="col-sm-8">{{ $product->description ?: '-' }}</dd>

                                <dt class="col-sm-4">Ditambahkan Pada:</dt>
                                <dd class="col-sm-8">{{ $product->created_at->format('d M Y H:i') }}</dd>

                                <dt class="col-sm-4">Diperbarui Pada:</dt>
                                <dd class="col-sm-8">{{ $product->updated_at->format('d M Y H:i') }}</dd>
                            </dl>
                        </div>
                    </div>

                    {{-- Bagian Histori Transaksi Produk Ini --}}
                    {{-- Ini akan ditampilkan jika ada relasi transactionDetails dan transaction di model Product --}}
                    @if($product->transactionDetails->isNotEmpty())
                        <hr>
                        <h5>Histori Transaksi Produk Ini:</h5>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th>Kode Transaksi</th>
                                        <th>Tanggal</th>
                                        <th>Jumlah Beli</th>
                                        <th>Harga Saat Itu</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($product->transactionDetails as $detail)
                                        <tr>
                                            <td>
                                                {{-- Link ke detail transaksi --}}
                                                <a href="{{ route('transactions.show', $detail->transaction->id) }}">
                                                    {{ $detail->transaction->transaction_code }}
                                                </a>
                                            </td>
                                            <td>{{ $detail->transaction->transaction_date->format('d M Y H:i') }}</td>
                                            <td>{{ $detail->quantity }}</td>
                                            <td>Rp {{ number_format($detail->price_at_transaction, 0, ',', '.') }}</td>
                                            <td>Rp {{ number_format($detail->quantity * $detail->price_at_transaction, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center mt-4">Belum ada histori transaksi untuk produk ini.</p>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection