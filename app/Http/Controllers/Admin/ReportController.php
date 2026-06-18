<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view reports');
    }

    public function sales(Request $request)
    {
        $from = $request->filled('from') ? Carbon::parse($request->from)->startOfDay() : Carbon::now()->startOfMonth();
        $to = $request->filled('to') ? Carbon::parse($request->to)->endOfDay() : Carbon::now()->endOfDay();

        $sales = Sale::with('customer')
            ->withSum('items as revenue', 'subtotal')
            ->withSum('items as cogs', 'cost_total')
            ->whereBetween('sale_date', [$from, $to])
            ->latest('sale_date')
            ->get();

        $totals = [
            'count' => $sales->count(),
            'revenue' => $sales->sum('revenue'),
            'cogs' => $sales->sum('cogs'),
            'profit' => $sales->sum('revenue') - $sales->sum('cogs'),
            'collected' => $sales->sum('total'),
            'due' => $sales->sum('due'),
        ];

        return view('admin.reports.sales', [
            'sales' => $sales,
            'totals' => $totals,
            'from' => $from->toDateString(),
            'to' => $to->toDateString(),
        ]);
    }

    public function stock(Request $request)
    {
        // FIFO inventory value = sum of remaining batch quantities × their unit cost.
        $batchValue = DB::table('stock_batches')
            ->select('product_id', DB::raw('SUM(remaining) as qty'), DB::raw('SUM(remaining * unit_cost) as value'))
            ->where('remaining', '>', 0)
            ->groupBy('product_id');

        $query = Product::query()
            ->leftJoinSub($batchValue, 'b', 'b.product_id', '=', 'products.id')
            ->select('products.*', DB::raw('COALESCE(b.value, 0) as stock_value'))
            ->orderByDesc('stock_value');

        if ($request->boolean('low')) {
            $query->lowStock();
        }

        $products = $query->get();

        $totals = [
            'products' => $products->count(),
            'units' => $products->sum('stock_quantity'),
            'value' => $products->sum('stock_value'),
            'retail' => $products->sum(fn ($p) => $p->stock_quantity * $p->sale_price),
        ];

        return view('admin.reports.stock', compact('products', 'totals'));
    }

    public function purchases(Request $request)
    {
        [$from, $to] = $this->range($request);

        $query = Purchase::with('supplier')->whereBetween('purchase_date', [$from, $to])->latest('purchase_date');
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }
        $purchases = $query->get();

        $totals = [
            'count' => $purchases->count(),
            'total' => $purchases->sum('total'),
            'paid' => $purchases->sum('paid'),
            'due' => $purchases->sum('due'),
        ];

        return view('admin.reports.purchases', [
            'purchases' => $purchases,
            'totals' => $totals,
            'suppliers' => Supplier::orderBy('name')->get(),
            'from' => $from->toDateString(),
            'to' => $to->toDateString(),
        ]);
    }

    public function products(Request $request)
    {
        [$from, $to] = $this->range($request);

        $rows = DB::table('sale_items')
            ->join('sales', 'sales.id', '=', 'sale_items.sale_id')
            ->join('products', 'products.id', '=', 'sale_items.product_id')
            ->whereBetween('sales.sale_date', [$from, $to])
            ->groupBy('products.id', 'products.name')
            ->select(
                'products.name',
                DB::raw('SUM(sale_items.quantity) as qty'),
                DB::raw('SUM(sale_items.subtotal) as revenue'),
                DB::raw('SUM(sale_items.cost_total) as cogs')
            )
            ->orderByDesc('qty')
            ->get();

        $totals = [
            'qty' => $rows->sum('qty'),
            'revenue' => $rows->sum('revenue'),
            'cogs' => $rows->sum('cogs'),
            'profit' => $rows->sum('revenue') - $rows->sum('cogs'),
        ];

        return view('admin.reports.products', [
            'rows' => $rows,
            'totals' => $totals,
            'from' => $from->toDateString(),
            'to' => $to->toDateString(),
        ]);
    }

    public function daily(Request $request)
    {
        [$from, $to] = $this->range($request);

        $sales = Sale::withSum('items as revenue', 'subtotal')
            ->withSum('items as cogs', 'cost_total')
            ->whereBetween('sale_date', [$from, $to])
            ->get();

        $days = $sales->groupBy(fn ($s) => $s->sale_date->toDateString())
            ->map(fn ($g) => [
                'count' => $g->count(),
                'revenue' => $g->sum('revenue'),
                'cogs' => $g->sum('cogs'),
                'profit' => $g->sum('revenue') - $g->sum('cogs'),
                'collected' => $g->sum('total'),
            ])
            ->sortKeysDesc();

        $totals = [
            'count' => $sales->count(),
            'revenue' => $sales->sum('revenue'),
            'profit' => $sales->sum('revenue') - $sales->sum('cogs'),
            'collected' => $sales->sum('total'),
        ];

        return view('admin.reports.daily', [
            'days' => $days,
            'totals' => $totals,
            'from' => $from->toDateString(),
            'to' => $to->toDateString(),
        ]);
    }

    /** Resolve a [from, to] date range from the request, defaulting to the current month. */
    private function range(Request $request): array
    {
        $from = $request->filled('from') ? Carbon::parse($request->from)->startOfDay() : Carbon::now()->startOfMonth();
        $to = $request->filled('to') ? Carbon::parse($request->to)->endOfDay() : Carbon::now()->endOfDay();

        return [$from, $to];
    }
}
