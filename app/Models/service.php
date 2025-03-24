<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'parent_id']; 


    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function subServices()
    {
        return $this->hasMany(Service::class, 'parent_id');
    }

    public function parentService()
    {
        return $this->belongsTo(Service::class, 'parent_id');
    }
    public function subServicesRecursive()
    {
        return $this->hasMany(Service::class, 'parent_id')->with('subServicesRecursive');
    }
    
    
}
