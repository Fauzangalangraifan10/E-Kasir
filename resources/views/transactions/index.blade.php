@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Daftar Transaksi</h2>
        {{-- Tombol untuk menuju halaman pembuatan transaksi baru --}}
        <a href="{{ route('transactions.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Transaksi Baru
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            Filter Transaksi
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('transactions.index') }}">
                <div class="mb-3">
                    <label for="payment_method_filter" class="form-label">Metode Pembayaran</label>
                    <select class="form-select" id="payment_method_filter" name="payment_method" onchange="this.form.submit()">
                        <option value="">Semua Metode</option>
                        @foreach($paymentMethods as $key => $value)
                            <option value="{{ $key }}" {{ $key == $paymentMethod ? 'selected' : '' }}>
                                {{ $value }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-light">
                <tr>
                    <th>Kode Transaksi</th>
                    <th>Tanggal</th>
                    <th>Total Belanja</th>
                    <th>Dibayar</th>
                    <th>Kembalian</th>
                    <th>Metode Pembayaran</th>
                    <th>Aksi</th>
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
                        <a href="{{ route('transactions.show', $transaction->id) }}" class="btn btn-info btn-sm mb-1">Lihat Struk</a>
                        <a href="{{ route('transactions.print-pdf', $transaction->id) }}" class="btn btn-secondary btn-sm mb-1" target="_blank">Cetak PDF</a>

                        {{-- Tombol Hapus dengan SweetAlert2 --}}
                        <form id="deleteForm{{ $transaction->id }}" action="{{ route('transactions.destroy', $transaction->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-danger btn-sm delete-button" data-id="{{ $transaction->id }}">Hapus</button>
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

    {{-- Pagination jika menggunakan paginate() --}}
    {{-- <div class="d-flex justify-content-center">
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
