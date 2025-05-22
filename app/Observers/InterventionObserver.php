<?php

namespace App\Observers;

use App\Models\Intervention;
use App\Models\Historique;
use App\Models\DetailsIntervention;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\TypeIntervention;
use App\Services\NotificationService;

class InterventionObserver
{
    public function updated(Intervention $intervention)
    {
        Log::info('Mise à jour intervention', ['intervention_id' => $intervention->id]);

        $this->logChangesInInterventionsHistorique($intervention);
        $this->handleDetailsIntervention($intervention);

        // Gestion des notifications
        $notificationService = app(NotificationService::class);

        // Si le technicien a changé
        if ($intervention->isDirty('technicien_id')) {
            $admin = Auth::user();
            $notificationService->notifyTechnicianAssignment($intervention, $admin);
        }

        // Si le statut a changé
        if ($intervention->isDirty('status') && $intervention->status === 'terminee') {
            $notificationService->notifyStatusChange($intervention);
        }
    }

    private function handleDetailsIntervention(Intervention $intervention)
    {
        $detailsIntervention = DetailsIntervention::firstOrNew([
            'intervention_id' => $intervention->id
        ]);

        $updateData = [
            'titre' => $intervention->titre,
            'description' => $intervention->description,
            'date' => $intervention->date,
            'status' => $intervention->status,
            'technicien_id' => $intervention->technicien_id,
            'type_intervention_id' => $intervention->type_intervention_id ?? TypeIntervention::firstOrCreate(['type' => 'Non spécifié'])->id,
            'updated_at' => now(),
        ];

        if (request()->has('contenu')) {
            $updateData['contenu'] = request()->input('contenu');
        }

        $detailsIntervention->fill($updateData)->save();
    }

    private function logChangesInInterventionsHistorique(Intervention $intervention)
    {
        $user = Auth::user();
        if (!$user) return;

        $original = $intervention->getOriginal();
        $changes = $intervention->getDirty();
        $actionLines = [];

        foreach ($changes as $field => $newValue) {
            if ($field === 'updated_at') continue;

            if (in_array($field, ['titre', 'description', 'status', 'technicien_id'])) {
                $oldValue = $original[$field] ?? 'vide';
                if ($oldValue == $newValue) continue;

                $fieldName = $this->getFieldDisplayName($field);
                $actionLines[] = "Le champ \"$fieldName\" a été modifié de \"$oldValue\" à \"$newValue\".";
            }
        }

        if (request()->has('contenu')) {
            $detailsIntervention = DetailsIntervention::where('intervention_id', $intervention->id)->first();
            if ($detailsIntervention && $detailsIntervention->contenu !== request()->input('contenu')) {
                $oldValue = $detailsIntervention->contenu ?? 'vide';
                $newValue = request()->input('contenu');
                $actionLines[] = "Le champ \"contenu\" a été modifié de \"$oldValue\" à \"$newValue\".";
            }
        }

        if (!empty($actionLines)) {
            $actionText = implode("\n", $actionLines);

            \DB::table('interventions_historiques')->insert([
                'intervention_id' => $intervention->id,
                'user_id' => $user->id,
                'action' => $actionText,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function getFieldDisplayName($field)
    {
        $names = [
            'titre' => 'Titre',
            'description' => 'Description',
            'status' => 'Statut',
            'technicien_id' => 'Technicien'
        ];

        return $names[$field] ?? $field;
    }
}
