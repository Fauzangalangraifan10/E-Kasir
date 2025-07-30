<!DOCTYPE html>
<html>
<head>
    <title>Struk Transaksi - {{ $transaction->transaction_code }}</title>
    <style>
        /* CSS Disederhanakan dari Bootstrap untuk Kompatibilitas PDF */
        body {
            font-family: 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif;
            font-size: 14px; /* Default font size */
            line-height: 1.5;
            color: #212529; /* Bootstrap's default text color */
            -webkit-print-color-adjust: exact; /* Pastikan warna background dicetak */
        }

        .container {
            width: 100%; /* Lebar penuh */
            padding-right: 15px;
            padding-left: 15px;
            margin-right: auto;
            margin-left: auto;
        }

        /* Card styles */
        .card {
            position: relative;
            display: flex;
            flex-direction: column;
            min-width: 0;
            word-wrap: break-word;
            background-color: #fff;
            background-clip: border-box;
            border: 1px solid rgba(0,0,0,.125); /* Default card border */
            border-radius: .25rem; /* Default card border-radius */
            margin-bottom: 1rem; /* Default card margin */
        }
        .shadow-sm {
            box-shadow: 0 .125rem .25rem rgba(0,0,0,.075)!important; /* Small shadow */
        }
        .p-4 {
            padding: 1.5rem!important; /* Padding 4 */
        }
        .py-4 {
            padding-top: 1.5rem!important;
            padding-bottom: 1.5rem!important;
        }

        /* Typography */
        h4 {
            margin-top: 0;
            margin-bottom: .5rem;
            font-size: 1.5rem; /* H4 size */
            font-weight: 500;
            line-height: 1.2;
        }
        hr {
            margin-top: 1rem;
            margin-bottom: 1rem;
            border: 0;
            border-top: 1px solid rgba(0,0,0,.1); /* HR border */
        }
        p {
            margin-top: 0;
            margin-bottom: 1rem;
        }
        strong {
            font-weight: bolder;
        }

        /* Table styles */
        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
            border-collapse: collapse; /* Ensure collapse for borders */
        }
        .table th,
        .table td {
            padding: .75rem; /* Default padding */
            vertical-align: top;
            border-top: 1px solid #dee2e6; /* Top border */
        }
        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6; /* Header bottom border */
        }
        .table tbody + tbody {
            border-top: 2px solid #dee2e6;
        }
        .table-bordered {
            border: 1px solid #dee2e6; /* Outer border for bordered tables */
        }
        .table-bordered th,
        .table-bordered td {
            border: 1px solid #dee2e6; /* Cell borders for bordered tables */
        }
        .table-bordered thead th,
        .table-bordered thead td {
            border-bottom-width: 2px;
        }
        .mt-3 {
            margin-top: 1rem!important;
        }
        .mt-4 {
            margin-top: 1.5rem!important;
        }

        /* Text alignment */
        .text-right {
            text-align: right!important;
        }

        /* Buttons (sembunyikan untuk PDF) */
        .btn-secondary {
            /* Gaya dasar tombol, tapi akan disembunyikan */
            background-color: #6c757d;
            color: #fff;
            border: 1px solid #6c757d;
            padding: .375rem .75rem;
            font-size: 1rem;
            line-height: 1.5;
            border-radius: .25rem;
            text-decoration: none; /* remove underline for anchor tags acting as buttons */
        }

        /* Sembunyikan tombol saat dicetak/PDF */
        @media print {
            .btn {
                display: none;
            }
            .card {
                box-shadow: none !important; /* Hapus shadow di PDF */
                border: none !important; /* Hapus border card di PDF jika tidak diinginkan */
            }
            /* Jika Anda ingin container lebih lebar di PDF, bisa atur di sini */
            .container {
                max-width: none; /* Hapus max-width */
            }
        }

        /* CSS untuk browser (override jika diperlukan) */
        @media screen {
            body {
                background-color: #f8f9fa; /* Warna background browser */
            }
        }
    </style>
</head>
<body>
    {{-- Layout tidak dipakai lagi karena kita embedded CSS --}}
    {{-- @extends('layouts.app') --}}
    {{-- @section('content') --}}

    <div class="container py-4">
        <div class="card shadow-sm p-4">
            <h4>Struk Transaksi</h4>
            <hr>
            <p>Kode Transaksi: <strong>{{ $transaction->transaction_code }}</strong></p>
            <p>Tanggal Transaksi: {{ $transaction->created_at->format('d M Y') }}</p>
            <p>Waktu Transaksi: {{ $transaction->created_at->format('H:i:s') }}</p>
            {{-- Menggunakan $paymentMethods dari controller --}}
            <p><strong>Metode Pembayaran:</strong> {{ $paymentMethods[$transaction->payment_method] ?? ucfirst($transaction->payment_method) }}</p>

            <table class="table table-bordered mt-3">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th class="text-right">Jumlah</th>
                        <th class="text-right">Harga</th>
                        <th class="text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaction->details as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td class="text-right">{{ $item->quantity }}</td>
                        <td class="text-right">Rp {{ number_format($item->price) }}</td>
                        <td class="text-right">Rp {{ number_format($item->subtotal) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-4">
                <p>Total: <strong>Rp {{ number_format($transaction->total_price) }}</strong></p>
                <p>Dibayar: Rp {{ number_format($transaction->paid) }}</p>
                <p>Kembalian: Rp {{ number_format($transaction->change) }}</p>
            </div>

            {{-- Tombol Kembali - Sembunyikan saat dicetak/PDF --}}
            <a href="{{ route('transactions.index') }}" class="btn btn-secondary mt-3">Kembali</a>
        </div>
    </div>

    {{-- @endsection --}}
</body>
</html>