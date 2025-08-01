<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Product;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource (daftar transaksi).
     */
    public function index(Request $request)
    {
        $paymentMethod = $request->input('payment_method');

        $transactions = Transaction::with('details')
            ->when($paymentMethod, function ($query) use ($paymentMethod) {
                $query->where('payment_method', $paymentMethod);
            })
            ->latest()
            ->get();

        $paymentMethods = [
            'cash' => 'Tunai',
            'ewallet' => 'E-Wallet',
            'banking' => 'M-Banking',
            'qris' => 'QRIS',
        ];

        return view('transactions.index', compact('transactions', 'paymentMethod', 'paymentMethods'));
    }

    /**
     * Show the form for creating a new resource (form transaksi baru).
     */
    public function create()
    {
        $products = Product::all();
        $payments = PaymentMethod::where('is_active', 1)->get();

        return view('transactions.create', compact('products', 'payments'));
    }

    /**
     * Store a newly created resource in storage (memproses transaksi).
     */
    public function store(Request $request)
    {
        // Jika QRIS, jumlah bayar akan otomatis sama dengan total belanja
        $rules = [
            'payment_method' => 'required|string|in:cash,ewallet,banking,qris',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.subtotal' => 'required|numeric|min:0',
        ];

        // Hanya validasi "paid" jika metode pembayaran bukan QRIS
        if ($request->payment_method !== 'qris') {
            $rules['paid'] = 'required|numeric|min:0';
        }

        $request->validate($rules);

        DB::beginTransaction();

        try {
            $calculatedTotalPrice = 0;
            $transactionDetailsData = [];

            // Hitung total harga dan cek stok produk
            foreach ($request->items as $itemData) {
                $product = Product::find($itemData['product_id']);

                if (!$product) {
                    DB::rollBack();
                    return redirect()->back()->withErrors([
                        'message' => 'Produk dengan ID ' . $itemData['product_id'] . ' tidak ditemukan.'
                    ])->withInput();
                }

                if ($product->stock < $itemData['quantity']) {
                    DB::rollBack();
                    return redirect()->back()->withErrors([
                        'message' => 'Stok tidak cukup untuk produk: ' . $product->name . '. Stok tersedia: ' . $product->stock . ', diminta: ' . $itemData['quantity']
                    ])->withInput();
                }

                $itemPrice = $product->price;
                $itemSubtotal = $itemPrice * $itemData['quantity'];
                $calculatedTotalPrice += $itemSubtotal;

                $transactionDetailsData[] = [
                    'product_id' => $product->id,
                    'quantity' => $itemData['quantity'],
                    'price' => $itemPrice,
                    'subtotal' => $itemSubtotal,
                ];
            }

            // Jika QRIS: auto-set jumlah bayar sama dengan total belanja
            if ($request->payment_method === 'qris') {
                $paidAmount = $calculatedTotalPrice;
                $changeAmount = 0;
            } else {
                // Cek jika jumlah bayar mencukupi
                if ($request->paid < $calculatedTotalPrice) {
                    DB::rollBack();
                    return redirect()->back()->withErrors([
                        'paid' => 'Jumlah bayar tidak mencukupi total belanja. Total: ' . number_format($calculatedTotalPrice) . ', Dibayar: ' . number_format($request->paid)
                    ])->withInput();
                }

                $paidAmount = $request->paid;
                $changeAmount = $paidAmount - $calculatedTotalPrice;
            }

            // Buat kode transaksi unik
            $transactionCode = 'TRX-' . date('YmdHis') . '-' . Str::random(5);

            // Simpan transaksi
            $transaction = Transaction::create([
                'user_id' => auth()->id(),
                'transaction_code' => $transactionCode,
                'total_price' => $calculatedTotalPrice,
                'paid' => $paidAmount,
                'change' => $changeAmount,
                'payment_method' => $request->payment_method,
            ]);

            // Simpan detail transaksi dan update stok produk
            foreach ($transactionDetailsData as $itemData) {
                $itemData['transaction_id'] = $transaction->id;
                TransactionDetail::create($itemData);

                $product = Product::find($itemData['product_id']);
                $product->decrement('stock', $itemData['quantity']);
            }

            DB::commit();

            return redirect()->route('transactions.show', $transaction->id)
                ->with('success', 'Transaksi berhasil dengan kode: ' . $transaction->transaction_code);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Transaction failed: ' . $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine());
            return redirect()->back()->withErrors([
                'error' => 'Gagal melakukan transaksi. Mohon coba lagi. Detail: ' . $e->getMessage()
            ])->withInput();
        }
    }

    /**
     * Display the specified transaction (untuk menampilkan struk di browser).
     */
    public function show(Transaction $transaction)
    {
        $transaction->load('details.product', 'user');
        $paymentMethods = [
            'cash' => 'Tunai',
            'ewallet' => 'E-Wallet',
            'banking' => 'M-Banking',
            'qris' => 'QRIS',
        ];

        return view('transactions.struk', compact('transaction', 'paymentMethods'));
    }

    /**
     * Generate PDF for the transaction (untuk mendownload struk PDF).
     */
    public function printPdf(Transaction $transaction)
    {
        $transaction->load('details.product', 'user');
        $paymentMethods = [
            'cash' => 'Tunai',
            'ewallet' => 'E-Wallet',
            'banking' => 'M-Banking',
            'qris' => 'QRIS',
        ];

        $pdf = Pdf::loadView('transactions.struk', compact('transaction', 'paymentMethods'));
        return $pdf->download('struk_' . $transaction->transaction_code . '.pdf');
    }

    /**
     * Remove the specified resource from storage (menghapus transaksi).
     */
    public function destroy(Transaction $transaction)
    {
        DB::beginTransaction();
        try {
            $transaction->details()->delete();
            $transaction->delete();

            DB::commit();
            return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Transaction deletion failed: ' . $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine());
            return redirect()->route('transactions.index')->with('error', 'Gagal menghapus transaksi. Mohon coba lagi. Detail: ' . $e->getMessage());
        }
    }

    public function edit(Transaction $transaction)
    {
        // Implementasi edit jika diperlukan
    }

    public function update(Request $request, Transaction $transaction)
    {
        // Implementasi update jika diperlukan
    }
}
