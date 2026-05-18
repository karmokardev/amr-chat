<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\ChatMember;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class ChatController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $chats= Auth::user()->chats()
        ->with(['lastMessage.sender','members'])
        ->orderBy('updated_at')
        ->get();
        return view('chat.index',compact('chats'));

    }
    public function show(Chat $chat)
    {
        $this->authorize('view', $chat);

        $messages = $chat->messages()
        ->with(['sender','media','replyTo.sender','reactions'])
        ->latest()
        ->paginate(50);

        $members = $chat->members()->get();
         
        return view('chat.show',compact('chat','messages','members',));
    }
    
    public function createPrivate(Request $request)
    {
        $request->validate([
            'user_id'=>['required','exists:users,id']
        ]);

        $authId = Auth::id();
        $userId = $request->user_id;

        // Already exists check
        $existing = Chat::where('type', 'private')
            ->whereHas('members', fn($q) => $q->where('user_id', $authId))
            ->whereHas('members', fn($q) => $q->where('user_id', $userId))
            ->first();


        if ($existing) {
            return request()->wantsJson()
                ? response()->json($existing)
                : redirect()->route('chat.show', $existing);
        }

        $chat = Chat::create([
            'uuid' => Str::uuid(),
            'type' => 'private',
            'created_by' => $authId,
        ]);

        ChatMember::insert([
            [
                'chat_id'    => $chat->id,
                'user_id'    => $authId,
                'role'       => 'owner',
                'joined_at'  => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'chat_id'    => $chat->id,
                'user_id'    => $userId,
                'role'       => 'member',
                'joined_at'  => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
        
        return request()->wantsJson()
            ? response()->json($chat)
            : redirect()->route('chat.show', $chat);
    }

    // group chat create
    public function createGroup(Request $request)
    {
        $request->validate([
            'name'=>['required','string', 'max:255'],
            'members'=>['required','array', 'min:2'],
            'members.*'=>['exists:users,id']
        ]);

        $chat = Chat::create([
            'uuid' => Str::uuid(),
            'type' => 'group',
            'name' => $request->name,
            'created_by' => Auth::id(),
        ]);

        $members = collect($request->members)
        ->push(Auth::id())
        ->unique()
        ->map(fn($userId)=>[
            'chat_id'=> $chat->id,
            'user_id'=> $userId,
            'role'=>$userId == Auth::id() ? 'owner' : 'member',
            'joined_at'=>now(),
            'createed_at'=>now(),
            'updated_at'=>now(),
        ])->toArray();

        ChatMember::insert($members);

        return redirect()->route('chat.show', $chat);
    }

    public function searchUsers(Request $request)
    {
        $query = $request->get('q');

        if (!$query || strlen($query) < 2) {
            return response()->json([]);
        }

        $users = User::where('id', '!=', Auth::id())
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                ->orWhere('username', 'like', "%{$query}%");
            })
            ->limit(5)
            ->get(['id', 'name', 'username', 'avatar', 'is_online']);

        return response()->json($users);
    }
}