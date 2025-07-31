@extends('layouts.app')

@section('content')
<style>
    /* ====== STYLE UNTUK DAFTAR TRANSAKSI ====== */
    .card {
        border-radius: 8px;
        border: 1px solid #dee2e6;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);
    }

    .card-header {
        background-color: #f8f9fc;
        font-weight: 600;
        border-bottom: 1px solid #dee2e6;
        padding: 0.6rem 1rem;
    }

    h2.mb-0 {
        font-size: 1.3rem;
        font-weight: 700;
    }

    .filter-card {
        border: 1px solid #dee2e6;
        margin-bottom: 1rem;
    }

    /* Table compact & text smaller */
    .table {
        margin-bottom: 0;
        border: 1px solid #dee2e6;
        font-size: 0.78rem;
    }

    .table thead th {
        background-color: #f8f9fa;
        font-weight: 600;
        text-align: center;
        vertical-align: middle;
        padding: 4px 6px;
        font-size: 0.78rem;
    }

    .table tbody td {
        vertical-align: middle;
        text-align: center;
        padding: 4px 6px;
        font-size: 0.78rem;
    }

    /* Button size optimization */
    .btn {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 0.7rem;
        padding: 2px 5px;
    }

    .btn i {
        font-size: 0.65rem;
    }

    .table-responsive {
        border: 1px solid #dee2e6;
        border-top: none;
        border-radius: 0 0 8px 8px;
        overflow: hidden;
    }

    .search-input {
        font-size: 0.78rem;
        padding: 4px 8px;
        height: 30px;
    }
</style>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Daftar Transaksi</h2>
        {{-- Tombol untuk menuju halaman pembuatan transaksi baru --}}
        <a href="{{ route('transactions.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Transaksi
        </a>
    </div>

    {{-- Filter & Pencarian Transaksi --}}
    <div class="card filter-card">
        <div class="card-header">
            Filter & Pencarian Transaksi
        </div>
        <div class="card-body p-2">
            <form method="GET" action="{{ route('transactions.index') }}" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label for="payment_method_filter" class="form-label mb-1" style="font-size: 0.78rem;">Metode Pembayaran</label>
                    <select class="form-select form-select-sm" id="payment_method_filter" name="payment_method">
                        <option value="">Semua Metode</option>
                        @foreach($paymentMethods as $key => $value)
                            <option value="{{ $key }}" {{ $key == $paymentMethod ? 'selected' : '' }}>
                                {{ $value }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="search_transaction" class="form-label mb-1" style="font-size: 0.78rem;">Cari Berdasarkan Kode Transaksi</label>
                    <input type="text" id="search_transaction" name="search" value="{{ request('search') }}" class="form-control form-control-sm search-input" placeholder="Masukkan kode transaksi...">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-sm btn-primary w-100">
                        <i class="fas fa-search"></i> Cari
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('transactions.index') }}" class="btn btn-sm btn-secondary w-100">
                        <i class="fas fa-sync"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Daftar Transaksi --}}
    <div class="card">
        <div class="card-header">
            Tabel Daftar Transaksi
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 110px;">Kode Transaksi</th>
                        <th style="width: 120px;">Tanggal</th>
                        <th style="width: 100px;">Total Belanja</th>
                        <th style="width: 100px;">Dibayar</th>
                        <th style="width: 100px;">Kembalian</th>
                        <th style="width: 140px;">Metode Pembayaran</th>
                        <th style="width: 170px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $transaction)
                    <tr>
                        <td>{{ $transaction->transaction_code }}</td>
                        <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                        <td>Rp {{ number_format($transaction->total_price) }}</td>
                        <td>Rp {{ number_format($transaction->paid) }}</td>
                        <td>Rp {{ number_format($transaction->change) }}</td>
                        <td>{{ $paymentMethods[$transaction->payment_method] ?? $transaction->payment_method }}</td>
                        <td>
                            <a href="{{ route('transactions.show', $transaction->id) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-receipt"></i> Lihat
                            </a>
                            <a href="{{ route('transactions.print-pdf', $transaction->id) }}" class="btn btn-secondary btn-sm" target="_blank">
                                <i class="fas fa-file-pdf"></i> Cetak
                            </a>

                            {{-- Tombol Hapus dengan SweetAlert2 --}}
                            <form id="deleteForm{{ $transaction->id }}" action="{{ route('transactions.destroy', $transaction->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm delete-button" data-id="{{ $transaction->id }}">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">Tidak ada transaksi ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination jika menggunakan paginate() --}}
    {{-- <div class="d-flex justify-content-center mt-2">
        {{ $transactions->links() }}
    </div> --}}
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.delete-button').forEach(button => {
            button.addEventListener('click', function () {
                const transactionId = this.dataset.id;
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data transaksi ini akan dihapus permanen dan stok produk tidak akan dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('deleteForm' + transactionId).submit();
                    }
                });
            });
        });
    });
</script>
@endpush
@endsection
