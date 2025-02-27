<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Intervention;

class StatisticsController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalAdmins = User::where('profile_id', 1)->count();
        $totalTechnicians = User::where('profile_id', 2)->count();
        $totalInterventions = Intervention::count();

        return view('admin.statistics', compact('totalUsers', 'totalAdmins', 'totalTechnicians', 'totalInterventions'));
    }
}
