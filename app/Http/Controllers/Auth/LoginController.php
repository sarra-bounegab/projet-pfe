<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');  // Point to the view where your login form is
    }

    public function login(Request $request)
{
    // Validate form data
    $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    // Retrieve user by email
    $user = \App\Models\User::where('email', $request->email)->first();

    if ($user) {
        // Check if the user is inactive
        if ($user->status == 0) {
            // Log the event
            \Log::info('User account is inactive: ' . $user->email);
            dd(session()->all()); 
            // Redirect with a session variable for showing the popup
            return redirect()->route('login')->with('show_popup', 'Votre compte est en attente d\'approbation.');
        }

        // Attempt to login
        if (Auth::attempt($request->only('email', 'password'))) {
            return redirect()->route('dashboard');
        }
    }

    return back()->withErrors(['email' => 'Les informations d\'identification sont incorrectes.']);
}







    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/');
    }
}
