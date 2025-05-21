<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Historique extends Model
{
    protected $table = 'historiques'; // Nom de la table
    protected $fillable = ['intervention_id', 'user_id', 'action']; // Les champs remplissables


public function intervention()
{
    return $this->belongsTo(Intervention::class, 'intervention_id');
}

    public function user()
    {
        return $this->belongsTo(User::class);
    }

     // La relation avec le technicien (technicien_id)
     public function technicien()
     {
         return $this->belongsTo(User::class, 'technicien_id');
     }

     // La relation avec l'intervention

}
