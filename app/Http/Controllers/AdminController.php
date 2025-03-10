<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
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
   
    $users = User::with('service')->get();
    return view('admin.users.index', compact('users'));
     }
    // Show the list of technicians
    use App\Models\User;

    public function listTechnicians()
    {
        $technicians = User::where('profile_id', 2)->get(); // Récupère les utilisateurs ayant profile_id = 2
        return view('admin.technicians', compact('technicians'));
    }

    use App\Models\Service; // Import the Service model

    public function create()
    {
        $services = Service::all(); // Retrieve all services
        return view('admin.users.create', compact('services'));
    }
    
    
    
}
