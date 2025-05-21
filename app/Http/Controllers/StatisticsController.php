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
        $divisions = Service::whereNull('parent_id')->get(); // Divisions principales

        // ✅ Comptage des interventions par statut
        $statusCounts = [
            'en attente' => Intervention::where('status', 'en attente')->count(),
            'en cours'   => Intervention::where('status', 'en cours')->count(),
            'terminè'    => Intervention::where('status', 'terminè')->count(),
            'annulée'    => Intervention::where('status', 'annulée')->count(),
        ];

        return view('admin.statistics', compact(
            'totalUsers',
            'totalAdmins',
            'totalTechnicians',
            'totalInterventions',
            'totalServices',
            'divisions',
            'statusCounts' // 🟩 Important : passé à la vue
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
        $dataType = $request->input('dataType', 'interventions');
        $timeFrame = $request->input('timeFrame', 'all');
        $divisionId = $request->input('division_id');
        $subServiceId = $request->input('sub_service_id');

        $startDate = null;
        $now = Carbon::now();

        switch ($timeFrame) {
            case '7days':
                $startDate = $now->copy()->subDays(7);
                break;
            case '30days':
                $startDate = $now->copy()->subDays(30);
                break;
            case 'month':
                $startDate = $now->copy()->startOfMonth();
                break;
            case 'year':
                $startDate = $now->copy()->startOfYear();
                break;
        }

        // Filtrage des services
        $services = Service::query();

        if ($subServiceId) {
            $services->where('id', $subServiceId);
        } elseif ($divisionId) {
            $services->where('parent_id', $divisionId);
        }

        $services = $services->get();

        switch ($dataType) {
            case 'interventions':
                return $this->buildInterventionStats($services, $startDate);
            case 'admins':
                return $this->buildUserStats($services, 1, $startDate);
            case 'technicians':
                return $this->buildUserStats($services, 2, $startDate);
            case 'users':
                return $this->buildUserStats($services, 3, $startDate);
            default:
                return response()->json(['error' => 'Type de données non valide'], 400);
        }
    }

    private function buildInterventionStats($services, $startDate = null)
    {
        $labels = [];
        $values = [];

        foreach ($services as $service) {
            $interventions = $service->interventions();

            if ($startDate) {
                $interventions->where('created_at', '>=', $startDate);
            }

            $labels[] = $service->name;
            $values[] = $interventions->count();
        }

        return response()->json([
            'labels' => $labels,
            'values' => $values,
            'title' => "Nombre d'interventions par service"
        ]);
    }

    private function buildUserStats($services, $profileId, $startDate = null)
    {
        $labels = [];
        $values = [];

        foreach ($services as $service) {
            $users = $service->users()->where('profile_id', $profileId);

            if ($startDate) {
                $users->where('created_at', '>=', $startDate);
            }

            $labels[] = $service->name;
            $values[] = $users->count();
        }

        $title = match ($profileId) {
            1 => "Nombre d'administrateurs par service",
            2 => "Nombre de techniciens par service",
            3 => "Nombre d'utilisateurs par service",
            default => "Nombre d'utilisateurs par service",
        };

        return response()->json([
            'labels' => $labels,
            'values' => $values,
            'title' => $title,
        ]);
    }
}
