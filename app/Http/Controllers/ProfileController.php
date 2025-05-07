<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // Met à jour les informations de l'utilisateur avec les données validées
        $request->user()->fill($request->validated());

        // Si l'email a été modifié, réinitialiser la vérification de l'email
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        // Sauvegarder les informations de l'utilisateur
        $request->user()->save();

        // Retourner à la page d'édition avec un message de succès
        return Redirect::route('profile.edit')->with('status', 'profile-updated');
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
