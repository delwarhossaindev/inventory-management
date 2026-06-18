@extends('layouts.app')
@section('title', 'Purchases')
@section('heading', 'Purchases')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white">
        <form method="GET" class="row g-2">
            <div class="col-md-5">
                <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="Search invoice no...">
            </div>
            <div class="col text-end">
                <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-search"></i></button>
                <a href="{{ route('admin.purchases.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg"></i> New Purchase</a>
            </div>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light"><tr><th>Invoice</th><th>Supplier</th><th>Date</th><th>Total</th><th>Paid</th><th>Due</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
            <tbody>
                @forelse ($purchases as $p)
                    <tr>
                        <td><a href="{{ route('admin.purchases.show', $p) }}" class="fw-semibold">{{ $p->invoice_no }}</a></td>
                        <td class="small">{{ optional($p->supplier)->name ?: '—' }}</td>
                        <td class="small text-muted">{{ $p->purchase_date?->format('d M Y') }}</td>
                        <td>@money($p->total)</td>
                        <td>@money($p->paid)</td>
                        <td>@money($p->due)</td>
                        <td><span class="badge bg-{{ $p->status === 'received' ? 'success' : 'warning text-dark' }}">{{ ucfirst($p->status) }}</span></td>
                        <td class="text-end text-nowrap">
                            <a href="{{ route('admin.purchases.show', $p) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a>
                            <form action="{{ route('admin.purchases.destroy', $p) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete purchase and reverse stock?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">No purchases yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white">{{ $purchases->links() }}</div>
</div>
@endsection
