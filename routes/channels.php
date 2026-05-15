<?php

use App\Models\Chat;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat.{chatId}', function ($user, int $chatId) {
    return Chat::find($chatId)
        ?->members()
        ->where('user_id', $user->id)
        ->exists() ?? false;
});