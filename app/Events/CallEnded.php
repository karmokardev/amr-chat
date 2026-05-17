<?php

namespace App\Events;

use App\Models\CallRoom;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CallEnded implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public CallRoom $callRoom) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('chat.' . $this->callRoom->chat_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'call.ended';
    }

    public function broadcastWith(): array
    {
        return [
            'call_room_id' => $this->callRoom->id,
        ];
    }
}