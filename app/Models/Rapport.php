<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



    class Rapport extends Model {
        protected $fillable = ['titre', 'contenu', 'technicien_id', 'intervention_id'];



        public function taches() {
            return $this->hasMany(Tache::class);

        }


        public function technicien()
    {
        return $this->belongsTo(User::class, 'technicien_id');
    }
// Relation avec historique des modifications
public function historique()
{
    return $this->hasMany(RapportDetail::class);
}
    }
