<?php

namespace App\Listeners;

use App\Enums\NotificationType;
use App\Events\PostLikedEvent;
use App\Models\User;
use App\Notifications\LikesNotification;
use App\Traits\AdminNotificationGate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendLikeNotification implements ShouldQueue
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
    public function handle(PostLikedEvent $event): void
    {
        $like = $event->like;
        $post = $like->post;
        $liker = $like->user;

      if($post->user->id !== $liker->id){
        // Mail::to($post->user)->queue(new postlike($post->user, auth()->user(), $post));
        $post->user->notify(new LikesNotification($liker,$post));
      }
      
      // Notify  admins
      User::where('is_admin', true)
         ->whereKeyNot($post->user->id)
         ->get()
         ->each(function ($admin) use ($liker, $post) {
      if($admin && $this->allow($admin,NotificationType::LIKE)){
      $admin->notify(new LikesNotification($liker,$post));
      }
         });
    }
}
