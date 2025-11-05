<?php

namespace App\Livewire\Admin\Product;

use App\Modules\Ecommerce\Brand\Models\Brand;
use App\Modules\Ecommerce\Category\Models\Category;
use App\Modules\Ecommerce\Product\Models\Product;
use App\Modules\Ecommerce\Product\Services\ProductService;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProductForm extends Component
{
    use WithFileUploads;

    public ?Product $product = null;
    public bool $isEdit = false;

    // Product Info
    public $name = '';
    public $slug = '';
    public $description = '';
    public $short_description = '';
    public $category_id = '';
    public $brand_id = '';
    public $product_type = 'simple';
    public $is_featured = false;
    public $is_active = true;

    // Affiliate
    public $external_url = '';
    public $button_text = 'Buy Now';

    // SEO
    public $meta_title = '';
    public $meta_description = '';
    public $meta_keywords = '';

    // Variant (for simple products)
    public $variant = [
        'sku' => '',
        'price' => '',
        'sale_price' => '',
        'cost_price' => '',
        'stock_quantity' => 0,
        'low_stock_alert' => 5,
        'weight' => '',
        'length' => '',
        'width' => '',
        'height' => '',
        'shipping_class' => '',
    ];

    // Images
    public $images = [];
    public $existingImages = [];

    // Grouped Products
    public $selectedChildProducts = [];
    public $childProductSearch = '';

    // UI State
    public $currentStep = 1;
    public $showVariantSection = false;

    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug,' . ($this->product->id ?? 'NULL'),
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'product_type' => 'required|in:simple,grouped,affiliate,variable',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];

        if ($this->product_type === 'simple' || $this->product_type === 'grouped') {
            $rules['variant.price'] = 'required|numeric|min:0';
            $rules['variant.sale_price'] = 'nullable|numeric|min:0|lt:variant.price';
            $rules['variant.stock_quantity'] = 'required|integer|min:0';
            $rules['variant.sku'] = 'nullable|string|max:100';
            $rules['variant.cost_price'] = 'nullable|numeric|min:0';
            $rules['variant.low_stock_alert'] = 'nullable|integer|min:0';
            $rules['variant.weight'] = 'nullable|numeric|min:0';
            $rules['variant.length'] = 'nullable|numeric|min:0';
            $rules['variant.width'] = 'nullable|numeric|min:0';
            $rules['variant.height'] = 'nullable|numeric|min:0';
        }

        if ($this->product_type === 'affiliate') {
            $rules['external_url'] = 'required|url';
            $rules['button_text'] = 'required|string|max:50';
        }

        return $rules;
    }

    protected function messages()
    {
        return [
            'variant.price.required' => 'Regular price is required.',
            'variant.sale_price.lt' => 'Sale price must be less than regular price.',
            'variant.stock_quantity.required' => 'Stock quantity is required.',
        ];
    }

    public function mount(?Product $product = null)
    {
        if ($product && $product->exists) {
            $this->isEdit = true;
            $this->product = $product;
            $this->fill([
                'name' => $product->name,
                'slug' => $product->slug,
                'description' => $product->description,
                'short_description' => $product->short_description,
                'category_id' => $product->category_id,
                'brand_id' => $product->brand_id,
                'product_type' => $product->product_type,
                'is_featured' => $product->is_featured,
                'is_active' => $product->is_active,
                'external_url' => $product->external_url,
                'button_text' => $product->button_text ?? 'Buy Now',
                'meta_title' => $product->meta_title,
                'meta_description' => $product->meta_description,
                'meta_keywords' => $product->meta_keywords,
            ]);

            if ($product->product_type === 'simple') {
                $defaultVariant = $product->variants->where('is_default', true)->first();
                if ($defaultVariant) {
                    $this->variant = [
                        'sku' => $defaultVariant->sku,
                        'price' => $defaultVariant->price,
                        'sale_price' => $defaultVariant->sale_price,
                        'cost_price' => $defaultVariant->cost_price,
                        'stock_quantity' => $defaultVariant->stock_quantity,
                        'low_stock_alert' => $defaultVariant->low_stock_alert,
                        'weight' => $defaultVariant->weight,
                        'length' => $defaultVariant->length,
                        'width' => $defaultVariant->width,
                        'height' => $defaultVariant->height,
                        'shipping_class' => $defaultVariant->shipping_class,
                    ];
                }
            }

            $this->existingImages = $product->images->pluck('image_path')->toArray();
        }
    }

    public function updatedName($value)
    {
        if (!$this->isEdit) {
            $this->slug = \Illuminate\Support\Str::slug($value);
        }
    }

    public function updatedProductType($value)
    {
        $this->showVariantSection = $value === 'simple';
    }

    public function save(ProductService $service)
    {
        try {
            $this->validate();

            $data = [
                'name' => $this->name,
                'slug' => $this->slug,
                'description' => $this->description,
                'short_description' => $this->short_description,
                'category_id' => $this->category_id ?: null,
                'brand_id' => $this->brand_id ?: null,
                'product_type' => $this->product_type,
                'is_featured' => $this->is_featured,
                'is_active' => $this->is_active,
                'external_url' => $this->external_url,
                'button_text' => $this->button_text,
                'meta_title' => $this->meta_title,
                'meta_description' => $this->meta_description,
                'meta_keywords' => $this->meta_keywords,
            ];

            if ($this->product_type === 'simple' || $this->product_type === 'grouped') {
                $data['variant'] = $this->variant;
            }

            if ($this->product_type === 'grouped') {
                $data['child_products'] = $this->selectedChildProducts;
            }

            if ($this->isEdit) {
                $product = $service->update($this->product, $data);
                session()->flash('success', 'Product updated successfully!');
            } else {
                $product = $service->create($data);
                session()->flash('success', 'Product created successfully!');
            }

            return redirect()->route('admin.products.index');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Re-throw validation exceptions so Livewire can handle them
            throw $e;
        } catch (\Exception $e) {
            \Log::error('Product save error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Error saving product: ' . $e->getMessage());
            $this->dispatch('error', message: 'Error saving product: ' . $e->getMessage());
        }
    }

    public function nextStep()
    {
        $this->currentStep++;
    }

    public function previousStep()
    {
        $this->currentStep--;
    }

    public function addChildProduct($productId)
    {
        if (!in_array($productId, $this->selectedChildProducts)) {
            $this->selectedChildProducts[] = $productId;
            $this->childProductSearch = '';
        }
    }

    public function removeChildProduct($index)
    {
        unset($this->selectedChildProducts[$index]);
        $this->selectedChildProducts = array_values($this->selectedChildProducts);
    }

    public function render()
    {
        return view('livewire.admin.product.product-form-enhanced', [
            'categories' => Category::orderBy('name')->get(),
            'brands' => Brand::orderBy('name')->get(),
        ]);
    }
}
