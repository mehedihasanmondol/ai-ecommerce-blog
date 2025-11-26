php artisan migrate --path=database/migrations/2025_11_25_150714_create_system_settings_table.php

php artisan db:seed --class=SystemSettingSeeder
php artisan db:seed --class=RolePermissionSeeder

 php artisan migrate --path=database/migrations/2025_11_26_000001_create_feedback_votes_table.php

# Seed feedback settings
php artisan db:seed --class=SiteSettingSeeder

# Fix users email unique constraint
php artisan migrate --path=database/migrations/2025_11_26_071500_modify_users_email_unique.php

# Clear caches
php artisan view:clear
php artisan config:clear
php artisan cache:clear
