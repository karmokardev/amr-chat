<?php

namespace App\Events;

use App\Models\CallRoom;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CallInitiated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public CallRoom $callRoom,
        public User $caller
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('chat.' . $this->callRoom->chat_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'call.initiated';
    }

    public function broadcastWith(): array
    {
        return [
            'call_room' => [
                'id'   => $this->callRoom->id,
                'type' => $this->callRoom->type,
            ],
            'caller' => [
                'id'   => $this->caller->id,
                'name' => $this->caller->name,
            ],
        ];
    }
}