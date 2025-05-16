<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\DailySummaryNotification;
use Illuminate\Console\Command;

class SendDailyNotifications extends Command
{
    protected $signature = 'notifications:send-daily {--role= : Filter users by role (e.g., admin, manager)}';
    protected $description = 'Send daily notifications to users, optionally filtered by role';

    public function handle()
    {
        $role = $this->option('role');

        User::when($role, function ($query) use ($role) {
                $query->whereHas('employee', function ($q) use ($role) {
                    $q->whereJsonContains('roles', $role); // Ensure 'roles' is a JSON array
                });
            })
            ->whereHas('pendingNotifications', fn($q) => $q->where('is_sent', false))
            ->each(function (User $user) {
                $notifications = $user->pendingNotifications()
                    ->where('is_sent', false)
                    ->get()
                    ->pluck('data')
                    ->toArray();

                if (!empty($notifications)) {
                    $user->notify(new DailySummaryNotification($notifications));
                    $user->pendingNotifications()->update(['is_sent' => true]);
                }
            });

        $this->info('Notifications sent' . ($role ? " to {$role} users" : ' to all users'));
    }
}
