<?php

namespace App\Modules\Stock\Services;

use App\Modules\Stock\Models\StockMovement;
use App\Modules\Stock\Repositories\StockMovementRepository;
use App\Modules\Stock\Repositories\StockAlertRepository;
use App\Modules\Ecommerce\Product\Models\ProductVariant;
use Illuminate\Support\Facades\DB;

class StockService
{
    protected $stockMovementRepo;
    protected $stockAlertRepo;

    public function __construct(
        StockMovementRepository $stockMovementRepo,
        StockAlertRepository $stockAlertRepo
    ) {
        $this->stockMovementRepo = $stockMovementRepo;
        $this->stockAlertRepo = $stockAlertRepo;
    }

    /**
     * Add stock (purchase, return from customer)
     */
    public function addStock(array $data)
    {
        return DB::transaction(function () use ($data) {
            $currentStock = $this->getCurrentStock(
                $data['product_id'],
                $data['variant_id'] ?? null,
                $data['warehouse_id']
            );

            $movement = $this->stockMovementRepo->create([
                'reference_number' => StockMovement::generateReferenceNumber('in'),
                'product_id' => $data['product_id'],
                'variant_id' => $data['variant_id'] ?? null,
                'warehouse_id' => $data['warehouse_id'],
                'type' => StockMovement::TYPE_IN,
                'quantity' => $data['quantity'],
                'quantity_before' => $currentStock,
                'quantity_after' => $currentStock + $data['quantity'],
                'unit_cost' => $data['unit_cost'] ?? null,
                'total_cost' => ($data['unit_cost'] ?? 0) * $data['quantity'],
                'reason' => $data['reason'] ?? null,
                'notes' => $data['notes'] ?? null,
                'supplier_id' => $data['supplier_id'] ?? null,
                'created_by' => auth()->id(),
            ]);

            // Update variant stock
            $this->updateVariantStock($data['product_id'], $data['variant_id'] ?? null, $data['quantity'], true);

            // Check and resolve stock alerts
            $this->checkStockAlerts($data['product_id'], $data['variant_id'] ?? null, $data['warehouse_id']);

            return $movement;
        });
    }

    /**
     * Remove stock (sale, damaged, lost)
     */
    public function removeStock(array $data)
    {
        return DB::transaction(function () use ($data) {
            $currentStock = $this->getCurrentStock(
                $data['product_id'],
                $data['variant_id'] ?? null,
                $data['warehouse_id']
            );

            if ($currentStock < $data['quantity']) {
                throw new \Exception('Insufficient stock');
            }

            $movement = $this->stockMovementRepo->create([
                'reference_number' => StockMovement::generateReferenceNumber($data['type']),
                'product_id' => $data['product_id'],
                'variant_id' => $data['variant_id'] ?? null,
                'warehouse_id' => $data['warehouse_id'],
                'type' => $data['type'], // out, damaged, lost
                'quantity' => $data['quantity'],
                'quantity_before' => $currentStock,
                'quantity_after' => $currentStock - $data['quantity'],
                'unit_cost' => $data['unit_cost'] ?? null,
                'total_cost' => ($data['unit_cost'] ?? 0) * $data['quantity'],
                'reason' => $data['reason'] ?? null,
                'notes' => $data['notes'] ?? null,
                'order_id' => $data['order_id'] ?? null,
                'created_by' => auth()->id(),
            ]);

            // Update variant stock
            $this->updateVariantStock($data['product_id'], $data['variant_id'] ?? null, -$data['quantity'], true);

            // Check for low stock alerts
            $this->checkStockAlerts($data['product_id'], $data['variant_id'] ?? null, $data['warehouse_id']);

            return $movement;
        });
    }

    /**
     * Adjust stock (manual correction)
     */
    public function adjustStock(array $data)
    {
        return DB::transaction(function () use ($data) {
            $currentStock = $this->getCurrentStock(
                $data['product_id'],
                $data['variant_id'] ?? null,
                $data['warehouse_id']
            );

            $adjustment = $data['new_quantity'] - $currentStock;

            $movement = $this->stockMovementRepo->create([
                'reference_number' => StockMovement::generateReferenceNumber('adjustment'),
                'product_id' => $data['product_id'],
                'variant_id' => $data['variant_id'] ?? null,
                'warehouse_id' => $data['warehouse_id'],
                'type' => StockMovement::TYPE_ADJUSTMENT,
                'quantity' => $adjustment,
                'quantity_before' => $currentStock,
                'quantity_after' => $data['new_quantity'],
                'reason' => $data['reason'] ?? 'Stock Adjustment',
                'notes' => $data['notes'] ?? null,
                'created_by' => auth()->id(),
                'approved_by' => $data['approved_by'] ?? null,
                'approved_at' => isset($data['approved_by']) ? now() : null,
            ]);

            // Update variant stock
            $this->updateVariantStock($data['product_id'], $data['variant_id'] ?? null, $adjustment, true);

            return $movement;
        });
    }

