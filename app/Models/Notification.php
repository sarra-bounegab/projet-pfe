<?php
// app/Models/Notification.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Notification extends Model
{
    use HasFactory;
     protected $table = 'user_notifications';

    protected $fillable = [
        'user_id',
        'intervention_id',
        'sender_id',
        'type',
        'title',
        'message',
        'data',
        'is_read'
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function intervention()
    {
        return $this->belongsTo(Intervention::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', Carbon::now()->subDays($days));
    }

    // Accessors
    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getIconAttribute()
    {
        return match($this->type) {
            'intervention_assigned' => 'fa-user-plus',
            'intervention_completed' => 'fa-check-circle',
            'intervention_updated' => 'fa-edit',
            'intervention_created' => 'fa-plus-circle',
            'intervention_reopened' => 'fa-redo',
            default => 'fa-bell'
        };
    }

    public function getColorAttribute()
    {
        return match($this->type) {
            'intervention_assigned' => 'text-blue-600',
            'intervention_completed' => 'text-green-600',
            'intervention_updated' => 'text-yellow-600',
            'intervention_created' => 'text-purple-600',
            'intervention_reopened' => 'text-orange-600',
            default => 'text-gray-600'
        };
    }

    // Marquer comme lu
    public function markAsRead()
    {
        $this->update(['is_read' => true]);
    }
}
