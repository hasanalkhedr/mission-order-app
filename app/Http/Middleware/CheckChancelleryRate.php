<?php
namespace App\Http\Middleware;

use App\Models\User;
use App\Notifications\ChancelleryRateMissingNotification;
use Closure;
use App\Models\ChancelleryRate;

class CheckChancelleryRate
{
    public function handle($request, Closure $next)
    {

        $missionRoutes = [
            'mission_orders/*/m_create',
            'mission_orders/*/m_edit',
            'tournees/*/m_create',
            'tournees/*/m_edit',
            'mission_orders/create',
            'mission_orders/*/edit',
            'tournees/create',
            'tournees/*/edit'
        ];

        foreach ($missionRoutes as $route) {
            if ($request->is($route)) {
                if (!ChancelleryRate::hasCurrentRate()) {
                    $notification = new ChancelleryRateMissingNotification();
                    $users = User::whereHas('employee', function ($query) {
                        $query->whereJsonContains('roles', 'controller');
                    })->get();
                    foreach ($users as $user) {
                        $user->notify($notification);
                    }
                    // return redirect()->route('/')
                    //     ->with('error', 'New missions cannot be created until admin updates the currency rate for this month.');
                    abort(505);
                }
                break; // No need to check further if we found a match
            }
        }
        return $next($request);
    }
}
