@extends('layouts.app')
@section('title', 'Payments')
@section('heading', 'Payment History')

@section('content')
<div class="card border-0 shadow-sm mb-3">
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
