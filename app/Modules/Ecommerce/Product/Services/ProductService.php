<?php

namespace App\Modules\Ecommerce\Product\Services;

use App\Modules\Ecommerce\Product\Models\Product;
use App\Modules\Ecommerce\Product\Models\ProductVariant;
use App\Modules\Ecommerce\Product\Repositories\ProductRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductService
{
    public function __construct(
        protected ProductRepository $repository
    ) {}

    public function create(array $data): Product
    {
        return DB::transaction(function () use ($data) {
            // Generate slug if not provided
            if (empty($data['slug'])) {
                $data['slug'] = Str::slug($data['name']);
            }

            // Create product
            $product = $this->repository->create($data);

            // Create default variant for simple and grouped products
            if (($product->product_type === 'simple' || $product->product_type === 'grouped') && !empty($data['variant'])) {
                $this->createDefaultVariant($product, $data['variant']);
            }

            // Handle images
            if (!empty($data['images'])) {
                $this->syncImages($product, $data['images'], $data['primary_image_index'] ?? null);
            }

            // Handle grouped products
            if ($product->product_type === 'grouped' && !empty($data['child_products'])) {
                $this->syncGroupedProducts($product, $data['child_products']);
            }

            // Handle temporary variations for variable products
            if ($product->product_type === 'variable' && !empty($data['temp_variations'])) {
                $this->createTempVariations($product, $data['temp_variations']);
            }

            return $product->load(['variants', 'images']);
        });
    }

    public function update(Product $product, array $data): Product
    {
        return DB::transaction(function () use ($product, $data) {
            // Update product
            $this->repository->update($product, $data);

            // Update variant for simple and grouped products
            if (($product->product_type === 'simple' || $product->product_type === 'grouped') && !empty($data['variant'])) {
                $this->updateDefaultVariant($product, $data['variant']);
            }

            // Handle images
            if (isset($data['images'])) {
                $this->syncImages($product, $data['images'], $data['primary_image_index'] ?? null);
            }

            // Handle grouped products
            if ($product->product_type === 'grouped' && isset($data['child_products'])) {
                $this->syncGroupedProducts($product, $data['child_products']);
            }

            return $product->fresh(['variants', 'images']);
        });
    }

    public function delete(Product $product): bool
    {
        return $this->repository->delete($product);
    }

    protected function createDefaultVariant(Product $product, array $variantData): ProductVariant
    {
        $variantData['is_default'] = true;
        
        // Set variant name (same as product name for simple products)
        if (empty($variantData['name'])) {
            $variantData['name'] = $product->name;
        }
        
        // Auto-generate SKU if not provided
        if (empty($variantData['sku'])) {
            $variantData['sku'] = $this->generateSku($product);
        }
        
        // Convert empty strings to null for nullable fields
        $nullableFields = ['sale_price', 'cost_price', 'weight', 'length', 'width', 'height', 'shipping_class'];
        foreach ($nullableFields as $field) {
            if (isset($variantData[$field]) && $variantData[$field] === '') {
                $variantData[$field] = null;
            }
        }

        return $product->variants()->create($variantData);
    }

    protected function updateDefaultVariant(Product $product, array $variantData): void
    {
        // Convert empty strings to null for nullable fields
        $nullableFields = ['sale_price', 'cost_price', 'weight', 'length', 'width', 'height', 'shipping_class'];
        foreach ($nullableFields as $field) {
            if (isset($variantData[$field]) && $variantData[$field] === '') {
                $variantData[$field] = null;
            }
        }
        
        $variant = $product->defaultVariant->first();
        
        if ($variant) {
            $variant->update($variantData);
        } else {
            $this->createDefaultVariant($product, $variantData);
        }
    }

    protected function syncImages(Product $product, array $imagesData, ?int $primaryIndex = null): void
    {
        if (empty($imagesData)) {
            return;
        }

        // Handle uploaded files
        $imagePaths = [];
        foreach ($imagesData as $index => $image) {
            if (is_object($image) && method_exists($image, 'store')) {
                // It's an uploaded file
                $path = $image->store('products', 'public');
                $imagePaths[] = $path;
            } elseif (is_string($image)) {
                // It's already a path
                $imagePaths[] = $image;
            }
        }

        // Delete existing images if we're replacing them
        if (!empty($imagePaths)) {
            $product->images()->delete();

            // Add new images
            foreach ($imagePaths as $index => $imagePath) {
                $product->images()->create([
                    'image_path' => $imagePath,
                    'is_primary' => $primaryIndex !== null ? ($index === $primaryIndex) : ($index === 0),
                    'sort_order' => $index,
                ]);
            }
        }
    }

    protected function syncGroupedProducts(Product $product, array $childProducts): void
    {
        $syncData = [];
        
        foreach ($childProducts as $index => $childData) {
            // Handle both array format and simple ID format
            if (is_array($childData)) {
                $syncData[$childData['id']] = [
                    'quantity' => $childData['quantity'] ?? 1,
                    'sort_order' => $index,
                ];
            } else {
                // Simple product ID
                $syncData[$childData] = [
                    'quantity' => 1,
                    'sort_order' => $index,
                ];
            }
        }

        $product->childProducts()->sync($syncData);
    }

    protected function generateSku(Product $product): string
    {
        $prefix = strtoupper(substr($product->name, 0, 3));
        $random = strtoupper(Str::random(6));
        
        return $prefix . '-' . $random;
    }

    public function createVariant(Product $product, array $data): ProductVariant
    {
        if (empty($data['sku'])) {
            $data['sku'] = $this->generateSku($product) . '-' . Str::random(4);
        }

        return $product->variants()->create($data);
    }

    public function updateVariant(ProductVariant $variant, array $data): ProductVariant
    {
        $variant->update($data);
        return $variant->fresh();
    }

    public function deleteVariant(ProductVariant $variant): bool
    {
        // Don't allow deleting the last variant
        if ($variant->product->variants()->count() <= 1) {
            return false;
        }

        return $variant->delete();
    }

    public function toggleFeatured(Product $product): Product
    {
        $product->update(['is_featured' => !$product->is_featured]);
        return $product->fresh();
    }

    public function toggleActive(Product $product): Product
    {
        $product->update(['is_active' => !$product->is_active]);
        return $product->fresh();
    }

    protected function createTempVariations(Product $product, array $variations): void
    {
        foreach ($variations as $index => $variationData) {
            $variantData = [
                'sku' => $variationData['sku'] ?? $this->generateSku($product) . '-' . Str::random(4),
                'price' => $variationData['price'] ?? 0,
                'sale_price' => $variationData['sale_price'] ?? null,
                'cost_price' => $variationData['cost_price'] ?? null,
                'stock_quantity' => $variationData['stock_quantity'] ?? 0,
                'low_stock_alert' => $variationData['low_stock_alert'] ?? 5,
                'weight' => $variationData['weight'] ?? null,
                'length' => $variationData['length'] ?? null,
                'width' => $variationData['width'] ?? null,
                'height' => $variationData['height'] ?? null,
                'is_default' => false,
            ];

            $variant = $product->variants()->create($variantData);

            // Attach attribute values if provided
            if (!empty($variationData['attributes'])) {
                foreach ($variationData['attributes'] as $attribute) {
                    if (isset($attribute['attribute_id']) && isset($attribute['value_id'])) {
                        $variant->attributeValues()->attach($attribute['value_id'], [
                            'product_attribute_id' => $attribute['attribute_id'],
                        ]);
                    }
                }
            }
        }
    }
}
