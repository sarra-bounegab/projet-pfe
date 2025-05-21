<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DetailsIntervention;

class RapportController extends Controller
{
    public function storeOrUpdate(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'intervention_id' => 'required|exists:interventions,id',
            'contenu' => 'required|string',
            'type_ids' => 'required|array',
            'type_ids.*' => 'exists:types_intervention,id',
        ]);
        
        $technicienId = Auth::id();
        $interventionId = $validated['intervention_id'];
        $contenu = $validated['contenu'];

        foreach ($validated['type_ids'] as $typeId) {
            // Vérifie s’il existe déjà un détail avec ce combo
            $detail = DetailsIntervention::where([
                'intervention_id' => $interventionId,
                'technicien_id' => $technicienId,
                'type_intervention_id' => $typeId,
            ])->first();

            if ($detail) {
                // Mise à jour du contenu
                $detail->update(['contenu_rapport' => $contenu]);
            } else {
                // Création
                DetailsIntervention::create([
                    'intervention_id' => $interventionId,
                    'technicien_id' => $technicienId,
                    'type_intervention_id' => $typeId,
                    'contenu_rapport' => $contenu,
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Rapport(s) enregistré(s) avec les types associés.'
        ]);
    }
}
