<?php

namespace App\Modules\Ecommerce\Delivery\Services;

use App\Modules\Ecommerce\Delivery\Repositories\DeliveryRepository;
use App\Modules\Ecommerce\Delivery\Models\DeliveryZone;
use App\Modules\Ecommerce\Delivery\Models\DeliveryMethod;
use App\Modules\Ecommerce\Delivery\Models\DeliveryRate;
use Illuminate\Support\Str;

class DeliveryService
{
    public function __construct(
        protected DeliveryRepository $repository
    ) {}

    /**
     * Calculate shipping cost for an order.
     */
    public function calculateShippingCost(
        int $zoneId,
        int $methodId,
        float $orderTotal,
        float $orderWeight = 0,
        int $itemCount = 0,
        bool $isCod = false
    ): array {
        $method = $this->repository->getMethodById($methodId);
        
        if (!$method) {
            return [
                'success' => false,
                'message' => 'Delivery method not found',
            ];
        }

        // Check if qualifies for free shipping
        if ($method->qualifiesForFreeShipping($orderTotal)) {
            return [
                'success' => true,
                'cost' => 0,
                'breakdown' => [
                    'base_rate' => 0,
                    'handling_fee' => 0,
                    'insurance_fee' => 0,
                    'cod_fee' => 0,
                    'total' => 0,
                ],
                'is_free' => true,
                'message' => 'Free shipping applied',
            ];
        }

        // Get applicable rate
        $rate = $this->repository->getRate($zoneId, $methodId, $orderTotal, $orderWeight, $itemCount);

        if (!$rate) {
            return [
                'success' => false,
                'message' => 'No delivery rate found for this zone and method',
            ];
        }

        // Calculate cost
        $cost = $rate->calculateCost($orderTotal, $orderWeight, $itemCount, $isCod);
        $breakdown = $rate->getCostBreakdown($orderTotal, $orderWeight, $itemCount, $isCod);

        return [
            'success' => true,
            'cost' => $cost,
            'breakdown' => $breakdown,
            'is_free' => false,
            'estimated_delivery' => $method->getEstimatedDeliveryText(),
        ];
    }

    /**
     * Get available delivery options for an order.
     */
    public function getAvailableDeliveryOptions(
        string $country = null,
        string $state = null,
        string $city = null,
        string $postalCode = null,
        float $orderTotal = 0,
        float $orderWeight = 0,
        int $itemCount = 0
    ): array {
        // Find applicable zone
        $zone = $this->repository->findZoneByLocation($country, $state, $city, $postalCode);

        if (!$zone) {
            return [
                'success' => false,
                'message' => 'No delivery zone found for this location',
                'options' => [],
            ];
        }

        // Get available methods for this zone
        $methods = $this->repository->getMethodsForZone($zone->id, $orderTotal, $orderWeight, $itemCount);

        $options = [];
        foreach ($methods as $method) {
            $costData = $this->calculateShippingCost(
                $zone->id,
                $method->id,
                $orderTotal,
                $orderWeight,
                $itemCount
            );

            if ($costData['success']) {
                $options[] = [
                    'zone_id' => $zone->id,
                    'zone_name' => $zone->name,
                    'method_id' => $method->id,
                    'method_name' => $method->name,
                    'method_code' => $method->code,
                    'description' => $method->description,
                    'carrier_name' => $method->carrier_name,
                    'estimated_delivery' => $method->getEstimatedDeliveryText(),
                    'cost' => $costData['cost'],
                    'breakdown' => $costData['breakdown'],
                    'is_free' => $costData['is_free'] ?? false,
                    'icon' => $method->icon,
                ];
            }
        }

        return [
            'success' => true,
            'zone' => [
                'id' => $zone->id,
                'name' => $zone->name,
                'code' => $zone->code,
            ],
            'options' => $options,
        ];
    }

    /**
     * Create delivery zone.
     */
    public function createZone(array $data): DeliveryZone
    {
        // Generate code if not provided
        if (empty($data['code'])) {
            $data['code'] = Str::slug($data['name']);
        }

        return $this->repository->createZone($data);
    }

    /**
     * Update delivery zone.
     */
    public function updateZone(int $id, array $data): bool
    {
        // Update code if name changed
        if (isset($data['name']) && empty($data['code'])) {
            $data['code'] = Str::slug($data['name']);
        }

        return $this->repository->updateZone($id, $data);
    }

    /**
     * Delete delivery zone.
     */
    public function deleteZone(int $id): bool
    {
        return $this->repository->deleteZone($id);
    }

    /**
     * Create delivery method.
     */
    public function createMethod(array $data): DeliveryMethod
    {
        // Generate code if not provided
        if (empty($data['code'])) {
            $data['code'] = Str::slug($data['name']);
        }

        // Generate estimated_days if min/max provided
        if (empty($data['estimated_days']) && isset($data['min_days']) && isset($data['max_days'])) {
            $data['estimated_days'] = $data['min_days'] . '-' . $data['max_days'] . ' days';
        }

        return $this->repository->createMethod($data);
    }

    /**
     * Update delivery method.
     */
    public function updateMethod(int $id, array $data): bool
    {
        // Update code if name changed
        if (isset($data['name']) && empty($data['code'])) {
            $data['code'] = Str::slug($data['name']);
        }

        // Update estimated_days if min/max provided
        if (empty($data['estimated_days']) && isset($data['min_days']) && isset($data['max_days'])) {
            $data['estimated_days'] = $data['min_days'] . '-' . $data['max_days'] . ' days';
        }

        return $this->repository->updateMethod($id, $data);
    }

    /**
     * Delete delivery method.
     */
    public function deleteMethod(int $id): bool
    {
        return $this->repository->deleteMethod($id);
    }

    /**
     * Create delivery rate.
     */
    public function createRate(array $data): DeliveryRate
    {
        return $this->repository->createRate($data);
    }

    /**
     * Update delivery rate.
     */
    public function updateRate(int $id, array $data): bool
    {
        return $this->repository->updateRate($id, $data);
    }

    /**
     * Delete delivery rate.
     */
    public function deleteRate(int $id): bool
    {
        return $this->repository->deleteRate($id);
    }

    /**
     * Get all zones.
     */
    public function getAllZones(int $perPage = 15)
    {
        return $this->repository->getAllZonesPaginated($perPage);
    }

    /**
     * Get all methods.
     */
    public function getAllMethods(int $perPage = 15)
    {
        return $this->repository->getAllMethodsPaginated($perPage);
    }

    /**
     * Get all rates.
     */
    public function getAllRates(int $perPage = 15)
    {
        return $this->repository->getAllRatesPaginated($perPage);
    }
}
