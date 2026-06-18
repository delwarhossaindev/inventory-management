<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $stats = [
            'products' => Product::count(),
            'categories' => Category::count(),
            'low_stock' => Product::lowStock()->count(),
            'stock_value' => Product::selectRaw('SUM(stock_quantity * purchase_price) as v')->value('v') ?? 0,
            'today_sales' => Sale::whereDate('sale_date', $today)->sum('total'),
            'today_sales_count' => Sale::whereDate('sale_date', $today)->count(),
            'month_sales' => Sale::whereMonth('sale_date', $today->month)->whereYear('sale_date', $today->year)->sum('total'),
            'month_purchases' => Purchase::whereMonth('purchase_date', $today->month)->whereYear('purchase_date', $today->year)->sum('total'),
        ];

        $recentSales = Sale::with('customer')->latest()->take(8)->get();
        $lowStock = Product::lowStock()->orderBy('stock_quantity')->take(8)->get();

        return view('admin.dashboard', compact('stats', 'recentSales', 'lowStock'));
    }
}
