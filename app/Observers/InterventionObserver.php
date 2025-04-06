<?php

namespace App\Observers;
use Illuminate\Support\Facades\Log;

use App\Models\Intervention;
use App\Models\Historique;
use Illuminate\Support\Facades\Auth;

class InterventionObserver
{
    public function updated(Intervention $intervention)
    {
        Log::info('Méthode updated appelée', ['intervention_id' => $intervention->id]);
    
        
        if ($intervention->isDirty('status')) {
            Log::info('Changement de statut détecté', [
                'intervention_id' => $intervention->id,
                'status' => $intervention->status,
            ]);
    
            Historique::create([
                'intervention_id' => $intervention->id,
                'user_id' => Auth::id(),
                'action' => 'Changement de statut à : ' . $intervention->status,
            ]);
        }
    
        if ($intervention->isDirty('technicien_id')) {
            Log::info('Changement de technicien détecté', [
                'intervention_id' => $intervention->id,
                'technicien_id' => $intervention->technicien_id,
            ]);
    
            Historique::create([
                'intervention_id' => $intervention->id,
                'user_id' => Auth::id(),
                'action' => 'Attribuée au technicien ID : ' . $intervention->technicien_id,
            ]);
        }
    }
    
}
