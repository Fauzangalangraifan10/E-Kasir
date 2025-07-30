<!DOCTYPE html>
<html>
<head>
    <title>Laporan Penjualan</title>
    <style>
        body { font-family: sans-serif; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
        h1, h2 { text-align: center; margin-bottom: 5px; }
        .header-info { text-align: center; margin-bottom: 20px; }
    </style>
</head>
<body>
    <h1>Laporan Penjualan</h1>
    <div class="header-info">
        <p>Periode: {{ $startDate }} - {{ $endDate }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID Transaksi</th>
                <th>Tanggal</th>
                <th>Total Amount</th>
                <th>Metode Pembayaran</th>
                <th>Detail Produk</th>
            </tr>
        </thead>
        <tbody>
            {{-- Ubah $sales menjadi $transaction, dan saleItems menjadi details --}}
            @forelse ($sales as $transaction)
            <tr>
                <td>{{ $transaction->id }}</td>
                {{-- Ubah transaction_date menjadi created_at --}}
                <td>{{ $transaction->created_at->format('Y-m-d H:i') }}</td>
                {{-- Ubah total_amount menjadi total_price --}}
                <td>Rp{{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                <td>{{ $transaction->payment_method ?? 'Cash' }}</td>
                <td>
                    <ul>
                        {{-- Ubah saleItems menjadi details dan price_per_item menjadi price --}}
                        @foreach ($transaction->details as $item)
                            <li>{{ $item->product->name }} ({{ $item->quantity }}x @ Rp{{ number_format($item->price, 0, ',', '.') }})</li>
                        @endforeach
                    </ul>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center;">Tidak ada data penjualan pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>