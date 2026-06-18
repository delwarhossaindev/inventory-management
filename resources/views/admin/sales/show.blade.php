@extends('layouts.app')
@section('title', $sale->invoice_no)
@section('heading', 'Sale ' . $sale->invoice_no)

@section('content')
<div class="d-flex justify-content-end gap-2 mb-3">
    <a href="{{ route('admin.sales.index') }}" class="btn btn-sm btn-outline-secondary">Back</a>
    <a href="{{ route('admin.pos.index') }}" class="btn btn-sm btn-success"><i class="bi bi-cart-check me-1"></i>New Sale</a>
    <button onclick="window.print()" class="btn btn-sm btn-outline-primary"><i class="bi bi-printer me-1"></i>Print</button>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-6">
                <h5 class="fw-bold mb-1">Sales Invoice</h5>
                <div class="text-muted">{{ $sale->invoice_no }}</div>
                <div class="text-muted small">Date: {{ $sale->sale_date?->format('d M Y') }}</div>
            </div>
            <div class="col-6 text-end">
                <div class="text-muted small">Customer</div>
                <div class="fw-semibold">{{ optional($sale->customer)->name ?: 'Walk-in Customer' }}</div>
                <div class="small text-muted">{{ optional($sale->customer)->phone }}</div>
                <span class="badge bg-light text-dark border mt-1">{{ ucfirst($sale->payment_method) }}</span>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead class="table-light"><tr><th>#</th><th>Product</th><th class="text-end">Qty</th><th class="text-end">Unit Price</th><th class="text-end">Subtotal</th></tr></thead>
                <tbody>
                    @foreach ($sale->items as $i => $item)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ optional($item->product)->name ?: 'Deleted product' }}</td>
                            <td class="text-end">{{ $item->quantity }}</td>
                            <td class="text-end">@money($item->unit_price)</td>
                            <td class="text-end">@money($item->subtotal)</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="row justify-content-end">
            <div class="col-md-5">
                <div class="d-flex justify-content-between py-1"><span class="text-muted">Subtotal</span><span>@money($sale->subtotal)</span></div>
                <div class="d-flex justify-content-between py-1"><span class="text-muted">Discount</span><span>- @money($sale->discount)</span></div>
                <div class="d-flex justify-content-between py-1"><span class="text-muted">Tax</span><span>+ @money($sale->tax)</span></div>
                <div class="d-flex justify-content-between py-1 border-top fw-bold fs-5"><span>Total</span><span>@money($sale->total)</span></div>
                <div class="d-flex justify-content-between py-1"><span class="text-muted">Paid</span><span>@money($sale->paid)</span></div>
                <div class="d-flex justify-content-between py-1"><span class="text-muted">Due</span><span>@money($sale->due)</span></div>
            </div>
        </div>

        @php
            $cogs = $sale->items->sum('cost_total');
            $profit = $sale->subtotal - $cogs;
        @endphp
        <div class="row justify-content-end mt-2">
            <div class="col-md-5">
                <div class="border rounded bg-light p-2">
                    <div class="d-flex justify-content-between py-1 small"><span class="text-muted">Cost of Goods (FIFO)</span><span>@money($cogs)</span></div>
                    <div class="d-flex justify-content-between py-1 fw-semibold {{ $profit >= 0 ? 'text-success' : 'text-danger' }}"><span>Gross Profit</span><span>@money($profit)</span></div>
                </div>
            </div>
        </div>

        @if ($sale->note)
            <div class="mt-3 text-muted small"><strong>Note:</strong> {{ $sale->note }}</div>
        @endif
    </div>
</div>
@endsection
