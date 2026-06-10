<?php

namespace App\Listeners;

use App\Enums\NotificationType;
use App\Events\ReplyCommentEvent;
use App\Models\User;
use App\Notifications\RepliedCommentNotification;
use App\Traits\AdminNotificationGate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendReplyNotification implements ShouldQueue
{
    use AdminNotificationGate;
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ReplyCommentEvent $event): void
    {    $comment = $event->comment;
         $reply = $event->reply;
         $replier = $event->replier;
         $post= $comment->post;
         
     if($comment->user->id !== $replier->id){
       $comment->user->notify(new RepliedCommentNotification($comment,$reply,$replier,$post));
     }

// Notify  admins
User::where('is_admin', true)->get()->each(function ($admin) use ($comment,$reply, $replier,$post) {
  if($admin && $this->allow($admin,NotificationType::COMMENTS)){
$admin->notify(new RepliedCommentNotification($comment, $reply, $replier,$post));
  }
});
    }
}
