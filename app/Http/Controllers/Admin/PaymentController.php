<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Purchase;
use App\Models\Sale;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view sales');
    }

    public function index(Request $request)
    {
        $query = Payment::with('payable')->latest('payment_date')->latest('id');

        if ($request->filled('type')) {
            $map = ['sale' => Sale::class, 'purchase' => Purchase::class];
            if (isset($map[$request->type])) {
                $query->where('payable_type', $map[$request->type]);
            }
        }

        $dueSales = Sale::with('customer')->where('due', '>', 0)->latest('sale_date')->get();
        $duePurchases = Purchase::with('supplier')->where('due', '>', 0)->latest('purchase_date')->get();

        return view('admin.payments.index', [
            'payments' => $query->paginate(20)->withQueryString(),
            'dueSales' => $dueSales,
            'duePurchases' => $duePurchases,
            'receivable' => $dueSales->sum('due'),
            'payable' => $duePurchases->sum('due'),
        ]);
    }

    public function storeSalePayment(Request $request, Sale $sale)
    {
        $data = $request->validate([
            'amount' => 'required|numeric|min:0.01|max:' . $sale->due,
            'method' => 'required|in:cash,card,mobile',
            'payment_date' => 'required|date',
            'note' => 'nullable|string|max:255',
        ]);

        $sale->payments()->create($data + ['created_by' => auth()->id()]);
        $sale->recalculateDue();

        return back()->with('success', 'Payment of ৳' . number_format($data['amount'], 2) . ' recorded.');
    }

    public function storePurchasePayment(Request $request, Purchase $purchase)
    {
        $data = $request->validate([
            'amount' => 'required|numeric|min:0.01|max:' . $purchase->due,
            'method' => 'required|in:cash,card,mobile',
            'payment_date' => 'required|date',
            'note' => 'nullable|string|max:255',
        ]);

        $purchase->payments()->create($data + ['created_by' => auth()->id()]);
        $purchase->recalculateDue();

        return back()->with('success', 'Payment of ৳' . number_format($data['amount'], 2) . ' recorded.');
    }
}
