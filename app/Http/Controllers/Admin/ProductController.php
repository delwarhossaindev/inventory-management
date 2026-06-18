<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view products')->only(['index', 'show', 'labels']);
        $this->middleware('permission:create products')->only(['create', 'store']);
        $this->middleware('permission:edit products')->only(['edit', 'update', 'bulkPricing', 'bulkPricingUpdate']);
        $this->middleware('permission:delete products')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $query = Product::with(['mainCategory', 'category', 'subCategory'])->latest();

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('model', 'like', "%{$q}%")
                    ->orWhere('slug', 'like', "%{$q}%");
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('main_category_id')) {
            $query->where('main_category_id', $request->main_category_id);
        }

        $products = $query->paginate(15)->withQueryString();
        $mains = Category::level(Category::LEVEL_MAIN)->orderBy('name')->get();

        return view('admin.products.index', compact('products', 'mains'));
    }

    public function create()
    {
        return view('admin.products.create', $this->formData(new Product()));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['slug'] = $this->uniqueSlug($data['slug'] ?: $data['name']);
        $data = $this->normalizeJson($request, $data);

        $opening = (int) $request->input('stock_quantity', 0);

        $product = Product::create($data);
        $product->ensureBarcode(); // auto-generate if left blank

        if ($opening > 0) {
            $product->stockIn($opening, (float) $product->purchase_price, 'adjustment', null, 'Opening stock');
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        $product->load(['mainCategory', 'category', 'subCategory']);

        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', $this->formData($product));
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validateData($request);
        $data['slug'] = $this->uniqueSlug($data['slug'] ?: $data['name'], $product->id);
        $data = $this->normalizeJson($request, $data);

        $product->update($data);
        $product->ensureBarcode();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    public function bulkPricing(Request $request)
    {
        $query = Product::with('mainCategory')->orderBy('name');
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(fn ($s) => $s->where('name', 'like', "%{$q}%")
                ->orWhere('sku', 'like', "%{$q}%")
                ->orWhere('model', 'like', "%{$q}%"));
        }
        if ($request->filled('main_category_id')) {
            $query->where('main_category_id', $request->main_category_id);
        }

        return view('admin.products.bulk-pricing', [
            'products' => $query->paginate(30)->withQueryString(),
            'mains' => Category::level(Category::LEVEL_MAIN)->orderBy('name')->get(),
        ]);
    }

    public function bulkPricingUpdate(Request $request)
    {
        $data = $request->validate([
            'rows' => ['required', 'array'],
            'rows.*.purchase_price' => ['nullable', 'numeric', 'min:0'],
            'rows.*.sale_price' => ['nullable', 'numeric', 'min:0'],
            'rows.*.alert_quantity' => ['nullable', 'integer', 'min:0'],
            'rows.*.stock' => ['nullable', 'integer', 'min:0'],
        ]);

        $updated = 0;

        DB::transaction(function () use ($data, &$updated) {
            $products = Product::whereIn('id', array_keys($data['rows']))->get()->keyBy('id');

            foreach ($data['rows'] as $id => $row) {
                $product = $products->get($id);
                if (! $product) {
                    continue;
                }

                $product->purchase_price = $row['purchase_price'] ?? $product->purchase_price;
                $product->sale_price = $row['sale_price'] ?? $product->sale_price;
                $product->alert_quantity = $row['alert_quantity'] ?? $product->alert_quantity;
                $product->save();

                // Stock change goes through FIFO: add a batch or consume oldest first.
                if (isset($row['stock']) && (int) $row['stock'] !== $product->stock_quantity) {
                    $delta = (int) $row['stock'] - $product->stock_quantity;
                    if ($delta > 0) {
                        $product->stockIn($delta, (float) $product->purchase_price, 'adjustment', null, 'Bulk stock update');
                    } else {
                        $product->stockOut(-$delta, 'adjustment', null, 'Bulk stock update');
                    }
                }

                $updated++;
            }
        });

        return back()->with('success', "Updated {$updated} products.");
    }

    public function labels(Request $request)
    {
        $query = Product::whereNotNull('barcode')->orderBy('name');
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(fn ($s) => $s->where('name', 'like', "%{$q}%")
                ->orWhere('sku', 'like', "%{$q}%")
                ->orWhere('barcode', 'like', "%{$q}%"));
        }
        if ($request->filled('main_category_id')) {
            $query->where('main_category_id', $request->main_category_id);
        }

        return view('admin.products.labels', [
            'products' => $query->get(),
            'mains' => Category::level(Category::LEVEL_MAIN)->orderBy('name')->get(),
            'copies' => max(1, min((int) $request->input('copies', 1), 50)),
        ]);
    }

    private function formData(Product $product): array
    {
        return [
            'product' => $product,
            'mains' => Category::level(Category::LEVEL_MAIN)->orderBy('name')->get(),
            // Pre-load children so dropdowns show correctly when editing.
            'categories' => $product->main_category_id
                ? Category::where('parent_id', $product->main_category_id)->orderBy('name')->get()
                : collect(),
            'subCategories' => $product->category_id
                ? Category::where('parent_id', $product->category_id)->orderBy('name')->get()
                : collect(),
        ];
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'model' => ['nullable', 'string', 'max:255'],
            'sku' => ['nullable', 'string', 'max:255'],
            'barcode' => ['nullable', 'string', 'max:255'],
            'purchase_price' => ['nullable', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0'],
            'alert_quantity' => ['nullable', 'integer', 'min:0'],
            'unit' => ['nullable', 'string', 'max:50'],
            'main_category_id' => ['nullable', 'exists:categories,id'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'sub_category_id' => ['nullable', 'exists:categories,id'],
            'status' => ['required', 'in:active,inactive'],
            'image_url' => ['nullable', 'string', 'max:2048'],
            'short_description' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'advantages' => ['nullable', 'string'],
            'specifications' => ['nullable', 'string'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
        ]);
    }

    /**
     * Build gallery_images and faqs arrays from the repeating form inputs.
     */
    private function normalizeJson(Request $request, array $data): array
    {
        $gallery = collect($request->input('gallery_images', []))
            ->map(fn ($url) => trim((string) $url))
            ->filter()
            ->values()
            ->all();
        $data['gallery_images'] = $gallery ?: null;

        $faqs = collect($request->input('faqs', []))
            ->map(fn ($faq) => [
                'question' => trim((string) ($faq['question'] ?? '')),
                'answer' => trim((string) ($faq['answer'] ?? '')),
            ])
            ->filter(fn ($faq) => $faq['question'] !== '' || $faq['answer'] !== '')
            ->values()
            ->all();
        $data['faqs'] = $faqs ?: null;

        return $data;
    }

    private function uniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $base = Str::slug($value);
        $slug = $base;
        $i = 1;

        while (Product::where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }
}
