<?php

namespace App\Notifications;

use App\Models\Intervention;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InterventionAttribueeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $intervention;
    protected $attribuedBy;

    public function __construct(Intervention $intervention, User $attribuedBy)
    {
        $this->intervention = $intervention;
        $this->attribuedBy = $attribuedBy;
    }

    public function via($notifiable)
    {
        return ['mail', 'database']; // Ajout de 'mail'
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Nouvelle intervention attribuée')
            ->line('Une intervention vous a été attribuée par ' . $this->attribuedBy->name)
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
            'message' => 'Une intervention vous a été attribuée par ' . $this->attribuedBy->name,
            'action_url' => '/interventions/' . $this->intervention->id,
        ];
    }
}
