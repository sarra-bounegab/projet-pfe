<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Intervention;
use App\Models\Historique;
use Illuminate\Http\Request;

class HistoriqueController extends Controller
{
   
    public function index()
{
    // Récupérer l'utilisateur connecté
    $user = Auth::user();
    
    // Initialiser les variables
    $interventions = collect();
    $historiques = collect();
    
    if ($user->role === 'employe') {
        // Interventions créées par l'employé
        $interventions = Intervention::where('user_id', $user->id)
            ->where('status', 'terminée')
            ->get();
        
        $historiques = Historique::whereIn('intervention_id', $interventions->pluck('id'))->get();
    } elseif ($user->role === 'technicien') {
        // Interventions attribuées à ce technicien
        $interventions = Intervention::where('technicien_id', $user->id)
            ->where('status', 'terminée')
            ->get();
        
        $historiques = Historique::whereIn('intervention_id', $interventions->pluck('id'))->get();
    } else {
        // Admin : voir tous les historiques
        $interventions = Intervention::where('status', 'terminée')->get();
        $historiques = Historique::all();
    }
    
    return view('historiques', compact('interventions', 'historiques'));
}

public function showHistorique(Request $request)
{
    // Récupérer l'utilisateur connecté
    $user = Auth::user();
    
    // Vérifier le profil de l'utilisateur
    if ($user->profile_id == 1) { // Admin
        // L'admin voit tous les historiques
        $historiques = Historique::with('intervention')->get();
        $interventions = Intervention::where('status', 'terminé')->get();
    } else {
        // Les utilisateurs et techniciens ne voient que leurs propres historiques
        $historiques = Historique::with('intervention')
            ->where('user_id', $user->id)
            ->orWhereHas('intervention', function ($query) use ($user) {
                $query->where('user_id', $user->id);  // Filtrer les interventions associées à l'utilisateur ou technicien
            })
            ->get();
        
        // Récupérer les interventions associées à cet utilisateur
        if ($user->role === 'employe') {
            $interventions = Intervention::where('user_id', $user->id)
                ->where('status', 'terminé')
                ->get();
        } elseif ($user->role === 'technicien') {
            $interventions = Intervention::where('technicien_id', $user->id)
                ->where('status', 'terminé')
                ->get();
        } else {
            $interventions = collect(); // Collection vide par défaut
        }
    }
    
    // Vérifier si des historiques existent
    if ($historiques->isEmpty()) {
        \Log::debug('Aucun historique trouvé pour l\'utilisateur avec ID: ' . $user->id);
    }
    
    // Passer les données à la vue
    return view('historique', compact('historiques', 'interventions'));
}
 




public function showStory()
{
    $interventions = Intervention::with(['user', 'typeIntervention'])
        ->where('status', 'terminée')
        ->orderBy('created_at', 'desc')
        ->get();

    return view('story', compact('interventions'));
}


}