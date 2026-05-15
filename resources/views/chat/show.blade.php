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
        @foreach(Auth::user()->chats()->with(['lastMessage', 'members'])->orderByDesc('updated_at')->get() as $item)
            <a href="{{ route('chat.show', $item) }}"
                class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-800 transition border-b border-gray-100 dark:border-gray-700
                {{ $item->id === $chat->id ? 'bg-orange-50 dark:bg-gray-800 border-l-2 border-l-[#D97757]' : '' }}">

                <div class="w-10 h-10 rounded-full bg-[#D97757] flex items-center justify-center text-white font-bold shrink-0">
                    {{ strtoupper(substr($item->type === 'group' ? $item->name : $item->members->where('id', '!=', Auth::id())->first()?->name ?? 'U', 0, 1)) }}
                </div>

                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium truncate">
                            {{ $item->type === 'group' ? $item->name : $item->members->where('id', '!=', Auth::id())->first()?->name ?? 'Unknown' }}
                        </span>
                        <span class="ml-2 text-xs text-gray-400 shrink-0">
                            {{ $item->lastMessage?->created_at?->diffForHumans(short: true) }}
                        </span>
                    </div>
                    <p class="text-xs text-gray-500 truncate mt-0.5">
                        {{ $item->lastMessage?->message ?? 'No messages yet' }}
                    </p>
                </div>
            </a>
        @endforeach
    </div>
    
</div>

