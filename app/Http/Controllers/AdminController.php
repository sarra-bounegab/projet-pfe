<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Technician;
use App\Models\User;
  use App\Models\Service; // Import the Service model
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
    $users = User::where('profile_id', 3)->with('service')->get(); 
    return view('admin.gestionUsers', compact('users'));
}

   
  

    public function listTechnicians()
    {
        $technicians = User::where('profile_id', 2)->get(); 
        return view('admin.technicians', compact('technicians'));
    }

  

    public function create()
    {
        $services = Service::all(); // Récupérer tous les services
    return view('admin.gestionsGlobale', compact('services'));
    }
    
    
    
    
}
