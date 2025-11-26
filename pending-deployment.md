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
# Frontend: Author profile pages (responsive layout)

# ✅ FEEDBACK ENHANCEMENTS COMPLETE!
# - Customizable feedback title (Site Settings > Feedback)
# - Time display toggle (Site Settings > Feedback)
# - Helpful/Not Helpful voting toggle (Site Settings > Feedback)
# - Full width when appointments disabled
# - No "Coming Soon" content when appointments off
# - Settings work on both author profile AND /feedback page

# ✅ AUTHOR PROFILE WHATSAPP ADDED!
# - WhatsApp social link added to author profiles
# - Shows with other social links (Twitter, Facebook, etc.)
# - Admin can add WhatsApp number in user create/edit forms
# - Format: Include country code (e.g., 8801712345678)




php artisan make:migration add_whatsapp_to_author_profiles_table
