<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\SystemSetting;

class CheckMaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Always allow access to login, logout, and auth routes
        $excludedRoutes = [
            'login',
            'logout',
            'register',
            'password.request',
            'password.email',
            'password.reset',
            'password.update',
            'verification.notice',
            'verification.verify',
            'verification.send',
        ];
        
        if ($request->routeIs($excludedRoutes) || $request->is('login') || $request->is('logout')) {
            return $next($request);
        }
        
        // Check if maintenance mode is enabled
        $maintenanceMode = SystemSetting::get('maintenance_mode', false);
        
        if ($maintenanceMode) {
            // Allow all logged-in admin panel users to bypass maintenance mode
            // (admin, super_admin, author, manager - anyone with admin.access)
            if (auth()->check() && (
                auth()->user()->hasRole('super_admin') || 
                auth()->user()->hasRole('admin') || 
                auth()->user()->hasRole('author') ||
                auth()->user()->hasRole('manager')
            )) {
                return $next($request);
            }
            
            // Show maintenance page to regular users
            $title = SystemSetting::get('maintenance_title', 'We\'ll be back soon!');
            $message = SystemSetting::get('maintenance_message', 'Sorry for the inconvenience. We\'re performing some maintenance at the moment.');
            $image = SystemSetting::get('maintenance_image', '');
            
            return response()->view('maintenance', compact('title', 'message', 'image'), 503);
        }
        
        return $next($request);
    }
}
