<?php

namespace App\Livewire\Admin\Order;

use App\Modules\Ecommerce\Order\Models\Order;
use Livewire\Component;

class EditCostsDiscounts extends Component
{
    public Order $order;
    public $shipping_cost;
    public $discount_amount;
    public $coupon_code;
    public $isEditing = false;
    public $isSaving = false;
    public $calculatedTotal;

    protected $rules = [
        'shipping_cost' => 'nullable|numeric|min:0',
        'discount_amount' => 'nullable|numeric|min:0',
        'coupon_code' => 'nullable|string|max:50',
    ];

    public function mount(Order $order)
    {
        $this->order = $order;
        $this->shipping_cost = $order->shipping_cost ?? 0;
        $this->discount_amount = $order->discount_amount ?? 0;
        $this->coupon_code = $order->coupon_code;
        $this->calculateTotal();
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['shipping_cost', 'discount_amount'])) {
            $this->calculateTotal();
        }
    }

    public function calculateTotal()
    {
        $this->calculatedTotal = $this->order->subtotal + 
                                ($this->shipping_cost ?? 0) + 
                                ($this->order->tax_amount ?? 0) - 
                                ($this->discount_amount ?? 0);
    }

    public function toggleEdit()
    {
        $this->isEditing = !$this->isEditing;
        
        if (!$this->isEditing) {
            $this->shipping_cost = $this->order->shipping_cost ?? 0;
            $this->discount_amount = $this->order->discount_amount ?? 0;
            $this->coupon_code = $this->order->coupon_code;
            $this->calculateTotal();
            $this->resetValidation();
        }
    }

    public function save()
    {
        $this->validate();
        
        $this->isSaving = true;

        try {
            $this->order->update([
                'shipping_cost' => $this->shipping_cost,
                'discount_amount' => $this->discount_amount,
                'coupon_code' => $this->coupon_code,
                'total_amount' => $this->calculatedTotal,
            ]);

            $this->isEditing = false;
            $this->isSaving = false;
            
            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Costs & discounts updated successfully!'
            ]);
            
            $this->dispatch('orderUpdated');
        } catch (\Exception $e) {
            $this->isSaving = false;
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Failed to update costs: ' . $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.order.edit-costs-discounts');
    }
}
