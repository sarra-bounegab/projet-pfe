<?php
// First, let's update your Intervention model to support a many-to-many relationship with TypeIntervention

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Intervention extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'user_id',
        'description',
        'status',
        
       
        'date'
    ];

    // Relation avec l'utilisateur qui a créé l'intervention
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function techniciens()
    {
    return $this->belongsToMany(User::class, 'details_interventions', 'intervention_id', 'technicien_id')
     ->withPivot('type_intervention_id', 'status', 'created_at', 'updated_at')
     ->withTimestamps();
    }
    

    // Single type relationship (keep for backward compatibility if needed)
    public function type()
    {
        return $this->belongsTo(TypeIntervention::class, 'type_intervention_id');
    }
    
    // New many-to-many relationship with types
    public function types()
    {
        return $this->belongsToMany(TypeIntervention::class, 'intervention_type_intervention', 'intervention_id', 'type_intervention_id');
    }

    // Relation avec le rapport (s'il existe)
    public function rapport()
    {
        return $this->hasOne(Rapport::class);
    }
    public function interventions()
    {
   return $this->belongsToMany(Intervention::class, 'details_interventions', 'technicien_id', 'intervention_id')
   ->withPivot('type_intervention_id', 'status')
    ->withTimestamps();
    }
    
// Intervention.php
public function details()
{
    return $this->hasOne(DetailsIntervention::class);
}

}