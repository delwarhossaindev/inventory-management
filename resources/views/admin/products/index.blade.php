@extends('layouts.app')
@section('title', 'Products')
@section('heading', 'Products')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white">
        <form method="GET" class="row g-2 align-items-center">
            <div class="col-md-4">
                <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="Search name, model, slug...">
            </div>
            <div class="col-md-3">
                <select name="main_category_id" class="form-select form-select-sm">
                    <option value="">All Main Categories</option>
                    @foreach ($mains as $m)
                        <option value="{{ $m->id }}" @selected(request('main_category_id') == $m->id)>{{ $m->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Status</option>
                    <option value="active" @selected(request('status') === 'active')>Active</option>
                    <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
                </select>
            </div>
            <div class="col-md-3 text-end">
                <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-search"></i></button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
                @can('view products')<a href="{{ route('admin.products.labels') }}" class="btn btn-sm btn-outline-dark"><i class="bi bi-upc"></i> Labels</a>@endcan
                @can('edit products')<a href="{{ route('admin.products.bulk-pricing') }}" class="btn btn-sm btn-outline-success"><i class="bi bi-tags"></i> Bulk Pricing</a>@endcan
                @can('create products')<a href="{{ route('admin.products.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg"></i> Add</a>@endcan
            </div>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th><th>Image</th><th>Name</th><th>Model</th>
                    <th>Main</th><th>Category</th><th>Sub</th><th>Status</th><th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td class="text-muted">{{ $product->id }}</td>
                        <td>
                            @if ($product->image_url)
                                <img src="{{ $product->image_url }}" alt="" width="42" height="42" class="rounded object-fit-cover" style="object-fit:cover;">
                            @else
                                <span class="text-muted"><i class="bi bi-image"></i></span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.products.show', $product) }}" class="fw-semibold text-decoration-none">{{ $product->name }}</a>
                            <div class="small text-muted">{{ $product->slug }}</div>
                        </td>
                        <td>{{ $product->model ?: '—' }}</td>
                        <td class="small">{{ optional($product->mainCategory)->name ?: '—' }}</td>
                        <td class="small">{{ optional($product->category)->name ?: '—' }}</td>
                        <td class="small">{{ optional($product->subCategory)->name ?: '—' }}</td>
                        <td><span class="badge bg-{{ $product->status === 'active' ? 'success' : 'secondary' }}">{{ ucfirst($product->status) }}</span></td>
                        <td class="text-end text-nowrap">
                            @can('edit products')<a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>@endcan
                            @can('delete products')
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this product?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="text-center text-muted py-4">No products found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white">{{ $products->links() }}</div>
</div>
@endsection
