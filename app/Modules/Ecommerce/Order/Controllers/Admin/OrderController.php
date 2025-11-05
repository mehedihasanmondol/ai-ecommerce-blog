<?php

namespace App\Modules\Ecommerce\Order\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Ecommerce\Order\Models\Order;
use App\Modules\Ecommerce\Order\Repositories\OrderRepository;
use App\Modules\Ecommerce\Order\Services\OrderService;
use App\Modules\Ecommerce\Order\Services\OrderStatusService;
use App\Modules\Ecommerce\Order\Requests\CreateOrderRequest;
use App\Modules\Ecommerce\Order\Requests\UpdateOrderRequest;
use App\Modules\Ecommerce\Order\Requests\UpdateOrderStatusRequest;
use App\Modules\Ecommerce\Product\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(
        protected OrderRepository $orderRepository,
        protected OrderService $orderService,
        protected OrderStatusService $statusService
    ) {
        // Middleware is applied in routes/admin.php
    }

    /**
     * Display a listing of orders.
     */
    public function index(Request $request)
    {
        $filters = [
            'status' => $request->get('status'),
            'payment_status' => $request->get('payment_status'),
            'search' => $request->get('search'),
            'date_from' => $request->get('date_from'),
            'date_to' => $request->get('date_to'),
        ];

        $orders = $this->orderRepository->paginate(15, $filters);
        $statistics = $this->orderService->getStatistics();

        return view('admin.orders.index', compact('orders', 'statistics', 'filters'));
    }

    /**
     * Show the form for creating a new order.
     */
    public function create()
    {
        return view('admin.orders.create');
    }

    /**
     * Store a newly created order.
     */
    public function store(CreateOrderRequest $request)
    {
        try {
            $validated = $request->validated();
            
            // Prepare items data
            $items = [];
            foreach ($validated['items'] as $itemData) {
                $product = Product::find($itemData['product_id']);
                $items[] = [
                    'product' => $product,
                    'variant' => null, // Can be extended for variants
                    'quantity' => $itemData['quantity'],
                ];
            }
            
            // Prepare order data
            $orderData = [
                'user_id' => $validated['user_id'] ?? null,
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
                'customer_phone' => $validated['customer_phone'],
                'customer_notes' => $validated['customer_notes'] ?? null,
                'payment_method' => $validated['payment_method'],
                'items' => $items,
                'billing_address' => [
                    'first_name' => $validated['billing_first_name'],
                    'last_name' => $validated['billing_last_name'],
                    'phone' => $validated['billing_phone'],
                    'email' => $validated['billing_email'] ?? null,
                    'address_line_1' => $validated['billing_address_line_1'],
                    'address_line_2' => $validated['billing_address_line_2'] ?? null,
                    'city' => $validated['billing_city'],
                    'state' => $validated['billing_state'] ?? null,
                    'postal_code' => $validated['billing_postal_code'],
                    'country' => $validated['billing_country'],
                ],
                'shipping_cost' => $validated['shipping_cost'],
                'coupon_code' => $validated['coupon_code'] ?? null,
            ];
            
            // Add shipping address
            if (!empty($validated['same_as_billing'])) {
                $orderData['shipping_address'] = $orderData['billing_address'];
            } else {
                $orderData['shipping_address'] = [
                    'first_name' => $validated['shipping_first_name'],
                    'last_name' => $validated['shipping_last_name'],
                    'phone' => $validated['shipping_phone'],
                    'email' => $validated['shipping_email'] ?? null,
                    'address_line_1' => $validated['shipping_address_line_1'],
                    'address_line_2' => $validated['shipping_address_line_2'] ?? null,
                    'city' => $validated['shipping_city'],
                    'state' => $validated['shipping_state'] ?? null,
                    'postal_code' => $validated['shipping_postal_code'],
                    'country' => $validated['shipping_country'],
                ];
            }
            
            // Create order
            $order = $this->orderService->createOrder($orderData);
            
            // Update payment status if paid
            if ($validated['payment_status'] === 'paid') {
                $order->update([
                    'payment_status' => 'paid',
                    'paid_at' => now(),
                ]);
            }
            
            // Add admin notes
            if (!empty($validated['admin_notes'])) {
                $order->update(['admin_notes' => $validated['admin_notes']]);
            }
            
            return redirect()
                ->route('admin.orders.show', $order)
                ->with('success', 'Order created successfully.');
                
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to create order: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        $order = $this->orderRepository->find($order->id);
        $availableStatuses = $this->statusService->getAvailableStatuses($order);

        return view('admin.orders.show', compact('order', 'availableStatuses'));
    }

    /**
     * Show the form for editing the specified order.
     */
    public function edit(Order $order)
    {
        $order = $this->orderRepository->find($order->id);

        return view('admin.orders.edit', compact('order'));
    }

    /**
     * Update the specified order.
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        try {
            $this->orderService->updateOrder($order, $request->validated());

            return redirect()
                ->route('admin.orders.show', $order)
                ->with('success', 'Order updated successfully.');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to update order: ' . $e->getMessage());
        }
    }

    /**
     * Update order status.
     */
    public function updateStatus(UpdateOrderStatusRequest $request, Order $order)
    {
        try {
            $validated = $request->validated();

            // Update tracking info if provided
            if (!empty($validated['tracking_number'])) {
                $this->orderService->updateOrder($order, [
                    'tracking_number' => $validated['tracking_number'],
                    'carrier' => $validated['carrier'] ?? null,
                ]);
            }

            // Update status
            $this->statusService->updateStatus(
                $order,
                $validated['status'],
                $validated['notes'] ?? null,
                $validated['notify_customer'] ?? true
            );

            return redirect()
                ->route('admin.orders.show', $order)
                ->with('success', 'Order status updated successfully.');

        } catch (\Exception $e) {
            return back()
                ->with('error', 'Failed to update status: ' . $e->getMessage());
        }
    }

    /**
     * Cancel order.
     */
    public function cancel(Request $request, Order $order)
    {
        try {
            $reason = $request->input('reason', 'Cancelled by admin');
            $this->orderService->cancelOrder($order, $reason);

            return redirect()
                ->route('admin.orders.show', $order)
                ->with('success', 'Order cancelled successfully.');

        } catch (\Exception $e) {
            return back()
                ->with('error', 'Failed to cancel order: ' . $e->getMessage());
        }
    }

    /**
     * Delete order (soft delete).
     */
    public function destroy(Order $order)
    {
        try {
            $this->orderRepository->delete($order);

            return redirect()
                ->route('admin.orders.index')
                ->with('success', 'Order deleted successfully.');

        } catch (\Exception $e) {
            return back()
                ->with('error', 'Failed to delete order: ' . $e->getMessage());
        }
    }

    /**
     * Print invoice.
     */
    public function invoice(Order $order)
    {
        $order = $this->orderRepository->find($order->id);

        return view('admin.orders.invoice', compact('order'));
    }
}
