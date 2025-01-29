<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Technician extends Model
{
    // Add your table name if it's different
    // protected $table = 'technicians'; 
    
    // If you need to define fillable properties (which can be mass-assigned)
    protected $fillable = [ 'email'];

    // Other relationships or methods can go here
}

