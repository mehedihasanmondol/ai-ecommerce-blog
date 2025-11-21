## Universal Image Uploader Deployment

# Step 1: Run migration for media_library tables
php artisan migrate --path=database/migrations/2025_11_20_130000_create_media_library_table.php

# Step 2: Add media_id to categories table
php artisan migrate --path=database/migrations/2025_11_21_020000_add_media_id_to_categories_table.php

# Step 3: Seed image upload settings
php artisan db:seed --class=ImageUploadSettingSeeder

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


