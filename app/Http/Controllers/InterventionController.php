<?php

namespace App\Http\Controllers;

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
        $interventions = Intervention::all();
        $techniciens = User::where('role', 'technicien')->get(); // Vérifie que "role" est bien défini pour les techniciens

        return view('admin', compact('interventions', 'techniciens'));
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
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $intervention = Intervention::findOrFail($id);
        $intervention->update($request->all());

        return redirect()->route('user.gestionsinterventions')->with('success', 'Intervention mise à jour.');
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
    ]);

    $intervention = Intervention::findOrFail($request->intervention_id);

    if ($intervention->status === 'En cours') {
        $intervention->technicien_id = null; 
        $intervention->status = 'En attente'; 
        $intervention->save();

        return response()->json(['success' => true, 'message' => 'Attribution annulée.']);
    }

    return response()->json(['success' => false, 'message' => 'Action non autorisée.'], 403);
}


public function ajouterTacheRapport(Request $request, $rapportId) {
    $request->validate([
        'description' => 'required|string',
        'date_execution' => 'required|date'
    ]);

    $tache = TacheRapport::create([
        'rapport_id' => $rapportId,
        'description' => $request->description,
        'date_execution' => $request->date_execution
    ]);

    return response()->json(['message' => 'Tâche ajoutée', 'tache' => $tache]);
}


public function cloturer($id)
{
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


public function getRapport($interventionId)
{
    $rapport = Rapport::where('intervention_id', $interventionId)->with('taches', 'technicien')->first();

    if (!$rapport) {
        return response()->json(['error' => 'Aucun rapport trouvé pour cette intervention.'], 404);
    }

    return response()->json([
        'rapport_id' => $rapport->id,
        'intervention_id' => $rapport->intervention_id,
        'date_traitement' => $rapport->date_traitement,
        'technicien_nom' => $rapport->technicien ? $rapport->technicien->name : 'Non attribué',
        'contenu' => $rapport->contenu,
        'taches' => $rapport->taches->pluck('description'),
    ]);
}


}