<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeIntervention extends Model
{
    use HasFactory;

    protected $table = 'type_interventions'; 

    protected $fillable = ['type']; 

    public function interventions()
    {
        return $this->hasMany(Intervention::class, 'type_intervention_id');
    }
}
