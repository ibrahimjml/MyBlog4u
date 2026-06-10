<?php

namespace App\Listeners;

use App\Enums\NotificationType;
use App\Events\PostCreatedEvent;
use App\Models\User;
use App\Notifications\FollowingPostCreatedNotification;
use App\Traits\AdminNotificationGate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendPostNotification implements ShouldQueue
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
    public function handle(PostCreatedEvent $event): void
    {   
        $post = $event->post;
        $creator = $post->user;
      $followers = $creator->followers->filter(fn($user) => !$user->is_admin);
        // Notify  users
      foreach($followers as $follower){
        $follower->notify(new FollowingPostCreatedNotification($creator,$post));
      }
        // Notify  admins
     User::where('is_admin', true)->get()->each(function ($admin) use ($creator, $post) {
    if($admin && $this->allow($admin,NotificationType::POSTCREATED)){
     $admin->notify(new FollowingPostCreatedNotification( $creator, $post));
    }
       });
    }
}
