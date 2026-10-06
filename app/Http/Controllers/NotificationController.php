<?php

namespace App\Http\Controllers;

use App\Models\ServiceHarian;
use App\Models\Product;
use App\Models\DailySale;
use App\Models\SalesInstrument;
use App\Models\Assignment;
use App\Models\LeaveRequest;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class NotificationController extends Controller
{
    /**
     * Get all notifications count
     */
    public function getAllNotifications(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Sesi login habis. Silakan login ulang.',
            ], 401);
        }

        $sessionKeys = [
            'service-harian_last_viewed',
            'products_last_viewed',
            'daily-sales_last_viewed',
            'sales-instruments_last_viewed',
            'assignments_last_viewed',
            'leaves_last_viewed',
            'members_last_viewed',
        ];
        $sessionState = collect($sessionKeys)->mapWithKeys(fn ($key) => [$key => session($key)])->all();
        $cacheKey = 'notifications.all.'.$user->id.'.'.md5(json_encode($sessionState));

        $notifications = Cache::remember($cacheKey, now()->addSeconds(10), function () use ($user) {
            $notifications = [];

            // Service Harian - untuk admin, hitung yang dibuat oleh staff
            if ($user->role === 'admin') {
                $lastViewed = session('service-harian_last_viewed', now()->subDays(7)->toISOString());
                $count = ServiceHarian::where('created_at', '>', $lastViewed)->count();
                $notifications['service-harian'] = $count;
            }

            // Products - untuk admin, hitung yang input_system = 'Belum'
            if ($user->role === 'admin') {
                $lastViewed = session('products_last_viewed', now()->subDays(7)->toISOString());
                $count = Product::where('input_system', 'Belum')
                    ->where('created_at', '>', $lastViewed)
                    ->count();
                $notifications['products'] = $count;
            }

            // Daily Sales - untuk admin, hitung yang dibuat oleh staff
            if ($user->role === 'admin') {
                $lastViewed = session('daily-sales_last_viewed', now()->subDays(7)->toISOString());
                $count = DailySale::where('created_at', '>', $lastViewed)->count();
                $notifications['daily-sales'] = $count;
            }

            // Sales Instruments - untuk admin, hitung yang dibuat oleh staff
            if ($user->role === 'admin') {
                $lastViewed = session('sales-instruments_last_viewed', now()->subDays(7)->toISOString());
                $count = SalesInstrument::where('created_at', '>', $lastViewed)->count();
                $notifications['sales-instruments'] = $count;
            }

            // Assignments - untuk staff, hitung yang belum dibaca
            if ($user->role === 'staff') {
                $staff = \App\Models\Staff::where('user_id', $user->id)->first();
                if ($staff) {
                    $lastViewed = session('assignments_last_viewed', now()->subDays(7)->toISOString());
                    $count = Assignment::where('staff_id', $staff->id)
                        ->where('created_at', '>', $lastViewed)
                        ->count();
                    $notifications['assignments'] = $count;
                }
            }

            // Leaves - untuk admin, hitung yang status pending
            if ($user->role === 'admin') {
                $lastViewed = session('leaves_last_viewed', now()->subDays(7)->toISOString());
                $count = LeaveRequest::where('status', 'pending')
                    ->where('created_at', '>', $lastViewed)
                    ->count();
                $notifications['leaves'] = $count;
            }

            // Members - untuk admin, hitung member baru
            if ($user->role === 'admin') {
                $lastViewed = session('members_last_viewed', now()->subDays(7)->toISOString());
                $count = Member::where('created_at', '>', $lastViewed)->count();
                $notifications['members'] = $count;
            }

            return $notifications;
        });

        return response()->json([
            'success' => true,
            'notifications' => $notifications
        ]);
    }

    /**
     * Mark menu as viewed
     */
    public function markViewed(Request $request)
    {
        $menu = $request->input('menu');
        
        if (!$menu) {
            return response()->json(['success' => false, 'message' => 'Menu tidak valid'], 400);
        }

        // Set session untuk menu yang dibuka
        session(["{$menu}_last_viewed" => now()->toISOString()]);

        return response()->json([
            'success' => true,
            'message' => 'Menu marked as viewed'
        ]);
    }
}
