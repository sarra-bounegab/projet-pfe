<?php
namespace App\Http\Controllers;

use App\Models\Technician;
use App\Models\User;

class AdminController extends Controller
{
    // Show the admin dashboard
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    // Show the list of users
    public function gestionUsers()
    {
        $users = User::where('profile_id', 3)->get();
        return view('admin.gestionUsers', compact('users'));
    }

    // Show the list of technicians
    public function gestionTechnicians()
    {
        // Get all users with the 'technician' profile (profile_id = 2)
        $technicians = User::where('profile_id', 2)->get(); // Adjust if you have a 'profile' relation

        // Pass data to the view
        return view('admin.gestionTechnicians', compact('technicians'));
    }
}
