<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Modules\Ecommerce\Order\Models\Order;
use App\Modules\User\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

/**
 * Customer Controller
 * 
 * Handles customer dashboard, profile, and account management
 */
class CustomerController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display customer dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Get user statistics
        $stats = [
            'total_orders' => Order::where('user_id', $user->id)->count(),
            'pending_orders' => Order::where('user_id', $user->id)->where('status', 'pending')->count(),
            'completed_orders' => Order::where('user_id', $user->id)->where('status', 'delivered')->count(),
            'wishlist_count' => $user->wishlistCount(),
        ];

        // Get recent orders
        $recentOrders = Order::where('user_id', $user->id)
            ->with('items')
            ->latest()
            ->take(5)
            ->get();

        return view('customer.dashboard', compact('stats', 'recentOrders'));
    }

    /**
     * Display customer profile
     */
    public function profile()
    {
        return view('customer.profile.index');
    }

    /**
     * Update customer profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'mobile' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            // Handle avatar upload
            if ($request->hasFile('avatar')) {
                // Delete old avatar if exists
                if ($user->avatar) {
                    Storage::disk('public')->delete($user->avatar);
                }

                $avatarPath = $request->file('avatar')->store('avatars', 'public');
                $validated['avatar'] = $avatarPath;
            }

            $this->userService->update($user->id, $validated);

            return redirect()
                ->route('customer.profile')
                ->with('success', 'Profile updated successfully!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to update profile. Please try again.');
        }
    }

    /**
     * Display account settings
     */
    public function settings()
    {
        return view('customer.settings.index');
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = Auth::user();

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()
                ->back()
                ->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        try {
            $user->update([
                'password' => Hash::make($request->password),
            ]);

            return redirect()
                ->route('customer.settings')
                ->with('success', 'Password updated successfully!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to update password. Please try again.');
        }
    }

    /**
     * Update email preferences
     */
    public function updatePreferences(Request $request)
    {
        $user = Auth::user();

        try {
            $user->update([
                'email_order_updates' => $request->has('email_order_updates'),
                'email_promotions' => $request->has('email_promotions'),
                'email_newsletter' => $request->has('email_newsletter'),
                'email_recommendations' => $request->has('email_recommendations'),
            ]);

            return redirect()
                ->route('customer.settings')
                ->with('success', 'Preferences updated successfully!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to update preferences. Please try again.');
        }
    }

    /**
     * Display addresses page
     */
    public function addresses()
    {
        return view('customer.addresses.index');
    }

    /**
     * Delete customer account
     */
    public function deleteAccount(Request $request)
    {
        $user = Auth::user();

        try {
            // Check if user has any pending orders
            $pendingOrders = Order::where('user_id', $user->id)
                ->whereIn('status', ['pending', 'processing', 'shipped'])
                ->count();

            if ($pendingOrders > 0) {
                return redirect()
                    ->back()
                    ->with('error', 'Cannot delete account with pending orders. Please wait for orders to complete.');
            }

            // Delete user avatar
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Soft delete or hard delete based on requirements
            $user->delete();

            // Logout user
            Auth::logout();

            return redirect()
                ->route('home')
                ->with('success', 'Your account has been deleted successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to delete account. Please contact support.');
        }
    }
}
