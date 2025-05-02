<?php
namespace App\Observers;

use App\Models\Intervention;
use App\Models\Historique;
use App\Models\DetailsIntervention;
use App\Models\InterventionTechnicien; // Nouveau modèle pour la relation many-to-many
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\TypeIntervention;

class InterventionObserver
{
    public function updated(Intervention $intervention)
    {
        Log::info('Mise à jour intervention', ['intervention_id' => $intervention->id]);

        // Historique pour statut
        if ($intervention->isDirty('status')) {
            Historique::create([
                'intervention_id' => $intervention->id,
                'user_id' => Auth::id(),
                'action' => 'Statut changé à: ' . $intervention->status,
            ]);
        }

        // Nouveau: Historique pour changement des techniciens (many-to-many)
        if ($intervention->wasChanged('techniciens')) {
            $newTechniciens = $intervention->techniciens->pluck('name')->implode(', ');
            Historique::create([
                'intervention_id' => $intervention->id,
                'user_id' => Auth::id(),
                'action' => 'Techniciens modifiés: ' . $newTechniciens,
            ]);
        }

        // Synchronisation des champs
        if ($intervention->isDirty(['titre', 'description', 'date', 'status', 'type_intervention_id'])) {
            try {
                $updateData = [
                    'titre' => $intervention->titre,
                    'description' => $intervention->description,
                    'date' => $intervention->date,
                    'status' => $intervention->status,
                    'type_intervention_id' => $intervention->type_intervention_id ?? TypeIntervention::firstOrCreate(['type' => 'Non spécifié'])->id,
                    'updated_at' => now(),
                ];
                
                DetailsIntervention::updateOrCreate(
                    ['intervention_id' => $intervention->id],
                    $updateData
                );

                Log::info('Détails intervention mis à jour', [
                    'intervention_id' => $intervention->id,
                    'data' => $updateData
                ]);
            } catch (\Exception $e) {
                Log::error('Erreur mise à jour details_interventions', [
                    'error' => $e->getMessage(),
                    'intervention_id' => $intervention->id
                ]);
            }
        }

        $this->logChangesInHistorique($intervention);
    }

    private function logChangesInHistorique(Intervention $intervention)
    {
        $user = Auth::user();
        if (!$user) return;

        $original = $intervention->getOriginal();
        $changes = $intervention->getDirty();

        $action = '';

        foreach ($changes as $field => $newValue) {
            // Ne pas logger les changements de techniciens (géré séparément)
            if ($field === 'techniciens') continue;
            
            $oldValue = $original[$field] ?? 'vide';
            if ($oldValue == $newValue) continue;

            $action .= "Le champ \"$field\" a été modifié de \"$oldValue\" à \"$newValue\". ";
        }

        if (!empty($action)) {
            Historique::create([
                'intervention_id' => $intervention->id,
                'user_id' => $user->id,
                'action' => $action,
            ]);
        }
    }

    // Nouvelle méthode pour suivre les changements sur la relation many-to-many
    public static function observeTechniciensChanges($intervention, $techniciensIds)
    {
        $currentTechniciens = $intervention->techniciens->pluck('id')->toArray();
        $added = array_diff($techniciensIds, $currentTechniciens);
        $removed = array_diff($currentTechniciens, $techniciensIds);

        foreach ($added as $technicienId) {
            Historique::create([
                'intervention_id' => $intervention->id,
                'user_id' => Auth::id(),
                'action' => 'Technicien ajouté: ' . $technicienId,
            ]);
        }

        foreach ($removed as $technicienId) {
            Historique::create([
                'intervention_id' => $intervention->id,
                'user_id' => Auth::id(),
                'action' => 'Technicien retiré: ' . $technicienId,
            ]);
        }
    }
}