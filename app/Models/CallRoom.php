<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CallRoom extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_id',
        'created_by',
        'type',
        'status',
        'started_at',
        'ended_at',
        'duration_seconds',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at'   => 'datetime',
    ];

    // Relations
    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function participants()
    {
        return $this->hasMany(CallParticipant::class);
    }

    // Helpers
    public function isActive()
    {
        return $this->status === 'active';
    }

    public function isEnded()
    {
        return $this->status === 'ended';
    }
}