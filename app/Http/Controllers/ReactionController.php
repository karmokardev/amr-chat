<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\MessageReaction;
use App\Events\MessageReacted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReactionController extends Controller
{
    public function toggle(Request $request, Message $message)
    {
        $request->validate([
            'emoji' => ['required', 'string'],
        ]);

        $existing = MessageReaction::where('message_id', $message->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existing) {
            if ($existing->emoji === $request->emoji) {
                // Same emoji — remove
                $existing->delete();
                $action = 'removed';
            } else {
                // Different emoji — update
                $existing->update(['emoji' => $request->emoji]);
                $action = 'updated';
            }
        } else {
            // New reaction
            MessageReaction::create([
                'message_id' => $message->id,
                'user_id'    => Auth::id(),
                'emoji'      => $request->emoji,
            ]);
            $action = 'added';
        }

        // Reactions reload
        $reactions = $message->reactions()
            ->selectRaw('emoji, COUNT(*) as count')
            ->groupBy('emoji')
            ->get();

        broadcast(new MessageReacted($message, $reactions, Auth::user()))->toOthers();

        return response()->json([
            'action'    => $action,
            'reactions' => $reactions,
        ]);
    }
}