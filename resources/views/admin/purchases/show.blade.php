@extends('layouts.app')
@section('title', $purchase->invoice_no)
@section('heading', 'Purchase ' . $purchase->invoice_no)

@section('content')
<div class="d-flex justify-content-end gap-2 mb-3">
    <a href="{{ route('admin.purchases.index') }}" class="btn btn-sm btn-outline-secondary">Back</a>
    <button onclick="window.print()" class="btn btn-sm btn-outline-primary"><i class="bi bi-printer me-1"></i>Print</button>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-6">
                <h5 class="fw-bold mb-1">Purchase Invoice</h5>
                <div class="text-muted">{{ $purchase->invoice_no }}</div>
                <div class="text-muted small">Date: {{ $purchase->purchase_date?->format('d M Y') }}</div>
                <span class="badge bg-{{ $purchase->status === 'received' ? 'success' : 'warning text-dark' }}">{{ ucfirst($purchase->status) }}</span>
            </div>
            <div class="col-6 text-end">
                <div class="text-muted small">Supplier</div>
                <div class="fw-semibold">{{ optional($purchase->supplier)->name ?: '—' }}</div>
                <div class="small text-muted">{{ optional($purchase->supplier)->phone }}</div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead class="table-light"><tr><th>#</th><th>Product</th><th class="text-end">Qty</th><th class="text-end">Unit Cost</th><th class="text-end">Subtotal</th></tr></thead>
                <tbody>
                    @foreach ($purchase->items as $i => $item)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ optional($item->product)->name ?: 'Deleted product' }}</td>
                            <td class="text-end">{{ $item->quantity }}</td>
                            <td class="text-end">@money($item->unit_cost)</td>
                            <td class="text-end">@money($item->subtotal)</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="row justify-content-end">
            <div class="col-md-5">
                <div class="d-flex justify-content-between py-1"><span class="text-muted">Subtotal</span><span>@money($purchase->subtotal)</span></div>
                <div class="d-flex justify-content-between py-1"><span class="text-muted">Discount</span><span>- @money($purchase->discount)</span></div>
                <div class="d-flex justify-content-between py-1"><span class="text-muted">Tax</span><span>+ @money($purchase->tax)</span></div>
                <div class="d-flex justify-content-between py-1 border-top fw-bold fs-5"><span>Total</span><span>@money($purchase->total)</span></div>
                <div class="d-flex justify-content-between py-1"><span class="text-muted">Paid</span><span>@money($purchase->paid)</span></div>
                <div class="d-flex justify-content-between py-1"><span class="text-muted">Due</span><span>@money($purchase->due)</span></div>
            </div>
        </div>

        @if ($purchase->note)
            <div class="mt-3 text-muted small"><strong>Note:</strong> {{ $purchase->note }}</div>
        @endif
    </div>
</div>
@endsection
