<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Ecommerce\Delivery\Services\DeliveryService;
use App\Modules\Ecommerce\Delivery\Repositories\DeliveryRepository;
use Illuminate\Http\Request;

class DeliveryRateController extends Controller
{
    public function __construct(
        protected DeliveryService $deliveryService,
        protected DeliveryRepository $deliveryRepository
    ) {
    }

    /**
     * Display a listing of delivery rates.
     */
    public function index()
    {
        // Check permission
        abort_if(!auth()->user()->hasPermission('delivery-rates.view'), 403, 'You do not have permission to view delivery rates.');

        $rates = $this->deliveryService->getAllRates(15);

        return view('admin.delivery.rates.index', compact('rates'));
    }

    /**
     * Show the form for creating a new delivery rate.
     */
    public function create()
    {
        // Check permission
        abort_if(!auth()->user()->hasPermission('delivery-rates.create'), 403, 'You do not have permission to create delivery rates.');

        $zones = $this->deliveryRepository->getActiveZones();
        $methods = $this->deliveryRepository->getActiveMethods();

        return view('admin.delivery.rates.create', compact('zones', 'methods'));
    }

    /**
     * Store a newly created delivery rate.
     */
    public function store(Request $request)
    {
        // Check permission
        abort_if(!auth()->user()->hasPermission('delivery-rates.create'), 403, 'You do not have permission to create delivery rates.');

        $validated = $request->validate([
            'delivery_zone_id' => 'required|exists:delivery_zones,id',
            'delivery_method_id' => 'required|exists:delivery_methods,id',
            'base_rate' => 'required|numeric|min:0',
            'weight_from' => 'nullable|numeric|min:0',
            'weight_to' => 'nullable|numeric|min:0',
            'rate_per_kg' => 'nullable|numeric|min:0',
            'price_from' => 'nullable|numeric|min:0',
            'price_to' => 'nullable|numeric|min:0',
            'rate_percentage' => 'nullable|numeric|min:0|max:100',
            'item_from' => 'nullable|integer|min:0',
            'item_to' => 'nullable|integer|min:0',
            'rate_per_item' => 'nullable|numeric|min:0',
            'handling_fee' => 'nullable|numeric|min:0',
            'insurance_fee' => 'nullable|numeric|min:0',
            'cod_fee' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $this->deliveryService->createRate($validated);

        return redirect()
            ->route('admin.delivery.rates.index')
            ->with('success', 'Delivery rate created successfully.');
    }

    /**
     * Show the form for editing the specified delivery rate.
     */
    public function edit(int $id)
    {
        // Check permission
        abort_if(!auth()->user()->hasPermission('delivery-rates.edit'), 403, 'You do not have permission to edit delivery rates.');

        $rate = $this->deliveryService->getAllRates(999999)
            ->firstWhere('id', $id);

        if (!$rate) {
            return redirect()
                ->route('admin.delivery.rates.index')
                ->with('error', 'Delivery rate not found.');
        }

        $zones = $this->deliveryRepository->getActiveZones();
        $methods = $this->deliveryRepository->getActiveMethods();

        return view('admin.delivery.rates.edit', compact('rate', 'zones', 'methods'));
    }

    /**
     * Update the specified delivery rate.
     */
    public function update(Request $request, int $id)
    {
        // Check permission
        abort_if(!auth()->user()->hasPermission('delivery-rates.edit'), 403, 'You do not have permission to edit delivery rates.');

        $validated = $request->validate([
            'delivery_zone_id' => 'required|exists:delivery_zones,id',
            'delivery_method_id' => 'required|exists:delivery_methods,id',
            'base_rate' => 'required|numeric|min:0',
            'weight_from' => 'nullable|numeric|min:0',
            'weight_to' => 'nullable|numeric|min:0',
            'rate_per_kg' => 'nullable|numeric|min:0',
            'price_from' => 'nullable|numeric|min:0',
            'price_to' => 'nullable|numeric|min:0',
            'rate_percentage' => 'nullable|numeric|min:0|max:100',
            'item_from' => 'nullable|integer|min:0',
            'item_to' => 'nullable|integer|min:0',
            'rate_per_item' => 'nullable|numeric|min:0',
            'handling_fee' => 'nullable|numeric|min:0',
            'insurance_fee' => 'nullable|numeric|min:0',
            'cod_fee' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $this->deliveryService->updateRate($id, $validated);

        return redirect()
            ->route('admin.delivery.rates.index')
            ->with('success', 'Delivery rate updated successfully.');
    }

    /**
     * Remove the specified delivery rate.
     */
    public function destroy(int $id)
    {
        // Check permission
        abort_if(!auth()->user()->hasPermission('delivery-rates.delete'), 403, 'You do not have permission to delete delivery rates.');

        $this->deliveryService->deleteRate($id);

        return redirect()
            ->route('admin.delivery.rates.index')
            ->with('success', 'Delivery rate deleted successfully.');
    }

    /**
     * Toggle rate active status.
     */
    public function toggleStatus(int $id)
    {
        // Check permission
        if (!auth()->user()->hasPermission('delivery-rates.toggle-status')) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to toggle rate status.',
            ], 403);
        }

        $rate = $this->deliveryService->getAllRates(999999)
            ->firstWhere('id', $id);

        if ($rate) {
            $this->deliveryService->updateRate($id, [
                'is_active' => !$rate->is_active
            ]);

            return response()->json([
                'success' => true,
                'is_active' => !$rate->is_active
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Rate not found'
        ], 404);
    }
}
