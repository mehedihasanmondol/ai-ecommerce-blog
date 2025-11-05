<?php

namespace App\Modules\Ecommerce\Order\Services;

class OrderCalculationService
{
    /**
     * Tax rate (percentage).
     */
    protected float $taxRate = 0; // 0% tax, adjust as needed

    /**
     * Calculate order totals.
     */
    public function calculateOrderTotals(
        array $items,
        float $shippingCost = 0,
        ?string $couponCode = null
    ): array {
        $subtotal = $this->calculateSubtotal($items);
        $taxAmount = $this->calculateTax($subtotal);
        $discountAmount = $this->calculateDiscount($subtotal, $couponCode);
        $totalAmount = $subtotal + $taxAmount + $shippingCost - $discountAmount;

        return [
            'subtotal' => round($subtotal, 2),
            'tax_amount' => round($taxAmount, 2),
            'shipping_cost' => round($shippingCost, 2),
            'discount_amount' => round($discountAmount, 2),
            'total_amount' => round($totalAmount, 2),
        ];
    }

    /**
     * Calculate subtotal from items.
     */
    protected function calculateSubtotal(array $items): float
    {
        $subtotal = 0;

        foreach ($items as $item) {
            $product = $item['product'];
            $variant = $item['variant'] ?? null;
            $price = $variant ? $variant->price : $product->price;
            $quantity = $item['quantity'];

            $subtotal += $price * $quantity;
        }

        return $subtotal;
    }

    /**
     * Calculate tax amount.
     */
    protected function calculateTax(float $subtotal): float
    {
        return $subtotal * ($this->taxRate / 100);
    }

    /**
     * Calculate discount amount.
     */
    protected function calculateDiscount(float $subtotal, ?string $couponCode): float
    {
        if (!$couponCode) {
            return 0;
        }

        // TODO: Implement coupon validation and discount calculation
        // This will be integrated with Coupon module later
        
        return 0;
    }

    /**
     * Calculate shipping cost based on location and weight.
     */
    public function calculateShippingCost(string $city, float $weight = 0): float
    {
        // Basic shipping calculation
        // Inside Dhaka: 60 BDT
        // Outside Dhaka: 120 BDT
        
        $insideDhaka = ['dhaka', 'ঢাকা'];
        
        if (in_array(strtolower($city), $insideDhaka)) {
            return 60;
        }

        return 120;
    }

    /**
     * Set tax rate.
     */
    public function setTaxRate(float $rate): void
    {
        $this->taxRate = $rate;
    }
}
