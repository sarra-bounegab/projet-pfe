<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Intervention extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'type_intervention_id', 'description', 'date', 'status'];

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
    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id'); // Assurez-vous que la clé étrangère est correcte
    }

}

