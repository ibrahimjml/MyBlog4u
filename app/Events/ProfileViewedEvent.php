<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProfileViewedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $user;
    public $viewer;
    public function __construct(User $user, User $viewer)
    {
        $this->user = $user;
        $this->viewer = $viewer;
    }

}
