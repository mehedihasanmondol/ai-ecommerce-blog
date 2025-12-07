<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Admin Appointment Controller
 * 
 * Handles admin panel appointment management views
 * 
 * @category Controllers
 * @package  Admin
 * @created  2025-11-26
 */
class AppointmentController extends Controller
{
    /**
     * Display appointment management page
     */
    public function index()
    {
        // Verify user has permission to view appointments
        if (!auth()->user()->hasPermission('appointments.view')) {
            abort(403, 'You do not have permission to view appointments.');
        }

        return view('admin.appointments.index');
    }
}
