<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CallParticipant extends Model
{
    use HasFactory;

    protected $fillable = [
        'call_room_id',
        'user_id',
        'joined_at',
        'left_at',
        'is_audio_enabled',
        'is_video_enabled',
        'status',
    ];

    protected $casts = [
        'joined_at'        => 'datetime',
        'left_at'          => 'datetime',
        'is_audio_enabled' => 'boolean',
        'is_video_enabled' => 'boolean',
    ];

    // Relations
    public function callRoom()
    {
        return $this->belongsTo(CallRoom::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helpers
    public function isActive()
    {
        return $this->status === 'joined' && is_null($this->left_at);
    }
}