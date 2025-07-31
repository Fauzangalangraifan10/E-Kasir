<!-- resources/views/categories/index.blade.php -->
@extends('layouts.app')

@section('content')
<style>
    /* ====== STYLE HALAMAN KATEGORI ====== */
    .card {
        border-radius: 10px;
        border: 1px solid #e3e6f0;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    }

    .card-header {
        background-color: #f8f9fc;
        border-bottom: 1px solid #e3e6f0;
        padding: 0.75rem 1.25rem;
    }

    .card-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin: 0;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .btn-sm i {
        font-size: 0.8rem;
    }

    .table {
        margin-bottom: 0;
        border: 1px solid #dee2e6;
    }

    .table thead th {
        background-color: #f8f9fa;
        font-weight: 600;
        font-size: 0.9rem;
        text-align: center;
        vertical-align: middle;
    }

    .table tbody td {
        vertical-align: middle;
        font-size: 0.9rem;
        text-align: center;
    }

    .table-responsive {
        margin-top: 10px;
    }

    .badge {
        font-size: 0.75rem;
        padding: 5px 10px;
    }

    .alert {
        font-size: 0.9rem;
    }

    /* Pagination kecil */
    .pagination {
        justify-content: center;
        margin-top: 15px;
    }

    .pagination svg {
        width: 1em !important;
        height: 1em !important;
    }

    .container-fluid .card-title.mb-0 {
    font-size: 1.8rem; /* lebih besar */
    font-weight: 500;  /* lebih tebal */
    color: #333;       /* warna lebih jelas */
    }

</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Daftar Kategori</h3>

                    {{-- Tombol tambah kategori hanya untuk admin --}}
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('categories.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Tambah Kategori
                        </a>
                    @endif
                </div>

                <div class="card-body">
                    {{-- Alert sukses --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    {{-- Alert error --}}
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered align-middle">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th>Nama Kategori</th>
                                    <th style="width: 150px;">Jumlah Produk</th>
                                    <th style="width: 180px;">Dibuat</th>
                                    <th style="width: 150px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $category)
                                    <tr>
                                        <td>{{ $loop->iteration + ($categories->currentPage() - 1) * $categories->perPage() }}</td>
                                        <td class="text-start">{{ $category->name }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ $category->products_count }} produk</span>
                                        </td>
                                        <td>{{ $category->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                {{-- Semua role bisa lihat detail kategori --}}
                                                <a href="{{ route('categories.show', $category) }}" 
                                                   class="btn btn-sm btn-info" title="Lihat">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                                {{-- Hanya admin yang bisa edit dan hapus --}}
                                                @if(auth()->user()->role === 'admin')
                                                    <a href="{{ route('categories.edit', $category) }}" 
                                                       class="btn btn-sm btn-warning" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('categories.destroy', $category) }}" 
                                                          method="POST" class="d-inline"
                                                          onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">Belum ada kategori</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-3">
                        {{ $categories->withQueryString()->links('pagination::bootstrap-5') }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
