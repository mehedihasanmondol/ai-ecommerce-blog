<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Ecommerce\Delivery\Services\DeliveryService;
use Illuminate\Http\Request;

class DeliveryMethodController extends Controller
{
    public function __construct(
        protected DeliveryService $deliveryService
    ) {
    }

    /**
     * Display a listing of delivery methods.
     */
    public function index()
    {
        // Check permission
        abort_if(!auth()->user()->hasPermission('delivery-methods.view'), 403, 'You do not have permission to view delivery methods.');

        $methods = $this->deliveryService->getAllMethods(15);

        return view('admin.delivery.methods.index', compact('methods'));
    }

    /**
     * Show the form for creating a new delivery method.
     */
    public function create()
    {
        // Check permission
        abort_if(!auth()->user()->hasPermission('delivery-methods.create'), 403, 'You do not have permission to create delivery methods.');

        return view('admin.delivery.methods.create');
    }

    /**
     * Store a newly created delivery method.
     */
    public function store(Request $request)
    {
        // Check permission
        abort_if(!auth()->user()->hasPermission('delivery-methods.create'), 403, 'You do not have permission to create delivery methods.');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:255|unique:delivery_methods,code',
            'description' => 'nullable|string',
            'estimated_days' => 'nullable|string|max:255',
            'min_days' => 'nullable|integer|min:0',
            'max_days' => 'nullable|integer|min:0',
            'carrier_name' => 'nullable|string|max:255',
            'carrier_code' => 'nullable|string|max:255',
            'tracking_url' => 'nullable|url|max:500',
            'calculation_type' => 'required|in:flat_rate,weight_based,price_based,item_based,free',
            'free_shipping_threshold' => 'nullable|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_order_amount' => 'nullable|numeric|min:0',
            'max_weight' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
            'show_on_checkout' => 'boolean',
            'sort_order' => 'integer|min:0',
            'icon' => 'nullable|string|max:255',
        ]);

        $this->deliveryService->createMethod($validated);

        return redirect()
            ->route('admin.delivery.methods.index')
            ->with('success', 'Delivery method created successfully.');
    }

    /**
     * Show the form for editing the specified delivery method.
     */
    public function edit(int $id)
    {
        // Check permission
        abort_if(!auth()->user()->hasPermission('delivery-methods.edit'), 403, 'You do not have permission to edit delivery methods.');

        $method = $this->deliveryService->getAllMethods(999999)
            ->firstWhere('id', $id);

        if (!$method) {
            return redirect()
                ->route('admin.delivery.methods.index')
                ->with('error', 'Delivery method not found.');
        }

        return view('admin.delivery.methods.edit', compact('method'));
    }

    /**
     * Update the specified delivery method.
     */
    public function update(Request $request, int $id)
    {
        // Check permission
        abort_if(!auth()->user()->hasPermission('delivery-methods.edit'), 403, 'You do not have permission to edit delivery methods.');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:255|unique:delivery_methods,code,' . $id,
            'description' => 'nullable|string',
            'estimated_days' => 'nullable|string|max:255',
            'min_days' => 'nullable|integer|min:0',
            'max_days' => 'nullable|integer|min:0',
            'carrier_name' => 'nullable|string|max:255',
            'carrier_code' => 'nullable|string|max:255',
            'tracking_url' => 'nullable|url|max:500',
            'calculation_type' => 'required|in:flat_rate,weight_based,price_based,item_based,free',
            'free_shipping_threshold' => 'nullable|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_order_amount' => 'nullable|numeric|min:0',
            'max_weight' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
            'show_on_checkout' => 'boolean',
            'sort_order' => 'integer|min:0',
            'icon' => 'nullable|string|max:255',
        ]);

        $this->deliveryService->updateMethod($id, $validated);

        return redirect()
            ->route('admin.delivery.methods.index')
            ->with('success', 'Delivery method updated successfully.');
    }

    /**
     * Remove the specified delivery method.
     */
    public function destroy(int $id)
    {
        // Check permission
        abort_if(!auth()->user()->hasPermission('delivery-methods.delete'), 403, 'You do not have permission to delete delivery methods.');

        $this->deliveryService->deleteMethod($id);

        return redirect()
            ->route('admin.delivery.methods.index')
            ->with('success', 'Delivery method deleted successfully.');
    }

    /**
     * Toggle method active status.
     */
    public function toggleStatus(int $id)
    {
        // Check permission
        if (!auth()->user()->hasPermission('delivery-methods.toggle-status')) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to toggle method status.',
            ], 403);
        }

        $method = $this->deliveryService->getAllMethods(999999)
            ->firstWhere('id', $id);

        if ($method) {
            $this->deliveryService->updateMethod($id, [
                'is_active' => !$method->is_active
            ]);

            return response()->json([
                'success' => true,
                'is_active' => !$method->is_active
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Method not found'
        ], 404);
    }
}
