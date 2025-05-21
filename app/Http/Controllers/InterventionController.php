<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers;
use App\Models\Historique;
use Illuminate\Validation\Rule;

use App\Models\User;
use App\Models\Intervention;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TypeIntervention;
use App\Models\Rapport;


class InterventionController extends Controller
{
   public function index()
{
    $user = auth()->user(); // ou Auth::user()

    $interventions = Intervention::with(['user', 'typeIntervention'])
        ->orderBy('created_at', 'desc')
        ->get();
      $interventions = Intervention::all();
        $techniciens = User::where('profile_id', 'technicien')->get();

    $typesIntervention = TypeIntervention::all();

    // Vérifier les profiles 1 (Admin) et 4 (Autre, si nécessaire)
    if ($user->profile_id == 1 || $user->profile_id == 4) {
        return view('admin.gestionsinterventions', compact('interventions', 'typesIntervention', 'techniciens'));
    }

    // Vérifier le profile 2 (Technicien)
    if ($user->profile_id == 2) {
        return view('technician.gestionsinterventions', compact('interventions', 'typesIntervention'));
    }

    // Vérifier le profile 3 (Utilisateur)
    if ($user->profile_id == 3) {
        return view('user.gestionsinterventions', compact('interventions', 'typesIntervention'));
    }

    // Si aucun profile ne correspond
    abort(403); // ou rediriger vers une page d'erreur
}

    public function adminIndex()
{
    $interventions = Intervention::with('user')->get();
    $techniciens = User::where('profile_id', 2)->get(); // Sélectionne les techniciens

    return view('admin.gestionsinterventions', compact('interventions', 'techniciens'));
}




    public function userIndex()
    {
        $interventions = Intervention::where('user_id', Auth::id())->get();
        return view('user.gestionsinterventions', compact('interventions'));
    }

    public function create()
    {
        $types = TypeIntervention::all();
        return view('user.createintervention', compact('types'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'type_intervention_id' => 'required|exists:type_interventions,id',
            'description' => 'required|string',
        ]);
        
        Intervention::create([
            'titre' => $request->titre,
            'type_intervention_id' => $request->type_intervention_id,
            'description' => $request->description,
            'user_id' => auth()->id(),
            'status' => 'En attente',
        ]);
        
        return redirect()->route('user.gestionsinterventions')->with('success', 'Intervention ajoutée.');
    }

    public function edit($id)
    {
        $intervention = Intervention::findOrFail($id);
        return view('user.editintervention', compact('intervention'));
    }

    public function update(Request $request, $id)
{
    // Trouver l'intervention
    $intervention = Intervention::findOrFail($id);

    // Vérifier si la réouverture est demandée
    if ($request->has('reopen')) {
        // Enregistrer l'action de réouverture
        $intervention->historiques()->create([
            'action' => 'Intervention réouverte',
            'user_id' => auth()->id(),
            'created_at' => now()
        ]);
    }

    // Mettre à jour les informations du rapport
    $rapport = $intervention->rapport;
    $rapport->update([
        'date_traitement' => $request->input('date_traitement'),
        'contenu' => $request->input('contenu'),
        'technicien_id' => auth()->id(),
    ]);

    // Si d'autres champs sont modifiés dans le rapport
    if ($request->has('taches')) {
        foreach ($request->input('taches') as $taskId) {
            $task = Task::find($taskId);
            // Vous pouvez mettre à jour les tâches si nécessaire
            $task->update([
                'status' => 'Complété',
                'updated_at' => now(),
            ]);
        }
    }

    // Enregistrer un historique des changements si nécessaire
    $intervention->historiques()->create([
        'action' => 'Modification de l\'intervention',
        'user_id' => auth()->id(),
        'created_at' => now()
    ]);

    return redirect()->route('interventions.show', ['id' => $id]);
}

