<?php
// app/Services/NotificationService.php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Models\Intervention;

class NotificationService
{
    /**
     * Créer une notification pour l'attribution d'une intervention à un technicien
     */
    public function notifyTechnicianAssigned($intervention, $technicianId, $assignedBy)
    {
        return Notification::create([
            'user_id' => $technicianId,
            'intervention_id' => $intervention->id,
            'sender_id' => $assignedBy,
            'type' => 'intervention_assigned',
            'title' => 'Nouvelle intervention assignée',
            'message' => "Une nouvelle intervention '{$intervention->titre}' vous a été assignée.",
            'data' => [
                'intervention_title' => $intervention->titre,
                'assigned_by' => User::find($assignedBy)->name ?? 'Admin'
            ]
        ]);
    }

    /**
     * Notifier l'utilisateur quand son intervention est terminée
     */
    public function notifyUserInterventionCompleted($intervention, $completedBy)
    {
        return Notification::create([
            'user_id' => $intervention->user_id,
            'intervention_id' => $intervention->id,
            'sender_id' => $completedBy,
            'type' => 'intervention_completed',
            'title' => 'Intervention terminée',
            'message' => "Votre intervention '{$intervention->titre}' a été marquée comme terminée.",
            'data' => [
                'intervention_title' => $intervention->titre,
                'completed_by' => User::find($completedBy)->name ?? 'Technicien'
            ]
        ]);
    }

    /**
     * Notifier l'admin de toutes les actions des techniciens/utilisateurs
     */
    public function notifyAdminOfAction($action, $intervention, $userId, $details = [])
    {
        $admins = User::whereIn('profile_id', [1, 4])->get();
        $user = User::find($userId);

        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'intervention_id' => $intervention->id,
                'sender_id' => $userId,
                'type' => $action,
                'title' => $this->getActionTitle($action),
                'message' => $this->getActionMessage($action, $intervention, $user),
                'data' => array_merge([
                    'intervention_title' => $intervention->titre,
                    'user_name' => $user->name ?? 'Utilisateur',
                    'user_role' => $this->getUserRole($user->profile_id ?? 3)
                ], $details)
            ]);
        }
    }

    /**
     * Notifier le technicien d'actions sur ses interventions
     */
    public function notifyTechnicianOfUpdate($intervention, $technicianId, $updatedBy, $updateType = 'updated')
    {
        if ($technicianId == $updatedBy) {
            return; // Ne pas notifier le technicien de ses propres actions
        }

        return Notification::create([
            'user_id' => $technicianId,
            'intervention_id' => $intervention->id,
            'sender_id' => $updatedBy,
            'type' => 'intervention_updated',
            'title' => 'Intervention modifiée',
            'message' => "L'intervention '{$intervention->titre}' a été modifiée.",
            'data' => [
                'intervention_title' => $intervention->titre,
                'updated_by' => User::find($updatedBy)->name ?? 'Admin',
                'update_type' => $updateType
            ]
        ]);
    }

    /**
     * Marquer toutes les notifications d'un utilisateur comme lues
     */
    public function markAllAsRead($userId)
    {
        return Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }

    /**
     * Obtenir le nombre de notifications non lues pour un utilisateur
     */
    public function getUnreadCount($userId)
    {
        return Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->count();
    }

    /**
     * Obtenir les notifications récentes d'un utilisateur
     */
    public function getRecentNotifications($userId, $limit = 5)
    {
        return Notification::with(['intervention', 'sender'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    private function getActionTitle($action)
    {
        return match($action) {
            'intervention_created' => 'Nouvelle intervention créée',
            'intervention_updated' => 'Intervention modifiée',
            'intervention_assigned' => 'Intervention assignée',
            'intervention_completed' => 'Intervention terminée',
            'intervention_reopened' => 'Intervention réouverte',
            default => 'Action sur intervention'
        };
    }

    private function getActionMessage($action, $intervention, $user)
    {
        $userName = $user->name ?? 'Utilisateur';
        $role = $this->getUserRole($user->profile_id ?? 3);

        return match($action) {
            'intervention_created' => "{$userName} ({$role}) a créé l'intervention '{$intervention->titre}'",
            'intervention_updated' => "{$userName} ({$role}) a modifié l'intervention '{$intervention->titre}'",
            'intervention_completed' => "{$userName} ({$role}) a terminé l'intervention '{$intervention->titre}'",
            'intervention_reopened' => "{$userName} ({$role}) a réouvert l'intervention '{$intervention->titre}'",
            default => "{$userName} ({$role}) a effectué une action sur '{$intervention->titre}'"
        };
    }

    private function getUserRole($profileId)
    {
        return match($profileId) {
            1, 4 => 'Admin',
            2 => 'Technicien',
            3 => 'Utilisateur',
            default => 'Inconnu'
        };
    }
}
