<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

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

        // Last 7 days sales chart data
        $chartDays = collect(range(6, 0))->map(fn ($i) => Carbon::today()->subDays($i));
        $dailySales = Sale::whereBetween('sale_date', [$chartDays->first(), $chartDays->last()])
            ->selectRaw('DATE(sale_date) as day, SUM(total) as total, COUNT(*) as count')
            ->groupBy('day')->pluck('total', 'day');

        $chartLabels = $chartDays->map(fn ($d) => $d->format('d M'))->values();
        $chartData = $chartDays->map(fn ($d) => (float) ($dailySales[$d->toDateString()] ?? 0))->values();

        // Top 5 categories by sales
        $topCategories = DB::table('sale_items')
            ->join('products', 'products.id', '=', 'sale_items.product_id')
            ->join('categories', 'categories.id', '=', 'products.main_category_id')
            ->selectRaw('categories.name, SUM(sale_items.subtotal) as total')
            ->groupBy('categories.name')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $pieLabels = $topCategories->pluck('name')->values();
        $pieData = $topCategories->pluck('total')->map(fn ($v) => (float) $v)->values();

        return view('admin.dashboard', compact(
            'stats', 'recentSales', 'lowStock',
            'chartLabels', 'chartData', 'pieLabels', 'pieData'
        ));
    }
}
