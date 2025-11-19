<?php

/**
 * Fix Product Categories - Assign products to correct categories
 * Run: php fix-product-categories.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== FIX PRODUCT CATEGORIES ===\n\n";

// Get all products in category 1 (Emery Barker - test category)
$productsInCat1 = DB::table('products')
    ->where('category_id', 1)
    ->whereNull('deleted_at')
    ->get();

echo "Found {$productsInCat1->count()} products in 'Emery Barker' category (ID: 1)\n\n";

if ($productsInCat1->count() === 0) {
    echo "No products to reassign.\n";
    exit;
}

// Show available Supplements subcategories
echo "Available Supplements subcategories:\n";
$supplementsCategories = DB::table('categories')
    ->where('parent_id', 3) // Supplements
    ->where('is_active', true)
    ->get();

foreach ($supplementsCategories as $cat) {
    echo "  {$cat->id}. {$cat->name}\n";
}

echo "\n";
echo "Current products:\n";
foreach ($productsInCat1 as $product) {
    echo "  - ID: {$product->id}, Name: {$product->name}, Brand: {$product->brand_id}\n";
}

echo "\n";
echo "========================================\n";
echo "CHOOSE ONE OF THE FOLLOWING OPTIONS:\n";
echo "========================================\n\n";

echo "Option 1: Assign ALL products to 'Vitamins' (ID: 4)\n";
echo "  Command: UPDATE products SET category_id = 4 WHERE category_id = 1 AND deleted_at IS NULL;\n\n";

echo "Option 2: Assign ALL products to 'Protein Supplements' (ID: 9)\n";
echo "  Command: UPDATE products SET category_id = 9 WHERE category_id = 1 AND deleted_at IS NULL;\n\n";

echo "Option 3: Assign to Supplements parent category (ID: 3)\n";
echo "  Command: UPDATE products SET category_id = 3 WHERE category_id = 1 AND deleted_at IS NULL;\n\n";

echo "========================================\n";
echo "TO APPLY A FIX, RUN ONE OF THESE:\n";
echo "========================================\n\n";

echo "For Vitamins:\n";
echo "  php artisan tinker --execute=\"DB::table('products')->where('category_id', 1)->whereNull('deleted_at')->update(['category_id' => 4]); echo 'Products moved to Vitamins';\"\n\n";

echo "For Protein Supplements:\n";
echo "  php artisan tinker --execute=\"DB::table('products')->where('category_id', 1)->whereNull('deleted_at')->update(['category_id' => 9]); echo 'Products moved to Protein Supplements';\"\n\n";

echo "For Supplements (parent):\n";
echo "  php artisan tinker --execute=\"DB::table('products')->where('category_id', 1)->whereNull('deleted_at')->update(['category_id' => 3]); echo 'Products moved to Supplements';\"\n\n";

echo "========================================\n";
echo "AFTER MOVING, RUN THIS TO CLEAR CACHE:\n";
echo "========================================\n";
echo "  php artisan cache:clear\n\n";

echo "=== SCRIPT COMPLETE ===\n";
