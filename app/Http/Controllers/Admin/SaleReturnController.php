<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleReturn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleReturnController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view sales');
    }

    public function index()
    {
        $returns = SaleReturn::with('sale.customer')
            ->latest('return_date')
            ->latest('id')
            ->paginate(20);

        return view('admin.returns.index', compact('returns'));
    }

    public function create(Sale $sale)
    {
        $sale->load('items.product');

        return view('admin.returns.create', compact('sale'));
    }

    public function store(Request $request, Sale $sale)
    {
        $data = $request->validate([
            'return_date' => 'required|date',
            'reason' => 'nullable|string|max:255',
            'note' => 'nullable|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $return = DB::transaction(function () use ($sale, $data) {
            $total = 0;
            $lines = [];

            foreach ($data['items'] as $row) {
                if ($row['quantity'] <= 0) {
                    continue;
                }
                $lineSub = $row['quantity'] * $row['unit_price'];
                $total += $lineSub;
                $lines[] = [
                    'product_id' => $row['product_id'],
                    'quantity' => $row['quantity'],
                    'unit_price' => $row['unit_price'],
                    'subtotal' => $lineSub,
                ];
            }

            $return = $sale->returns()->create([
                'return_date' => $data['return_date'],
                'total' => $total,
                'reason' => $data['reason'] ?? null,
                'note' => $data['note'] ?? null,
                'created_by' => auth()->id(),
            ]);

            foreach ($lines as $line) {
                $return->items()->create($line);

                $product = Product::find($line['product_id']);
                if ($product) {
                    $unitCost = (float) $product->purchase_price;
                    $product->stockIn(
                        $line['quantity'],
                        $unitCost,
                        'return',
                        $return,
                        'Sale return from ' . $sale->invoice_no
                    );
                }
            }

            return $return;
        });

        return redirect()->route('admin.returns.index')
            ->with('success', 'Return processed for ' . $sale->invoice_no . ' — ৳' . number_format($return->total, 2));
    }

    public function show(SaleReturn $return)
    {
        $return->load(['sale.customer', 'items.product']);

        return view('admin.returns.show', compact('return'));
    }
}
