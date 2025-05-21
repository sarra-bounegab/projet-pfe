<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Service; // Import Service model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

class RegisteredUserController extends Controller
{
    public function create()
    {
        $services = Service::all(); // Retrieve all services from the database
        // Récupère les services parents avec leurs sous-services
       $parentServices = Service::whereNull('parent_id')->with('subServicesRecursive')->get();
        return view('auth.register', compact('services','parentServices'));
    }

    public function store(Request $request)
{
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => [
            'required',
            'string',
            'min:8',
            'regex:/[a-z]/',
            'regex:/[A-Z]/',
            'regex:/[0-9]/',
            'regex:/[@$!%*#?&]/',
            'confirmed'
        ],
        'service_id' => ['required', 'exists:services,id'],
    ], [
        'password.required' => 'Le mot de passe est obligatoire.',
        'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
        'password.regex' => 'Le mot de passe doit contenir au moins une minuscule, une majuscule, un chiffre et un caractère spécial.',
        'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'profile_id' => 3,
        'status' => 0,
        'service_id' => $request->service_id,
    ]);

    event(new Registered($user));

    return redirect(route('login'))->with('status', 'Votre compte est en attente d\'approbation.');
}
}
