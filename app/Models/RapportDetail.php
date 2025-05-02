<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RapportDetail extends Model
{
    use HasFactory;
    
    protected $table = 'rapport_details';
    
    public $timestamps = false;
    
    protected $fillable = [
        'intervention_id',
        'user_id',
        'contenu',
        'contenu_precedent',
        'modification_date',
        'rapport_id'
    ];
    
    // Relation avec rapport
    public function rapport()
    {
        return $this->belongsTo(Rapport::class);
    }
    
    // Relation avec utilisateur
    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    // Relation avec intervention
    public function intervention()
    {
        return $this->belongsTo(Intervention::class);
    }
}