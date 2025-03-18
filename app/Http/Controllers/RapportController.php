<?php

namespace App\Http\Controllers;

use App\Models\Rapport;
use App\Models\Tache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RapportController extends Controller
{
    public function storeOrUpdate(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            'intervention_id' => 'required|exists:interventions,id',
            'contenu' => 'required|string',
            'taches' => 'nullable|array',
            'taches.*' => 'string',
        ]);

        // Vérifier si un rapport existe déjà pour cette intervention
        $rapport = Rapport::where('intervention_id', $validated['intervention_id'])->first();

        if ($rapport) {
            // Mettre à jour le rapport existant
            $rapport->update([
                'contenu' => $validated['contenu']
            ]);
        } else {
            // Créer un nouveau rapport
            $rapport = Rapport::create([
                'contenu' => $validated['contenu'],
                'technicien_id' => Auth::id(),
                'intervention_id' => $validated['intervention_id']
            ]);
        }

        // Supprimer les anciennes tâches
        $rapport->taches()->delete();

        // Ajouter les nouvelles tâches
        if (!empty($validated['taches'])) {
            foreach ($validated['taches'] as $description) {
                $rapport->taches()->create(['description' => $description]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Rapport mis à jour avec succès.',
            'rapport' => $rapport
        ]);
    }

    public function getRapportEtTaches($interventionId)
    {
        $rapport = Rapport::where('intervention_id', $interventionId)->first();
        $taches = $rapport ? $rapport->taches : [];

        return response()->json([
            'success' => true,
            'rapport' => $rapport,
            'taches' => $taches
        ]);
    }
}
