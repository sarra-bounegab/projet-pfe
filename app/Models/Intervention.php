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

public function rapport()
{
    return $this->belongsTo(RapportTechnicien::class, 'rapport_id');
}

    
}

