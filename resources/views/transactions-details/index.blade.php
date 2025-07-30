@extends('layouts.app') {{-- Sesuaikan jika kamu menggunakan layout lain --}}

@section('content')
<div class="container">
    <h2 class="mb-4">Daftar Detail Transaksi</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('transaction-details.create') }}" class="btn btn-primary mb-3">+ Tambah Detail Transaksi</a>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Kode Transaksi</th>
                <th>Nama Produk</th>
                <th>Harga Satuan</th>
                <th>Jumlah</th>
                <th>Subtotal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($details as $key => $detail)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $detail->transaction->transaction_code ?? '-' }}</td>
                    <td>{{ $detail->product->name ?? '-' }}</td>
                    <td>Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                    <td>{{ $detail->quantity }}</td>
                    <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    <td>
                        <a href="{{ route('transaction-details.edit', $detail->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('transaction-details.destroy', $detail->id) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Belum ada data detail transaksi.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
