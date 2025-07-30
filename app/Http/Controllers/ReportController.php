<?php

namespace App\Http\Controllers;

use App\Models\Transaction; // Ubah dari Sale
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportController extends Controller
{
    public function salesReport(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::today()->toDateString());
        $endDate = $request->input('end_date', Carbon::today()->toDateString());

        // Ubah Sale::with('saleItems.product') menjadi Transaction::with('details.product')
        $sales = Transaction::with('details.product')
                    // Ubah transaction_date menjadi created_at
                    ->whereDate('created_at', '>=', $startDate)
                    ->whereDate('created_at', '<=', $endDate)
                    // Ubah transaction_date menjadi created_at
                    ->orderBy('created_at', 'desc')
                    ->get();

        if ($request->has('export_pdf')) {
            $pdf = Pdf::loadView('reports.sales_pdf', compact('sales', 'startDate', 'endDate'));
            return $pdf->download('laporan_penjualan_' . $startDate . '_to_' . $endDate . '.pdf');
        }

        if ($request->has('export_excel')) {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A1', 'Laporan Penjualan');
            $sheet->setCellValue('A3', 'Dari Tanggal: ' . $startDate);
            $sheet->setCellValue('B3', 'Sampai Tanggal: ' . $endDate);

            $sheet->setCellValue('A5', 'ID Transaksi');
            $sheet->setCellValue('B5', 'Tanggal');
            $sheet->setCellValue('C5', 'Total Amount');
            $sheet->setCellValue('D5', 'Metode Pembayaran');
            $sheet->setCellValue('E5', 'Produk');
            $sheet->setCellValue('F5', 'Kuantitas');
            $sheet->setCellValue('G5', 'Harga Satuan'); // Label ini tetap, namun ambil dari 'price' di detail

            $row = 6;
            foreach ($sales as $sale) { // 'sale' di sini merujuk pada objek Transaction
                $sheet->setCellValue('A' . $row, $sale->id);
                // Ubah transaction_date menjadi created_at
                $sheet->setCellValue('B' . $row, $sale->created_at->format('Y-m-d H:i'));
                // Ubah total_amount menjadi total_price
                $sheet->setCellValue('C' . $row, $sale->total_price);
                $sheet->setCellValue('D' . $row, $sale->payment_method);
                
                $productDetails = [];
                // Ubah saleItems menjadi details dan price_per_item menjadi price
                foreach ($sale->details as $item) {
                    $productDetails[] = $item->product->name . ' (' . $item->quantity . 'x ' . $item->price . ')';
                }
                $sheet->setCellValue('E' . $row, implode(", ", $productDetails));
                
                $row++;
            }

            $writer = new Xlsx($spreadsheet);
            $fileName = 'laporan_penjualan_' . $startDate . '_to_' . $endDate . '.xlsx';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . urlencode($fileName) . '"');
            $writer->save('php://output');
            exit;
        }

        return view('reports.sales', compact('sales', 'startDate', 'endDate'));
    }

    public function stockReport(Request $request)
    {
        $products = Product::orderBy('stock', 'asc')->get();

        if ($request->has('export_pdf')) {
            $pdf = Pdf::loadView('reports.stock_pdf', compact('products'));
            return $pdf->download('laporan_stok_produk.pdf');
        }

        if ($request->has('export_excel')) {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A1', 'Laporan Stok Produk');

            $sheet->setCellValue('A3', 'ID Produk');
            $sheet->setCellValue('B3', 'Nama Produk');
            $sheet->setCellValue('C3', 'Kategori');
            $sheet->setCellValue('D3', 'Harga');
            $sheet->setCellValue('E3', 'Stok');
            
            $row = 4;
            foreach ($products as $product) {
                $sheet->setCellValue('A' . $row, $product->id);
                $sheet->setCellValue('B' . $row, $product->name);
                $sheet->setCellValue('C' . $row, $product->category->name ?? 'N/A');
                $sheet->setCellValue('D' . $row, $product->price);
                $sheet->setCellValue('E' . $row, $product->stock);
                $row++;
            }

            $writer = new Xlsx($spreadsheet);
            $fileName = 'laporan_stok_produk.xlsx';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . urlencode($fileName) . '"');
            $writer->save('php://output');
            exit;
        }

        return view('reports.stock', compact('products'));
    }
}