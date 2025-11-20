# Deployment Checklist

✅ php artisan db:seed --class=SiteSettingSeeder (Completed)

✅ php artisan db:seed --class=HomepageSettingSeeder (Completed)

.env setup for google cloud google+ api

php artisan cache:clear-megamenu

php artisan migrate --path=database/migrations/2025_11_20_000001_migrate_category_id_to_pivot_table.php

php artisan migrate --path=database/migrations/2025_11_20_000002_drop_category_id_from_products.php

## Theme System Setup (NEW - Nov 20, 2025)

# Step 1: Load helper functions
composer dump-autoload

# Step 2: Create theme database table
php artisan migrate --path=database/migrations/2025_11_20_100000_create_theme_settings_table.php

# Step 3: Add frontend theme columns (50+ variables)
php artisan migrate --path=database/migrations/2025_11_20_100001_add_frontend_theme_columns.php

# Step 4: Seed predefined themes (6 themes with frontend colors)
php artisan db:seed --class=ThemeSettingSeeder

# Step 5: Clear caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# ✅ Setup complete!
# Access: /admin/theme-settings
# Menu: Admin Sidebar → System → Theme Settings
# Features: 70+ Admin + 50+ Frontend theme variables
# Docs: development-docs/theme-system-complete.md
# Frontend Guide: development-docs/frontend-theme-implementation.md


## Login Page Settings Implemented
- Login page title and content (TinyMCE)
- Social login enable/disable toggles
- TinyMCE editor support added to admin panel

## TinyMCE API Key Management
- ✅ Added TinyMCE API key setting to site settings (General group)
- ✅ Updated all 6 files to use dynamic API key from settings:
  - `resources/views/admin/blog/posts/create.blade.php`
  - `resources/views/admin/blog/posts/edit.blade.php`
  - `resources/views/admin/product/create-livewire.blade.php`
  - `resources/views/admin/product/edit-livewire.blade.php`
  - `resources/views/admin/footer-management/index.blade.php`
  - `resources/views/admin/site-settings/index.blade.php`
- ✅ Default API key: `8wacbe3zs5mntet5c9u50n4tenlqvgqm9bn1k6uctyqo3o7m`
- 🔧 Admin can now change API key from: Admin Panel → Site Settings → General → TinyMCE API Key
