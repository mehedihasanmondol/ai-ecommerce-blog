<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SecondaryMenuItem;
use Illuminate\Http\Request;

/**
 * SecondaryMenuController
 * Purpose: Manage secondary navigation menu items from admin panel (Livewire-based)
 * 
 * @author AI Assistant
 * @date 2025-11-06
 * @updated 2025-11-06
 */
class SecondaryMenuController extends Controller
{
    /**
     * Display secondary menu settings with Livewire component
     */
    public function index()
    {
        abort_if(!auth()->user()->hasPermission('secondary-menu.manage'), 403, 'You do not have permission to manage secondary menu.');

        return view('admin.secondary-menu.index');
    }

}
