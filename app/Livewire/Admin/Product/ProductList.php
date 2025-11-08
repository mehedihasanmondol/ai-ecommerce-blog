<?php

namespace App\Livewire\Admin\Product;

use App\Modules\Ecommerce\Brand\Models\Brand;
use App\Modules\Ecommerce\Category\Models\Category;
use App\Modules\Ecommerce\Product\Models\Product;
use App\Modules\Ecommerce\Product\Repositories\ProductRepository;
use App\Modules\Ecommerce\Product\Services\ProductService;
use Livewire\Component;
use Livewire\WithPagination;

class ProductList extends Component
{
    use WithPagination;

    public $search = '';
    public $categoryFilter = '';
    public $brandFilter = '';
    public $typeFilter = '';
    public $statusFilter = '';
    public $perPage = 15;
    public $sortBy = 'id';
    public $sortOrder = 'desc';

    public $showFilters = false;
    public $showDeleteModal = false;
    public $productToDelete = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'categoryFilter' => ['except' => ''],
        'brandFilter' => ['except' => ''],
        'typeFilter' => ['except' => ''],
        'statusFilter' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter()
    {
        $this->resetPage();
    }

    public function updatingBrandFilter()
    {
        $this->resetPage();
    }

    public function updatingTypeFilter()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function sortByColumn($column)
    {
        if ($this->sortBy === $column) {
            $this->sortOrder = $this->sortOrder === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortOrder = 'asc';
        }
    }

    public function toggleFeatured($productId, ProductService $service)
    {
        $product = Product::find($productId);
        if ($product) {
            $service->toggleFeatured($product);
            $this->dispatch('product-updated');
        }
    }

    public function toggleActive($productId, ProductService $service)
    {
        $product = Product::find($productId);
        if ($product) {
            $service->toggleActive($product);
            $this->dispatch('product-updated');
        }
    }

    public function confirmDelete($productId)
    {
        $this->productToDelete = $productId;
        $this->showDeleteModal = true;
    }

    public function deleteProduct(ProductService $service)
    {
        if ($this->productToDelete) {
            $product = Product::find($this->productToDelete);
            if ($product) {
                $service->delete($product);
                session()->flash('success', 'Product deleted successfully!');
            }
        }

        $this->showDeleteModal = false;
        $this->productToDelete = null;
    }

    public function clearFilters()
    {
        $this->reset(['search', 'categoryFilter', 'brandFilter', 'typeFilter', 'statusFilter']);
        $this->resetPage();
    }

    public function render(ProductRepository $repository)
    {
        try {
            $filters = [
                'search' => $this->search,
                'category_id' => $this->categoryFilter,
                'brand_id' => $this->brandFilter,
                'product_type' => $this->typeFilter,
                'sort_by' => $this->sortBy,
                'sort_order' => $this->sortOrder,
            ];

            if ($this->statusFilter !== '') {
                $filters['is_active'] = $this->statusFilter === 'active';
            }

            $products = $repository->paginate($this->perPage, $filters);
            $categories = Category::orderBy('name')->get();
            $brands = Brand::orderBy('name')->get();

            return view('livewire.admin.product.product-list', [
                'products' => $products,
                'categories' => $categories,
                'brands' => $brands,
            ]);
        } catch (\Exception $e) {
            \Log::error('ProductList render error: ' . $e->getMessage());
            return view('livewire.admin.product.product-list', [
                'products' => new \Illuminate\Pagination\LengthAwarePaginator([], 0, 15),
                'categories' => collect([]),
                'brands' => collect([]),
                'error' => $e->getMessage(),
            ]);
        }
    }
}
