<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Intervention;
use Illuminate\Support\Facades\Auth;
use App\Models\TypeIntervention;

class InterventionController extends Controller
{
    // Liste des interventions pour l'admin
    public function adminIndex()
    {
        $interventions = Intervention::with('user')->get(); // Récupérer toutes les interventions
        return view('admin.gestionsinterventions', compact('interventions'));
    }

    // Liste des interventions pour un utilisateur
    public function userIndex()
    {
        $interventions = Intervention::where('user_id', Auth::id())->get();
        return view('user.gestionsinterventions', compact('interventions'));
    }

    // Formulaire de création d'une intervention
    public function create()
    {
        $types = TypeIntervention::all(); // Charger les types d'interventions
        return view('user.createintervention', compact('types'));
    }

    // Enregistrer une intervention
    public function store(Request $request)
    {
        $request->validate([
            'type_intervention_id' => 'required|exists:type_interventions,id',
            'description' => 'required|string',
        ]);

        Intervention::create([
            'type_intervention_id' => $request->type_intervention_id,
            'description' => $request->description,
            'user_id' => Auth::id(),
            'date' => now()->toDateString(), // Générer automatiquement la date
            'status' => 'en attente', // Statut par défaut
        ]);

        return redirect()->route('user.gestionsinterventions')->with('success', 'Intervention ajoutée.');
    }


    // Modifier une intervention
    public function edit($id)
    {
        $intervention = Intervention::findOrFail($id);
        return view('user.editintervention', compact('intervention'));
    }

    // Mise à jour d'une intervention
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

    // Supprimer une intervention
    public function destroy($id)
    {
        $intervention = Intervention::findOrFail($id);
        $intervention->delete();

        return redirect()->route('user.gestionsinterventions')->with('success', 'Intervention supprimée.');
    }
}
