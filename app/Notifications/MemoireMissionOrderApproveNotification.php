<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use MBarlow\Megaphone\Types\BaseAnnouncement;

class MemoireMissionOrderApproveNotification extends BaseAnnouncement
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
        switch ($missionApprove->memor_status) {
            case 'draft':
                $this->title = "Votre Mémoire de frais d'Ordre de Mission " . $this->missionOrder->order_number . '|'
                    . $this->missionOrder->purpose . ' besoin de revoir!';
                $this->body = 'Révisé par: ' . $this->missionApprove->employee->first_name . ' '
                    . $this->missionApprove->employee->last_name . '\nRôle révisé: '
                    . $this->missionApprove->approval_role. '\nCommentaire de révision:'
                    . $this->missionApprove->comment;
                    $this->icon = 'review';
                break;
            case 'rejected':
                $this->title = "Votre Mémoire de frais d'Ordre de Mission " . $this->missionOrder->order_number . '|'
                    . $this->missionOrder->purpose . ' Rejeté!';
                $this->body = 'Rejeté par: ' . $this->missionApprove->employee->first_name . ' '
                    . $this->missionApprove->employee->last_name . '\nRôle rejeté: '
                    . $this->missionApprove->approval_role. '\nCommentaire de révision:'
                    . $this->missionApprove->comment;
                    $this->icon = 'reject';
                break;
            // case 'controller_approve':
            //     $this->title = "Votre Mémoire de frais d'Ordre de Mission " . $this->missionOrder->order_number . '|'
            //         . $this->missionOrder->purpose . ' Approuvé!';
            //     $this->body = 'Approuvé par: ' . $this->missionApprove->employee->first_name . ' '
            //         . $this->missionApprove->employee->last_name . "\nRôle d'approbation: "
            //         . $this->missionApprove->approval_role. '\nCommentaire de révision:'
            //         . $this->missionApprove->comment. "\nEn attente d'un examen des Contrôleur maintenant";
            //         $this->icon = 'ok';
            //     break;
            case 'sg_approve':
                $this->title = "Votre Mémoire de frais d'Ordre de Mission " . $this->missionOrder->order_number . '|'
                    . $this->missionOrder->purpose . ' Approuvé!';
                $this->body = 'Approuvé par: ' . $this->missionApprove->employee->first_name . ' '
                    . $this->missionApprove->employee->last_name . "\nRôle d'approbation: "
                    . $this->missionApprove->approval_role. '\nCommentaire de révision:'
                    . $this->missionApprove->comment. '\nEn attente SG (Secrétariat Général) Réviser maintenant';
                    $this->icon = 'ok';
                break;
            case 'approved':
                $this->title = "Votre Mémoire de frais d'Ordre de Mission " . $this->missionOrder->order_number . '|'
                    . $this->missionOrder->purpose . ' Approuvé!';
                $this->body = 'Approuvé par: ' . $this->missionApprove->employee->first_name . ' '
                    . $this->missionApprove->employee->last_name . "\nRôle d'approbation: "
                    . $this->missionApprove->approval_role. '\nCommentaire de révision:'
                    . $this->missionApprove->comment. "\nVotre Mémoire de frais d'Ordre de Mission a été approuvée, vous pouvez demander à le payer maintenant";
                    $this->icon = 'ok';
                break;
        }
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
            'order_id' => $this->missionOrder->id,
            'order_number' => $this->missionOrder->order_number,
            'purpose' => $this->missionOrder->purpose,
            'approval_role' => $this->missionApprove->approval_role,
            'approval_id' => $this->missionApprove->approval_id,
            'approval_name' => $this->missionApprove->employee->first_name . ' ' . $this->missionApprove->employee->last_name,
            'status' => $this->missionApprove->memor_status,
            'title' => $this->title,
            'body' => $this->body,
            'link' => $this->link,
            'linkText' => $this->linkText,
            'icon' => $this->icon,
        ];
    }
}
