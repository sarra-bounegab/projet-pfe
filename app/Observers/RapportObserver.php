<?php

namespace App\Observers;

use App\Models\Rapport;
use App\Models\RapportDetail;
use Illuminate\Support\Facades\Auth;

class RapportObserver
{
   
    public function updating(Rapport $rapport)
    {
      
        if ($rapport->isDirty('contenu')) {
            $ancienContenu = $rapport->getOriginal('contenu');
            $this->creerHistorique($rapport, $ancienContenu);
        }
    }
    
    
    public function created(Rapport $rapport)
    {
     
        $this->creerHistorique($rapport, null);
    }
    
   
    private function creerHistorique(Rapport $rapport, $contenuPrecedent)
    {
        RapportDetail::create([
            'intervention_id' => $rapport->intervention_id,
            'user_id' => Auth::id(), // L'utilisateur connecté
            'contenu' => $rapport->contenu,
            'contenu_precedent' => $contenuPrecedent,
            'modification_date' => now(),
            'rapport_id' => $rapport->id
        ]);
    }
}