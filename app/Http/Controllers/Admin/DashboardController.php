<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Modules\User\Models\Role;
use App\Modules\User\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard
     */
    public function index()
    {
        // User Statistics
        $totalUsers = User::count();
        $activeUsers = User::where('is_active', true)->count();
        $inactiveUsers = User::where('is_active', false)->count();
        $newUsersThisMonth = User::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Role Distribution
        $roleDistribution = User::select('role', DB::raw('count(*) as count'))
            ->groupBy('role')
            ->get();

        // Recent Users
        $recentUsers = User::with('roles')
            ->latest()
            ->take(5)
            ->get();

        // Recent Activities
        $recentActivities = UserActivity::with('user')
            ->latest()
            ->take(10)
            ->get();

        // User Growth (Last 7 days)
        $userGrowth = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $userGrowth[] = [
                'date' => $date->format('M d'),
                'count' => User::whereDate('created_at', $date->toDateString())->count(),
            ];
        }

        // Activity Types Count
        $activityTypes = UserActivity::select('activity_type', DB::raw('count(*) as count'))
            ->groupBy('activity_type')
            ->get();

        // Top Active Users (by activity count)
        $topActiveUsers = User::withCount('activities')
            ->orderBy('activities_count', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard.index', compact(
            'totalUsers',
            'activeUsers',
            'inactiveUsers',
            'newUsersThisMonth',
            'roleDistribution',
            'recentUsers',
            'recentActivities',
            'userGrowth',
            'activityTypes',
            'topActiveUsers'
        ));
    }
}
