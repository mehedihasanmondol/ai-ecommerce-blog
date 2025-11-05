<?php

namespace App\Modules\Ecommerce\Product\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'sku',
        'name',
        'is_default',
        'price',
        'sale_price',
        'cost_price',
        'stock_quantity',
        'low_stock_alert',
        'manage_stock',
        'stock_status',
        'weight',
        'length',
        'width',
        'height',
        'image',
        'shipping_class',
        'is_active',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_active' => 'boolean',
        'manage_stock' => 'boolean',
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'weight' => 'decimal:2',
        'length' => 'decimal:2',
        'width' => 'decimal:2',
        'height' => 'decimal:2',
        'stock_quantity' => 'integer',
        'low_stock_alert' => 'integer',
    ];

    // Relationships
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(
            ProductAttribute::class,
            'product_variant_attributes',
            'variant_id',
            'attribute_id'
        )->withPivot('attribute_value_id');
    }

    public function attributeValues(): BelongsToMany
    {
        return $this->belongsToMany(
            ProductAttributeValue::class,
            'product_variant_attributes',
            'product_variant_id',
            'product_attribute_value_id'
        )->withPivot('product_attribute_id')->withTimestamps();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock_status', 'in_stock')
            ->where('stock_quantity', '>', 0);
    }

    public function scopeLowStock($query)
    {
        return $query->whereColumn('stock_quantity', '<=', 'low_stock_alert')
            ->where('stock_quantity', '>', 0);
    }

    // Accessors
    public function getDisplayPriceAttribute()
    {
        return $this->sale_price ?? $this->price;
    }

    public function getDiscountPercentageAttribute()
    {
        if (!$this->sale_price) {
            return 0;
        }
        
        return round((($this->price - $this->sale_price) / $this->price) * 100);
    }

    public function getIsLowStockAttribute(): bool
    {
        return $this->manage_stock && $this->stock_quantity <= $this->low_stock_alert && $this->stock_quantity > 0;
    }

    public function getIsOutOfStockAttribute(): bool
    {
        return $this->manage_stock && $this->stock_quantity <= 0;
    }

    // Methods
    public function decreaseStock(int $quantity): bool
    {
        if (!$this->manage_stock) {
            return true;
        }

        if ($this->stock_quantity < $quantity) {
            return false;
        }

        $this->decrement('stock_quantity', $quantity);
        $this->updateStockStatus();
        
        return true;
    }

    public function increaseStock(int $quantity): void
    {
        if (!$this->manage_stock) {
            return;
        }

        $this->increment('stock_quantity', $quantity);
        $this->updateStockStatus();
    }

    protected function updateStockStatus(): void
    {
        if ($this->stock_quantity <= 0) {
            $this->update(['stock_status' => 'out_of_stock']);
        } else {
            $this->update(['stock_status' => 'in_stock']);
        }
    }
}
