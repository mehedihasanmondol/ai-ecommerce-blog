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
                $this->syncImages($product, $data['images']);
            }

            // Handle grouped products
            if ($product->product_type === 'grouped' && !empty($data['child_products'])) {
                $this->syncGroupedProducts($product, $data['child_products']);
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
                $this->syncImages($product, $data['images']);
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

        return $product->variants()->create($variantData);
    }

    protected function updateDefaultVariant(Product $product, array $variantData): void
    {
        $variant = $product->defaultVariant->first();
        
        if ($variant) {
            $variant->update($variantData);
        } else {
            $this->createDefaultVariant($product, $variantData);
        }
    }

    protected function syncImages(Product $product, array $images): void
    {
        // Delete existing images
        $product->images()->delete();

        // Add new images
        foreach ($images as $index => $imagePath) {
            $product->images()->create([
                'image_path' => $imagePath,
                'is_primary' => $index === 0,
                'sort_order' => $index,
            ]);
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
}
