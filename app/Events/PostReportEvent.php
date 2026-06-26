<?php

namespace App\Events;

use App\Models\Post;
use App\Models\PostReport;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PostReportEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $post;
    public $user;
    public $report;
    public function __construct(Post $post, User $user,PostReport $report)
    {
        $this->post = $post;
        $this->user = $user;
        $this->report = $report;
    }

}
