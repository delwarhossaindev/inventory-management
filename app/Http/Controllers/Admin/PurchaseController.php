<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view purchases')->only(['index', 'show']);
        $this->middleware('permission:create purchases')->only(['create', 'store']);
        $this->middleware('permission:delete purchases')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $query = Purchase::with('supplier')->latest();
        if ($request->filled('q')) {
            $query->where('invoice_no', 'like', '%' . $request->q . '%');
        }

        return view('admin.purchases.index', ['purchases' => $query->paginate(15)->withQueryString()]);
    }

    public function create()
    {
        return view('admin.purchases.create', [
            'suppliers' => Supplier::where('status', 'active')->orderBy('name')->get(),
            'products' => Product::orderBy('name')->get(['id', 'name', 'sku', 'model', 'purchase_price', 'stock_quantity']),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        $purchase = DB::transaction(function () use ($data) {
            $computed = $this->computeTotals($data);

            $purchase = Purchase::create([
                'invoice_no' => $this->nextInvoiceNo(),
                'supplier_id' => $data['supplier_id'] ?? null,
                'purchase_date' => $data['purchase_date'],
                'subtotal' => $computed['subtotal'],
                'discount' => $data['discount'] ?? 0,
                'tax' => $data['tax'] ?? 0,
                'total' => $computed['total'],
                'paid' => $data['paid'] ?? 0,
                'due' => $computed['total'] - ($data['paid'] ?? 0),
                'status' => $data['status'],
                'note' => $data['note'] ?? null,
            ]);

            foreach ($computed['items'] as $item) {
                $purchase->items()->create($item);

                // Stock only moves when goods are actually received.
                // Each received line becomes a FIFO batch at its unit cost.
                if ($data['status'] === 'received') {
                    Product::find($item['product_id'])
                        ->stockIn($item['quantity'], $item['unit_cost'], 'purchase', $purchase, 'Purchase ' . $purchase->invoice_no);
                }
            }

            return $purchase;
        });

        return redirect()->route('admin.purchases.show', $purchase)->with('success', 'Purchase recorded.');
    }

    public function show(Purchase $purchase)
    {
        $purchase->load(['supplier', 'items.product']);

        return view('admin.purchases.show', compact('purchase'));
    }

    public function destroy(Purchase $purchase)
    {
        DB::transaction(function () use ($purchase) {
            $purchase->load('items');

            // Reverse the stock that this purchase added (FIFO out).
            if ($purchase->status === 'received') {
                foreach ($purchase->items as $item) {
                    $product = Product::find($item->product_id);
                    if ($product) {
                        $product->stockOut($item->quantity, 'adjustment', $purchase, 'Reversal of purchase ' . $purchase->invoice_no);
                    }
                }
            }

            $purchase->delete(); // cascades purchase_items
        });

        return redirect()->route('admin.purchases.index')->with('success', 'Purchase deleted and stock reversed.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'supplier_id' => ['nullable', 'exists:suppliers,id'],
            'purchase_date' => ['required', 'date'],
            'status' => ['required', 'in:received,pending'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'tax' => ['nullable', 'numeric', 'min:0'],
            'paid' => ['nullable', 'numeric', 'min:0'],
            'note' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.unit_cost' => ['required', 'numeric', 'min:0'],
        ]);
    }

    private function computeTotals(array $data): array
    {
        $items = [];
        $subtotal = 0;
        foreach ($data['items'] as $row) {
            $lineSub = $row['quantity'] * $row['unit_cost'];
            $subtotal += $lineSub;
            $items[] = [
                'product_id' => $row['product_id'],
                'quantity' => $row['quantity'],
                'unit_cost' => $row['unit_cost'],
                'subtotal' => $lineSub,
            ];
        }
        $total = $subtotal - ($data['discount'] ?? 0) + ($data['tax'] ?? 0);

        return ['items' => $items, 'subtotal' => $subtotal, 'total' => $total];
    }

    private function nextInvoiceNo(): string
    {
        return 'PUR-' . str_pad((string) (Purchase::max('id') + 1), 5, '0', STR_PAD_LEFT);
    }
}
