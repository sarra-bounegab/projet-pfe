<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Intervention;

class UserStatisticsController extends Controller
{
    public function index()
    {
        // Récupérer l'utilisateur connecté
        $user = Auth::user();

        // Compter le nombre d'interventions créées par cet utilisateur
        $userInterventions = Intervention::where('user_id', $user->id)->count();

        return view('user.statistics', compact('userInterventions'));
    }
}
