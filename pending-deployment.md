php artisan migrate --path=database/migrations/2025_11_25_150714_create_system_settings_table.php

php artisan db:seed --class=SystemSettingSeeder
php artisan db:seed --class=RolePermissionSeeder