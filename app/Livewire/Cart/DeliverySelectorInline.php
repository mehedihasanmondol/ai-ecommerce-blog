<?php

namespace App\Livewire\Cart;

use App\Modules\Ecommerce\Delivery\Models\DeliveryZone;
use App\Modules\Ecommerce\Delivery\Models\DeliveryMethod;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DeliverySelectorInline extends Component
{
    public $selectedZoneId;
    public $selectedMethodId;
    public $selectedZone;
    public $selectedMethod;

    protected $listeners = ['deliveryUpdated' => 'refreshSelection'];

    public function mount()
    {
        // Load from session
        $this->selectedZoneId = session('delivery_zone_id');
        $this->selectedMethodId = session('delivery_method_id');
        
        // Load user's default if logged in and no session
        if (!$this->selectedZoneId && Auth::check() && Auth::user()->default_delivery_zone_id) {
            $this->selectedZoneId = Auth::user()->default_delivery_zone_id;
            $this->selectedMethodId = Auth::user()->default_delivery_method_id;
        }
        
        $this->loadSelection();
    }

    public function loadSelection()
    {
        if ($this->selectedZoneId) {
            $this->selectedZone = DeliveryZone::find($this->selectedZoneId);
        }
        
        if ($this->selectedMethodId) {
            $this->selectedMethod = DeliveryMethod::find($this->selectedMethodId);
        }
    }

    public function refreshSelection()
    {
        $this->selectedZoneId = session('delivery_zone_id');
        $this->selectedMethodId = session('delivery_method_id');
        $this->loadSelection();
    }

    public function render()
    {
        return view('livewire.cart.delivery-selector-inline');
    }
}
