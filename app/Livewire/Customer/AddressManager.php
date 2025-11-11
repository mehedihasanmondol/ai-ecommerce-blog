<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use App\Modules\User\Models\UserAddress;
use Illuminate\Support\Facades\Auth;

/**
 * AddressManager Livewire Component
 * 
 * Manages customer addresses with CRUD operations
 * Features: Add, Edit, Delete, Set Default
 */
class AddressManager extends Component
{
    public $addresses;
    public $showModal = false;
    public $editMode = false;
    public $addressId;

    // Form fields
    public $label;
    public $address_line1;
    public $address_line2;
    public $city;
    public $state;
    public $postal_code;
    public $country = 'Bangladesh';
    public $phone;
    public $is_default = false;

    // Removed listeners - using direct wire:click now

    protected function rules()
    {
        return [
            'label' => 'required|string|max:50',
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'is_default' => 'boolean',
        ];
    }

    public function mount()
    {
        $this->loadAddresses();
    }

    public function loadAddresses()
    {
        $this->addresses = UserAddress::where('user_id', Auth::id())
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit($addressId)
    {
        $address = UserAddress::where('user_id', Auth::id())->findOrFail($addressId);
        
        $this->addressId = $address->id;
        $this->label = $address->label;
        $this->address_line1 = $address->address_line1;
        $this->address_line2 = $address->address_line2;
        $this->city = $address->city;
        $this->state = $address->state;
        $this->postal_code = $address->postal_code;
        $this->country = $address->country;
        $this->phone = $address->phone;
        $this->is_default = $address->is_default;
        
        $this->editMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        try {
            if ($this->editMode) {
                $address = UserAddress::where('user_id', Auth::id())->findOrFail($this->addressId);
                $address->update($this->getFormData());
                session()->flash('success', 'Address updated successfully!');
            } else {
                UserAddress::create(array_merge(
                    $this->getFormData(),
                    ['user_id' => Auth::id()]
                ));
                session()->flash('success', 'Address added successfully!');
            }

            // If this address is set as default, remove default from others
            if ($this->is_default) {
                $this->setAsDefault($this->addressId ?? UserAddress::where('user_id', Auth::id())->latest()->first()->id);
            }

            $this->closeModal();
            $this->loadAddresses();
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to save address. Please try again.');
        }
    }

    public function delete($addressId)
    {
        try {
            $address = UserAddress::where('user_id', Auth::id())->findOrFail($addressId);
            $address->delete();
            
            $this->loadAddresses();
            session()->flash('success', 'Address deleted successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete address.');
        }
    }

    public function setAsDefault($addressId)
    {
        try {
            // Remove default from all addresses
            UserAddress::where('user_id', Auth::id())->update(['is_default' => false]);
            
            // Set this address as default
            $address = UserAddress::where('user_id', Auth::id())->findOrFail($addressId);
            $address->update(['is_default' => true]);
            
            $this->loadAddresses();
            session()->flash('success', 'Default address updated!');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update default address.');
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->reset([
            'addressId',
            'label',
            'address_line1',
            'address_line2',
            'city',
            'state',
            'postal_code',
            'phone',
            'is_default',
            'editMode'
        ]);
        $this->country = 'Bangladesh';
    }

    private function getFormData()
    {
        return [
            'label' => $this->label,
            'address_line1' => $this->address_line1,
            'address_line2' => $this->address_line2,
            'city' => $this->city,
            'state' => $this->state,
            'postal_code' => $this->postal_code,
            'country' => $this->country,
            'phone' => $this->phone,
            'is_default' => $this->is_default,
        ];
    }

    public function render()
    {
        return view('livewire.customer.address-manager');
    }
}
