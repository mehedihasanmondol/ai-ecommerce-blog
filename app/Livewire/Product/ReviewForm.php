<?php

namespace App\Livewire\Product;

use App\Modules\Ecommerce\Product\Services\ProductReviewService;
use Livewire\Component;
use Livewire\WithFileUploads;

/**
 * ModuleName: Review Form Livewire Component
 * Purpose: Handle review submission with image uploads
 * 
 * @category Livewire
 * @package  Product
 * @author   Windsurf AI
 * @created  2025-11-08
 */
class ReviewForm extends Component
{
    use WithFileUploads;

    public $productId;
    public $rating = 0;
    public $title = '';
    public $review = '';
    public $images = [];
    public $reviewerName = '';
    public $reviewerEmail = '';

    protected function rules()
    {
        $rules = [
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'review' => 'required|string|min:10|max:2000',
            'images.*' => 'nullable|image|max:2048',
        ];

        if (!auth()->check()) {
            $rules['reviewerName'] = 'required|string|max:255';
            $rules['reviewerEmail'] = 'required|email|max:255';
        }

        return $rules;
    }

    public function mount($productId)
    {
        $this->productId = $productId;
    }

    public function setRating($rating)
    {
        $this->rating = $rating;
    }

    public function removeImage($index)
    {
        array_splice($this->images, $index, 1);
    }

    public function submit()
    {
        $this->validate();

        try {
            $data = [
                'product_id' => $this->productId,
                'rating' => $this->rating,
                'title' => $this->title,
                'review' => $this->review,
                'images' => $this->images,
            ];

            if (!auth()->check()) {
                $data['reviewer_name'] = $this->reviewerName;
                $data['reviewer_email'] = $this->reviewerEmail;
            }

            app(ProductReviewService::class)->createReview($data);

            // Reset form
            $this->reset(['rating', 'title', 'review', 'images', 'reviewerName', 'reviewerEmail']);

            session()->flash('success', 'Thank you! Your review has been submitted and is pending approval.');
            $this->dispatch('review-submitted');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.product.review-form');
    }
}
