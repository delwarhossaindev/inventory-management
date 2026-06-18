@extends('layouts.app')
@section('title', 'Dashboard')
@section('heading', 'Dashboard')

@section('content')
<div class="row g-3 mb-4">
    @php
        $cards = [
            ["Today's Sales", '৳ ' . number_format($stats['today_sales'], 2), 'bi-cash-coin', 'success', $stats['today_sales_count'] . ' invoices'],
            ['This Month Sales', '৳ ' . number_format($stats['month_sales'], 2), 'bi-graph-up-arrow', 'primary', null],
            ['Stock Value', '৳ ' . number_format($stats['stock_value'], 2), 'bi-clipboard-data', 'info', $stats['products'] . ' products'],
            ['Low Stock', $stats['low_stock'], 'bi-exclamation-triangle', 'danger', 'need restock'],
        ];
    @endphp
    @foreach ($cards as [$label, $value, $icon, $color, $sub])
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded bg-{{ $color }} bg-opacity-10 p-3 me-3">
                        <i class="bi {{ $icon }} fs-4 text-{{ $color }}"></i>
                    </div>
                    <div>
                        <div class="text-muted small">{{ $label }}</div>
                        <div class="fs-5 fw-bold">{{ $value }}</div>
                        @if ($sub)<div class="text-muted" style="font-size:.75rem">{{ $sub }}</div>@endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="row g-3">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span class="fw-semibold">Recent Sales</span>
                <a href="{{ route('admin.pos.index') }}" class="btn btn-sm btn-success"><i class="bi bi-cart-check me-1"></i>New Sale (POS)</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light"><tr><th>Invoice</th><th>Customer</th><th>Total</th><th>Paid</th><th>Date</th></tr></thead>
                    <tbody>
                        @forelse ($recentSales as $sale)
                            <tr>
                                <td><a href="{{ route('admin.sales.show', $sale) }}">{{ $sale->invoice_no }}</a></td>
                                <td class="small">{{ optional($sale->customer)->name ?: 'Walk-in' }}</td>
                                <td>@money($sale->total)</td>
                                <td>@money($sale->paid)</td>
                                <td class="small text-muted">{{ $sale->sale_date?->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted py-4">No sales yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold text-danger"><i class="bi bi-exclamation-triangle me-1"></i>Low Stock Alerts</div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light"><tr><th>Product</th><th class="text-end">Stock</th><th class="text-end">Alert</th></tr></thead>
                    <tbody>
                        @forelse ($lowStock as $p)
                            <tr>
                                <td><a href="{{ route('admin.stock.adjust', $p) }}">{{ $p->name }}</a></td>
                                <td class="text-end"><span class="badge bg-danger">{{ $p->stock_quantity }}</span></td>
                                <td class="text-end text-muted">{{ $p->alert_quantity }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted py-4">All stock levels healthy.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
