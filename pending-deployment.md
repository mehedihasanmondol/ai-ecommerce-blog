php artisan migrate --path=database/migrations/2025_11_25_150714_create_system_settings_table.php

php artisan db:seed --class=SystemSettingSeeder

php artisan db:seed --class=RolePermissionSeeder

php artisan migrate --path=database/migrations/2025_11_25_162223_create_feedback_table.php

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



--------------------------------



php artisan migrate --path=database/migrations/2025_11_26_025300_create_chambers_table.php

php artisan migrate --path=database/migrations/2025_11_26_025301_create_appointments_table.php

php artisan db:seed --class=RolePermissionSeeder
php artisan db:seed --class=SiteSettingSeeder
php artisan db:seed --class=ChamberSeeder

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# ✅ APPOINTMENT SYSTEM COMPLETE!
# Admin: /admin/appointments
# Frontend: Author profile pages (60/40 sticky layout)
