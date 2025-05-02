<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Intervention;
use App\Models\TypeIntervention;
use App\Models\Technician;
use Illuminate\Http\Request;
use App\Models\DetailsIntervention;
use Illuminate\Support\Facades\Log; 

class TechnicianController extends Controller
{
    // Méthodes existantes...
    // Afficher la liste des techniciens
    public function index()
    {
        $types = TypeIntervention::all();
        $technicians = Technician::all();

        $interventions = Intervention::with(['user', 'details.technicien', 'details.typeIntervention'])->get();
    $typesIntervention = TypeIntervention::all();
        return view('technician.gestionsinterventions', compact('technicians', 'types','interventions', 'typesIntervention'));
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

    public function gestionInterventions()
    {
        $technicianId = Auth::id(); // Récupérer l'ID du technicien connecté
        $interventions = Intervention::where('technicien_id', $technicianId)->get();
        // Récupérer les types d'interventions pour le menu déroulant
        $typesIntervention = TypeIntervention::all();
    
        return view('technician.gestionsinterventions', compact('interventions', 'typesIntervention'));
    }

    // Nouvelle méthode pour obtenir tous les détails d'une intervention
    public function getDetailsInterventions($id)
    {
        \Log::info('Récupération de tous les détails', ['intervention_id' => $id]);
        
        $details = DetailsIntervention::with('typeIntervention')
                    ->where('intervention_id', $id)
                    ->where('technicien_id', auth()->id())
                    ->get();

        return response()->json($details);
    }

    // Conserver l'ancienne méthode pour la compatibilité
    public function getDetailsIntervention($id)
    {
        \Log::info('Tentative de récupération des détails', ['intervention_id' => $id]);
        
        $details = DetailsIntervention::where('intervention_id', $id)
                    ->where('technicien_id', auth()->id())
                    ->first();

        return response()->json($details ?? ['contenu' => '', 'type_intervention_id' => null]);
    }

    // Nouvelle méthode pour ajouter un nouveau détail
    public function addDetail(Request $request)
    {
        $validated = $request->validate([
            'intervention_id' => 'required|exists:interventions,id',
            'contenu' => 'required|string',
            'type_intervention_id' => 'required|exists:type_interventions,id'
        ]);

        \Log::info('Tentative d\'ajout de détail', $validated);

        try {
            // Vérifier si ce type existe déjà pour cette intervention
            $existingDetail = DetailsIntervention::where('intervention_id', $validated['intervention_id'])
                ->where('technicien_id', auth()->id())
                ->where('type_intervention_id', $validated['type_intervention_id'])
                ->first();

            if ($existingDetail) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ce type d\'intervention existe déjà pour cette intervention.'
                ], 400);
            }

            // Récupérer l'intervention pour les données nécessaires
            $intervention = Intervention::findOrFail($validated['intervention_id']);
            
            // Créer un nouveau détail avec toutes les données requises
            $detail = DetailsIntervention::create([
                'intervention_id' => $validated['intervention_id'],
                'technicien_id' => auth()->id(),
                'contenu' => $validated['contenu'],
                'type_intervention_id' => $validated['type_intervention_id'],
                'titre' => $intervention->titre,
                'description' => $intervention->description,
                'date' => now()->format('Y-m-d'),
                'status' => 'En cours'
            ]);

            \Log::info('Détail ajouté avec succès', ['detail_id' => $detail->id]);

