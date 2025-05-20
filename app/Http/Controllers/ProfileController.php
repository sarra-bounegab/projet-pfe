<?php

namespace App\Http\Controllers;
use Illuminate\Validation\Rule;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    
    // ProfileController.php

public function edit()
{
    $user = auth()->user();
    return view('profile.edit', compact('user'));
}

public function update(Request $request)
{
    $user = auth()->user();

    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
    ]);

    $user->update([
        'name' => $request->name,
        'email' => $request->email,
    ]);

    return redirect()->route('profile.edit')->with('status', 'profile-updated');
}

    /**
     * Supprime le compte de l'utilisateur.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Validation de la suppression avec le mot de passe actuel de l'utilisateur
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Se déconnecter de l'utilisateur
        Auth::logout();

        // Supprimer l'utilisateur de la base de données
        $user->delete();

        // Invalider la session et régénérer le token CSRF
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Rediriger vers la page d'accueil après la suppression du compte
        return Redirect::to('/');
    }
}