{{-- Chat Area --}}
<div class="flex flex-col flex-1" x-data="chatApp({{ $chat->id }})">

    {{-- Chat Header --}}
    @php
        $otherUser = $chat->type === 'private'
            ? $chat->members->where('id', '!=', Auth::id())->first()
            : null;
    @endphp
    <meta name="other-user-id" content="{{ $otherUser?->id ?? 0 }}">

    <div class="flex items-center justify-between px-4 border-b border-gray-200 h-14 dark:border-gray-700 shrink-0">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-full bg-[#D97757] flex items-center justify-center text-white font-bold text-sm">
                {{ strtoupper(substr($chat->type === 'group' ? $chat->name : $otherUser?->name ?? 'U', 0, 1)) }}
            </div>
            <div>
                <p class="text-sm font-semibold">
                    {{ $chat->type === 'group' ? $chat->name : $otherUser?->name ?? 'Unknown' }}
                </p>
                <p class="text-xs text-gray-400" x-text="typingText"></p>
            </div>
        </div>

        {{-- Call Buttons --}}
        <div class="flex items-center gap-2">

            {{-- Audio Call --}}
            <button @click="startCall('audio')"
                class="p-2 transition rounded-full hover:bg-gray-100 dark:hover:bg-gray-800">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
            </button>

            {{-- Video Call --}}
            <button @click="startCall('video')"
                class="p-2 transition rounded-full hover:bg-gray-100 dark:hover:bg-gray-800">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
            </button>

        </div>
    </div>
    {{-- Messages --}}
    <div class="flex-1 p-4 space-y-3 overflow-y-auto" id="messageContainer">
        @foreach($messages->reverse() as $message)
            <div class="flex {{ $message->sender_id === Auth::id() ? 'justify-end' : 'justify-start' }}">
                <div class="max-w-xs lg:max-w-md">

                    @if($chat->type === 'group' && $message->sender_id !== Auth::id())
                        <p class="mb-1 ml-1 text-xs text-gray-400">{{ $message->sender->name }}</p>
                    @endif

                    @if($message->replyTo)
                        <div class="text-xs bg-gray-100 dark:bg-gray-700 rounded px-2 py-1 mb-1 border-l-2 border-[#D97757]">
                            <span class="font-medium">{{ $message->replyTo->sender->name }}</span>
                            <p class="text-gray-500 truncate">{{ $message->replyTo->message }}</p>
                        </div>
                    @endif

                    <div class="px-4 py-2 rounded-2xl text-sm
                        {{ $message->sender_id === Auth::id()
                            ? 'bg-[#D97757] text-white rounded-br-sm'
                            : 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-bl-sm' }}">

                        @if($message->is_deleted_for_everyone)
                            <span class="italic opacity-60">Message deleted</span>
                        @elseif($message->media && $message->media->isImage())
                            <img src="{{ asset('storage/' . $message->media->path) }}"
                                class="max-w-xs rounded-lg cursor-pointer"
                                onclick="window.open('{{ asset('storage/' . $message->media->path) }}', '_blank')">
                        @elseif($message->media)
                            <a href="{{ asset('storage/' . $message->media->path) }}" target="_blank"
                                class="flex items-center gap-2 underline">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                {{ $message->media->original_name }}
                            </a>
                        @else
                            {{ $message->message }}
                        @endif
                    </div>

                    <p class="text-xs text-gray-400 mt-1 {{ $message->sender_id === Auth::id() ? 'text-right' : 'text-left' }}">
                        {{ $message->created_at->format('h:i A') }}
                        @if($message->is_edited)
                            · <span class="italic">edited</span>
                        @endif
                    </p>

                </div>
            </div>
        @endforeach

        {{-- Dynamic messages --}}
        <template x-for="message in messages" :key="message.id">
            <div class="flex" :class="message.sender_id === {{ Auth::id() }} ? 'justify-end' : 'justify-start'">
                <div class="max-w-xs lg:max-w-md">
                    <div class="px-4 py-2 text-sm rounded-2xl"
                        :class="message.sender_id === {{ Auth::id() }}
                            ? 'bg-[#D97757] text-white rounded-br-sm'
                            : 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-bl-sm'">

                        <template x-if="message.media && message.media.type === 'image'">
                            <img :src="message.media.url" class="max-w-xs rounded-lg cursor-pointer"
                                @click="window.open(message.media.url, '_blank')">
                        </template>

                        <template x-if="message.media && message.media.type !== 'image'">
                            <a :href="message.media.url" target="_blank" class="flex items-center gap-2 underline">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <span x-text="message.media.original_name"></span>
                            </a>
                        </template>

                        <template x-if="!message.media">
                            <span x-text="message.message"></span>
                        </template>

                    </div>
                    <p class="mt-1 text-xs text-gray-400"
                        :class="message.sender_id === {{ Auth::id() }} ? 'text-right' : 'text-left'"
                        x-text="message.created_at"></p>
                </div>
            </div>
        </template>
    </div>

    {{-- Input --}}
    <div class="p-4 border-t border-gray-200 dark:border-gray-700 shrink-0">
        <div class="flex items-center gap-3">

            <label class="p-2 transition rounded-full cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800 shrink-0">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                </svg>
                <input type="file" class="hidden" @change="handleFileSelect">
            </label>

            <input
                type="text"
                x-model="newMessage"
                @keyup.enter="sendMessage"
                @input="sendTyping"
                placeholder="Type a message..."
                class="flex-1 bg-gray-100 dark:bg-gray-800 rounded-full px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#D97757]">

            <button @click="sendMessage"
                class="w-10 h-10 bg-[#D97757] rounded-full flex items-center justify-center hover:bg-[#c4684a] transition shrink-0">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
            </button>

        </div>
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
{{-- Incoming Call Modal --}}
<div x-show="incomingCall" x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/60">
    <div class="p-8 text-center bg-white shadow-2xl dark:bg-gray-800 rounded-2xl w-80">
        <div class="w-16 h-16 rounded-full bg-[#D97757] flex items-center justify-center text-white text-2xl font-bold mx-auto mb-4">
            📞
        </div>
        <h3 class="mb-1 text-lg font-semibold" x-text="incomingCall?.caller?.name + ' is calling...'"></h3>
        <p class="mb-6 text-sm text-gray-400" x-text="incomingCall?.call_room?.type + ' call'"></p>
        <div class="flex justify-center gap-4">
            <button @click="acceptCall"
                class="flex items-center justify-center transition bg-green-500 rounded-full w-14 h-14 hover:bg-green-600">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
            </button>
            <button @click="rejectCall"
                class="flex items-center justify-center transition bg-red-500 rounded-full w-14 h-14 hover:bg-red-600">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 8l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2M5 3a2 2 0 00-2 2v1c0 8.284 6.716 15 15 15h1a2 2 0 002-2v-3.28a1 1 0 00-.684-.948l-4.493-1.498a1 1 0 00-1.21.502l-1.13 2.257a11.042 11.042 0 01-5.516-5.517l2.257-1.128a1 1 0 00.502-1.21L9.228 3.683A1 1 0 008.279 3H5z"/>
                </svg>
            </button>
        </div>
    </div>
</div>

{{-- Active Call Modal --}}
<div x-show="activeCall" x-cloak
    class="fixed inset-0 z-50 flex flex-col items-center justify-center bg-gray-900">

    {{-- Videos --}}
    <div class="relative w-full max-w-3xl">
        <video id="remoteVideo" autoplay playsinline
            class="w-full bg-gray-800 rounded-2xl"></video>
        <video id="localVideo" autoplay playsinline muted
            class="absolute w-32 bg-gray-700 border-2 border-white bottom-4 right-4 rounded-xl"></video>
    </div>

    {{-- Controls --}}
    <div class="flex items-center gap-6 mt-8">

        {{-- Mute --}}
        <button @click="toggleAudio"
            :class="isAudioEnabled ? 'bg-gray-700' : 'bg-red-500'"
            class="flex items-center justify-center transition rounded-full w-14 h-14">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/>
            </svg>
        </button>

        {{-- End Call --}}
        <button @click="endCall"
            class="flex items-center justify-center w-16 h-16 transition bg-red-500 rounded-full hover:bg-red-600">
            <svg class="text-white w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 8l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2M5 3a2 2 0 00-2 2v1c0 8.284 6.716 15 15 15h1a2 2 0 002-2v-3.28a1 1 0 00-.684-.948l-4.493-1.498a1 1 0 00-1.21.502l-1.13 2.257a11.042 11.042 0 01-5.516-5.517l2.257-1.128a1 1 0 00.502-1.21L9.228 3.683A1 1 0 008.279 3H5z"/>
            </svg>
        </button>

        {{-- Camera --}}
        <button @click="toggleVideo"
            :class="isVideoEnabled ? 'bg-gray-700' : 'bg-red-500'"
            class="flex items-center justify-center transition rounded-full w-14 h-14">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
            </svg>
        </button>

    </div>
</div>
@endsection