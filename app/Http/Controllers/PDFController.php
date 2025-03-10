<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Intervention;
use App\Models\User;

class PDFController extends Controller
{
    // 📄 Générer le PDF des interventions
    public function generateInterventionsPDF()
    {
        $interventions = Intervention::with('user', 'technician')->get();
        $pdf = Pdf::loadView('pdf.interventions', compact('interventions'));
        return $pdf->download('interventions.pdf');
    }

    // 📄 Générer le PDF des utilisateurs
    public function generateUsersPDF()
    {
        $users = User::all();
        $pdf = Pdf::loadView('pdf.users', compact('users'));
        return $pdf->download('users.pdf');
    }

    // 📄 Générer le PDF des statistiques
    public function generateStatisticsPDF()
    {
        $totalUsers = User::count();
        $totalInterventions = Intervention::count();
        $completedInterventions = Intervention::where('status', 'Terminée')->count();
        $pendingInterventions = Intervention::where('status', 'En cours')->count();

        $data = compact('totalUsers', 'totalInterventions', 'completedInterventions', 'pendingInterventions');

        $pdf = Pdf::loadView('pdf.statistics', $data);
        return $pdf->download('statistics.pdf');
    }
}
