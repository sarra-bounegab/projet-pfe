<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Technician;
use Illuminate\Http\Request;

class TechnicianController extends Controller
{
    // Afficher la liste des techniciens
    public function index()
{
    $interventions = Intervention::all();
    $techniciens = User::where('role', 'technicien')->get(); // Récupère les techniciens

    return view('admin', compact('interventions', 'techniciens'));
}


    // Afficher le formulaire de création
    public function create()
    {
        return view('admin.technicians.create');
    }

    // Enregistrer un nouveau technicien
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:technicians,email',
            'status' => 'required|in:active,inactive',
        ]);

        Technician::create($request->all());

        return redirect()->route('admin.technicians.index')->with('success', 'Technicien créé avec succès.');
    }


    public function gestionInterventions()
{
    // Récupérer l'ID du technicien connecté
    $technicianId = auth()->user()->id;

    // Récupérer les interventions qui lui sont attribuées
    $interventions = Intervention::where('technicien_id', $technicianId)
                                 ->orderBy('created_at', 'desc')
                                 ->get();

    return view('technician.gestionsinterventions', compact('interventions'));
}

}

