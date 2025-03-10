<?php

namespace App\Http\Controllers;
use App\Models\Rapport;
use App\Models\Tache;
use Illuminate\Http\Request;

class RapportController extends Controller
{
    
    
    public function store(Request $request)
{
    // Si aucun rapport n'existe pour l'intervention, le contenu est requis.
    // Sinon, il peut être facultatif (ou mis à jour si fourni).
    $rules = [
        'intervention_id' => 'required|exists:interventions,id',
        'description' => 'required|string|max:255',
    ];

    // Vérifier si un rapport existe déjà pour l'intervention
    $rapport = Rapport::where('intervention_id', $request->intervention_id)->first();
    if (!$rapport) {
        $rules['contenu'] = 'required|string'; // obligatoire la première fois
    } else {
        // Optionnel : mettre à jour le contenu si l'utilisateur le fournit
        $rules['contenu'] = 'nullable|string';
    }

    $validated = $request->validate($rules);

    // Création ou mise à jour du rapport
    if (!$rapport) {
        $rapport = Rapport::create([
            'intervention_id' => $validated['intervention_id'],
            'titre' => 'Rapport automatique',  // ou autre titre par défaut
            'contenu' => $validated['contenu'],  // contenu fourni par l'utilisateur
            'technicien_id' => auth()->id(),
        ]);
    } else {
        if (isset($validated['contenu']) && $validated['contenu'] != "") {
            $rapport->contenu = $validated['contenu'];
            $rapport->save();
        }
    }

    // Ajout de la tâche liée au rapport
    $tache = Tache::create([
        'rapport_id' => $rapport->id,
        'description' => $validated['description'],
    ]);

    return response()->json([
        'success' => true, 
        'message' => 'Rapport et tâche enregistrés avec succès.',
        'rapport_id' => $rapport->id,
        'tache' => $tache,
    ]);
}


    public function enregistrerRapportEtTaches(Request $request) {
        $validatedData = $request->validate([
            'intervention_id' => 'required|integer',
            'technicien_id' => 'required|integer',
            'contenu' => 'required|string',
            'taches' => 'required|array',
            'taches.*.description' => 'required|string',
            'taches.*.date_execution' => 'required|date'
        ]);
    
        DB::beginTransaction();
        try {
            // Enregistrer le rapport
            $rapport = Rapport::create([
                'intervention_id' => $validatedData['intervention_id'],
                'technicien_id' => $validatedData['technicien_id'],
                'contenu' => $validatedData['contenu']
            ]);
    
            // Enregistrer les tâches associées au rapport
            foreach ($validatedData['taches'] as $tacheData) {
                Tache::create([
                    'rapport_id' => $rapport->id,
                    'description' => $tacheData['description'],
                    'date_execution' => $tacheData['date_execution']
                ]);
            }
    
            DB::commit();
            return response()->json(['message' => 'Rapport et tâches enregistrés avec succès !']);
    
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Erreur lors de l\'enregistrement'], 500);
        }
    }


    public function getRapport($id)
    {
        // Récupérer le rapport lié à l'intervention
        $rapport = Rapport::where('intervention_id', $id)->first();
        
        // Si aucun rapport n'existe, renvoyer une réponse vide
        if (!$rapport) {
            return response()->json([
                'rapport' => null,
                'taches' => []
            ]);
        }
    
        return response()->json([
            'rapport' => $rapport->contenu, // On envoie juste le contenu du rapport
            'taches' => $rapport->taches // On renvoie la liste des tâches liées
        ]);
    }
    
    public function ajouterTache(Request $request)
    {
        $validated = $request->validate([
            'intervention_id' => 'required|exists:interventions,id',
            'description' => 'required|string|max:255',
        ]);
    
        // Chercher le rapport lié à l'intervention
        $rapport = Rapport::where('intervention_id', $validated['intervention_id'])->first();
    
        // Si pas de rapport, on le crée correctement
        if (!$rapport) {
            $rapport = new Rapport();
            $rapport->intervention_id = $validated['intervention_id'];
            $rapport->titre = 'Rapport Auto';
            $rapport->contenu = ''; // ✅ on initialise vide
            $rapport->technicien_id = auth()->id() ?? 1; // ou une valeur par défaut
            $rapport->save();
        }
    
        // Création de la tâche
        $tache = new Tache();
        $tache->rapport_id = $rapport->id;
        $tache->description = $validated['description'];
        $tache->save();
    
        return response()->json(['success' => true, 'message' => 'Tâche ajoutée avec succès.']);
    }
    

    
public function edit($id)
{
    $intervention = Intervention::with(['rapport', 'taches'])->find($id);

    if (!$intervention) {
        return response()->json(['error' => 'Intervention non trouvée'], 404);
    }

    return response()->json([
        'rapport' => $intervention->rapport,
        'taches' => $intervention->taches
    ]);
}


} 