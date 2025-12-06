<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewArrivalProduct;
use App\Models\SiteSetting;
use App\Modules\Ecommerce\Product\Models\Product;
use Illuminate\Http\Request;

class NewArrivalProductController extends Controller
{
    /**
     * Display new arrival products management
     */
    public function index()
    {
        abort_if(!auth()->user()->hasPermission('new-arrivals.view'), 403, 'You do not have permission to view new arrival products.');

        $newArrivalProducts = NewArrivalProduct::with('product.variants')
            ->orderBy('sort_order')
            ->get();

        // Get section settings
        $sectionEnabled = SiteSetting::get('new_arrivals_section_enabled', '1');
        $sectionTitle = SiteSetting::get('new_arrivals_section_title', 'New Arrivals');

        return view('admin.new-arrival-products.index', compact('newArrivalProducts', 'sectionEnabled', 'sectionTitle'));
    }

    /**
     * Add product to new arrivals
     */
    public function store(Request $request)
    {
        abort_if(!auth()->user()->hasPermission('new-arrivals.create'), 403, 'You do not have permission to add new arrival products.');

        $validated = $request->validate([
            'product_id' => 'required|exists:products,id|unique:new_arrival_products,product_id',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $validated['sort_order'] = $validated['sort_order'] ?? NewArrivalProduct::max('sort_order') + 1;
        $validated['is_active'] = true;

        NewArrivalProduct::create($validated);

        return redirect()->route('admin.new-arrival-products.index')
            ->with('success', 'Product added to new arrivals successfully!');
    }

    /**
     * Update sort order
     */
    public function updateOrder(Request $request)
    {
        // Check permission for AJAX request
        if (!auth()->user()->hasPermission('new-arrivals.edit')) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to reorder new arrival products.',
            ], 403);
        }

        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:new_arrival_products,id',
            'items.*.sort_order' => 'required|integer|min:0',
        ]);

        foreach ($validated['items'] as $item) {
            NewArrivalProduct::where('id', $item['id'])
                ->update(['sort_order' => $item['sort_order']]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Toggle active status
     */
    public function toggleStatus(NewArrivalProduct $newArrivalProduct)
    {
        // Check permission for AJAX request
        if (!auth()->user()->hasPermission('new-arrivals.edit')) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to toggle new arrival product status.',
            ], 403);
        }

        $newArrivalProduct->is_active = !$newArrivalProduct->is_active;
        $newArrivalProduct->save();

        return response()->json([
            'success' => true,
            'is_active' => $newArrivalProduct->is_active,
        ]);
    }

    /**
     * Remove from new arrivals
     */
    public function destroy(NewArrivalProduct $newArrivalProduct)
    {
        abort_if(!auth()->user()->hasPermission('new-arrivals.delete'), 403, 'You do not have permission to remove new arrival products.');

        $newArrivalProduct->delete();

        return redirect()->route('admin.new-arrival-products.index')
            ->with('success', 'Product removed from new arrivals successfully!');
    }

    /**
     * Toggle section visibility on homepage
     */
    public function toggleSection(Request $request)
    {
        // Check permission for AJAX request
        if (!auth()->user()->hasPermission('new-arrivals.edit')) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to toggle section visibility.',
            ], 403);
        }

        SiteSetting::updateOrCreate(
            ['key' => 'new_arrivals_section_enabled'],
            ['value' => $request->enabled ? '1' : '0']
        );

        return response()->json(['success' => true]);
    }

    /**
     * Update section title
     */
    public function updateSectionTitle(Request $request)
    {
        // Check permission for AJAX request
        if (!auth()->user()->hasPermission('new-arrivals.edit')) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to update section title.',
            ], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        SiteSetting::updateOrCreate(
            ['key' => 'new_arrivals_section_title'],
            ['value' => $validated['title']]
        );

        return response()->json(['success' => true]);
    }

    /**
     * Search products for adding
     */
    public function searchProducts(Request $request)
    {
        // Check permission for AJAX request
        if (!auth()->user()->hasPermission('new-arrivals.create')) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to search products.',
            ], 403);
        }

        $search = $request->get('q', '');

        if (empty($search)) {
            return response()->json([]);
        }

        $existingProductIds = NewArrivalProduct::pluck('product_id')->toArray();

        $products = Product::where(function ($query) use ($search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('sku', 'like', "%{$search}%");
        })
            ->whereNotIn('id', $existingProductIds)
            ->where('is_active', true)
            ->with(['variants', 'images'])
            ->limit(10)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku ?? 'N/A',
                    'price' => $product->price,
                    'image_url' => $product->image_url ?? ($product->images->first()->image_path ?? null),
                ];
            });

        return response()->json($products);
    }
}
