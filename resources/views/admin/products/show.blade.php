@extends('layouts.app')
@section('title', $product->name)
@section('heading', 'Product Details')

@section('content')
<div class="d-flex justify-content-end mb-3 gap-2">
    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-primary"><i class="bi bi-pencil me-1"></i>Edit</a>
    <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-secondary">Back</a>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body text-center">
                @if ($product->image_url)
                    <img src="{{ $product->image_url }}" class="img-fluid rounded border mb-3" alt="{{ $product->name }}">
                @endif
                <h5 class="mb-1">{{ $product->name }}</h5>
                <div class="text-muted small mb-2">{{ $product->slug }}</div>
                <span class="badge bg-{{ $product->status === 'active' ? 'success' : 'secondary' }}">{{ ucfirst($product->status) }}</span>
            </div>
        </div>

        @if ($product->barcode)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <span class="fw-semibold">Barcode</span>
                    <button onclick="printBarcode()" class="btn btn-sm btn-outline-primary"><i class="bi bi-printer me-1"></i>Print label</button>
                </div>
                <div class="card-body text-center" id="barcode-label">
                    <div class="small fw-semibold">{{ $product->name }}</div>
                    @if ($product->sale_price > 0)<div class="small text-muted mb-1">@money($product->sale_price)</div>@endif
                    <svg id="barcode"></svg>
                </div>
            </div>
        @endif

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-semibold">Details</div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between"><span class="text-muted">Model</span><span>{{ $product->model ?: '—' }}</span></li>
                <li class="list-group-item d-flex justify-content-between"><span class="text-muted">SKU</span><span>{{ $product->sku ?: '—' }}</span></li>
                <li class="list-group-item d-flex justify-content-between"><span class="text-muted">Barcode</span><span>{{ $product->barcode ?: '—' }}</span></li>
                <li class="list-group-item d-flex justify-content-between"><span class="text-muted">Main Category</span><span>{{ optional($product->mainCategory)->name ?: '—' }}</span></li>
                <li class="list-group-item d-flex justify-content-between"><span class="text-muted">Category</span><span>{{ optional($product->category)->name ?: '—' }}</span></li>
                <li class="list-group-item d-flex justify-content-between"><span class="text-muted">Sub Category</span><span>{{ optional($product->subCategory)->name ?: '—' }}</span></li>
                <li class="list-group-item d-flex justify-content-between"><span class="text-muted">Created</span><span>{{ $product->created_at?->format('d M Y, h:i A') }}</span></li>
                <li class="list-group-item d-flex justify-content-between"><span class="text-muted">Updated</span><span>{{ $product->updated_at?->format('d M Y, h:i A') }}</span></li>
            </ul>
        </div>
    </div>

    <div class="col-lg-8">
        @php
            $sections = [
                'Short Description' => $product->short_description,
                'Description' => $product->description,
                'Advantages' => $product->advantages,
                'Specifications' => $product->specifications,
            ];
        @endphp
        @foreach ($sections as $label => $html)
            @if (filled($html))
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white fw-semibold">{{ $label }}</div>
                    <div class="card-body">{!! $html !!}</div>
                </div>
            @endif
        @endforeach

        @if (!empty($product->gallery_images))
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-semibold">Gallery</div>
                <div class="card-body">
                    <div class="row g-2">
                        @foreach ($product->gallery_images as $img)
                            <div class="col-4 col-md-3"><img src="{{ $img }}" class="img-fluid rounded border" alt=""></div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        @if (!empty($product->faqs))
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-semibold">FAQs</div>
                <div class="accordion accordion-flush" id="faqAccordion">
                    @foreach ($product->faqs as $i => $faq)
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq{{ $i }}">
                                    {{ $faq['question'] ?? '' }}
                                </button>
                            </h2>
                            <div id="faq{{ $i }}" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted">{{ $faq['answer'] ?? '' }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if ($product->meta_title || $product->meta_description)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-semibold">SEO</div>
                <div class="card-body">
                    <div class="mb-2"><span class="text-muted small d-block">Meta Title</span>{{ $product->meta_title ?: '—' }}</div>
                    <div><span class="text-muted small d-block">Meta Description</span>{{ $product->meta_description ?: '—' }}</div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
@if ($product->barcode)
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const code = @json($product->barcode);
    const isEan13 = /^\d{13}$/.test(code);
    try {
        JsBarcode('#barcode', code, { format: isEan13 ? 'EAN13' : 'CODE128', width: 2, height: 50, fontSize: 14 });
    } catch (e) {
        JsBarcode('#barcode', code, { format: 'CODE128', width: 2, height: 50, fontSize: 14 });
    }
});
function printBarcode() {
    const label = document.getElementById('barcode-label').innerHTML;
    const w = window.open('', '', 'width=400,height=300');
    w.document.write('<html><head><title>Barcode</title></head><body style="text-align:center;font-family:sans-serif">' + label + '</body></html>');
    w.document.close();
    w.focus();
    setTimeout(() => { w.print(); w.close(); }, 300);
}
</script>
@endif
@endpush