    public function show($id)
    {
        $intervention = Intervention::with(['rapport', 'taches', 'historiques.user'])->findOrFail($id);
    
        // Récupérer les événements de réouverture dans l'ordre chronologique
        $reouvertures = $intervention->historiques()
            ->where('action', 'Intervention réouverte')
            ->orderBy('created_at', 'asc')
            ->get();
    
        // Récupérer la dernière réouverture si elle existe
        $lastReouverture = $reouvertures->last();
    
        // Détecter si l'intervention a été réouverte
        $isReopened = $lastReouverture !== null;
    
        // Si l'intervention est réouverte, conserver les anciennes données du rapport dans la session
        if ($isReopened) {
            session(['ancien_contenu_rapport' => $intervention->rapport->contenu]);
            session(['date_reouverture' => $lastReouverture->created_at]);
        }
    
        return view('interventions.show', compact('intervention', 'reouvertures', 'lastReouverture', 'isReopened'));
    }
    


   

    public function destroy($id)
    {
        $intervention = Intervention::findOrFail($id);
        $intervention->delete();

        return redirect()->route('user.gestionsinterventions')->with('success', 'Intervention supprimée.');
    }

    public function assignTechnicianForm($id)
    {
        $intervention = Intervention::findOrFail($id);
        $technicians = User::where('profile_id', 2)->get();

        return view('admin.assignTechnician', compact('intervention', 'technicians'));
    }

