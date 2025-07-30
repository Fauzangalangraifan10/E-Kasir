<?php

namespace App\Http\Controllers;

use App\Models\TransactionDetail;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionDetailController extends Controller
{
    // Menampilkan semua detail transaksi
    public function index()
    {
        $details = TransactionDetail::with(['transaction', 'product'])->latest()->get();
        return view('transaction_details.index', compact('details'));
    }

    // Form tambah detail
    public function create()
    {
        $products = Product::all();
        $transactions = Transaction::all();
        return view('transaction_details.create', compact('products', 'transactions'));
    }

    // Simpan data detail transaksi
    public function store(Request $request)
    {
        $request->validate([
            'transaction_id' => 'required|exists:transactions,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric',
            'subtotal' => 'required|numeric',
        ]);

        TransactionDetail::create($request->all());

        return redirect()->route('transaction-details.index')
                         ->with('success', 'Detail transaksi berhasil ditambahkan.');
    }

    // Tampilkan satu detail
    public function show(TransactionDetail $transactionDetail)
    {
        return view('transaction_details.show', compact('transactionDetail'));
    }

    // Form edit
    public function edit(TransactionDetail $transactionDetail)
    {
        $products = Product::all();
        $transactions = Transaction::all();
        return view('transaction_details.edit', compact('transactionDetail', 'products', 'transactions'));
    }

    // Update data
    public function update(Request $request, TransactionDetail $transactionDetail)
    {
        $request->validate([
            'transaction_id' => 'required|exists:transactions,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric',
            'subtotal' => 'required|numeric',
        ]);

        $transactionDetail->update($request->all());

        return redirect()->route('transaction-details.index')
                         ->with('success', 'Detail transaksi berhasil diperbarui.');
    }

    // Hapus data
    public function destroy(TransactionDetail $transactionDetail)
    {
        $transactionDetail->delete();
        return redirect()->route('transaction-details.index')
                         ->with('success', 'Detail transaksi berhasil dihapus.');
    }
}
