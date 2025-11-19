<?php

/**
 * Debug script to check trending brands for categories
 * Run: php debug-trending-brands.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Modules\Ecommerce\Category\Models\Category;
use Illuminate\Support\Facades\DB;

echo "=== TRENDING BRANDS DEBUG ===\n\n";

// Find supplements category
$supplements = Category::where('name', 'LIKE', '%supplement%')
    ->orWhere('name', 'LIKE', '%Supplement%')
    ->first();

if (!$supplements) {
    echo "❌ No supplements category found!\n";
    echo "Available categories:\n";
    Category::where('is_active', true)->get()->each(function($cat) {
        echo "  - ID: {$cat->id}, Name: {$cat->name}, Parent: {$cat->parent_id}\n";
    });
    exit;
}

echo "✅ Found category: {$supplements->name} (ID: {$supplements->id})\n\n";

// Get all descendant categories
$categoryIds = [$supplements->id];

$children = Category::where('parent_id', $supplements->id)
    ->where('is_active', true)
    ->get();

echo "Child categories: " . $children->count() . "\n";
foreach ($children as $child) {
    echo "  - {$child->name} (ID: {$child->id})\n";
    $categoryIds[] = $child->id;
    
    // Get grandchildren
    $grandchildren = Category::where('parent_id', $child->id)
        ->where('is_active', true)
        ->get();
    
    foreach ($grandchildren as $grandchild) {
        echo "    - {$grandchild->name} (ID: {$grandchild->id})\n";
        $categoryIds[] = $grandchild->id;
    }
}

echo "\nAll category IDs to check: " . implode(', ', $categoryIds) . "\n\n";

// Check products in these categories
$productsCount = DB::table('products')
    ->whereIn('category_id', $categoryIds)
    ->whereNull('deleted_at')
    ->count();

echo "Products in these categories: {$productsCount}\n";

// Check products with brands
$productsWithBrands = DB::table('products')
    ->whereIn('category_id', $categoryIds)
    ->whereNotNull('brand_id')
    ->whereNull('deleted_at')
    ->count();

echo "Products with brand_id set: {$productsWithBrands}\n\n";

// Check orders
$ordersCount = DB::table('orders')
    ->where('status', '!=', 'cancelled')
    ->where('status', '!=', 'failed')
    ->where('created_at', '>=', now()->subDays(30))
    ->count();

echo "Valid orders (last 30 days): {$ordersCount}\n\n";

// Check order items for these categories
$orderItems = DB::table('order_items')
    ->select('products.brand_id', 'products.name as product_name', 'brands.name as brand_name', DB::raw('SUM(order_items.quantity) as total_sales'))
    ->join('orders', 'orders.id', '=', 'order_items.order_id')
    ->join('products', 'products.id', '=', 'order_items.product_id')
    ->leftJoin('brands', 'brands.id', '=', 'products.brand_id')
    ->whereIn('products.category_id', $categoryIds)
    ->where('orders.status', '!=', 'cancelled')
    ->where('orders.status', '!=', 'failed')
    ->where('orders.created_at', '>=', now()->subDays(30))
    ->whereNull('products.deleted_at')
    ->groupBy('products.brand_id', 'products.name', 'brands.name')
    ->orderByDesc('total_sales')
    ->get();

echo "Order items found: " . $orderItems->count() . "\n";

if ($orderItems->count() > 0) {
    echo "\nTrending brands based on sales:\n";
    foreach ($orderItems as $item) {
        echo "  - Brand: " . ($item->brand_name ?? 'NULL') . " (ID: {$item->brand_id}) - {$item->total_sales} sales\n";
        echo "    Product: {$item->product_name}\n";
    }
} else {
    echo "\n❌ No order items found for these categories!\n";
    echo "\nDiagnostic checks:\n";
    
    // Check if ANY order items exist
    $anyOrderItems = DB::table('order_items')->count();
    echo "Total order items in database: {$anyOrderItems}\n";
    
    // Check products in order items
    $productsInOrders = DB::table('order_items')
        ->join('products', 'products.id', '=', 'order_items.product_id')
        ->select('products.id', 'products.name', 'products.category_id', 'products.brand_id')
        ->distinct()
        ->get();
    
    echo "\nProducts that have been ordered:\n";
    foreach ($productsInOrders as $prod) {
        echo "  - {$prod->name} (Category: {$prod->category_id}, Brand: {$prod->brand_id})\n";
    }
}

echo "\n=== DEBUG COMPLETE ===\n";
