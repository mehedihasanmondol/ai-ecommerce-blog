<?php

namespace App\Livewire\Admin;

use App\Models\HeroSlider;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

/**
 * HeroSliderManager Livewire Component
 * Purpose: Manage hero sliders with create, edit, delete, and reorder functionality
 */
class HeroSliderManager extends Component
{
    use WithFileUploads;

    public $sliders;
    public $editingId = null;
    public $showModal = false;
    
    // Form fields
    public $title;
    public $subtitle;
    public $image;
    public $existingImage;
    public $link;
    public $button_text;
    public $is_active = true;
    public $order;

    protected $rules = [
        'title' => 'required|string|max:255',
        'subtitle' => 'nullable|string|max:255',
        'image' => 'nullable|image|max:2048',
        'link' => 'nullable|url|max:255',
        'button_text' => 'nullable|string|max:50',
        'is_active' => 'boolean',
        'order' => 'nullable|integer',
    ];

    public function mount()
    {
        $this->loadSliders();
    }

    public function loadSliders()
    {
        $this->sliders = HeroSlider::orderBy('order')->get();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->editingId = null;
        $this->showModal = true;
    }

    public function openEditModal($id)
    {
        $slider = HeroSlider::findOrFail($id);
        
        $this->editingId = $slider->id;
        $this->title = $slider->title;
        $this->subtitle = $slider->subtitle;
        $this->existingImage = $slider->image;
        $this->link = $slider->link;
        $this->button_text = $slider->button_text;
        $this->is_active = $slider->is_active;
        $this->order = $slider->order;
        
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        try {
            $data = [
                'title' => $this->title,
                'subtitle' => $this->subtitle,
                'link' => $this->link,
                'button_text' => $this->button_text,
                'is_active' => $this->is_active,
            ];

            // Handle image upload
            if ($this->image) {
                // Delete old image if editing
                if ($this->editingId && $this->existingImage) {
                    Storage::disk('public')->delete($this->existingImage);
                }
                
                $data['image'] = $this->image->store('sliders', 'public');
            }

            // Set order
            if (!$this->order) {
                $data['order'] = HeroSlider::max('order') + 1;
            } else {
                $data['order'] = $this->order;
            }

            if ($this->editingId) {
                $slider = HeroSlider::findOrFail($this->editingId);
                $slider->update($data);
                $message = 'Slider updated successfully!';
            } else {
                HeroSlider::create($data);
                $message = 'Slider created successfully!';
            }

            $this->loadSliders();
            $this->showModal = false;
            $this->resetForm();
            
            $this->dispatch('slider-saved', [
                'message' => $message,
                'type' => 'success'
            ]);

        } catch (\Exception $e) {
            $this->dispatch('slider-saved', [
                'message' => 'Error: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function deleteSlider($id)
    {
        try {
            $slider = HeroSlider::findOrFail($id);
            $slider->delete();
            
            $this->loadSliders();
            
            $this->dispatch('slider-saved', [
                'message' => 'Slider deleted successfully!',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('slider-saved', [
                'message' => 'Error deleting slider: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function toggleActive($id)
    {
        try {
            $slider = HeroSlider::findOrFail($id);
            $slider->update(['is_active' => !$slider->is_active]);
            
            $this->loadSliders();
            
            $this->dispatch('slider-saved', [
                'message' => 'Slider status updated!',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('slider-saved', [
                'message' => 'Error: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function updateOrder($orderedIds)
    {
        try {
            foreach ($orderedIds as $index => $id) {
                HeroSlider::where('id', $id)->update(['order' => $index + 1]);
            }
            
            $this->loadSliders();
            
            $this->dispatch('slider-saved', [
                'message' => 'Slider order updated!',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('slider-saved', [
                'message' => 'Error updating order: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->reset(['title', 'subtitle', 'image', 'existingImage', 'link', 'button_text', 'order']);
        $this->is_active = true;
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.admin.hero-slider-manager');
    }
}
