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
        $users = User::all();
        $services = Service::all();
        $parentServices = Service::whereNull('parent_id')->with('subServices')->get();
    
        return view('admin.gestionsGlobale', compact('users', 'services', 'parentServices'));
    }
    
    


   public function create()
   {
       $services = Service::all(); // Récupérer tous les services
       return view('admin.user.create', compact('services'));
   }
   
    

   public function gestionsGlobale()
   {
       $users = User::whereIn('profile_id', [1, 2, 3])->get();
       $services = Service::all();
       $parentServices = Service::whereNull('parent_id')->with('subServicesRecursive')->get();
       
       return view('admin.gestionsGlobale', compact('users', 'services', 'parentServices'));
   }
   

   public function show($id)
   {
       $user = User::findOrFail($id);
       return view('admin.user.show', compact('user'));
   }
   
  
   
    public function store(Request $request)
    {
        // Validation des données
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'profile_id' => 'required|integer',
            'status' => 'required|boolean',
            'service_id' => 'required|integer|exists:services,id',
        ]);
    
        // Création de l'utilisateur
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'profile_id' => $request->profile_id,
            'status' => $request->status,
            'service_id' => $request->service_id,
        ]);
    
        // Redirection avec message de succès
        return redirect()->back()->with('success', 'Utilisateur créé avec succès.');
    }
    

    public function update(Request $request, $id)
{
    // Validation des champs
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'status' => 'required|boolean',
        'profile_id' => 'required|integer',
    ]);

    // Trouver l'utilisateur et mettre à jour
    $user = User::findOrFail($id);
    $user->update([
        'name' => $request->name,
        'email' => $request->email,
        'status' => $request->status,
        'profile_id' => $request->profile_id,
    ]);

    return response()->json(['success' => 'Utilisateur mis à jour avec succès.']);
}

    

  

}
