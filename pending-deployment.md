## Universal Image Uploader Deployment

# Step 1: Run migration for media_library tables
php artisan migrate --path=database/migrations/2025_11_20_130000_create_media_library_table.php

# Step 2: Add media_id to categories table
php artisan migrate --path=database/migrations/2025_11_21_020000_add_media_id_to_categories_table.php

# Step 3: Add media_id to brands table
php artisan migrate --path=database/migrations/2025_11_21_094126_add_media_id_to_brands_table.php

# Step 4: Add media_id to product_images table
php artisan migrate --path=database/migrations/2025_11_21_101407_add_media_id_to_product_images_table.php

# Step 5: Add media_id to product_variants table
php artisan migrate --path=database/migrations/2025_11_21_101437_add_media_id_to_product_variants_table.php

php artisan migrate --path=database/migrations/2025_11_21_121700_make_product_images_paths_nullable.php
# Step 3: Seed image upload settings
php artisan db:seed --class=ImageUploadSettingSeeder

# Step 6: Add media_id to blog_posts table
php artisan migrate --path=database/migrations/2025_11_22_000001_add_media_id_to_blog_posts_table.php

php artisan migrate --path=database/migrations/2025_11_22_000002_add_media_id_to_users_table.php

php artisan migrate --path=database/migrations/2025_11_22_000003_add_media_id_to_author_profiles_table.php

php artisan migrate --path=database/migrations/2025_11_23_000033_update_login_settings_to_ckeditor.php

php artisan migrate --path=database/migrations/2025_11_23_001611_add_media_id_to_hero_sliders_table.php

php artisan db:seed --class=SiteSettingSeeder
php artisan cache:clear
php artisan view:clear


# Step 4: Build JavaScript assets (includes CropperJS)
npm run build

# Step 5: Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
php artisan optimize:clear

# ✅ Universal Image Uploader Ready!
# ✅ Product Categories now support image upload with cropping!
# Usage: <x-image-uploader target-field="your_field" />
# Docs: development-docs/universal-image-uploader-documentation.md

# 1. Run media library migration (if not done)
php artisan migrate --path=database/migrations/2025_11_20_130000_create_media_library_table.php

# 2. Add media_id to categories table
php artisan migrate --path=database/migrations/2025_11_21_020000_add_media_id_to_categories_table.php

# 3. Seed settings (if not done)
php artisan db:seed --class=ImageUploadSettingSeeder

# 4. Build assets (if not done)
npm run build

# 5. Clear caches
php artisan optimize:clear


