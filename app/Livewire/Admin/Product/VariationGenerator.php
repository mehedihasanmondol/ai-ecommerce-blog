<?php

namespace App\Livewire\Admin\Product;

use App\Modules\Ecommerce\Product\Models\ProductAttribute;
use App\Modules\Ecommerce\Product\Models\ProductVariant;
use Livewire\Component;
use Illuminate\Support\Str;

class VariationGenerator extends Component
{
    public $productId;
    public $selectedAttributes = [];
    public $selectedValues = [];
    public $variations = [];
    public $generatedVariations = [];
    public $showGenerator = false;

    protected $listeners = ['variationsGenerated' => 'loadVariations'];

    public function mount($productId = null)
    {
        $this->productId = $productId;
        if ($productId) {
            $this->loadVariations();
        }
    }

    public function loadVariations()
    {
        if ($this->productId) {
            $this->generatedVariations = ProductVariant::where('product_id', $this->productId)
                ->where('is_default', false)
                ->with('attributeValues.attribute')
                ->get()
                ->toArray();
        }
    }

    public function toggleGenerator()
    {
        $this->showGenerator = !$this->showGenerator;
    }

    public function addAttribute()
    {
        $this->selectedAttributes[] = [
            'attribute_id' => '',
            'value_ids' => []
        ];
    }

    public function removeAttribute($index)
    {
        unset($this->selectedAttributes[$index]);
        $this->selectedAttributes = array_values($this->selectedAttributes);
    }

    public function generateVariations()
    {
        $this->validate([
            'selectedAttributes' => 'required|array|min:1',
            'selectedAttributes.*.attribute_id' => 'required|exists:product_attributes,id',
            'selectedAttributes.*.value_ids' => 'required|array|min:1',
        ]);

        // Generate all combinations
        $combinations = $this->generateCombinations();
        
        $this->variations = [];
        foreach ($combinations as $combination) {
            $variantName = implode(' - ', array_column($combination, 'value'));
            $this->variations[] = [
                'name' => $variantName,
                'sku' => '',
                'price' => '',
                'sale_price' => '',
                'stock_quantity' => 0,
                'attributes' => $combination,
                'enabled' => true,
            ];
        }

        session()->flash('success', count($this->variations) . ' variations generated!');
    }

    private function generateCombinations()
    {
        $attributeValues = [];
        
        foreach ($this->selectedAttributes as $selected) {
            $attribute = ProductAttribute::with('values')->find($selected['attribute_id']);
            $values = $attribute->values->whereIn('id', $selected['value_ids'])->toArray();
            
            $attributeValues[] = array_map(function($value) use ($attribute) {
                return [
                    'attribute_id' => $attribute->id,
                    'attribute_name' => $attribute->name,
                    'value_id' => $value['id'],
                    'value' => $value['value'],
                ];
            }, $values);
        }

        return $this->cartesianProduct($attributeValues);
    }

    private function cartesianProduct($arrays)
    {
        $result = [[]];
        foreach ($arrays as $key => $values) {
            $append = [];
            foreach ($result as $product) {
                foreach ($values as $item) {
                    $product[$key] = $item;
                    $append[] = $product;
                }
            }
            $result = $append;
        }
        return $result;
    }

    public function saveVariations()
    {
        if (!$this->productId) {
            session()->flash('error', 'Please save the product first before adding variations.');
            return;
        }

        foreach ($this->variations as $variation) {
            if (!$variation['enabled']) continue;

            $variant = ProductVariant::create([
                'product_id' => $this->productId,
                'name' => $variation['name'],
                'sku' => $variation['sku'] ?: Str::random(8),
                'price' => $variation['price'] ?: 0,
                'sale_price' => $variation['sale_price'] ?: null,
                'stock_quantity' => $variation['stock_quantity'] ?: 0,
                'is_default' => false,
            ]);

            // Attach attribute values (you'll need a pivot table for this)
            // variant_attribute_values table
        }

        $this->reset('variations', 'selectedAttributes', 'showGenerator');
        $this->loadVariations();
        session()->flash('success', 'Variations saved successfully!');
    }

    public function deleteVariation($variationId)
    {
        ProductVariant::find($variationId)?->delete();
        $this->loadVariations();
        session()->flash('success', 'Variation deleted successfully!');
    }

    public function render()
    {
        $attributes = ProductAttribute::with('values')->active()->orderBy('name')->get();
        
        return view('livewire.admin.product.variation-generator', [
            'attributes' => $attributes,
        ]);
    }
}