    public function assignTechnician(Request $request)
    {
        $request->validate([
            'intervention_id' => 'required|exists:interventions,id',
            'technicien_id' => 'required|exists:users,id',
        ]);

        $intervention = Intervention::findOrFail($request->intervention_id);
        $intervention->technicien_id = $request->technicien_id;
        $intervention->status = 'En cours';
        $intervention->save();
 
        return redirect()->back()->with('success', 'Technicien attribué avec succès.');
    }

public function cancelTechnician(Request $request)
{
    $request->validate([
        'intervention_id' => 'required|exists:interventions,id',
        'technicien_id' => 'required|exists:users,id',
    ]);

    $intervention = Intervention::findOrFail($request->intervention_id);

    if ($intervention->status === 'En cours') {
        $intervention->technicien_id = null; 
        $intervention->status = 'En attente'; 
        $intervention->save();

    return response()->json(['success' => true, 'message' => 'Attribution annulée.']);
}
    
}

public function cloturer($id)
{
    // Trouver l'intervention principale
    $intervention = Intervention::findOrFail($id);

    if ($intervention->status !== 'Terminé') {
        $intervention->status = 'Terminé';
        $intervention->save();
        return redirect()->back()->with('success', 'Intervention clôturée avec succès.');
    }

    return redirect()->back()->with('info', 'Cette intervention est déjà terminée.');
}









public function showRapport($id)
{
    $intervention = Intervention::findOrFail($id);

    if ($intervention->status != 'Terminé') {
        return response()->json(['error' => 'Rapport non disponible pour cette intervention.']);
    }

    $rapport = Rapport::with(['technicien', 'taches'])->where('intervention_id', $id)->first();

    if (!$rapport) {
        return response()->json(['error' => 'Aucun rapport trouvé.']);
    }
    $taches = \App\Models\Tache::where('rapport_id', $rapport->id)->pluck('description');


    return response()->json([
        'rapport_id' => $rapport->id,
        'intervention_id' => $rapport->intervention_id,
        'date_traitement' => $rapport->created_at->format('d/m/Y H:i'),
        'technicien_nom' => $rapport->technicien ? $rapport->technicien->name : 'Non défini',
        'contenu' => $rapport->contenu,
        'taches' => $rapport->taches->map(function ($tache) {
            return [
                'id' => $tache->id,
                'description' => $tache->description
            ];
        })
    ]);
}
public function reouvrir($id)
{
    $intervention = Intervention::findOrFail($id);
    if ($intervention->status === 'Terminé') {
        // Met à jour le statut et la date
        $intervention->status = 'En cours';
        $intervention->updated_at = now();
        $intervention->save();

        // Optionnel : enregistrer un historique si tu en as un
        Historique::create([
            'intervention_id' => $intervention->id,
            'user_id' => auth()->id(),
            'action' => 'Réouverture',
            'created_at' => now()
        ]);
    }

    return redirect()->back()->with('success', 'Intervention réouverte avec succès.');
}
public function interventionsPlus()
{
    $interventions = Intervention::all(); // ou ce que tu veux charger
    return view('interventions.interventionsplus', compact('interventions'));
}

}

    public function update(Request $request, $id)
    {
        try {
            $validator = \Validator::make($request->all(), [
                'type_intervention_id' => 'required|exists:type_interventions,id',
                'details_technicien' => 'required|string'
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            $intervention = Intervention::findOrFail($id);
            
            // Log before update
            \Log::info('Before update:', [
                'id' => $id,
                'old_type_id' => $intervention->type_intervention_id,
                'new_type_id' => $request->type_intervention_id
            ]);
            
            $intervention->type_intervention_id = $request->type_intervention_id;
            $intervention->details_technicien = $request->details_technicien;
            $intervention->status = 'En cours';
            $saved = $intervention->save();
            
            // Refresh to get updated relations
            $intervention->refresh();
            
            // Log after update
            \Log::info('After update:', [
                'id' => $intervention->id,
                'type_id' => $intervention->type_intervention_id,
                'saved' => $saved ? 'yes' : 'no'
            ]);
            
            if (!$saved) {
                return response()->json([
                    'success' => false,
                    'message' => 'Échec de la mise à jour'
                ], 500);
            }
            
            // Find the type name directly from database to be sure
            $typeName = 'Type inconnu';
            $typeIntervention = TypeIntervention::find($intervention->type_intervention_id);
            if ($typeIntervention) {
                $typeName = $typeIntervention->type ?? $typeIntervention->nom ?? 'Type inconnu';
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Intervention mise à jour avec succès',
                'type_name' => $typeName
            ]);
        } catch (\Exception $e) {
            \Log::error('Exception: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Exception: ' . $e->getMessage()
            ], 500);
        }
    }

    
    public function addType(Request $request, $id)
{
    $request->validate([
        'type_intervention_id' => 'required|exists:type_interventions,id',
        'details_technicien' => 'required|string'
    ]);
    
    // Récupérer l'intervention originale
    $originalIntervention = Intervention::findOrFail($id);
    
    // Créer une nouvelle entrée d'intervention avec le même ID intervention mais un type différent
    $newInterventionType = new Intervention([
        'user_id' => $originalIntervention->user_id,
        'titre' => $originalIntervention->titre,
        'description' => $originalIntervention->description,
        'status' => 'En cours',
        'type_intervention_id' => $request->type_intervention_id,
        'details_technicien' => $request->details_technicien,
        'intervention_id' => $id // Pour lier à l'intervention d'origine
    ]);
    
    $newInterventionType->save();
    
    // Récupérer le nom du type
    $typeName = TypeIntervention::find($request->type_intervention_id)->type ?? 'Type inconnu';
    
    return response()->json([
        'success' => true,
        'message' => 'Nouveau type d\'intervention ajouté avec succès',
        'type_name' => $typeName
    ]);
}


public function print($id)
{
    $intervention = Intervention::with(['details.type', 'details.technicien', 'historiques'])->findOrFail($id);
    return view('interventions.print', compact('intervention'));
}








public function testAssignTechnician(Request $request)
{
    try {
        $request->validate([
            'intervention_id' => 'required|exists:interventions,id',
            'technicien_id' => 'required|exists:users,id'
        ]);

        $intervention = Intervention::find($request->intervention_id);
        $technicien = User::find($request->technicien_id);

        // Vérification si l'utilisateur est bien un technicien (profile_id = 2)
        if ($technicien->profile_id !== 2) {
            return response()->json([
                'success' => false,
                'message' => 'L\'utilisateur sélectionné n\'est pas un technicien'
            ]);
        }

        // Vérification si le technicien est déjà assigné
        if ($intervention->techniciens()->where('user_id', $technicien->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Ce technicien est déjà assigné à cette intervention'
            ]);
        }

        // Vérification du statut
        if ($intervention->status !== 'En attente') {
            return response()->json([
                'success' => false,
                'message' => 'L\'intervention doit être en statut "En attente"'
            ]);
        }

        // Si tout est valide
        return response()->json([
            'success' => true,
            'message' => 'Test réussi: Le technicien peut être assigné',
            'data' => [
                'intervention' => $intervention->id,
                'technicien' => $technicien->id
            ]
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Erreur de validation',
            'errors' => $e->errors()
        ], 422);
    }
}



















