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
        return view('auth.register', compact('services'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'service_id' => ['required', 'exists:services,id'], // Validate service selection
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'profile_id' => 3,
            'status' => 0,
            'service_id' => $request->service_id, // Store service ID
        ]);

        event(new Registered($user));

        return redirect(route('login'))->with('status', 'Votre compte est en attente d\'approbation.');
    }
}
