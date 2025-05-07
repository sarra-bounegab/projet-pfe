<?php

namespace App\Notifications;

use App\Models\Intervention;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InterventionTermineeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $intervention;

    public function __construct(Intervention $intervention)
    {
        $this->intervention = $intervention;
    }

    public function via($notifiable)
    {
        return ['mail', 'database']; // Ajout de 'mail'
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Intervention terminée')
            ->line('Votre intervention a été marquée comme terminée.')
            ->line('Titre: ' . $this->intervention->titre)
            ->line('Description: ' . $this->intervention->description)
            ->action('Voir les détails', url('/interventions/' . $this->intervention->id))
            ->line('Merci d\'utiliser notre application!');
    }

    public function toDatabase($notifiable)
    {
        $baseUrl = '';
        if ($notifiable->profile_id == 1) { // Admin
            $baseUrl = '/admin/gestionsinterventions/';
        } elseif ($notifiable->profile_id == 2) { // Technicien
            $baseUrl = '/technician/interventions/';
        } else { // Utilisateur normal
            $baseUrl = '/user/gestionsinterventions/';
        }
        
        return [
            'intervention_id' => $this->intervention->id,
            'titre' => $this->intervention->titre,
            'message' => 'Votre intervention a été marquée comme terminée',
            'action_url' => '/interventions/' . $this->intervention->id,
        ];
    }
}
