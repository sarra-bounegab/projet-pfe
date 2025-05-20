<?php

// app/Models/InterventionsHistorique.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InterventionsHistorique extends Model
{
    use HasFactory;

    // Table associée au modèle
    protected $table = 'interventions_historiques';

    // La clé primaire (si elle n'est pas 'id')
    protected $primaryKey = 'id';

    // Indiquer si la table utilise des timestamps (created_at, updated_at)
    public $timestamps = true;

    // Attributs pouvant être massivement assignés
    protected $fillable = [
        'intervention_id',
        'action',
        'user_id',
        'created_at',
        'updated_at'
    ];
     
    public function intervention()
    {
        return $this->belongsTo(Intervention::class);
    }
}
