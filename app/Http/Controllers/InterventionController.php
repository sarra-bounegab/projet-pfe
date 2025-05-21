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
use App\Models\DetailsIntervention;

use App\Http\Controllers\TechnicianController;


class InterventionController extends Controller
{
   public function index()
{
    $user = auth()->user(); // ou Auth::user()

    $interventions = Intervention::with(['user', 'typeIntervention'])
        ->orderBy('created_at', 'desc')
        ->get();
      $interventions = Intervention::all();
        $techniciens = User::where('profile_id', 2)->get();

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
        $techniciens = User::where('profile_id', 2)->get();
        $typesIntervention = TypeIntervention::all(); // ✅ ajouter cette ligne

        return view('admin.gestionsinterventions', compact('interventions', 'techniciens', 'typesIntervention'));
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
                'description' => 'required|string',
            ]);

            // Utiliser l'ID du type "Non spécifié" (ici on suppose que l'ID de ce type est 9)
            $typeInterventionId = 9; // ID du type "Non spécifié"

            // Création de l'intervention avec `type_intervention_id` = 9 (Non spécifié)
            $intervention = Intervention::create([
                'titre' => $request->titre,
                'type_intervention_id' => $typeInterventionId, // ID du type "Non spécifié"
                'description' => $request->description,
                'user_id' => auth()->id(),
                'status' => 'En attente',
            ]);

            return redirect()->route('user.gestionsinterventions')->with('success', 'Intervention ajoutée.');
        }



        public function update_intervention_user(Request $request, $id)
        {
            $intervention = Intervention::findOrFail($id);

            if (in_array($intervention->status, ['En attente', 'En cours'])) {
                $request->validate([
                    'titre' => 'required|string|max:255',
                    'description' => 'required|string',
                ]);

                $intervention->update([
                    'titre' => $request->titre,
                    'description' => $request->description,
                ]);

                return back()->with('success', 'Intervention mise à jour avec succès.');
            }

            return back()->with('error', 'Modification non autorisée.');
        }

        public function destroy_intervention_user($id)
        {
            $intervention = Intervention::findOrFail($id);

            if ($intervention->status == 'En attente') {
                $intervention->delete();
                return redirect()->back()->with('success', 'Intervention supprimée avec succès.');
            }

            return redirect()->back()->with('error', 'Suppression non autorisée.');
        }



        public function assignTechnicians(Request $request)
        {
            try {
                $validated = $request->validate([
                    'intervention_id' => 'required|exists:interventions,id',
                    'technicien_ids' => 'required|array|min:1',
                    'technicien_ids.*' => [
                        'exists:users,id',
                        function ($attribute, $value, $fail) {
                            $user = \App\Models\User::with('profile')->find($value);

                            if (!$user) {
                                $fail('L\'utilisateur sélectionné n\'existe pas.');
                                return;
                            }

                            if (!$user->profile) {
                                $fail('L\'utilisateur '.$user->name.' n\'a pas de profil associé.');
                                return;
                            }

                            if ($user->profile->id !== 2) { // 2 = ID du profil technicien
                                $fail('L\'utilisateur '.$user->name.' n\'est pas un technicien (Profil ID: '.$user->profile->id.')');
                            }
                        }
                    ]
                ]);

                // Simulation seulement - pas de création réelle
                $intervention = Intervention::find($validated['intervention_id']);

                // Vérification statut
                if ($intervention->status !== 'En attente') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Simulation: L\'intervention doit être en statut "En attente" pour assignation'
                    ]);
                }

                // Vérification des techniciens déjà assignés
                $existing = $intervention->techniciens()
                    ->whereIn('users.id', $validated['technicien_ids'])
                    ->count();

                if ($existing > 0) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Simulation: '.$existing.' technicien(s) sont déjà assignés à cette intervention'
                    ]);
                }

                // Si tout est valide
                return response()->json([
                    'success' => true,
                    'message' => 'Simulation: Validation réussie pour '.count($validated['technicien_ids']).' technicien(s)',
                    'details' => [
                        'intervention_id' => $intervention->id,
                        'current_status' => $intervention->status,
                        'techniciens_count' => count($validated['technicien_ids']),
                        'would_change_status_to' => 'En cours'
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
        'type_intervention_id' => 'required|exists:type_interventions,id',
    ]);

    $intervention = Intervention::findOrFail($request->intervention_id);

    // Créer une nouvelle entrée dans details_interventions
    $detailsIntervention = $intervention->techniciens()->attach($request->technicien_id, [
        'type_intervention_id' => $request->type_intervention_id,
        'status' => 'En cours',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // Mettre à jour le statut global de l'intervention
    $intervention->update(['status' => 'En cours']);

    return response()->json(['success' => true, 'message' => 'Technicien attribué avec succès.']);
}

public function cancelTechnician(Request $request)
{
    $request->validate([
        'intervention_id' => 'required|exists:interventions,id',
        'technicien_id' => 'required|exists:users,id',
    ]);

    $intervention = Intervention::findOrFail($request->intervention_id);

    // Supprimer la relation dans details_interventions
    $intervention->techniciens()->detach($request->technicien_id);

    // Vérifier s'il reste des techniciens assignés
    if ($intervention->techniciens()->count() === 0) {
        $intervention->update(['status' => 'En attente']);
    }

    return response()->json(['success' => true, 'message' => 'Attribution annulée.']);
}



public function cloturer($id)
{
    // Trouver l'intervention principale
    $intervention = Intervention::findOrFail($id);
    $intervention->update(['status' => 'Terminé']);

    // Mettre à jour aussi les détails liés à cette intervention
    DetailsIntervention::where('intervention_id', $id)
        ->update(['status' => 'Terminé']);

    return back()->with('success', 'Intervention clôturée avec succès.');
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
