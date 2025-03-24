<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoriqueAttribution extends Model
{
    protected $fillable = ['intervention_id', 'technicien_id', 'date_attribution'];

    public function technicien()
    {
        return $this->belongsTo(User::class, 'technicien_id');
    }
}

