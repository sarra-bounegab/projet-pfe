<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Intervention extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'type_intervention_id',
        'description',
        'user_id',
        'technicien_id', // Assurez-vous que cette colonne est bien dans $fillable
        'status',
    ];
    
    
    // Relation avec l'utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relation avec le type d'intervention
    public function typeIntervention()
    {
        return $this->belongsTo(TypeIntervention::class);
    }
    public function technicien()
{
    return $this->belongsTo(User::class, 'technicien_id');
}

// app/Models/Intervention.php
public function rapport()
{
    return $this->hasOne(Rapport::class);
}


    public function index()
    {
        $interventions = Intervention::all(); // Ou filtrer selon l'utilisateur
        return view('intervention.index', compact('interventions'));
    }
    
// Relation avec l'intervention
public function intervention()
{
    return $this->belongsTo(Intervention::class);
}



}
    


