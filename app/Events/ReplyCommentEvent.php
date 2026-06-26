<?php

namespace App\Events;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ReplyCommentEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $comment;
    public $reply;
    public $replier;
    public function __construct(Comment $comment, Comment $reply,User $replier)
    {
        $this->comment = $comment;
        $this->reply = $reply;
        $this->replier = $replier;
    }


}