    /**
     * Transfer stock between warehouses
     */
    public function transferStock(array $data)
    {
        return DB::transaction(function () use ($data) {
            // Remove from source warehouse
            $currentStockFrom = $this->getCurrentStock(
                $data['product_id'],
                $data['variant_id'] ?? null,
                $data['from_warehouse_id']
            );

            if ($currentStockFrom < $data['quantity']) {
                throw new \Exception('Insufficient stock in source warehouse');
            }

            $fromMovement = $this->stockMovementRepo->create([
                'reference_number' => StockMovement::generateReferenceNumber('transfer'),
                'product_id' => $data['product_id'],
                'variant_id' => $data['variant_id'] ?? null,
                'warehouse_id' => $data['from_warehouse_id'],
                'type' => StockMovement::TYPE_TRANSFER,
                'quantity' => -$data['quantity'],
                'quantity_before' => $currentStockFrom,
                'quantity_after' => $currentStockFrom - $data['quantity'],
                'transfer_to_warehouse_id' => $data['to_warehouse_id'],
                'reason' => 'Transfer to warehouse',
                'notes' => $data['notes'] ?? null,
                'created_by' => auth()->id(),
            ]);

            // Add to destination warehouse
            $currentStockTo = $this->getCurrentStock(
                $data['product_id'],
                $data['variant_id'] ?? null,
                $data['to_warehouse_id']
            );

            $toMovement = $this->stockMovementRepo->create([
                'reference_number' => $fromMovement->reference_number,
                'product_id' => $data['product_id'],
                'variant_id' => $data['variant_id'] ?? null,
                'warehouse_id' => $data['to_warehouse_id'],
                'type' => StockMovement::TYPE_TRANSFER,
                'quantity' => $data['quantity'],
                'quantity_before' => $currentStockTo,
                'quantity_after' => $currentStockTo + $data['quantity'],
                'transfer_to_warehouse_id' => $data['from_warehouse_id'],
                'reason' => 'Transfer from warehouse',
                'notes' => $data['notes'] ?? null,
                'created_by' => auth()->id(),
            ]);

            return ['from' => $fromMovement, 'to' => $toMovement];
        });
    }

    /**
     * Get current stock level
     */
    public function getCurrentStock($productId, $variantId = null, $warehouseId = null)
    {
        return $this->stockMovementRepo->getCurrentStock($productId, $variantId, $warehouseId);
    }

    /**
     * Update variant stock quantity
     */
    protected function updateVariantStock($productId, $variantId, $quantity, $relative = true)
    {
        if ($variantId) {
            $variant = ProductVariant::find($variantId);
            if ($variant) {
                if ($relative) {
                    $variant->increment('stock_quantity', $quantity);
                } else {
                    $variant->update(['stock_quantity' => $quantity]);
                }
            }
        }
    }

    /**
     * Check and create/resolve stock alerts
     */
    protected function checkStockAlerts($productId, $variantId, $warehouseId)
    {
        $currentStock = $this->getCurrentStock($productId, $variantId, $warehouseId);
        
        // Get variant low stock threshold
        if ($variantId) {
            $variant = ProductVariant::find($variantId);
            $minQuantity = $variant->low_stock_alert ?? 5;
        } else {
            $minQuantity = 5; // Default threshold
        }

        // Check if stock is low
        if ($currentStock < $minQuantity) {
            // Create alert if not exists
            if (!$this->stockAlertRepo->exists($productId, $variantId, $warehouseId)) {
                $this->stockAlertRepo->create([
                    'product_id' => $productId,
                    'variant_id' => $variantId,
                    'warehouse_id' => $warehouseId,
                    'min_quantity' => $minQuantity,
                    'current_quantity' => $currentStock,
                    'status' => 'pending',
                ]);
            }
        } else {
            // Resolve existing alerts
            $existingAlert = $this->stockAlertRepo->exists($productId, $variantId, $warehouseId);
            if ($existingAlert) {
                $alert = \App\Modules\Stock\Models\StockAlert::where('product_id', $productId)
                    ->where('variant_id', $variantId)
                    ->where('warehouse_id', $warehouseId)
                    ->whereIn('status', ['pending', 'notified'])
                    ->first();
                    
                if ($alert) {
                    $alert->markAsResolved('Stock replenished');
                }
            }
        }
    }
}
