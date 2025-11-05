<?php

namespace App\Modules\Ecommerce\Order\Services;

use App\Modules\Ecommerce\Order\Models\Order;
use App\Modules\Ecommerce\Order\Models\OrderAddress;
use App\Modules\Ecommerce\Order\Models\OrderItem;
use App\Modules\Ecommerce\Order\Repositories\OrderRepository;
use App\Modules\Ecommerce\Product\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use Exception;

class OrderService
{
    public function __construct(
        protected OrderRepository $orderRepository,
        protected OrderStatusService $statusService,
        protected OrderCalculationService $calculationService
    ) {}

    /**
     * Create new order from cart data.
     */
    public function createOrder(array $data): Order
    {
        try {
            DB::beginTransaction();

            // Calculate totals
            $calculations = $this->calculationService->calculateOrderTotals(
                $data['items'],
                $data['shipping_cost'] ?? 0,
                $data['coupon_code'] ?? null
            );

            // Create order
            $order = $this->orderRepository->create([
                'user_id' => $data['user_id'],
                'customer_name' => $data['customer_name'],
                'customer_email' => $data['customer_email'],
                'customer_phone' => $data['customer_phone'],
                'customer_notes' => $data['customer_notes'] ?? null,
                'payment_method' => $data['payment_method'],
                'subtotal' => $calculations['subtotal'],
                'tax_amount' => $calculations['tax_amount'],
                'shipping_cost' => $calculations['shipping_cost'],
                'discount_amount' => $calculations['discount_amount'],
                'total_amount' => $calculations['total_amount'],
                'coupon_code' => $data['coupon_code'] ?? null,
                'ip_address' => request()->ip(),
                'status' => 'pending',
                'payment_status' => $data['payment_method'] === 'cod' ? 'pending' : 'pending',
            ]);

            // Create order items
            foreach ($data['items'] as $item) {
                $this->createOrderItem($order, $item);
            }

            // Create billing address
            if (!empty($data['billing_address'])) {
                $this->createOrderAddress($order, $data['billing_address'], 'billing');
            }

            // Create shipping address
            if (!empty($data['shipping_address'])) {
                $this->createOrderAddress($order, $data['shipping_address'], 'shipping');
            } else {
                // Use billing address as shipping address
                $this->createOrderAddress($order, $data['billing_address'], 'shipping');
            }

            // Create initial status history
            $this->statusService->createStatusHistory($order, null, 'pending', 'Order created');

            // Update product stock
            $this->updateProductStock($data['items']);

            DB::commit();

            return $order->load(['items', 'billingAddress', 'shippingAddress']);

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Create order item.
     */
    protected function createOrderItem(Order $order, array $itemData): OrderItem
    {
        $product = $itemData['product'];
        $variant = $itemData['variant'] ?? null;

        // Per .windsurfrules: product_variant_id is required
        if (!$variant) {
            throw new \Exception("Product variant is required for product: {$product->name}");
        }

        // Use custom price from form if provided, otherwise use variant/product price
        $price = $itemData['price'] ?? ($variant->price ?? $product->price);
        $subtotal = $price * $itemData['quantity'];

        // Format variant attributes as key-value pairs
        $variantAttributes = null;
        if ($variant->attributeValues && $variant->attributeValues->count() > 0) {
            $variantAttributes = [];
            foreach ($variant->attributeValues as $attributeValue) {
                $attributeName = $attributeValue->attribute->name ?? 'Attribute';
                $variantAttributes[$attributeName] = $attributeValue->value;
            }
        }

        return OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_variant_id' => $variant->id, // Required, never null
            'product_name' => $product->name,
            'product_sku' => $variant->sku ?? 'N/A',
            'variant_name' => $variant->name,
            'variant_attributes' => $variantAttributes,
            'price' => $price,
            'quantity' => $itemData['quantity'],
            'subtotal' => $subtotal,
            'tax_amount' => 0, // Calculate if needed
            'discount_amount' => 0, // Calculate if needed
            'total' => $subtotal,
            'product_image' => $product->primary_image,
        ]);
    }

    /**
     * Create order address.
     */
    protected function createOrderAddress(Order $order, array $addressData, string $type): OrderAddress
    {
        return OrderAddress::create([
            'order_id' => $order->id,
            'type' => $type,
            'first_name' => $addressData['first_name'],
            'last_name' => $addressData['last_name'],
            'email' => $addressData['email'] ?? null,
            'phone' => $addressData['phone'],
            'company' => $addressData['company'] ?? null,
            'address_line_1' => $addressData['address_line_1'],
            'address_line_2' => $addressData['address_line_2'] ?? null,
            'city' => $addressData['city'],
            'state' => $addressData['state'] ?? null,
            'postal_code' => $addressData['postal_code'],
            'country' => $addressData['country'] ?? 'Bangladesh',
        ]);
    }

    /**
     * Update product stock after order.
     */
    protected function updateProductStock(array $items): void
    {
        foreach ($items as $item) {
            if (isset($item['variant'])) {
                $variant = ProductVariant::find($item['variant']->id);
                if ($variant) {
                    $variant->decrement('stock_quantity', $item['quantity']);
                }
            }
        }
    }

    /**
     * Update order.
     */
    public function updateOrder(Order $order, array $data): bool
    {
        return $this->orderRepository->update($order, $data);
    }

    /**
     * Cancel order.
     */
    public function cancelOrder(Order $order, string $reason = null): bool
    {
        if (!$order->canBeCancelled()) {
            throw new Exception('This order cannot be cancelled.');
        }

        try {
            DB::beginTransaction();

            // Update order status
            $this->orderRepository->update($order, [
                'status' => 'cancelled',
                'cancelled_at' => now(),
            ]);

            // Create status history
            $this->statusService->createStatusHistory(
                $order,
                $order->status,
                'cancelled',
                $reason ?? 'Order cancelled'
            );

            // Restore product stock
            foreach ($order->items as $item) {
                if ($item->product_variant_id) {
                    $variant = ProductVariant::find($item->product_variant_id);
                    if ($variant) {
                        $variant->increment('stock_quantity', $item->quantity);
                    }
                }
            }

            DB::commit();

            return true;

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Get order statistics.
     */
    public function getStatistics(array $filters = []): array
    {
        return $this->orderRepository->getStatistics($filters);
    }

    /**
     * Get daily orders data for chart.
     */
    public function getDailyOrdersData(int $days = 7): array
    {
        return $this->orderRepository->getDailyOrdersCount($days);
    }

    /**
     * Get daily revenue data for chart.
     */
    public function getDailyRevenueData(int $days = 7): array
    {
        return $this->orderRepository->getDailyRevenue($days);
    }

    /**
     * Get status distribution.
     */
    public function getStatusDistribution(): array
    {
        return $this->orderRepository->getStatusDistribution();
    }
}
