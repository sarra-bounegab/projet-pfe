<?php

namespace App\Observers;

use App\Models\DetailsIntervention;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\TypeIntervention;
use Illuminate\Support\Facades\DB;
use App\Services\NotificationService;

class DetailsInterventionObserver
{
    public function updated(DetailsIntervention $detailsIntervention)
    {
        Log::info('Mise à jour des détails d\'intervention', ['details_intervention_id' => $detailsIntervention->id]);

        if ($this->hasChanges($detailsIntervention)) {
            $this->logChangesInInterventionsHistorique($detailsIntervention);

            // Notifier les modifications faites par un technicien
            $user = Auth::user();
            if ($user && $user->isTechnicien()) {
                $notificationService = app(NotificationService::class);
                $changes = $detailsIntervention->getDirty();

                $notificationService->notifyInterventionModification(
                    $detailsIntervention->intervention,
                    $user,
                    $changes
                );
            }
        }
    }

    private function hasChanges(DetailsIntervention $detailsIntervention)
    {
        $original = $detailsIntervention->getOriginal();
        $changes = $detailsIntervention->getDirty();

        return !empty(array_diff(array_keys($changes), ['updated_at']));
    }

    private function logChangesInInterventionsHistorique(DetailsIntervention $detailsIntervention)
    {
        $user = Auth::user();
        if (!$user) return;

        $original = $detailsIntervention->getOriginal();
        $changes = $detailsIntervention->getDirty();
        $action = '';

        foreach ($changes as $field => $newValue) {
            if ($field === 'updated_at') continue;

            $oldValue = $original[$field] ?? 'vide';
            if ($oldValue == $newValue) continue;

            $fieldName = $this->getFieldDisplayName($field);
            $action .= "Le champ \"$fieldName\" a été modifié de \"$oldValue\" à \"$newValue\". ";
        }

        if (request()->has('contenu')) {
            $oldValue = $detailsIntervention->getOriginal('contenu') ?? 'vide';
            $newValue = request()->input('contenu');
            if ($oldValue !== $newValue) {
                $action .= "Le champ \"contenu\" a été modifié de \"$oldValue\" à \"$newValue\". ";
            }
        }

        if (!empty($action)) {
            $existingHistory = DB::table('interventions_historiques')
                ->where('intervention_id', $detailsIntervention->intervention_id)
                ->where('user_id', $user->id)
                ->where('action', $action)
                ->exists();

            if (!$existingHistory) {
                DB::table('interventions_historiques')->insert([
                    'intervention_id' => $detailsIntervention->intervention_id,
                    'user_id' => $user->id,
                    'action' => $action,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    private function getFieldDisplayName($field)
    {
        $names = [
            'titre' => 'Titre',
            'description' => 'Description',
            'date' => 'Date',
            'status' => 'Statut',
            'technicien_id' => 'Technicien',
            'type_intervention_id' => 'Type d\'intervention',
            'contenu' => 'Contenu'
        ];

        return $names[$field] ?? $field;
    }
}
