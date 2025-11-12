<?php

namespace App\Modules\Stock\Models;

use App\Models\User;
use App\Modules\Ecommerce\Order\Models\Order;
use App\Modules\Ecommerce\Product\Models\Product;
use App\Modules\Ecommerce\Product\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_number',
        'product_id',
        'variant_id',
        'warehouse_id',
        'type',
        'quantity',
        'quantity_before',
        'quantity_after',
        'unit_cost',
        'total_cost',
        'reason',
        'notes',
        'order_id',
        'supplier_id',
        'transfer_to_warehouse_id',
        'created_by',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'quantity_before' => 'integer',
        'quantity_after' => 'integer',
        'unit_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    const TYPE_IN = 'in';
    const TYPE_OUT = 'out';
    const TYPE_ADJUSTMENT = 'adjustment';
    const TYPE_TRANSFER = 'transfer';
    const TYPE_RETURN = 'return';
    const TYPE_DAMAGED = 'damaged';
    const TYPE_LOST = 'lost';

    /**
     * Get the product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the variant
     */
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    /**
     * Get the warehouse
     */
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * Get the supplier
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Get the order
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the transfer warehouse
     */
    public function transferToWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'transfer_to_warehouse_id');
    }

    /**
     * Get the user who created this movement
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who approved this movement
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Generate reference number
     */
    public static function generateReferenceNumber($type)
    {
        $prefix = strtoupper(substr($type, 0, 3));
        $date = now()->format('Ymd');
        $lastMovement = static::whereDate('created_at', today())
            ->where('type', $type)
            ->latest()
            ->first();

        $number = $lastMovement ? (intval(substr($lastMovement->reference_number, -4)) + 1) : 1;

        return $prefix . '-' . $date . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Scope for specific type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope for specific warehouse
     */
    public function scopeInWarehouse($query, $warehouseId)
    {
        return $query->where('warehouse_id', $warehouseId);
    }

    /**
     * Scope for specific product
     */
    public function scopeForProduct($query, $productId, $variantId = null)
    {
        $query->where('product_id', $productId);

        if ($variantId) {
            $query->where('variant_id', $variantId);
        }

        return $query;
    }

    /**
     * Check if approved
     */
    public function isApproved()
    {
        return !is_null($this->approved_at);
    }
}
