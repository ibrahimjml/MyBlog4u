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

class FollowUserEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

  public $follower;
  public $user;
  public $status;
    public function __construct(User $follower, User $user,string $status)
    {
        $this->follower = $follower;
        $this->user = $user;
        $this->status = $status;
    }


}
