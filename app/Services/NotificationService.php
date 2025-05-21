<?php

namespace App\Services;

use App\Models\Intervention;
use App\Models\User;
use App\Notifications\InterventionAttribueeNotification;
use App\Notifications\InterventionModifieeNotification;
use App\Notifications\InterventionTermineeNotification;

class NotificationService
{
    public function notifyStatusChange(Intervention $intervention)
    {
        if ($intervention->status === 'terminee') {
            // Notifier l'utilisateur qui a créé l'intervention
            $intervention->user->notify(
                new InterventionTermineeNotification($intervention)
            );
        }
    }

    public function notifyTechnicianAssignment(Intervention $intervention, User $admin)
    {
        // Notifier le technicien
        $intervention->technicien->notify(
            new InterventionAttribueeNotification($intervention, $admin)
        );
    }

    public function notifyInterventionModification(Intervention $intervention, User $modifier, array $changes)
    {
        // Notifier l'admin
        $admins = User::where('profile_id', 1)->get();
        foreach ($admins as $admin) {
            $admin->notify(
                new InterventionModifieeNotification($intervention, $modifier, $changes)
            );
        }

        // Si c'est un technicien qui modifie, notifier aussi l'admin
        if ($modifier->profile_id === 2 && $intervention->user->id !== $modifier->id) {
    $intervention->user->notify(
        new InterventionModifieeNotification($intervention, $modifier, $changes)
    );
}

    }

    public function notifyAdmin($message, Intervention $intervention)
    {
        $admins = User::where('profile_id', 1)->get();
        foreach ($admins as $admin) {
            $admin->notify(
                // Vous pourriez créer une Notification spécifique pour les admins
                new InterventionModifieeNotification($intervention, $intervention->technicien, ['message' => $message])
            );
        }
    }
}
