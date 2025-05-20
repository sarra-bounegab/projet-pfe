<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
class DetailsIntervention extends Model
{
    
       // Dans app/Models/DetailsIntervention.php
       protected $fillable = [
        'intervention_id',
        'technicien_id',
        'titre',
        'type_intervention_id',
        'contenu',
        'description',
        'date',
        'status',
    ];
    

    public function intervention(): BelongsTo
    {
        return $this->belongsTo(Intervention::class);
    }

    

   

 
// App\Models\DetailsIntervention.php
public function typeIntervention()
{
    return $this->belongsTo(TypeIntervention::class, 'type_intervention_id');
}


protected $table = 'details_interventions';






public function technician() {
    return $this->belongsTo(User::class, 'technicien_id');
}


// DetailsIntervention.php
public function technicien()
{
    return $this->belongsTo(User::class, 'technicien_id');  // Assurez-vous que 'technicien_id' est correct
}

public function type_intervention()
{
    return $this->belongsTo(TypeIntervention::class, 'type_intervention_id');  // Assurez-vous que 'type_intervention_id' est correct
}


public function type()
{
    return $this->belongsTo(TypeIntervention::class, 'type_intervention_id');
}




}
