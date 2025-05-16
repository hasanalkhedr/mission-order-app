<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Log;

class DailySummaryNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public array $notifications)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Daily Mission Orders: " . now()->format('d M Y'))
            ->view('emails.daily-summary', [
                'notifications' => $this->notifications,
                //'dashboardUrl' => route('mission_orders.index'), // Add any additional data
                //'url' => route('mission_orders.index'),
                'user' => $notifiable,
            ]);
    }
}
