<?php

namespace App\Livewire\Admin\SystemSettings;

use Livewire\Component;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class CacheManagement extends Component
{
    public $loading = false;
    public $successMessage = '';

    /**
     * Clear application cache
     */
    public function clearAppCache()
    {
        if (!auth()->user()->hasPermission('system.cache')) {
            session()->flash('error', 'You do not have permission to manage cache.');
            return;
        }

        try {
            Artisan::call('cache:clear');
            $this->successMessage = 'Application cache cleared successfully!';
            $this->dispatch('cache-cleared', type: 'application');
        } catch (\Exception $e) {
            $this->dispatch('cache-error', message: $e->getMessage());
        }
    }

    /**
     * Clear route cache
     */
    public function clearRouteCache()
    {
        if (!auth()->user()->hasPermission('system.cache')) {
            session()->flash('error', 'You do not have permission to manage cache.');
            return;
        }

        try {
            Artisan::call('route:clear');
            $this->successMessage = 'Route cache cleared successfully!';
            $this->dispatch('cache-cleared', type: 'route');
        } catch (\Exception $e) {
            $this->dispatch('cache-error', message: $e->getMessage());
        }
    }

    /**
     * Clear config cache
     */
    public function clearConfigCache()
    {
        if (!auth()->user()->hasPermission('system.cache')) {
            session()->flash('error', 'You do not have permission to manage cache.');
            return;
        }

        try {
            Artisan::call('config:clear');
            $this->successMessage = 'Configuration cache cleared successfully!';
            $this->dispatch('cache-cleared', type: 'config');
        } catch (\Exception $e) {
            $this->dispatch('cache-error', message: $e->getMessage());
        }
    }

    /**
     * Clear view cache
     */
    public function clearViewCache()
    {
        if (!auth()->user()->hasPermission('system.cache')) {
            session()->flash('error', 'You do not have permission to manage cache.');
            return;
        }

        try {
            Artisan::call('view:clear');
            $this->successMessage = 'View cache cleared successfully!';
            $this->dispatch('cache-cleared', type: 'view');
        } catch (\Exception $e) {
            $this->dispatch('cache-error', message: $e->getMessage());
        }
    }

    /**
     * Clear all caches
     */
    public function clearAllCache()
    {
        if (!auth()->user()->hasPermission('system.cache')) {
            session()->flash('error', 'You do not have permission to manage cache.');
            return;
        }

        try {
            Artisan::call('cache:clear');
            Artisan::call('route:clear');
            Artisan::call('config:clear');
            Artisan::call('view:clear');
            Artisan::call('optimize:clear');

            $this->successMessage = 'All caches cleared successfully!';
            $this->dispatch('cache-cleared', type: 'all');
        } catch (\Exception $e) {
            $this->dispatch('cache-error', message: $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.system-settings.cache-management');
    }
}
