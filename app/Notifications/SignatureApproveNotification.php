<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use MBarlow\Megaphone\Types\BaseAnnouncement;

class SignatureApproveNotification extends BaseAnnouncement
{
    use Queueable;

    public $signature;
    public $icon;
    /**
     * Create a new notification instance.
     */
    public function __construct($signature)
    {
        $this->signature = $signature;
        if($this->signature->status=='draft') {
            $this->title = "Votre signature a été rejetée.";
            $this->body = "Votre signature a été rejetée, veuillez vérifier votre signature.";
            $this->link = route('signatures.edit', $signature->id);
            $this->linkText = 'Cliquez ici pour voir la signature';
            $this->icon = 'reject';
        } else if($this->signature->status=='approved') {
            $this->title = "Votre signature a été examinée et approuvée.";
            $this->body = "Votre signature a été examinée et approuvée.";
            $this->link = route('signatures.show', $signature->id);
            $this->linkText = 'Cliquez ici pour voir la signature';
            $this->icon = 'ok';
        }
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
        return [
            'title' => $this->title,
            'body' => $this->body,
            'link' => $this->link,
            'linkText' => $this->linkText,
            'icon' => $this->icon,
        ];
    }
}
