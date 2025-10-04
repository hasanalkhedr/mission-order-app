<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use MBarlow\Megaphone\Types\BaseAnnouncement;

class TourneeApproveNotification extends BaseAnnouncement
{
    use Queueable;

    public $tournee;
    public $tourneeApprove;
    public $icon;
    /**
     * Create a new notification instance.
     */
    public function __construct($tournee, $tourneeApprove)
    {
        $this->tournee = $tournee;
        $this->tourneeApprove = $tourneeApprove;
        switch ($tourneeApprove->status) {
           case 'draft':
                $this->title = $this->tournee->firstDestination->arrive_location . ' - ' . $this->tournee->firstDestination->start_date->format('d/m/Y');
                $this->body = 'Votre Ordre de tournee besoin de revoir';
                if ($this->tourneeApprove->comment) {
                    $this->body = $this->body . '\nCommentaire de révision:' . $this->tourneeApprove->comment;
                }
                $this->link = route('tournees.edit', $tournee->id);
                $this->linkText = 'voir Ordre de tournee';
                $this->icon = 'review';
                break;
            case 'rejected':
                $this->title = $this->tournee->firstDestination->arrive_location . ' - ' . $this->tournee->firstDestination->start_date->format('d/m/Y');
                $this->body = 'Votre Ordre de tournee Rejeté!';
                if ($this->tourneeApprove->comment) {
                    $this->body = $this->body . '\nCommentaire de révision:' . $this->tourneeApprove->comment;
                }
                $this->link = route('tournees.report', $tournee->id);
                $this->linkText = 'voir Ordre de tournee';
                $this->icon = 'reject';
                break;
            case 'sg_approve':
                $this->title = $this->tournee->firstDestination->arrive_location . ' - ' . $this->tournee->firstDestination->start_date->format('d/m/Y');
                $this->body = 'Votre Ordre de tournee Approuvé! \nEn attente SG (Secrétariat Général) Réviser maintenant';
                $this->link = route('tournees.report', $tournee->id);
                $this->linkText = 'voir Ordre de tournee';
                $this->icon = 'ok';
                break;
            case 'approved':
                $this->title = $this->tournee->firstDestination->arrive_location . ' - ' . $this->tournee->firstDestination->start_date->format('d/m/Y');
                $this->body = 'Votre Ordre de tournee Approuvé! \nvous pouvez la démarrer maintenant';
                $this->link = route('tournees.report', $tournee->id);
                $this->linkText = 'voir Ordre de tournee';
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
            'order_id' => $this->tournee->id,
            'order_number' => $this->tournee->order_number,
            'purpose' => $this->tournee->purpose,
            'approval_role' => $this->tourneeApprove->approval_role,
            'approval_id' => $this->tourneeApprove->approval_id,
            'approval_name' => $this->tourneeApprove->employee->first_name . ' ' . $this->tourneeApprove->employee->last_name,
            'status' => $this->tourneeApprove->status,
            'title' => $this->title,
            'body' => $this->body,
            'link' => $this->link,
            'linkText' => $this->linkText,
            'icon' => $this->icon,
        ];
    }
}
