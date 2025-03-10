<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeIntervention extends Model
{
    use HasFactory;

    protected $fillable = ['type']; // Définir les colonnes qu'on peut remplir directement

    // Relation avec la table `interventions`
    public function interventions()
    {
        return $this->hasMany(Intervention::class, 'type_intervention_id');
    }

    public function typeIntervention()
{
    return $this->belongsTo(TypeIntervention::class, 'type_intervention_id');
}

}
