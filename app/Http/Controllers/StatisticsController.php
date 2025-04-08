<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Intervention;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    public function index(Request $request)
{
    // Récupérer les services
    $services = Service::all();
    $periodFilter = $request->get('period', '7days');

    // Récupérer le nombre d'interventions par service en fonction du filtrage
    $query = Intervention::query();

    switch ($periodFilter) {
        case '7days':
            $query->where('interventions.created_at', '>=', now()->subDays(7));
            break;
        case 'month':
            $query->where('interventions.created_at', '>=', now()->subMonth());
            break;
        case 'year':
            $query->where('interventions.created_at', '>=', now()->subYear());
            break;
    }

    // Si un service est sélectionné, ajouter le filtre
    if ($request->has('service') && $request->get('service') != '') {
        $query->where('users.service_id', $request->get('service'));
    }

    // Obtenir les interventions par service
    $interventionsByService = $query->join('users', 'users.id', '=', 'interventions.user_id')
        ->select('users.service_id', \DB::raw('count(*) as total'))
        ->groupBy('users.service_id')
        ->get();

    // Préparer les données pour le graphique
    $serviceNames = [];
    $interventionCounts = [];

    foreach ($interventionsByService as $item) {
        $serviceNames[] = $item->service_id;
        $interventionCounts[] = $item->total;
    }

    // Passer les données à la vue
    return view('Statistics', compact('interventionsByService', 'services', 'periodFilter', 'serviceNames', 'interventionCounts'));
}

}

