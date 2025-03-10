<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tache extends Model
{
    use HasFactory;

    protected $fillable = ['rapport_id', 'description'];

    public function rapport()
    {
        return $this->belongsTo(Rapport::class);
    }
}

