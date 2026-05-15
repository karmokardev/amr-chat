<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Chat;
use App\Models\Message;
use App\Models\MessageRead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function store(Request $request, Chat $chat)
    {
        $request->validate([
           'message'=>['nullable','string'],
           'type'=>['required','in:text,image,file,voice,video'],
           'media_id'=>['nullable','exists:media,id'],
           'reply_to_id'=>['nullable','exists:messages,id'] 
        ]);

        $message = Message::create([
            'chat_id'     => $chat->id,
            'sender_id'   => Auth::id(),
            'type'        => $request->type,
            'message'     => $request->message,
            'media_id'    => $request->media_id,
            'reply_to_id' => $request->reply_to_id,
        ]);

        $chat->update(['last_message_id' => $message->id]);
        // Load relations
        $message->load(['sender', 'media', 'replyTo.sender']);
        broadcast(new MessageSent($message))->toOthers();

        return response()->json($message);
    }

    // Message delete
    public function destroy(Message $message)
    {
        if ($message->sender_id !== Auth::id()) {
            abort(403);
        }

        $message->update(['is_deleted_for_everyone' => true]);
        $message->delete();

        return response()->json(['success' => true]);
    }

    // Message edit
    public function update(Request $request, Message $message)
    {
        if ($message->sender_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'message' => ['required', 'string'],
        ]);

        $message->update([
            'message'   => $request->message,
            'is_edited' => true,
            'edited_at' => now(),
        ]);

        $message->load(['sender', 'media', 'replyTo.sender']);

        return response()->json($message);
    }

        // Seen status
    public function markAsRead(Request $request, Chat $chat)
    {
        $lastMessage = $chat->messages()->latest()->first();

        if (!$lastMessage) {
            return response()->json(['success' => true]);
        }

        // message_reads update
        MessageRead::firstOrCreate([
            'message_id' => $lastMessage->id,
            'user_id'    => Auth::id(),
        ], [
            'read_at' => now(),
        ]);

        // chat_members last_read_message_id update
        $chat->chatMembers()
            ->where('user_id', Auth::id())
            ->update(['last_read_message_id' => $lastMessage->id]);

        return response()->json(['success' => true]);
    }
}