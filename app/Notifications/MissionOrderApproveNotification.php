<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use MBarlow\Megaphone\Types\BaseAnnouncement;

class MissionOrderApproveNotification extends BaseAnnouncement
{
    use Queueable;

    public $missionOrder;
    public $missionApprove;
    public $icon;
    /**
     * Create a new notification instance.
     */
    public function __construct($missionOrder, $missionApprove)
    {
        $this->missionOrder = $missionOrder;
        $this->missionApprove = $missionApprove;
        switch ($missionApprove->status) {
            case 'draft':
                $this->title = $this->missionOrder->arrive_location . ' - ' . $this->missionOrder->start_date->format('d/m/Y');
                $this->body = 'Votre Ordre de mission besoin de revoir';
                if ($this->missionApprove->comment) {
                    $this->body = $this->body . '\nCommentaire de révision:' . $this->missionApprove->comment;
                }
                $this->link = route('mission_orders.edit', $missionOrder->id);
                $this->linkText = 'voir Ordre de mission';
                $this->icon = 'review';
                break;
            case 'rejected':
                $this->title = $this->missionOrder->arrive_location . ' - ' . $this->missionOrder->start_date->format('d/m/Y');
                $this->body = 'Votre Ordre de mission Rejeté!';
                if ($this->missionApprove->comment) {
                    $this->body = $this->body . '\nCommentaire de révision:' . $this->missionApprove->comment;
                }
                $this->link = route('mission_orders.report', $missionOrder->id);
                $this->linkText = 'voir Ordre de mission';
                $this->icon = 'reject';
                break;
            case 'sg_approve':
                $this->title = $this->missionOrder->arrive_location . ' - ' . $this->missionOrder->start_date->format('d/m/Y');
                $this->body = 'Votre Ordre de mission Approuvé! \nEn attente SG (Secrétariat Général) Réviser maintenant';
                $this->link = route('mission_orders.report', $missionOrder->id);
                $this->linkText = 'voir Ordre de mission';
                $this->icon = 'ok';
                break;
            case 'approved':
                $this->title = $this->missionOrder->arrive_location . ' - ' . $this->missionOrder->start_date->format('d/m/Y');
                $this->body = 'Votre Ordre de mission Approuvé! \nvous pouvez la démarrer maintenant';
                $this->link = route('mission_orders.report', $missionOrder->id);
                $this->linkText = 'voir Ordre de mission';
                $this->icon = 'ok';
                break;
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

    /**
     * Get the mail representation of the notification.
     */
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
            'order_id' => $this->missionOrder->id,
            'order_number' => $this->missionOrder->order_number,
            'purpose' => $this->missionOrder->purpose,
            'approval_role' => $this->missionApprove->approval_role,
            'approval_id' => $this->missionApprove->approval_id,
            'approval_name' => $this->missionApprove->employee->first_name . ' ' . $this->missionApprove->employee->last_name,
            'status' => $this->missionApprove->status,
            'title' => $this->title,
            'body' => $this->body,
            'link' => $this->link,
            'linkText' => $this->linkText,
            'icon' => $this->icon,
        ];
    }
}
