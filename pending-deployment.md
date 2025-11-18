php artisan db:seed --class=RolePermissionSeeder  

# Re-seeded permissions
php artisan route:clear                           
# Cleared route cache
php artisan optimize:clear                        
# Cleared all caches

php artisan migrate --path=database/migrations/2025_11_18_000001_add_homepage_section_settings_to_site_settings.php

php artisan migrate --path=database/migrations/2025_11_18_000002_add_footer_section_enable_disable_settings.php

php artisan migrate --path=database/migrations/2025_11_18_064000_add_currency_settings_to_site_settings.php


php artisan db:seed --class=SiteSettingSeeder

php artisan migrate --path=database/migrations/2025_11_18_105344_create_blog_post_product_table.php