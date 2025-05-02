<?php
// app/Http/Controllers/DetailsInterventionController.php
// DetailsInterventionController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetailsIntervention;

class DetailsInterventionController extends Controller
{
    public function edit($id)
    {
        $details = DetailsIntervention::findOrFail($id); // Récupère les détails
        return view('technician.gestionsinterventions', compact('details'));
    }
    

    public function update(Request $request, $id)
{
    $request->validate([
        'contenu' => 'required|string',  // Validation pour le champ 'contenu'
    ]);

    $details = DetailsIntervention::findOrFail($id); // Récupère les détails
    $details->contenu = $request->input('contenu'); // Mise à jour du champ contenu
    $details->save(); // Sauvegarde les modifications

    return redirect()->route('details.edit', $id)
                     ->with('success', 'Le contenu a été mis à jour avec succès.');
}

















}
