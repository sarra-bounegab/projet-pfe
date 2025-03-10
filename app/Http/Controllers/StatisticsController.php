<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Intervention;
use App\Models\TypeIntervention;

class StatisticsController extends Controller
{
    public function index()
    {
        // Données pour l'histogramme des utilisateurs
        $totalUsers = User::count();
        $totalAdmins = User::where('profile_id', 1)->count();
        $totalTechnicians = User::where('profile_id', 2)->count();
        $totalInterventions = Intervention::count(); // Vérifie que cette ligne est bien présente

        // Données pour le pie chart des interventions
        $interventionsByType = TypeIntervention::withCount('interventions')->get();

        // Préparer les données pour le graphique
        $interventionLabels = $interventionsByType->pluck('type');
        $interventionData = $interventionsByType->pluck('interventions_count');

        return view('admin.statistics', compact('totalUsers', 'totalAdmins', 'totalTechnicians', 'totalInterventions', 'interventionLabels', 'interventionData'));
    }
}