            return response()->json([
                'success' => true,
                'message' => 'Détail ajouté avec succès',
                'detail' => $detail
            ]);

        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'ajout du détail', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'ajout du détail: ' . $e->getMessage()
            ], 500);
        }
    }

    // Méthode pour mettre à jour un détail existant
    public function updateDetail(Request $request)
    {
        $validated = $request->validate([
            'detail_id' => 'required|exists:details_interventions,id',
            'contenu' => 'required|string',
            'type_intervention_id' => 'required|exists:type_interventions,id'
        ]);

        \Log::info('Tentative de mise à jour du détail', $validated);

        try {
            $detail = DetailsIntervention::findOrFail($validated['detail_id']);
            
            // Vérifier si l'utilisateur est autorisé à modifier ce détail
            if ($detail->technicien_id != auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous n\'êtes pas autorisé à modifier ce détail.'
                ], 403);
            }
            
            // Vérifier si le type existe déjà pour un autre détail de cette intervention
            if ($detail->type_intervention_id != $validated['type_intervention_id']) {
                $existingDetail = DetailsIntervention::where('intervention_id', $detail->intervention_id)
                    ->where('technicien_id', auth()->id())
                    ->where('type_intervention_id', $validated['type_intervention_id'])
                    ->where('id', '!=', $detail->id)
                    ->first();
                
                if ($existingDetail) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Ce type d\'intervention existe déjà pour cette intervention.'
                    ], 400);
                }
            }
            
            // Mettre à jour le détail
            $detail->update([
                'contenu' => $validated['contenu'],
                'type_intervention_id' => $validated['type_intervention_id']
            ]);

            \Log::info('Détail mis à jour avec succès', ['detail_id' => $detail->id]);

            return response()->json([
                'success' => true,
                'message' => 'Détail mis à jour avec succès'
            ]);

        } catch (\Exception $e) {
            \Log::error('Erreur lors de la mise à jour du détail', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour du détail'
            ], 500);
        }
    }

    // Méthode pour supprimer un détail
    public function deleteDetail($id)
    {
        \Log::info('Tentative de suppression du détail', ['detail_id' => $id]);

        try {
            $detail = DetailsIntervention::findOrFail($id);
            
            // Vérifier si l'utilisateur est autorisé à supprimer ce détail
            if ($detail->technicien_id != auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous n\'êtes pas autorisé à supprimer ce détail.'
                ], 403);
            }
            
            $detail->delete();

            \Log::info('Détail supprimé avec succès', ['detail_id' => $id]);

            return response()->json([
                'success' => true,
                'message' => 'Détail supprimé avec succès'
            ]);

        } catch (\Exception $e) {
            \Log::error('Erreur lors de la suppression du détail', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression du détail'
            ], 500);
        }
    }

    // Pour la rétrocompatibilité, conserver l'ancienne méthode
    public function updateDetailsIntervention(Request $request)
    {
        $validated = $request->validate([
            'intervention_id' => 'required|exists:interventions,id',
            'contenu' => 'required|string',
            'type_intervention_id' => 'required|exists:type_interventions,id'
        ]);

        \Log::info('Tentative de mise à jour du contenu et du type', $validated);

        try {
            // Récupérer l'intervention pour les données requises
            $intervention = Intervention::findOrFail($validated['intervention_id']);
            
            $details = DetailsIntervention::updateOrCreate(
                [
                    'intervention_id' => $validated['intervention_id'],
                    'technicien_id' => auth()->id(),
                    'type_intervention_id' => $validated['type_intervention_id']
                ],
                [
                    'contenu' => $validated['contenu'],
                    'titre' => $intervention->titre,
                    'description' => $intervention->description,
                    'date' => now()->format('Y-m-d'),
                    'status' => 'En cours'
                ]
            );

            \Log::info('Contenu et type mis à jour avec succès', ['details_id' => $details->id]);

            return response()->json([
                'success' => true,
                'message' => 'Contenu et type enregistrés avec succès'
            ]);

        } catch (\Exception $e) {
            \Log::error('Erreur lors de la mise à jour', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'enregistrement: ' . $e->getMessage()
            ], 500);
        }
    }

    public function ajouterDetails(Request $request)
    {
        $request->validate([
            'type_intervention_id' => 'required|exists:type_interventions,id',
            'details_technicien' => 'required|string',
            'intervention_id' => 'required|exists:interventions,id',
        ]);

        $intervention = Intervention::find($request->intervention_id);

        $intervention->type_intervention_id = $request->type_intervention_id;
        $intervention->details_technicien = $request->details_technicien;
        $intervention->save();

        return response()->json(['success' => true]);
    }

    public function gestionsInterventions()
    {
        $technicianId = Auth::id(); // Récupérer l'ID du technicien connecté
        $interventions = Intervention::where('technicien_id', $technicianId)->get();
        $types = TypeIntervention::all(); // Récupérer tous les types d'intervention

        return view('technician.gestionsinterventions', compact('types', 'interventions'));
    }

    public function getInterventionDetails($id)
    {
        $intervention = Intervention::with(['details.technicien', 'details.typeIntervention'])->findOrFail($id);
    
        $details = $intervention->details->map(function ($detail) {
            return [
                'id' => $detail->id,
                'titre' => $detail->titre,
                'technicien' => $detail->technicien ? $detail->technicien->name : "Technicien non spécifié",
                'type_intervention' => $detail->typeIntervention ? $detail->typeIntervention->nom : "Type non spécifié",
                'contenu' => $detail->contenu,
                'description' => $detail->description,
                'date' => $detail->date,
                'status' => $detail->status,
                'created_at' => $detail->created_at,
            ];
        });
    
        return response()->json([
            'intervention' => $intervention,
            'details' => $details,
        ]);
    }
}