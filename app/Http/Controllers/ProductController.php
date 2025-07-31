<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('barcode', 'like', "%{$search}%");
            });
        }

        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('stock_filter') && !empty($request->stock_filter)) {
            if ($request->stock_filter == 'low') {
                $query->lowStock();
            } elseif ($request->stock_filter == 'out') {
                $query->where('stock', 0);
            }
        }

        $products = $query->latest()->paginate(10);
        $categories = Category::all();

        $isKasir = auth()->check() && auth()->user()->role === 'kasir';

        return view('products.index', compact('products', 'categories', 'isKasir'));
    }

    public function create()
    {
        if (auth()->user()->role === 'kasir') {
            return redirect()->route('products.index')->with('warning', 'Kasir tidak memiliki akses untuk menambah produk.');
        }

        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->role === 'kasir') {
            return redirect()->route('products.index')->with('warning', 'Kasir tidak memiliki akses untuk menambah produk.');
        }

        $request->validate([
            'name'        => ['required', 'string', 'max:255', Rule::unique('products', 'name')],
            'category_id' => 'nullable|exists:categories,id',
            'barcode'     => ['nullable', 'string', 'max:255', Rule::unique('products', 'barcode')],
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'min_stock'   => 'required|integer|min:0',
            'is_active'   => 'boolean',
        ]);

        try {
            $data = $request->only(['name', 'category_id', 'barcode', 'description', 'price', 'stock', 'min_stock']);
            $data['is_active'] = $request->has('is_active');

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $path = $image->store('products', 'public');
                $data['image'] = basename($path);
            } else {
                $data['image'] = null;
            }

            if (empty($data['barcode'])) {
                $data['barcode'] = $this->generateBarcode();
            }

            Product::create($data);

            return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Gagal menambahkan produk: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            return redirect()->back()->withInput()->withErrors(['error' => 'Gagal menambahkan produk. Mohon coba lagi.']);
        }
    }

    public function show(Product $product)
    {
        // Load histori transaksi beserta transaksi agar tanggal, harga, subtotal bisa ditampilkan
        $product->load(['category', 'transactionDetails.transaction']);
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        if (auth()->user()->role === 'kasir') {
            return redirect()->route('products.index')->with('warning', 'Kasir tidak memiliki akses untuk mengedit produk.');
        }

        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        if (auth()->user()->role === 'kasir') {
            return redirect()->route('products.index')->with('warning', 'Kasir tidak memiliki akses untuk mengedit produk.');
        }

        $request->validate([
            'name'        => ['required', 'string', 'max:255', Rule::unique('products', 'name')->ignore($product->id)],
            'category_id' => 'nullable|exists:categories,id',
            'barcode'     => ['nullable', 'string', 'max:255', Rule::unique('products', 'barcode')->ignore($product->id)],
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_image'=> 'nullable|boolean',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'min_stock'   => 'required|integer|min:0',
            'is_active'   => 'boolean',
        ]);

        try {
            $data = $request->only(['name', 'category_id', 'barcode', 'description', 'price', 'stock', 'min_stock']);
            $data['is_active'] = $request->has('is_active');

            if ($request->has('remove_image') && $request->boolean('remove_image')) {
                if ($product->image && Storage::disk('public')->exists('products/' . $product->image)) {
                    Storage::disk('public')->delete('products/' . $product->image);
                }
                $data['image'] = null;
            } elseif ($request->hasFile('image')) {
                if ($product->image && Storage::disk('public')->exists('products/' . $product->image)) {
                    Storage::disk('public')->delete('products/' . $product->image);
                }
                $image = $request->file('image');
                $path = $image->store('products', 'public');
                $data['image'] = basename($path);
            } else {
                $data['image'] = $product->image;
            }

            if (empty($data['barcode'])) {
                $data['barcode'] = $this->generateBarcode();
            }

            $product->update($data);

            return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Gagal memperbarui produk: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            return redirect()->back()->withInput()->withErrors(['error' => 'Gagal memperbarui produk. Mohon coba lagi.']);
        }
    }

    public function destroy(Product $product)
    {
        if (auth()->user()->role === 'kasir') {
            return redirect()->route('products.index')->with('warning', 'Kasir tidak memiliki akses untuk menghapus produk.');
        }

        try {
            if ($product->transactionDetails()->count() > 0) {
                return redirect()->route('products.index')->with('error', 'Tidak dapat menghapus produk yang sudah memiliki transaksi.');
            }

            if ($product->image && Storage::disk('public')->exists('products/' . $product->image)) {
                Storage::disk('public')->delete('products/' . $product->image);
            }

            $product->delete();

            return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Gagal menghapus produk: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('products.index')->with('error', 'Gagal menghapus produk. Mohon coba lagi. Detail: ' . $e->getMessage());
        }
    }

    public function lowStock()
    {
        $products = Product::with('category')->lowStock()->paginate(10);
        return view('products.low-stock', compact('products'));
    }

    public function bulkImport()
    {
        if (auth()->user()->role === 'kasir') {
            return redirect()->route('products.index')->with('warning', 'Kasir tidak memiliki akses untuk import produk.');
        }

        return view('products.bulk-import');
    }

    public function processBulkImport(Request $request)
    {
        if (auth()->user()->role === 'kasir') {
            return redirect()->route('products.index')->with('warning', 'Kasir tidak memiliki akses untuk import produk.');
        }

        $request->validate([
            'file' => 'required|mimes:csv,txt|max:2048'
        ]);

        $file = $request->file('file');
        $csvData = file_get_contents($file->getRealPath());
        $rows = array_map('str_getcsv', explode("\n", $csvData));
        $header = array_shift($rows);

        $imported = 0;
        $errors = [];

        DB::beginTransaction();
        try {
            foreach ($rows as $index => $row) {
                if (count(array_filter($row)) < 4) {
                    continue;
                }

                $productName = trim($row[0]);
                $categoryName = trim($row[1]);
                $price = (float) trim($row[2]);
                $stock = (int) trim($row[3]);
                $minStock = isset($row[4]) ? (int) trim($row[4]) : 5;

                if (empty($productName) || $price < 0 || $stock < 0 || $minStock < 0) {
                    $errors[] = "Baris " . ($index + 2) . ": Data tidak valid.";
                    continue;
                }

                $category = Category::firstOrCreate(['name' => $categoryName]);

                $data = [
                    'name'        => $productName,
                    'category_id' => $category->id,
                    'price'       => $price,
                    'stock'       => $stock,
                    'min_stock'   => $minStock,
                    'barcode'     => $this->generateBarcode(),
                    'is_active'   => true,
                    'description' => null,
                    'image'       => null,
                ];

                $existingProduct = Product::where('name', $productName)->first();
                if ($existingProduct) {
                    $existingProduct->update($data);
                    $imported++;
                } else {
                    Product::create($data);
                    $imported++;
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal memproses bulk import: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request_file' => $file->getClientOriginalName()
            ]);
            $errors[] = "Terjadi kesalahan umum saat import: " . $e->getMessage();
        }

        $message = "{$imported} produk berhasil diimport.";
        if (!empty($errors)) {
            $message .= " " . count($errors) . " produk gagal diimport.";
            return redirect()->route('products.index')->with('warning', $message)->with('import_errors', $errors);
        }

        return redirect()->route('products.index')->with('success', $message);
    }

    private function generateBarcode()
    {
        $maxAttempts = 10;
        $attempts = 0;
        do {
            $barcode = 'PRD' . now()->format('ymdHis') . str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);
            $attempts++;
            if ($attempts >= $maxAttempts) {
                Log::warning('Gagal menghasilkan barcode unik setelah ' . $maxAttempts . ' percobaan.');
                throw new \Exception('Failed to generate unique barcode.');
            }
        } while (Product::where('barcode', $barcode)->exists());

        return $barcode;
    }

    private function findCategoryId($categoryName)
    {
        $categoryName = trim($categoryName);
        if (empty($categoryName)) {
            return null;
        }
        $category = Category::firstOrCreate(['name' => $categoryName]);
        return $category->id;
    }

    public function downloadTemplate()
    {
        if (auth()->user()->role === 'kasir') {
            return redirect()->route('products.index')->with('warning', 'Kasir tidak memiliki akses untuk mengunduh template produk.');
        }

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=template_produk.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Nama Produk', 'Kategori', 'Harga', 'Stok', 'Min Stok', 'Barcode (opsional)', 'Deskripsi (opsional)', 'Aktif (1/0, opsional)'];
        $sampleData = [
            ['Indomie Goreng', 'Makanan Instan', '3500', '100', '10', '', 'Mie instan rasa ayam bawang', '1'],
            ['Aqua 600ml', 'Minuman', '3000', '50', '5', '8992765001234', 'Air mineral murni', '1'],
            ['Buku Tulis Sinar Dunia', 'Alat Tulis', '5000', '25', '5', '', 'Buku tulis 50 lembar', '0'],
        ];

        $callback = function () use ($columns, $sampleData) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($sampleData as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $products = Product::where('name', 'like', '%' . $query . '%')
            ->where('stock', '>', 0)
            ->limit(10)
            ->get(['id', 'name', 'price', 'stock']);

        return response()->json($products);
    }
}
