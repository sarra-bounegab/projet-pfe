<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    public function gestionsGlobale()
    {
        // Récupère tous les utilisateurs et techniciens
        $users = User::whereIn('profile_id', [1, 2, 3])->get();
        return view('admin.gestionsGlobale', compact('users'));
    }
}
