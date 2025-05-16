<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use MBarlow\Megaphone\Types\BaseAnnouncement;

class TourneeLevelNotification extends BaseAnnouncement
{
    use Queueable;

    public $tournee;
    public $icon;
    /**
     * Create a new notification instance.
     */
    public function __construct($tournee)
    {
        $this->tournee = $tournee;
        $this->title = 'La Tournee ' . $this->tournee->order_number . '|'
            . $this->tournee->purpose . ' a besoin de votre avis!';
        $this->body = 'Soumis par: ' . $this->tournee->employee->first_name . ' '
            . $this->tournee->employee->last_name.', Département: '.$tournee->employee->department->name ;
        $this->icon = 'review';
        $this->link = route('tournees.show', $tournee->id);
        $this->linkText = 'Cliquez ici pour voir la tournee';
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable): array
    {
        return ['database'/*, 'mail'*/];
    }

    /**
     * Get the mail representation of the notification.
     */
    /*public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->title)
            ->greeting($this->title)
            ->line('Bonjour '.$notifiable->employee->first_name.' '.$notifiable->employee->last_name)
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
        $notifiable->createPendingNotificationForMission($this->title, $this->body, $this->link, $this->linkText);
        return [
            'order_id' => $this->tournee->id,
            'order_number' => $this->tournee->order_number,
            'purpose' => $this->tournee->purpose,
            'status' => $this->tournee->status,
            'title' => $this->title,
            'body' => $this->body,
            'link' => $this->link,
            'linkText' => $this->linkText,
            'icon' => $this->icon,
        ];
    }
}
