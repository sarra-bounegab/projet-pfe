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




//  partie technicien

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


//partie gestions
use App\Http\Controllers\Admin\UserController;

// Route pour accéder à la gestion des utilisateurs
Route::get('/admin/gestions-users', [UserController::class, 'index'])->name('admin.gestionsUsers');

Route::get('/admin/gestions-globale', [UserController::class, 'gestionsGlobale'])->name('admin.gestionsGlobale');



    // Routes gestions interventions

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




// Routes statistique
use App\Http\Controllers\StatisticsController;

Route::get('/admin/statistics', [StatisticsController::class, 'index'])->name('statistics')->middleware('auth');

use App\Http\Controllers\UserStatisticsController;

Route::get('/user/statistics', [UserStatisticsController::class, 'index'])->name('user.statistics')->middleware('auth');


// Routes assign/unassign tech

Route::put('/intervention/{id}/assign', [InterventionController::class, 'assignTechnician'])->name('intervention.assign');


Route::get('/technician/interventions', [InterventionController::class, 'technicianIndex'])->name('technician.gestionsinterventions');


Route::get('/admin/technicians', [AdminController::class, 'listTechnicians'])->name('admin.technicians');
Route::put('/admin/assign-technician/{id}', [InterventionController::class, 'assignTechnician'])->name('admin.assignTechnician');
Route::put('/intervention/{id}/unassign', [InterventionController::class, 'unassign'])->name('intervention.unassign');









Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');





Route::get('/admin/users/create', [UserController::class, 'create'])->name('users.create');



Route::post('/users', [UserController::class, 'store'])->name('users.store');





Route::put('/admin/gestions-globale/{id}', [UserController::class, 'update'])->name('users.update');


Route::get('admin/users', [UserController::class, 'index'])->name('users.index');


Route::resource('users', UserController::class);











Route::put('/assign-technician', [InterventionController::class, 'assignTechnician'])->name('assign.technician');





Route::put('/assign-technician', [InterventionController::class, 'assignTechnician'])->name('assign.technician');





Route::put('/intervention/cancel', [InterventionController::class, 'cancelTechnician'])->name('cancel.technician');







Route::middleware(['auth'])->group(function () {
    Route::get('/technician/interventions', [TechnicianController::class, 'gestionInterventions'])
         ->name('technician.interventions');
});




Route::post('/rapports/{id}/tache', [InterventionController::class, 'ajouterTacheRapport']);




use App\Http\Controllers\RapportController;

Route::post('/rapports/ajouter', [RapportController::class, 'store'])->name('rapports.store');

Route::post('/rapport/ajouter', [RapportController::class, 'store']);
Route::post('/rapports/enregistrer', [RapportController::class, 'store'])->name('rapports.store');




Route::post('/rapports', [RapportController::class, 'store'])->name('rapports.store');


Route::get('/interventions/{id}/rapport', [RapportController::class, 'getRapport']);





Route::get('/interventions/{id}/edit', [InterventionController::class, 'edit']);


Route::post('/ajouter-tache', [RapportController::class, 'ajouterTache']);


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;






Route::post('/rapports', [RapportController::class, 'store'])->name('rapports.store');


Route::delete('/supprimer-tache/{id}', [RapportController::class, 'destroy'])->name('taches.destroy');





Route::get('/get-taches', [App\Http\Controllers\RapportController::class, 'getTaches']);








Route::post('/rapports/store', [RapportController::class, 'store'])->name('rapports.store');
Route::get('/intervention/{id}/taches', [RapportController::class, 'getTachesByIntervention'])->name('intervention.taches');



use App\Models\Tache;

Route::get('/intervention/{id}/taches', function ($id) {
    $taches = Tache::whereHas('rapport', function ($query) use ($id) {
        $query->where('intervention_id', $id);
    })->get();

    return response()->json([
        'success' => true,
        'taches' => $taches
    ]);
});



Route::get('/intervention/{id}/rapport-et-taches', [RapportController::class, 'getRapportEtTaches']);





// Route pour créer un rapport
Route::post('/rapport/store', [RapportController::class, 'store'])->name('rapports.store');

// Route pour mettre à jour le rapport et ses tâches
Route::post('/rapport/{rapport}/update-taches', [RapportController::class, 'updateTaches'])->name('rapports.updateTaches');







Route::get('/intervention/{id}/rapport', [RapportController::class, 'getRapportEtTaches']);
Route::post('/rapports/storeOrUpdate', [RapportController::class, 'storeOrUpdate'])->name('rapports.storeOrUpdate');

Route::post('intervention/{id}/cloturer', [InterventionController::class, 'cloturer'])->name('intervention.cloturer');




Route::get('/intervention/{id}/details', [InterventionController::class, 'show'])->name('intervention.details');


Route::get('/interventions', [InterventionController::class, 'index'])->name('intervention.index');
Route::get('/intervention/{id}/rapport', [InterventionController::class, 'showRapport']);



Route::get('/intervention/{id}/rapport', [InterventionController::class, 'getRapport'])->name('intervention.rapport');



Route::get('/admin/gestion-globale', [AdminController::class, 'gestionGlobale'])->name('admin.gestionGlobale');


Route::post('/admin/users', [UserController::class, 'store'])->name('users.store');
Route::get('/admin/gestion-globale', [UserController::class, 'index'])->name('admin.gestionGlobale');



Route::get('/admin/gestions-globale', [UserController::class, 'gestionsGlobale'])->name('admin.gestionsGlobale');

Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');





Route::post('/update-historique/{id}', [InterventionController::class, 'updateHistorique']);

Route::get('/stats/interventions', [StatisticsController::class, 'getInterventionsByService']);


Route::get('/stats/service-distribution', [StatisticsController::class, 'getServiceDistribution'])->name('stats.serviceDistribution');











use App\Http\Controllers\HistoriqueController;



Route::get('/historique', [HistoriqueController::class, 'index'])->name('historique')->middleware('auth');





Route::get('/intervention/{id}', [InterventionController::class, 'showInterventionDetails'])
    ->middleware('auth');

    Route::get('/historique', [InterventionController::class, 'showHistorique'])->name('historique');

// Route pour l'historique
Route::get('/historique', [HistoriqueController::class, 'showHistorique'])->name('historique');

require __DIR__.'/auth.php';
