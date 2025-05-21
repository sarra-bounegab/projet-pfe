<?php

namespace App\Observers;

use App\Models\DetailsIntervention;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\TypeIntervention;
use Illuminate\Support\Facades\DB;

class DetailsInterventionObserver
{
    public function updated(DetailsIntervention $detailsIntervention)
    {
        Log::info('Mise à jour des détails d\'intervention', ['details_intervention_id' => $detailsIntervention->id]);

        // Vérification pour éviter les entrées multiples dans l'historique
        if ($this->hasChanges($detailsIntervention)) {
            // Enregistrer les modifications dans l'historique
            $this->logChangesInInterventionsHistorique($detailsIntervention);
        }

        try {
            // Récupérer les données de mise à jour
            $updateData = [
                'titre' => $detailsIntervention->titre,
                'description' => $detailsIntervention->description,
                'date' => $detailsIntervention->date,
                'status' => $detailsIntervention->status,
                'technicien_id' => $detailsIntervention->technicien_id,
                'type_intervention_id' => $detailsIntervention->type_intervention_id ?? TypeIntervention::firstOrCreate(['type' => 'Non spécifié'])->id,
                'updated_at' => now(),
            ];

            // Si 'contenu' est présent dans la requête, l'ajouter
            if (request()->has('contenu')) {
                $updateData['contenu'] = request()->input('contenu');
            }

            // Mettre à jour les données de details_intervention
            $detailsIntervention->update($updateData);

            Log::info('Détails d\'intervention mis à jour', [
                'details_intervention_id' => $detailsIntervention->id,
                'data' => $updateData
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour des détails d\'intervention', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'details_intervention_id' => $detailsIntervention->id
            ]);
        }
    }

    // Fonction pour vérifier si les champs ont réellement changé
    private function hasChanges(DetailsIntervention $detailsIntervention)
    {
        $original = $detailsIntervention->getOriginal();
        $changes = $detailsIntervention->getDirty();

        // Vérifier si des changements ont eu lieu
        return !empty($changes);
    }

    // Fonction pour enregistrer l'historique dans la table interventions_historiques
   private function logChangesInInterventionsHistorique(DetailsIntervention $detailsIntervention)
{
    $user = Auth::user();
    if (!$user) return;

    $original = $detailsIntervention->getOriginal();
    $changes = $detailsIntervention->getDirty();
    $action = '';

    foreach ($changes as $field => $newValue) {
        // 🔥 Ignorer le champ updated_at
        if ($field === 'updated_at') continue;

        $oldValue = $original[$field] ?? 'vide';
        if ($oldValue == $newValue) continue;

        $action .= "Le champ \"$field\" a été modifié de \"$oldValue\" à \"$newValue\". ";
    }

    // Optionnel : vérifie si 'contenu' est modifié via la requête (déjà couvert ci-dessus)
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

            Log::info('Historique des détails d\'intervention enregistré', [
                'details_intervention_id' => $detailsIntervention->id,
                'action' => $action
            ]);
        }
    }
}

}
