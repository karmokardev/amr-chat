<?php

namespace App\Http\Controllers;

use App\Models\CallRoom;
use App\Models\CallParticipant;
use App\Models\Chat;
use App\Events\CallInitiated;
use App\Events\CallSignal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CallController extends Controller
{
    // Call শুরু করো
    public function initiate(Request $request, Chat $chat)
    {
        $request->validate([
            'type' => ['required', 'in:audio,video'],
        ]);

        // Active call আছে কিনা check
        $existing = $chat->callRooms()
            ->where('status', '!=', 'ended')
            ->first();

        if ($existing) {
            return response()->json(['call_room' => $existing], 200);
        }

        $callRoom = CallRoom::create([
            'chat_id'    => $chat->id,
            'created_by' => Auth::id(),
            'type'       => $request->type,
            'status'     => 'waiting',
        ]);

        // Creator কে participant হিসেবে add করো
        CallParticipant::create([
            'call_room_id'     => $callRoom->id,
            'user_id'          => Auth::id(),
            'joined_at'        => now(),
            'is_audio_enabled' => true,
            'is_video_enabled' => $request->type === 'video',
            'status'           => 'joined',
        ]);

        // অন্যদের notify করো
        broadcast(new CallInitiated($callRoom, Auth::user()))->toOthers();

        return response()->json(['call_room' => $callRoom]);
    }

    // Call join করো
    public function join(Request $request, CallRoom $callRoom)
    {
        $participant = CallParticipant::updateOrCreate(
            [
                'call_room_id' => $callRoom->id,
                'user_id'      => Auth::id(),
            ],
            [
                'joined_at'        => now(),
                'left_at'          => null,
                'is_audio_enabled' => true,
                'is_video_enabled' => $callRoom->type === 'video',
                'status'           => 'joined',
            ]
        );

        $callRoom->update(['status' => 'active']);

        return response()->json(['participant' => $participant]);
    }

    // Call ছেড়ে দাও
    public function leave(CallRoom $callRoom)
    {
        CallParticipant::where('call_room_id', $callRoom->id)
            ->where('user_id', Auth::id())
            ->update([
                'left_at' => now(),
                'status'  => 'left',
            ]);

        $activeCount = CallParticipant::where('call_room_id', $callRoom->id)
            ->where('status', 'joined')
            ->count();

        if ($activeCount === 0) {
            $callRoom->update([
                'status'           => 'ended',
                'ended_at'         => now(),
                'duration_seconds' => now()->diffInSeconds($callRoom->started_at ?? now()),
            ]);
        }

        // সবাইকে notify করো
        broadcast(new \App\Events\CallEnded($callRoom))->toOthers();

        return response()->json(['success' => true]);
    }

    // WebRTC Signal পাঠাও
    public function signal(Request $request, CallRoom $callRoom)
    {
        $request->validate([
            'signal'     => ['required'],
            'target_user_id' => ['required', 'exists:users,id'],
        ]);

        broadcast(new CallSignal(
            $callRoom,
            Auth::user(),
            $request->target_user_id,
            $request->signal
        ))->toOthers();

        return response()->json(['success' => true]);
    }
}