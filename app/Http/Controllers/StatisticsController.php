<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Intervention;
use App\Models\Service;
use Carbon\Carbon;

class StatisticsController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalAdmins = User::where('profile_id', 1)->count();
        $totalTechnicians = User::where('profile_id', 2)->count();
        $totalInterventions = Intervention::count();
        $totalServices = Service::count();
        $divisions = Service::whereNull('parent_id')->get();

        // Comptage des statuts
        $statusCounts = [
            'en attente' => Intervention::where('status', 'en attente')->count(),
            'en cours' => Intervention::where('status', 'en cours')->count(),
            'terminée' => Intervention::where('status', 'terminée')->count()
        ];

        return view('admin.statistics', compact(
            'totalUsers',
            'totalAdmins',
            'totalTechnicians',
            'totalInterventions',
            'totalServices',
            'divisions',
            'statusCounts'
        ));
    }

    public function getInterventionsByStatus()
    {
        $interventions = Intervention::select('status')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('status')
            ->get();

        $labels = $interventions->pluck('status');
        $values = $interventions->pluck('count');

        return response()->json([
            'labels' => $labels,
            'values' => $values,
            'title' => 'Répartition des interventions par statut'
        ]);
    }

    public function getSubServices($divisionId)
    {
        $subServices = Service::where('parent_id', $divisionId)->get();
        return response()->json($subServices);
    }

    public function getServiceDistribution(Request $request)
    {
        try {
            $dataType = $request->input('dataType', 'users'); // Changé de 'interventions' à 'users'
            $timeFrame = $request->input('timeFrame', 'all');
            $divisionId = $request->input('division_id');
            $subServiceId = $request->input('sub_service_id');

            $startDate = $this->getStartDate($timeFrame);

            // Construction de la requête pour les services
            $services = $this->getFilteredServices($divisionId, $subServiceId);

            if ($services->isEmpty()) {
                return response()->json([
                    'labels' => [],
                    'values' => [],
                    'title' => 'Aucun service trouvé',
                    'message' => 'Aucune donnée disponible pour les filtres sélectionnés'
                ]);
            }

            switch ($dataType) {
                case 'admins':
                    return $this->buildUserStats($services, 1, $startDate, "Nombre d'administrateurs par service");
                case 'technicians':
                    return $this->buildUserStats($services, 2, $startDate, "Nombre de techniciens par service");
                case 'users':
                    return $this->buildUserStats($services, 3, $startDate, "Nombre d'utilisateurs par service");
                default:
                    return response()->json(['error' => 'Type de données non valide'], 400);
            }
        } catch (\Exception $e) {
            \Log::error('Erreur dans getServiceDistribution: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'error' => 'Erreur lors de la récupération des données',
                'message' => config('app.debug') ? $e->getMessage() : 'Une erreur interne est survenue'
            ], 500);
        }
    }

    private function getStartDate($timeFrame)
    {
        $now = Carbon::now();

        switch ($timeFrame) {
            case '7days':
                return $now->copy()->subDays(7);
            case '30days':
                return $now->copy()->subDays(30);
            case 'month':
                return $now->copy()->startOfMonth();
            case 'year':
                return $now->copy()->startOfYear();
            case 'all':
            default:
                return null;
        }
    }

    private function getFilteredServices($divisionId, $subServiceId)
    {
        $query = Service::query();

        if ($subServiceId) {
            // Si un sous-service spécifique est sélectionné
            $query->where('id', $subServiceId);
        } elseif ($divisionId) {
            // Si une division est sélectionnée, récupérer ses sous-services
            $query->where('parent_id', $divisionId);
        } else {
            // Sinon, récupérer toutes les divisions (services parents)
            $query->whereNull('parent_id');
        }

        return $query->get();
    }

    private function buildUserStats($services, $profileId, $startDate = null, $title)
    {
        $labels = [];
        $values = [];

        foreach ($services as $service) {
            try {
                // Les utilisateurs sont directement liés au service
                $query = User::where('profile_id', $profileId)
                            ->where('service_id', $service->id);

                if ($startDate) {
                    $query->where('created_at', '>=', $startDate);
                }

                $count = $query->count();

                $labels[] = $service->name;
                $values[] = $count;

            } catch (\Exception $e) {
                \Log::error('Erreur dans buildUserStats: ' . $e->getMessage());
                $labels[] = $service->name;
                $values[] = 0;
            }
        }

        return response()->json([
            'labels' => $labels,
            'values' => $values,
            'title' => $title . ($startDate ? " (période filtrée)" : ""),
            'type' => 'users'
        ]);
    }
} 



