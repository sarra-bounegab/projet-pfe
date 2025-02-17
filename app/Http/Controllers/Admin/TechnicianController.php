<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Technician;
use Illuminate\Http\Request;

class TechnicianController extends Controller
{
    // Afficher la liste des techniciens
    public function index()
    {
        $technicians = Technician::all();
        return view('admin.technicians.index', compact('technicians'));
    }

    // Afficher le formulaire de création
    public function create()
    {
        return view('admin.technicians.create');
    }

    // Enregistrer un nouveau technicien
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:technicians,email',
            'status' => 'required|in:active,inactive',
        ]);

        Technician::create($request->all());

        return redirect()->route('admin.technicians.index')->with('success', 'Technicien créé avec succès.');
    }
}

