@extends('layouts.chat')

@section('content')

{{-- Sidebar --}}
<div class="flex flex-col border-r border-gray-200 w-80 dark:border-gray-700">

    {{-- Header --}}
    <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
        <h2 class="text-lg font-semibold">Chats</h2>
        <button onclick="document.getElementById('newChatModal').classList.remove('hidden')"
            class="p-2 transition rounded-full hover:bg-gray-100 dark:hover:bg-gray-800">
            <svg class="w-5 h-5 text-[#D97757]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
        </button>
    </div>

    {{-- Chat List --}}
    <div class="flex-1 overflow-y-auto">
        @forelse($chats as $chat)
            <a href="{{ route('chat.show', $chat) }}"
                class="flex items-center gap-3 px-4 py-3 transition border-b border-gray-100 hover:bg-gray-50 dark:hover:bg-gray-800 dark:border-gray-700">

                {{-- Avatar --}}
                <div class="w-10 h-10 rounded-full bg-[#D97757] flex items-center justify-center text-white font-bold shrink-0">
                    {{ strtoupper(substr($chat->type === 'group' ? $chat->name : $chat->members->where('id', '!=', Auth::id())->first()?->name ?? 'U', 0, 1)) }}
                </div>

                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium truncate">
                            {{ $chat->type === 'group' ? $chat->name : $chat->members->where('id', '!=', Auth::id())->first()?->name ?? 'Unknown' }}
                        </span>
                        <span class="ml-2 text-xs text-gray-400 shrink-0">
                            {{ $chat->lastMessage?->created_at?->diffForHumans(short: true) }}
                        </span>
                    </div>
                    <p class="text-xs text-gray-500 truncate mt-0.5">
                        {{ $chat->lastMessage?->message ?? 'No messages yet' }}
                    </p>
                </div>

            </a>
        @empty
            <div class="p-6 text-sm text-center text-gray-400">
                No chats yet. Start a new one!
            </div>
        @endforelse
    </div>

</div>

{{-- Empty State --}}
<div class="flex items-center justify-center flex-1 text-gray-400">
    <div class="text-center">
        <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
        </svg>
        <p class="text-lg font-medium">Select a chat to start messaging</p>
    </div>
</div>

{{-- New Chat Modal --}}
<div id="newChatModal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black/50">
    <div class="w-full max-w-md p-6 bg-white shadow-xl dark:bg-gray-800 rounded-xl">

        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">New Chat</h3>
            <button onclick="document.getElementById('newChatModal').classList.add('hidden')"
                class="text-gray-400 hover:text-gray-600">✕</button>
        </div>

        {{-- Private Chat --}}
        <form action="{{ route('chat.private') }}" method="POST" class="mb-4">
            @csrf
            <label class="block mb-1 text-sm font-medium">Private Chat (User ID)</label>
            <div class="flex gap-2">
                <input type="number" name="user_id" placeholder="Enter user ID"
                    class="flex-1 border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-[#D97757]">
                <button type="submit"
                    class="bg-[#D97757] text-white px-4 py-2 rounded-lg text-sm hover:bg-[#c4684a] transition">
                    Start
                </button>
            </div>
        </form>

        <hr class="mb-4 dark:border-gray-700">

        {{-- Group Chat --}}
        <form action="{{ route('chat.group') }}" method="POST">
            @csrf
            <label class="block mb-1 text-sm font-medium">Group Chat</label>
            <input type="text" name="name" placeholder="Group name"
                class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm dark:bg-gray-700 mb-2 focus:outline-none focus:ring-2 focus:ring-[#D97757]">
            <input type="text" name="members[]" placeholder="User IDs (comma separated)"
                class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm dark:bg-gray-700 mb-2 focus:outline-none focus:ring-2 focus:ring-[#D97757]">
            <button type="submit"
                class="w-full bg-[#D97757] text-white py-2 rounded-lg text-sm hover:bg-[#c4684a] transition">
                Create Group
            </button>
        </form>

    </div>
</div>

@endsection