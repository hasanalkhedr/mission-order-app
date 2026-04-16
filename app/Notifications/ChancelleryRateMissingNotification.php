<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use MBarlow\Megaphone\Types\BaseAnnouncement;

class ChancelleryRateMissingNotification extends BaseAnnouncement
{
    use Queueable;

    public $chancelleryRate;
    public $icon;
    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        $this->title = "Veuillez ajouter les taux de chancellerie pour le mois en cours: ".now()->isoFormat('MMMM YYYY');
        $this->body = "Veuillez ajouter les taux de chancellerie pour le mois en cours: ".now()->isoFormat('MMMM YYYY');
        $this->link = route('chancelleryRates.index');
        $this->linkText = 'Cliquez ici pour voir la taux de chancellerie';
        $this->icon = 'review';
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->title)
            ->greeting($this->title)
            ->line('Bonjour ' . $notifiable->employee->first_name . ' ' . $notifiable->employee->last_name)
            ->line($this->body)
            ->action($this->linkText, $this->link)
            ->salutation('Cordialement');
    }
    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase($notifiable): array
    {
        return [
            'title' => $this->title,
            'body' => $this->body,
            'link' => $this->link,
            'linkText' => $this->linkText,
            'icon' => $this->icon,
        ];
    }
}
