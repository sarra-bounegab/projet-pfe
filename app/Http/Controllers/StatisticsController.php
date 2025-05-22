<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Intervention;
use Illuminate\Http\Request;
use App\Models\User;


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



