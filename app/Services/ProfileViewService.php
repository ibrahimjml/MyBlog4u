<?php

namespace App\Services;

use App\Events\ProfileViewedEvent;
use App\Models\ProfileView;
use App\Models\User;

class ProfileViewService
{
    public function createView(User $profileowner,User $viewer)
    {
       
     if ($viewer->id === $profileowner->id || $viewer->is_admin) return;
     
      $profileView = ProfileView::firstOrCreate([
          'viewer_id' => auth()->id(),
          'profile_id' => $profileowner->id,
      ]);
      
   if ( $profileView->last_notified_at && $profileView->last_notified_at->gt(now()->subDay()))  return;
    

   event(new ProfileViewedEvent($profileowner, $viewer));

   $profileView->update([
        'last_notified_at' => now(),
    ]);
    }
}
