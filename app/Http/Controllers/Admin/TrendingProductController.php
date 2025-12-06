<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TrendingProduct;
use App\Models\SiteSetting;
use App\Modules\Ecommerce\Product\Models\Product;
use Illuminate\Http\Request;

class TrendingProductController extends Controller
{
    /**
     * Display trending products management
     */
    public function index()
    {
        abort_if(!auth()->user()->hasPermission('trending-products.view'), 403, 'You do not have permission to view trending products.');

        $trendingProducts = TrendingProduct::with('product.variants')
            ->orderBy('sort_order')
            ->get();

        // Get section settings
        $sectionEnabled = SiteSetting::get('trending_section_enabled', '1');
        $sectionTitle = SiteSetting::get('trending_section_title', 'Trending Now');

        return view('admin.trending-products.index', compact('trendingProducts', 'sectionEnabled', 'sectionTitle'));
    }

    /**
     * Add product to trending
     */
    public function store(Request $request)
    {
        abort_if(!auth()->user()->hasPermission('trending-products.create'), 403, 'You do not have permission to add trending products.');

        $validated = $request->validate([
            'product_id' => 'required|exists:products,id|unique:trending_products,product_id',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $validated['sort_order'] = $validated['sort_order'] ?? TrendingProduct::max('sort_order') + 1;
        $validated['is_active'] = true;

        TrendingProduct::create($validated);

        return redirect()->route('admin.trending-products.index')
            ->with('success', 'Product added to trending successfully!');
    }

    /**
     * Update sort order
     */
    public function updateOrder(Request $request)
    {
        // Check permission for AJAX request
        if (!auth()->user()->hasPermission('trending-products.edit')) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to reorder trending products.',
            ], 403);
        }

        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:trending_products,id',
            'items.*.sort_order' => 'required|integer|min:0',
        ]);

        foreach ($validated['items'] as $item) {
            TrendingProduct::where('id', $item['id'])
                ->update(['sort_order' => $item['sort_order']]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Toggle active status
     */
    public function toggleStatus(TrendingProduct $trendingProduct)
    {
        // Check permission for AJAX request
        if (!auth()->user()->hasPermission('trending-products.edit')) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to toggle trending product status.',
            ], 403);
        }

        $trendingProduct->is_active = !$trendingProduct->is_active;
        $trendingProduct->save();

        return response()->json([
            'success' => true,
            'is_active' => $trendingProduct->is_active,
        ]);
    }

    /**
     * Remove from trending
     */
    public function destroy(TrendingProduct $trendingProduct)
    {
        abort_if(!auth()->user()->hasPermission('trending-products.delete'), 403, 'You do not have permission to remove trending products.');

        $trendingProduct->delete();

        return redirect()->route('admin.trending-products.index')
            ->with('success', 'Product removed from trending successfully!');
    }

    /**
     * Toggle section visibility on homepage
     */
    public function toggleSection(Request $request)
    {
        // Check permission for AJAX request
        if (!auth()->user()->hasPermission('trending-products.edit')) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to toggle section visibility.',
            ], 403);
        }

        SiteSetting::updateOrCreate(
            ['key' => 'trending_section_enabled'],
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
        if (!auth()->user()->hasPermission('trending-products.edit')) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to update section title.',
            ], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        SiteSetting::updateOrCreate(
            ['key' => 'trending_section_title'],
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
        if (!auth()->user()->hasPermission('trending-products.create')) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to search products.',
            ], 403);
        }

        $search = $request->get('q', '');

        if (empty($search)) {
            return response()->json([]);
        }

        $existingProductIds = TrendingProduct::pluck('product_id')->toArray();

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
