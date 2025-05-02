<?php

namespace App\Models;
use Illuminate\Http\Request;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $attributes = [
        'profile_id' => 3,  // Default value for profile_id
    ];
    protected $fillable = [
        'name', 'email', 'password', 'profile_id', 'service_id', 'status'
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function profile()
{
    return $this->belongsTo(Profile::class);
}

public function service()
{
    return $this->belongsTo(Service::class);
}

//public function interventions()
//{
 //   return $this->hasMany(Intervention::class);
//}


public function interventions()
{
return $this->belongsToMany(Intervention::class, 'details_interventions', 'technicien_id', 'intervention_id')
->withPivot('type_intervention_id', 'status', 'created_at', 'updated_at')
->withTimestamps();
}

}
