<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_id',
        'user_id',
        'role',
        'joined_at',
        'last_read_message_id',
        'is_muted',
        'is_archived',
    ];

    protected $casts = [
        'joined_at'  => 'datetime',
        'is_muted'   => 'boolean',
        'is_archived' => 'boolean',
    ];

    // Relations
    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lastReadMessage()
    {
        return $this->belongsTo(Message::class, 'last_read_message_id');
    }
}