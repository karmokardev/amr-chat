<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

   protected $fillable = [
        'uuid',
        'name',
        'username',
        'email',
        'password',
        'avatar',
        'is_online',
        'last_seen_at',
    ];
    
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_seen_at' => 'datetime',
        'is_online' => 'boolean',
    ];


    public function chats()
    {
        return $this->belongsToMany(Chat::class, 'chat_members')
                    ->withPivot('role', 'joined_at', 'last_read_message_id', 'is_muted', 'is_archived')
                    ->withTimestamps();
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }
    public function callPaticipants()
    {
        return $this->hasMany(CallParticipant::class);
    }
    public function media()
    {
        return $this->hasMany(Media::class, 'uploaded_by');
    }
}