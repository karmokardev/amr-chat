<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'chat_id',
        'sender_id',
        'reply_to_id',
        'type',
        'message',
        'media_id',
        'is_edited',
        'edited_at',
        'is_deleted_for_everyone',
    ];

    protected $casts = [
        'is_edited'               => 'boolean',
        'is_deleted_for_everyone' => 'boolean',
        'edited_at'               => 'datetime',
    ];

    // Relations
    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function media()
    {
        return $this->belongsTo(Media::class);
    }

    public function replyTo()
    {
        return $this->belongsTo(Message::class, 'reply_to_id');
    }

    public function reads()
    {
        return $this->hasMany(MessageRead::class);
    }

    public function reactions()
    {
        return $this->hasMany(MessageReaction::class);
    }
}