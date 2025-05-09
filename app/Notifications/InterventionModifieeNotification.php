<?php

namespace App\Notifications;

use App\Models\Intervention;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InterventionModifieeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $intervention;
    protected $modifiedBy;
    protected $changes;

    public function __construct(Intervention $intervention, User $modifiedBy, array $changes)
    {
        $this->intervention = $intervention;
        $this->modifiedBy = $modifiedBy;
        $this->changes = $changes;
    }

    public function via($notifiable)
    {
        return ['mail', 'database']; // Ajout de 'mail'
    }

    public function toMail($notifiable)
    {
        $message = (new MailMessage)
            ->subject('Intervention modifiée')
            ->line('Une intervention a été modifiée par ' . $this->modifiedBy->name)
            ->line('Titre: ' . $this->intervention->titre);

        foreach ($this->changes as $field => $value) {
            $message->line("Changement: $field => $value");
        }

        return $message
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
            'message' => 'Une intervention a été modifiée par ' . $this->modifiedBy->name,
            'changes' => $this->changes,
            'action_url' => '/interventions/' . $this->intervention->id,
        ];
    }
}
