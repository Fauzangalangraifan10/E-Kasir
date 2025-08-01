<?php
// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Basic Stats
        $stats = [
            'total_products' => Product::count(),
            'total_categories' => Category::count(),
            'low_stock_products_count' => Product::lowStock()->count(),
            'total_transactions_today' => Transaction::whereDate('created_at', today())->count(),
            'total_revenue_today' => Transaction::whereDate('created_at', today())->sum('total_price'),
            'total_revenue_month' => Transaction::whereMonth('created_at', now()->month)
                                                ->whereYear('created_at', now()->year)
                                                ->sum('total_price'),
        ];

        // Sales Chart Data (Last 7 days)
        $salesChart = $this->getSalesChartData();

        // Top Products
        $topProducts = $this->getTopProducts();

        // Recent Transactions
        $recentTransactions = Transaction::with('user')
                                        ->orderBy('created_at', 'desc')
                                        ->limit(5)
                                        ->get();

        // Low Stock Products
        $lowStockProducts = Product::with('category')
                                    ->lowStock()
                                    ->limit(5)
                                    ->get();

        // Category Sales
        $categorySales = $this->getCategorySales();

        return view('dashboard', compact(
            'stats',
            'salesChart',
            'topProducts',
            'recentTransactions',
            'lowStockProducts',
            'categorySales'
        ));
    }

    private function getSalesChartData()
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $revenue = Transaction::whereDate('created_at', $date)->sum('total_price');
            $data[] = [
                'date' => $date->format('d/m'),
                'revenue' => $revenue
            ];
        }
        return $data;
    }

    private function getTopProducts($limit = 5)
    {
        return Product::select(
                'products.id',
                'products.name',
                'products.price',
                'products.stock',
                'products.image',
                'products.category_id',
                DB::raw('SUM(transaction_details.quantity) as total_sold')
            )
            ->join('transaction_details', 'products.id', '=', 'transaction_details.product_id')
            ->groupBy(
                'products.id',
                'products.name',
                'products.price',
                'products.stock',
                'products.image',
                'products.category_id'
            )
            ->orderByDesc('total_sold')
            ->limit($limit)
            ->with('category')
            ->get();
    }

    private function getCategorySales()
    {
        return Category::select('categories.name')
                        ->join('products', 'categories.id', '=', 'products.category_id')
                        ->join('transaction_details', 'products.id', '=', 'transaction_details.product_id')
                        ->selectRaw('SUM(transaction_details.subtotal) as total_sales')
                        ->groupBy('categories.id', 'categories.name')
                        ->orderByDesc('total_sales')
                        ->limit(5)
                        ->get();
    }
}
