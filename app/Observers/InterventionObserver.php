<?php
namespace App\Observers;
<<<<<<< HEAD

=======
>>>>>>> 9cb757b780aed92b354e86f514ea09f8392649e2
use App\Models\Intervention;
use App\Models\Historique;
use App\Models\DetailsIntervention;
use Illuminate\Support\Facades\Auth;
<<<<<<< HEAD
use App\Services\NotificationService;
=======
use Illuminate\Support\Facades\Log;
use App\Models\TypeIntervention;
use Illuminate\Http\Request;
>>>>>>> 9cb757b780aed92b354e86f514ea09f8392649e2

class InterventionObserver
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function updated(Intervention $intervention)
    {
<<<<<<< HEAD
        $changes = $intervention->getChanges();
        $original = $intervention->getOriginal();

        // Historique pour tous les changements
        $changedFields = [];
        foreach ($changes as $field => $newValue) {
            if ($field !== 'updated_at') {
                $changedFields[$field] = [
                    'from' => $original[$field] ?? null,
                    'to' => $newValue
                ];
            }
        }

        if (!empty($changedFields)) {
            Historique::create([
                'intervention_id' => $intervention->id,
                'user_id' => Auth::id(),
                'action' => 'Modification: ' . json_encode($changedFields),
            ]);

            // Notifier les modifications
            $this->notificationService->notifyInterventionModification(
                $intervention,
                Auth::user(),
                $changedFields
            );
        }

        // Gestion spécifique du statut
        if ($intervention->isDirty('status')) {
            $this->notificationService->notifyStatusChange($intervention);
            $this->notificationService->notifyAdmin(
                'Changement de statut à ' . $intervention->status,
                $intervention
            );
        }

        // Gestion spécifique du technicien
        if ($intervention->isDirty('technicien_id')) {
            $this->notificationService->notifyTechnicianAssignment(
                $intervention,
                Auth::user()
            );
        }
    }
=======
        Log::info('Mise à jour intervention', ['intervention_id' => $intervention->id]);
        
        // Vérification de la table 'interventions'
        $this->logChangesInInterventionsHistorique($intervention);
        
        // Récupérer les détails d'intervention existants
        $detailsIntervention = DetailsIntervention::where('intervention_id', $intervention->id)->first();
        
        // Si l'entrée details_interventions n'existe pas, la créer
        if (!$detailsIntervention) {
            try {
                $updateData = [
                    'intervention_id' => $intervention->id,
                    'titre' => $intervention->titre,
                    'description' => $intervention->description,
                    'date' => $intervention->date,
                    'status' => $intervention->status,
                    'technicien_id' => $intervention->technicien_id,
                    'type_intervention_id' => $intervention->type_intervention_id ?? TypeIntervention::firstOrCreate(['type' => 'Non spécifié'])->id,
                    'contenu' => request()->input('contenu') ?? '',  // Récupérer la valeur du champ contenu depuis la requête
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                
                DetailsIntervention::create($updateData);
                Log::info('Nouveaux détails d\'intervention créés', [
                    'intervention_id' => $intervention->id,
                    'data' => $updateData
                ]);
            } catch (\Exception $e) {
                Log::error('Erreur lors de la création de details_interventions', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'intervention_id' => $intervention->id
                ]);
            }
        } 
        // Sinon, mettre à jour l'entrée existante
        else {
            try {
                $updateData = [
                    'titre' => $intervention->titre,
                    'description' => $intervention->description,
                    'date' => $intervention->date,
                    'status' => $intervention->status,
                    'technicien_id' => $intervention->technicien_id,
                    'type_intervention_id' => $intervention->type_intervention_id ?? TypeIntervention::firstOrCreate(['type' => 'Non spécifié'])->id,
                    'updated_at' => now(),
                ];
                
                // Mettre à jour le champ contenu uniquement s'il est présent dans la requête
                if (request()->has('contenu')) {
                    $updateData['contenu'] = request()->input('contenu');
                }
                
                // Mise à jour des détails
                $detailsIntervention->update($updateData);
                
                Log::info('Détails de l\'intervention mis à jour', [
                    'intervention_id' => $intervention->id,
                    'data' => $updateData
                ]);
            } catch (\Exception $e) {
                Log::error('Erreur lors de la mise à jour de details_interventions', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'intervention_id' => $intervention->id
                ]);
            }
        }
    }

  private function logChangesInInterventionsHistorique(Intervention $intervention)
{
    $user = Auth::user();
    if (!$user) return;
    
    $original = $intervention->getOriginal();
    $changes = $intervention->getDirty();
    $actionLines = [];

    foreach ($changes as $field => $newValue) {
        // Ignorer le champ 'updated_at'
        if ($field === 'updated_at') continue;

        // Ne garder que certains champs (titre, description, statut)
        if (!in_array($field, ['titre', 'description', 'statut'])) continue;

        $oldValue = $original[$field] ?? 'vide';
        if ($oldValue == $newValue) continue;

        $actionLines[] = "Le champ \"$field\" a été modifié de \"$oldValue\" à \"$newValue\".";
    }

    // Vérifie si 'contenu' a changé dans DetailsIntervention
    if (request()->has('contenu')) {
        $detailsIntervention = DetailsIntervention::where('intervention_id', $intervention->id)->first();
        if ($detailsIntervention && $detailsIntervention->contenu !== request()->input('contenu')) {
            $oldValue = $detailsIntervention->contenu ?? 'vide';
            $newValue = request()->input('contenu');
            $actionLines[] = "Le champ \"contenu\" a été modifié de \"$oldValue\" à \"$newValue\".";
        }
    }

    if (!empty($actionLines)) {
        $actionText = implode("\n", $actionLines); // sauter ligne entre chaque champ modifié

        \DB::table('interventions_historiques')->insert([
            'intervention_id' => $intervention->id,
            'user_id' => $user->id,
            'action' => $actionText,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Log::info('Historique de l\'intervention enregistré', [
            'intervention_id' => $intervention->id,
            'action' => $actionText
        ]);
    }
>>>>>>> 9cb757b780aed92b354e86f514ea09f8392649e2
}

}