public function assignMultipleTechnicians(Request $request)
{
    $request->validate([
        'intervention_id' => 'required|exists:interventions,id',
        'technicien_ids' => 'required|array|min:1',
        'technicien_ids.*' => 'exists:users,id',
        // 'type_intervention_id' est facultatif
    ]);

    $intervention = Intervention::findOrFail($request->intervention_id);
    $messages = [];

    // Utilise 9 par défaut si non fourni
    $typeInterventionId = $request->input('type_intervention_id', 9);

    foreach ($request->technicien_ids as $technicienId) {
        $alreadyAssigned = DetailsIntervention::where('intervention_id', $intervention->id)
            ->where('technicien_id', $technicienId)
            ->exists();

        if ($alreadyAssigned) {
            $technicien = User::find($technicienId);
            $messages[] = "Le technicien {$technicien->name} est déjà assigné à cette intervention.";
            continue;
        }

        DetailsIntervention::create([
            'intervention_id' => $intervention->id,
            'technicien_id' => $technicienId,
            'type_intervention_id' => $typeInterventionId,
            'status' => 'En cours',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    $intervention->update(['status' => 'En cours']);

    return response()->json([
        'success' => true,
        'message' => count($messages) > 0 ? implode("<br>", $messages) : 'Techniciens attribués avec succès.',
    ]);
}

public function unassignTechnicians(Request $request)
{
    

    $request->validate([
        'intervention_id' => 'required|exists:interventions,id',
        'technicien_ids' => 'required|array|min:1',
        'technicien_ids.*' => 'exists:users,id',
    ]);

    $intervention = Intervention::find($request->intervention_id);
    $messages = [];
    $unassignedCount = 0;

    foreach ($request->technicien_ids as $technicienId) {
        $deleted = DetailsIntervention::where('intervention_id', $intervention->id)
            ->where('technicien_id', $technicienId)
            ->delete();

        if ($deleted) {
            $unassignedCount++;
        } else {
            $technicien = User::find($technicienId);
            $messages[] = "Le technicien {$technicien->name} n'était pas assigné à cette intervention.";
        }
    }

    // Si tous les techniciens ont été désassignés, remettre le statut à "En attente"
    $remainingAssignments = DetailsIntervention::where('intervention_id', $intervention->id)->count();
    if ($remainingAssignments === 0) {
        $intervention->update(['status' => 'En attente']);
    }

    return response()->json([
        'success' => true,
        'message' => $unassignedCount > 0 
            ? ($unassignedCount . " technicien(s) désassigné(s) avec succès." . (count($messages) > 0 ? " " . implode(" ", $messages) : ""))
            : "Aucun technicien désassigné. " . implode(" ", $messages),
    ]);
}






public function cancelTechnicians(Request $request)
{
    $request->validate([
        'intervention_id' => 'required|exists:interventions,id',
        'technicien_ids' => 'required|array',
        'technicien_ids.*' => 'exists:users,id',
    ]);

    $interventionId = $request->input('intervention_id');
    $technicienIds = $request->input('technicien_ids');

    // Supprimer uniquement les détails d'intervention pour les techniciens sélectionnés
    DetailsIntervention::where('intervention_id', $interventionId)
        ->whereIn('technicien_id', $technicienIds)
        ->delete();

    // Vérifier s'il reste des techniciens assignés
    $remainingTechnicians = DetailsIntervention::where('intervention_id', $interventionId)->count();
    
    // Si plus aucun technicien n'est assigné, mettre à jour le statut
    if ($remainingTechnicians == 0) {
        Intervention::where('id', $interventionId)->update(['status' => 'En attente']);
    }

    return redirect()->back()->with('success', 'Les techniciens sélectionnés ont été désassignés avec succès.');
}

public function showDetails(Intervention $intervention)
{
    // Charger les relations nécessaires
    $intervention->load([
        'user',
        'details.typeIntervention',
        'details.technicien',
        'historiques.user'
    ]);

    return view('interventions.details', compact('intervention'));
}


public function getInterventionDetails($id)
{
    try {
        $intervention = Intervention::with(['details.technicien', 'details.type_intervention', 'user'])
            ->findOrFail($id);

        return response()->json([
            'id' => $intervention->id,
            'user' => $intervention->user->name ?? 'Inconnu',
            'titre' => $intervention->titre,
            'description' => $intervention->description ?? 'Aucune description',
            'created_at' => $intervention->created_at,
            'type' => $intervention->details->type_intervention->libelle ?? 'Non défini',
            'status' => $intervention->status,
            'technicien' => $intervention->details->technicien->name ?? 'Inconnu',
            'contenu' => $intervention->details->contenu ?? 'Aucun contenu'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Intervention non trouvée'
        ], 404);
    }
}







public function interventionsDetails($id)
{
    $intervention = Intervention::with(['technicien', 'type'])->findOrFail($id);

    $user = auth()->user();

    // Technicien peut voir seulement ses interventions
    if ($user->profile_id == 3 && $user->id !== $intervention->technicien_id) {
        abort(403, 'Accès interdit');
    }

    return view('intervention.details', compact('intervention'));
}

public function show($id)
{
    $intervention = Intervention::with(['details.type', 'details.technicien'])
            ->findOrFail($id);
    $intervention = Intervention::with([
        'details' => function($query) {
            $query->with(['technicien:id,name', 'type:id,type'])
                  ->orderBy('created_at', 'desc');
        },
        'user:id,name' // Si vous avez un créateur
    ])->findOrFail($id);

    // Debug final (à supprimer après vérification)
    logger('Détails chargés:', [
        'count' => $intervention->details->count(),
        'premier' => $intervention->details->first()?->toArray()
    ]);

    

    return view('interventions.details', compact('intervention'));
}




public function showHistorique($id)
{
    // Récupérer l'intervention avec ses détails et relations
    $intervention = Intervention::with([
        'details' => function($query) {
            $query->with(['technicien:id,name', 'type:id,type'])
                  ->orderBy('created_at', 'desc');
        },
        'user:id,name'
    ])->findOrFail($id);

    // Récupérer les historiques associés à l'intervention avec leurs utilisateurs
    $historiques = InterventionHistorique::with('user') // ✅ Charger la relation user
        ->where('intervention_id', $intervention->id)
        ->orderBy('created_at', 'desc')
        ->get();

    // Log pour débogage
    logger('Historiques chargés:', [
        'count' => $historiques->count(),
        'premier' => $historiques->first() ? $historiques->first()->toArray() : null
    ]);

    

    // Passer les données à la vue
    return view('interventions.details', compact('intervention', 'historiques'));
}



public function historiqueIndex()
{
    // Récupérer toutes les interventions terminées avec leurs relations
    $interventions = Intervention::with(['user:id,name', 'typeIntervention:id,type', 'techniciens:id,name'])
        ->where('status', 'Terminé')
        ->orderBy('updated_at', 'desc')
        ->get();
    
    return view('interventions.historique', compact('interventions'));

}


public function story()
{
    $interventions = Intervention::with(['user:id,name', 'techniciens:id,name', 'typeIntervention:id,type'])
        ->where('statut', 'terminée') // ou adapte si besoin
        ->orderBy('created_at', 'desc')
        ->get();

    return view('story', compact('interventions'));


}



}
