<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;
use App\Models\Intervention;

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


use App\Http\Controllers\TechnicianController;

Route::resource('technicians', TechnicianController::class);




Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    // Afficher la liste des techniciens
    Route::get('/technicians', [TechnicianController::class, 'index'])->name('technicians.index');

    // Afficher le formulaire de création d'un technicien
    Route::get('/technicians/create', [TechnicianController::class, 'create'])->name('technicians.create');

    // Enregistrer un nouveau technicien
    Route::post('/technicians', [TechnicianController::class, 'store'])->name('technicians.store');
});



use App\Http\Controllers\Admin\UserController;

// Route pour accéder à la gestion des utilisateurs
Route::get('/admin/gestions-users', [UserController::class, 'index'])->name('admin.gestionsUsers');

Route::get('/admin/gestions-globale', [UserController::class, 'gestionsGlobale'])->name('admin.gestionsGlobale');



use App\Http\Controllers\InterventionController;


// Routes pour l'admin
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/interventions', [InterventionController::class, 'adminIndex'])
        ->name('admin.gestionsinterventions');
});

// Routes pour les utilisateurs normaux
Route::middleware(['auth'])->group(function () {
    Route::get('/user/interventions', [InterventionController::class, 'userIndex'])
        ->name('user.gestionsinterventions');

    Route::get('/user/interventions/create', [InterventionController::class, 'create'])
        ->name('user.gestionsinterventions.create');

    Route::post('/user/interventions', [InterventionController::class, 'store'])
        ->name('user.gestionsinterventions.store');
        Route::post('/user/interventions', [InterventionController::class, 'store'])
        ->name('user.storeintervention');
});

// Routes pour modifier/supprimer les interventions
Route::middleware(['auth'])->group(function () {
    Route::get('/interventions/{id}/edit', [InterventionController::class, 'edit'])
        ->name('interventions.edit');

    Route::put('/interventions/{id}', [InterventionController::class, 'update'])
        ->name('interventions.update');

    Route::delete('/interventions/{id}', [InterventionController::class, 'destroy'])
        ->name('interventions.destroy');
});

use App\Http\Controllers\StatisticsController;

Route::get('/admin/statistics', [StatisticsController::class, 'index'])->name('statistics')->middleware('auth');

use App\Http\Controllers\UserStatisticsController;

Route::get('/user/statistics', [UserStatisticsController::class, 'index'])->name('user.statistics')->middleware('auth');


Route::put('/intervention/{id}/assign', [InterventionController::class, 'assignTechnician'])->name('intervention.assign');




Route::get('/technician/interventions', [InterventionController::class, 'technicianIndex'])->name('technician.gestionsinterventions');


Route::get('/admin/technicians', [AdminController::class, 'listTechnicians'])->name('admin.technicians');
Route::put('/admin/assign-technician/{id}', [InterventionController::class, 'assignTechnician'])->name('admin.assignTechnician');



Route::put('/intervention/{id}/unassign', [InterventionController::class, 'unassign'])->name('intervention.unassign');


require __DIR__.'/auth.php';
