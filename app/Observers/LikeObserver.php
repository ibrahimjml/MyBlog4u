<?php

namespace App\Observers;

use App\Events\PostLikedEvent;
use App\Models\Like;
use App\Models\User;
use App\Notifications\LikesNotification;
use Illuminate\Notifications\DatabaseNotification;


class LikeObserver
{
    /**
     * Handle the Like "created" event.
     */
    public function created(Like $like): void
    {
      $like->load(['user','post.user']);
      event(new PostLikedEvent($like));
    }


public function deleted(Like $like): void
{

  $like->load(['user','post']);
  $post = $like->post;
  $liker = $like->user;
    // Auto delete like notification for admin and poster
    $notifiableIds = User::where('is_admin', true)
        ->pluck('id')
        ->push($post->user_id); 

    DatabaseNotification::where('type', LikesNotification::class)
        ->whereIn('notifiable_id', $notifiableIds)
        ->whereJsonContains('data->post_id', $post->id)
        ->whereJsonContains('data->user_id', $liker->id)
        ->delete();
}



}
