# Deployment Checklist

✅ php artisan db:seed --class=SiteSettingSeeder (Completed)


.env setup for google cloud google+ api


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