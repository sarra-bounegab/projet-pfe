<?php



namespace App\Http\Controllers;

use App\Models\Technician;
use Illuminate\Http\Request;

class TechnicianController extends Controller
{
    // Afficher la liste des techniciens
    public function index()
    {
        $technicians = Technician::all();
        return view('technicians.index', compact('technicians'));
    }

    // Afficher le formulaire de création d'un technicien
    public function create()
    {
        return view('technicians.create');
    }

    // Créer un nouveau technicien
    public function store(Request $request)
    {
        // Validation des données
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:technicians,email',
            'status' => 'required|in:active,inactive',
        ]);

        // Création du technicien
        Technician::create([
            'name' => $request->name,
            'email' => $request->email,
            'status' => $request->status,
        ]);

        // Rediriger vers la page des techniciens avec un message de succès
        return redirect()->route('technicians.index')->with('success', 'Technicien créé avec succès!');
    }

    // Afficher le formulaire d'édition d'un technicien
    public function edit($id)
    {
        $technician = Technician::findOrFail($id);
        return view('technicians.edit', compact('technician'));
    }

    // Mettre à jour un technicien
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:technicians,email,' . $id,
            'status' => 'required|in:active,inactive',
        ]);

        $technician = Technician::findOrFail($id);
        $technician->update([
            'name' => $request->name,
            'email' => $request->email,
            'status' => $request->status,
        ]);

        return redirect()->route('technicians.index')->with('success', 'Technicien mis à jour avec succès!');
    }

    // Supprimer un technicien
    public function destroy($id)
    {
        $technician = Technician::findOrFail($id);
        $technician->delete();

        return redirect()->route('technicians.index')->with('success', 'Technicien supprimé avec succès!');
    }
}
