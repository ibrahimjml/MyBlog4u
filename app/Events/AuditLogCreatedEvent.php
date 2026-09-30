<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AuditLogCreatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
     public function __construct(
             public readonly string $module,
             public readonly string $action,
             public readonly ?int $userId = null,
             public readonly ?int $referenceUser = null,
             public readonly ?int $referenceId = null,
             public readonly ?string $referenceName = null,
             public readonly ?string $type = null,
             public readonly ?string $request = null,
             public readonly ?string $userAgent = null,
             public readonly ?string $ipAddress = null,
         ) {}

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
