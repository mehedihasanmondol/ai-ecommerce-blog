<?php

namespace App\Http\Middleware;

use App\Traits\ModuleAccess;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * ModuleName: System Module Access Control
 * Purpose: Middleware to check if module is enabled before allowing access
 * 
 * This middleware checks if a module is enabled at the system level before
 * allowing access to routes. It should be applied to route groups to enforce
 * module-level access control.
 * 
 * Usage: Route::middleware(['auth', 'module:product'])->group(...)
 * 
 * @author AI Assistant
 * @date 2025-12-04
 */
class CheckModuleAccess
{
    use ModuleAccess;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $module): Response
    {
        // Check if the module is enabled
        if (!$this->isModuleEnabled($module)) {
            abort(403, "The {$this->getModuleName($module)} module is currently disabled.");
        }

        return $next($request);
    }
}
