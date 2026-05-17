<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'uuid',
        'type',
        'name',
        'avatar',
        'created_by',
        'last_message_id',
    ];
    
    protected $casts =[
      'type' => 'string',
    ];
    
    public function members(){
        return $this->belongsToMany(User::class, 'chat_members')
                    ->withPivot('role', 'joined_at', 'last_read_message_id', 'is_muted', 'is_archived')
                    ->withTimestamps();
    }
    
    public function messages(){
        return $this->hasMany(Message::class);
    }
     public function lastMessage()
    {
        return $this->belongsTo(Message::class, 'last_message_id');
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function callRooms(){
        return $this->hasMany(CallRoom::class);
    }
    public function chatMembers()
    {
        return $this->hasMany(ChatMember::class);
    }

    public  function unreadCount(int $userId): int 
    {
        $member = $this->chatMembers()->where('user_id', $userId)->first();

        if(!$member || !$member->last_read_message_id){
            return $this->messages()->count();
        }

        return $this->messages()
        ->where('id', '>', $member->last_read_message_id)
        ->where('sender_id', '!=', $userId)
        ->count();
    }
}