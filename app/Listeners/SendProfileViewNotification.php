<?php

namespace App\Listeners;

use App\Events\ProfileViewedEvent;
use App\Models\User;
use App\Notifications\viewedProfileNotification;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Notifications\DatabaseNotification;
class SendProfileViewNotification implements ShouldQueue
{
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
    public function handle(ProfileViewedEvent $event): void
    {
      $viewer = $event->viewer;
      $profileOwner = $event->user;

    if ($viewer->is_admin || $viewer->id === $profileOwner->id) return;

          $notifyIDs = User::where('is_admin',true)
                  ->pluck('id')
                  ->push($profileOwner->id)
                  ->unique();
    
    foreach($notifyIDs as $notifyID){
    
      if($user = User::find($notifyID)){
         $user->notify(
                new ViewedProfileNotification($profileOwner, $viewer)
            );
        }
     }
    }
}
