<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Intervention extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'technician_id', 'description', 'status'];

    // Relation avec l'utilisateur qui demande l'intervention
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relation avec le technicien affecté à l'intervention
    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }
}

