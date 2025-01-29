<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Middleware\CheckUserStatus;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\AdminController;

// Route principale (page d'accueil)
Route::get('/', function () {
    return view('welcome');
});

// Routes de connexion et d'inscription
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

    Route::post('login', [LoginController::class, 'login'])->name('login');
    Route::get('login', function () {
        return view('auth.login'); // Make sure this route corresponds to a proper login page if needed
    });

    Route::post('login', function (Request $request) {
        $credentials = $request->only('email', 'password');
    
        if (Auth::attempt($credentials)) {
            if (Auth::user()->status == 0) {
                Auth::logout();
                return redirect()->route('login')->withErrors([
                    'email' => 'Votre compte est en attente d\'approbation.',
                ]);
            }
    
            return redirect()->route('dashboard');
        }
    
        return back()->withErrors([
            'email' => 'Identifiants invalides.',
        ]);
    });

    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
});

// Routes après connexion, accessibles uniquement aux utilisateurs authentifiés
Route::middleware(['auth', CheckUserStatus::class])->group(function () {
    // Tableau de bord pour les utilisateurs connectés
    Route::get('/dashboard', function () {
        $user = Auth::user();
        
        // Redirect based on the user's profile
        if ($user->profile && $user->profile->id == 1) {
            return view('admin.dashboard');  // Admin Dashboard
        } elseif ($user->profile && $user->profile->id == 2) {
            return view('technician.dashboard');  // Technician Dashboard
        }

        // Default dashboard for normal users
        return view('user.dashboard');  // User Dashboard (Default for all normal users)
    })->name('dashboard');
  
    // Profil de l'utilisateur : affichage, mise à jour, suppression
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Déconnexion
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');






//side bar  admin content 



Route::middleware(['auth'])->group(function () {
    // Admin dashboard
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // Manage users
    Route::get('/admin/gestion-users', [AdminController::class, 'gestionUsers'])->name('admin.gestionUsers');
    
    // Manage technicians
    Route::get('/admin/gestion-technicians', [AdminController::class, 'gestionTechnicians'])->name('admin.gestionTechnicians');
});


require __DIR__.'/auth.php';
