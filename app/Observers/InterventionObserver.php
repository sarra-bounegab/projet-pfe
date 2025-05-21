<?php
namespace App\Observers;
use App\Models\Intervention;
use App\Models\Historique;
use App\Models\DetailsIntervention;
use Illuminate\Support\Facades\Auth;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Log;
use App\Models\TypeIntervention;
use Illuminate\Http\Request;

class InterventionObserver
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function updated(Intervention $intervention)
    {
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
}
