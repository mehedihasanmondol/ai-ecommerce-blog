<?php

namespace App\Livewire\Admin\Advertisement;

use App\Modules\Advertisement\Models\AdSlot;
use Livewire\Component;
use Livewire\WithPagination;

class AdSlotList extends Component
{
    use WithPagination;

    // Filters
    public $search = '';
    public $locationFilter = '';
    public $statusFilter = '';
    public $showFilters = false;

    // Pagination
    public $perPage = 15;

    // Modals
    public $showEditModal = false;
    public $showDeleteModal = false;

    // Edit slot data
    public $editSlotId;
    public $editName;
    public $editSlug;
    public $editLocation;
    public $editDescription;
    public $editDefaultWidth;
    public $editDefaultHeight;
    public $editIsActive;
    public $editLazyLoad;

    // Delete
    public $deleteSlotId;

    protected $queryString = [
        'search' => ['except' => ''],
        'locationFilter' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'perPage' => ['except' => 15],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingLocationFilter()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->reset(['search', 'locationFilter', 'statusFilter']);
        $this->resetPage();
    }

    public function toggleStatus($slotId)
    {
        if (!auth()->user()->hasPermission('advertisements.edit')) {
            session()->flash('error', 'You do not have permission to edit ad slots.');
            return;
        }

        $slot = AdSlot::find($slotId);
        if ($slot) {
            $slot->update(['is_active' => !$slot->is_active]);
            session()->flash('success', 'Ad slot status updated successfully!');
        }
    }

    public function openEditModal($slotId)
    {
        $slot = AdSlot::find($slotId);
        if (!$slot) {
            session()->flash('error', 'Ad slot not found.');
            return;
        }

        $this->editSlotId = $slot->id;
        $this->editName = $slot->name;
        $this->editSlug = $slot->slug;
        $this->editLocation = $slot->location;
        $this->editDescription = $slot->description;
        $this->editDefaultWidth = $slot->default_width;
        $this->editDefaultHeight = $slot->default_height;
        $this->editIsActive = $slot->is_active;
        $this->editLazyLoad = $slot->lazy_load;

        $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->reset([
            'showEditModal',
            'editSlotId',
            'editName',
            'editSlug',
            'editLocation',
            'editDescription',
            'editDefaultWidth',
            'editDefaultHeight',
            'editIsActive',
            'editLazyLoad'
        ]);
        $this->resetValidation();
    }

    public function updateSlot()
    {
        if (!auth()->user()->hasPermission('advertisements.edit')) {
            session()->flash('error', 'You do not have permission to edit ad slots.');
            $this->closeEditModal();
            return;
        }

        $this->validate([
            'editName' => 'required|string|max:255',
            'editSlug' => 'required|string|max:255|unique:ad_slots,slug,' . $this->editSlotId,
            'editLocation' => 'required|in:header,footer,sidebar,inline,popup,native',
            'editDescription' => 'nullable|string',
            'editDefaultWidth' => 'nullable|integer|min:1',
            'editDefaultHeight' => 'nullable|integer|min:1',
        ]);

        $slot = AdSlot::find($this->editSlotId);
        if ($slot) {
            $slot->update([
                'name' => $this->editName,
                'slug' => $this->editSlug,
                'location' => $this->editLocation,
                'description' => $this->editDescription,
                'default_width' => $this->editDefaultWidth,
                'default_height' => $this->editDefaultHeight,
                'is_active' => $this->editIsActive ?? false,
                'lazy_load' => $this->editLazyLoad ?? false,
            ]);

            session()->flash('success', 'Ad slot updated successfully!');
            $this->closeEditModal();
        }
    }

    public function confirmDelete($slotId)
    {
        $this->deleteSlotId = $slotId;
        $this->showDeleteModal = true;
    }

    public function deleteSlot()
    {
        if (!auth()->user()->hasPermission('advertisements.delete')) {
            session()->flash('error', 'You do not have permission to delete ad slots.');
            $this->showDeleteModal = false;
            return;
        }

        $slot = AdSlot::find($this->deleteSlotId);
        if ($slot) {
            $slot->delete();
            session()->flash('success', 'Ad slot deleted successfully!');
        }

        $this->reset(['showDeleteModal', 'deleteSlotId']);
    }

    public function render()
    {
        $query = AdSlot::query()->withCount('campaigns');

        // Apply search filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('slug', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        // Apply location filter
        if ($this->locationFilter) {
            $query->where('location', $this->locationFilter);
        }

        // Apply status filter
        if ($this->statusFilter !== '') {
            $query->where('is_active', $this->statusFilter === 'active');
        }

        // Get statistics
        $allSlots = AdSlot::all();
        $statistics = [
            'total' => $allSlots->count(),
            'active' => $allSlots->where('is_active', true)->count(),
            'inactive' => $allSlots->where('is_active', false)->count(),
            'total_campaigns' => $allSlots->sum(fn($s) => $s->campaigns()->count()),
        ];

        // Get location breakdown for 4th stat card
        $locationCounts = $allSlots->groupBy('location')->map->count()->sortDesc();
        $topLocation = $locationCounts->keys()->first();

        return view('livewire.admin.advertisement.ad-slot-list', [
            'slots' => $query->orderBy('created_at', 'desc')->paginate($this->perPage),
            'statistics' => $statistics,
            'topLocation' => $topLocation,
            'locationCount' => $locationCounts->first() ?? 0,
        ]);
    }
}
