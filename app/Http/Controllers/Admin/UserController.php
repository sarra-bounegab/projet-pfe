<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Http\Controllers\Controller;
use App\Models\User;

use App\Models\Intervention;

use App\Models\Service;

class UserController extends Controller
{
  
    public function index()
    {
        
        $users = User::all(); // Récupère tous les utilisateurs
        return view('admin.gestionsglobale', compact('users'));

    }

   public function create()
   {
       $services = Service::all(); // Récupérer tous les services
       return view('admin.user.create', compact('services'));
   }
   
    

    public function gestionsGlobale()
    {
        // Récupère tous les utilisateurs et techniciens
        $users = User::whereIn('profile_id', [1, 2, 3])->get();
        return view('admin.gestionsGlobale', compact('users'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update($request->only(['name', 'email']));
        
        return redirect()->route('users.index')->with('success', 'Utilisateur mis à jour avec succès.');
    }
    


public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
        'password' => 'required|string|min:6',
        'profile_id' => 'required|integer',
       'service_id' => 'nullable|integer', //
        'status' => 'required|integer',
    ]);

    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password), // Hash du mot de passe
        'profile_id' => $request->profile_id,
        'service_id' => $request->service_id,
        'status' => $request->status,
    ]);

    return redirect()->back()->with('success', 'Utilisateur créé avec succès.');
}






}
