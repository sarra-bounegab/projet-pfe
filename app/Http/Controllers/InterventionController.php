<?php

namespace App\Http\Controllers;
use App\Models\User;

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

    public function assignTechnicianForm($id)
{
    $intervention = Intervention::findOrFail($id);
    $technicians = User::where('profile_id', 2)->get();
    // Sélectionne les techniciens

    return view('admin.assignTechnician', compact('intervention', 'technicians'));
}

public function assignTechnician(Request $request, $id)
{
    dd($request->all()); 

    $request->validate([
        'technicien_id' => 'required|exists:users,id',
    ]);

    $intervention = Intervention::findOrFail($id);
    $intervention->technicien_id = $request->technicien_id;
    $intervention->status = 'En attente'; // Mettre l'état initial après assignation
    $intervention->save();

    return redirect()->route('admin.gestionsinterventions')->with('success', 'Intervention attribuée avec succès.');
}



public function unassign($id)
{
    $intervention = Intervention::findOrFail($id);
    
    // Supprimer l'attribution et remettre l'état à "En attente"
    $intervention->update([
        'technicien_id' => null,
        'status' => 'En attente'
    ]);

    return redirect()->back()->with('success', 'L\'attribution a été annulée.');
}

}
