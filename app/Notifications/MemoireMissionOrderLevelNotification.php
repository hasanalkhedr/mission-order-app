<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use MBarlow\Megaphone\Types\BaseAnnouncement;

class MemoireMissionOrderLevelNotification extends BaseAnnouncement
{
    use Queueable;

    public $missionOrder;
    public $icon;
    /**
     * Create a new notification instance.
     */
    public function __construct($missionOrder)
    {
        $this->missionOrder = $missionOrder;
        $this->title = 'Mémoire de frais de la Mission ' . $this->missionOrder->order_number . '|'
            . $this->missionOrder->purpose . ' a besoin de votre avis!';
        $this->body = 'Soumis par: ' . $this->missionOrder->employee->first_name . ' '
            . $this->missionOrder->employee->last_name.', Département: '.$missionOrder->employee->department->name ;
        $this->icon = 'review';
        $this->link = route('mission_orders.m_show', $missionOrder->id);
        $this->linkText = 'Cliquez ici pour voir le Mémoire de frais de la Mission';
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
            'order_id' => $this->missionOrder->id,
            'order_number' => $this->missionOrder->order_number,
            'purpose' => $this->missionOrder->purpose,
            'status' => $this->missionOrder->memor_status,
            'title' => $this->title,
            'body' => $this->body,
            'link' => $this->link,
            'linkText' => $this->linkText,
            'icon' => $this->icon,
        ];
    }
}
