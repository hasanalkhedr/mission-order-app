<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use MBarlow\Megaphone\Types\BaseAnnouncement;

class MemoireTourneeApproveNotification extends BaseAnnouncement
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
        switch ($tourneeApprove->memor_status) {
            case 'draft':
                $this->title = "Votre Mémoire de frais d'Ordre de Tournee " . $this->tournee->order_number . '|'
                    . $this->tournee->purpose . ' besoin de revoir!';
                $this->body = 'Révisé par: ' . $this->tourneeApprove->employee->first_name . ' '
                    . $this->tourneeApprove->employee->last_name . '\nRôle révisé: '
                    . $this->tourneeApprove->approval_role . '\nCommentaire de révision:'
                    . $this->tourneeApprove->comment;
                $this->icon = 'review';
                break;
            case 'rejected':
                $this->title = "Votre Mémoire de frais d'Ordre de Tournee " . $this->tournee->order_number . '|'
                    . $this->tournee->purpose . ' Rejeté!';
                $this->body = 'Rejeté par: ' . $this->tourneeApprove->employee->first_name . ' '
                    . $this->tourneeApprove->employee->last_name . '\nRôle rejeté: '
                    . $this->tourneeApprove->approval_role . '\nCommentaire de révision:'
                    . $this->tourneeApprove->comment;
                $this->icon = 'reject';
                break;
            // case 'controller_approve':
            //     $this->title = "Votre Mémoire de frais d'Ordre de Tournee " . $this->tournee->order_number . '|'
            //         . $this->tournee->purpose . ' Approuvé!';
            //     $this->body = 'Approuvé par: ' . $this->tourneeApprove->employee->first_name . ' '
            //         . $this->tourneeApprove->employee->last_name . "\nRôle d'approbation: "
            //         . $this->tourneeApprove->approval_role . '\nCommentaire de révision:'
            //         . $this->tourneeApprove->comment . "\nEn attente d'un examen des Contrôleur maintenant";
            //     $this->icon = 'ok';
            //     break;
            case 'sg_approve':
                $this->title = "Votre Mémoire de frais d'Ordre de Tournee " . $this->tournee->order_number . '|'
                    . $this->tournee->purpose . ' Approuvé!';
                $this->body = 'Approuvé par: ' . $this->tourneeApprove->employee->first_name . ' '
                    . $this->tourneeApprove->employee->last_name . "\nRôle d'approbation: "
                    . $this->tourneeApprove->approval_role . '\nCommentaire de révision:'
                    . $this->tourneeApprove->comment . '\nEn attente SG (Secrétariat Général) Réviser maintenant';
                $this->icon = 'ok';
                break;
            case 'approved':
                $this->title = "Votre Mémoire de frais d'Ordre de Tournee " . $this->tournee->order_number . '|'
                    . $this->tournee->purpose . ' Approuvé!';
                $this->body = 'Approuvé par: ' . $this->tourneeApprove->employee->first_name . ' '
                    . $this->tourneeApprove->employee->last_name . "\nRôle d'approbation: "
                    . $this->tourneeApprove->approval_role . '\nCommentaire de révision:'
                    . $this->tourneeApprove->comment . "\nVotre Mémoire de frais d'Ordre de Tournee a été approuvée, vous pouvez demander à le payer maintenant";
                $this->icon = 'ok';
                break;
        }
        $this->link = route('tournees.m_show', $tournee->id);
        $this->linkText = 'Cliquez ici pour voir le Mémoire de frais de la Tournee';
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
            'order_id' => $this->tournee->id,
            'order_number' => $this->tournee->order_number,
            'purpose' => $this->tournee->purpose,
            'approval_role' => $this->tourneeApprove->approval_role,
            'approval_id' => $this->tourneeApprove->approval_id,
            'approval_name' => $this->tourneeApprove->employee->first_name . ' ' . $this->tourneeApprove->employee->last_name,
            'status' => $this->tourneeApprove->memor_status,
            'title' => $this->title,
            'body' => $this->body,
            'link' => $this->link,
            'linkText' => $this->linkText,
            'icon' => $this->icon,
        ];
    }
}
