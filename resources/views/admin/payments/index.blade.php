@extends('layouts.app')
@section('title', 'Payments')
@section('heading', 'Payment History')

@section('content')

{{-- Summary --}}
<div class="row g-3 mb-3">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center">
                <div class="rounded bg-success bg-opacity-10 p-3 me-3"><i class="bi bi-arrow-down-circle fs-4 text-success"></i></div>
                <div>
                    <div class="text-muted small">Receivable (Sales due)</div>
                    <div class="fs-5 fw-bold text-success">@money($receivable)</div>
                    <div class="text-muted" style="font-size:.75rem">{{ $dueSales->count() }} unpaid invoice(s)</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center">
                <div class="rounded bg-danger bg-opacity-10 p-3 me-3"><i class="bi bi-arrow-up-circle fs-4 text-danger"></i></div>
                <div>
                    <div class="text-muted small">Payable (Purchase due)</div>
                    <div class="fs-5 fw-bold text-danger">@money($payable)</div>
                    <div class="text-muted" style="font-size:.75rem">{{ $duePurchases->count() }} unpaid bill(s)</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Outstanding sales (receivable) --}}
@if ($dueSales->count())
<div class="card border-0 shadow-sm mb-3">
    <div class="card-header bg-white fw-semibold text-success"><i class="bi bi-cash-coin me-1"></i>Outstanding — Collect from Customers</div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr><th>Invoice</th><th>Customer</th><th>Date</th><th class="text-end">Total</th><th class="text-end">Due</th><th class="text-end">Action</th></tr>
            </thead>
            <tbody>
                @foreach ($dueSales as $s)
                    <tr>
                        <td><a href="{{ route('admin.sales.show', $s) }}" class="fw-semibold text-decoration-none">{{ $s->invoice_no }}</a></td>
                        <td class="small">{{ optional($s->customer)->name ?: 'Walk-in' }}</td>
                        <td class="small text-muted">{{ $s->sale_date?->format('d M Y') }}</td>
                        <td class="text-end">@money($s->total)</td>
                        <td class="text-end fw-semibold text-danger">@money($s->due)</td>
                        <td class="text-end"><a href="{{ route('admin.sales.show', $s) }}#collect" class="btn btn-sm btn-success"><i class="bi bi-cash me-1"></i>Collect</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- Outstanding purchases (payable) --}}
@if ($duePurchases->count())
<div class="card border-0 shadow-sm mb-3">
    <div class="card-header bg-white fw-semibold text-danger"><i class="bi bi-cash-stack me-1"></i>Outstanding — Pay to Suppliers</div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr><th>Invoice</th><th>Supplier</th><th>Date</th><th class="text-end">Total</th><th class="text-end">Due</th><th class="text-end">Action</th></tr>
            </thead>
            <tbody>
                @foreach ($duePurchases as $p)
                    <tr>
                        <td><a href="{{ route('admin.purchases.show', $p) }}" class="fw-semibold text-decoration-none">{{ $p->invoice_no }}</a></td>
                        <td class="small">{{ optional($p->supplier)->name ?: '—' }}</td>
                        <td class="small text-muted">{{ $p->purchase_date?->format('d M Y') }}</td>
                        <td class="text-end">@money($p->total)</td>
                        <td class="text-end fw-semibold text-danger">@money($p->due)</td>
                        <td class="text-end"><a href="{{ route('admin.purchases.show', $p) }}#collect" class="btn btn-sm btn-danger"><i class="bi bi-cash me-1"></i>Pay</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<div class="card border-0 shadow-sm mb-3">
    <div class="card-header bg-white fw-semibold"><i class="bi bi-clock-history me-1"></i>Payment History</div>
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-auto">
                <select name="type" class="form-select form-select-sm">
                    <option value="">All Types</option>
                    <option value="sale" @selected(request('type') === 'sale')>Sale Due Collection</option>
                    <option value="purchase" @selected(request('type') === 'purchase')>Purchase Payment</option>
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-sm btn-primary"><i class="bi bi-funnel me-1"></i>Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Invoice</th>
                    <th class="text-end">Amount</th>
                    <th>Method</th>
                    <th>Note</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($payments as $payment)
                    @php
                        $isSale = $payment->payable_type === \App\Models\Sale::class;
                        $label = $isSale ? 'Sale' : 'Purchase';
                        $invoice = optional($payment->payable)->invoice_no ?? '—';
                        $route = $payment->payable
                            ? ($isSale ? route('admin.sales.show', $payment->payable_id) : route('admin.purchases.show', $payment->payable_id))
                            : '#';
                    @endphp
                    <tr>
                        <td class="small">{{ $payment->payment_date->format('d M Y') }}</td>
                        <td><span class="badge bg-{{ $isSale ? 'success' : 'info' }}">{{ $label }}</span></td>
                        <td><a href="{{ $route }}">{{ $invoice }}</a></td>
                        <td class="text-end fw-semibold">@money($payment->amount)</td>
                        <td class="small">{{ ucfirst($payment->method) }}</td>
                        <td class="small text-muted">{{ $payment->note ?: '—' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No payments recorded yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($payments->hasPages())
        <div class="card-footer bg-white">{{ $payments->links() }}</div>
    @endif
</div>
@endsection
