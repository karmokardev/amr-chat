<?php

namespace App\Events;

use App\Models\CallRoom;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CallSignal implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public CallRoom $callRoom,
        public User $sender,
        public int $targetUserId,
        public mixed $signal
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('call.' . $this->callRoom->id . '.' . $this->targetUserId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'call.signal';
    }

    public function broadcastWith(): array
    {
        return [
            'sender_id' => $this->sender->id,
            'signal'    => $this->signal,
        ];
    }
}