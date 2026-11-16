<?php

namespace App\Observers;

use App\Models\User;
use App\Services\ClearCacheService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Helpers\CleanupNotifications;
use App\Models\Post;
use App\Models\PostReport;
use App\Models\PostView;

class UserObserver
{

    /**
     * Handle the User "deleting" event.
     */
    public function created(User $user)
    {
      if(app()->runningInConsole()){
        return;
      }
      $user->profile()->create([
        'is_public' => true,
        'user_id' => $user->id
      ]);
      // clear cache 
      app(ClearCacheService::class)->clearUserCaches();
    }
    public function updated(User $user)
    {
      // clear cache 
      app(ClearCacheService::class)->clearUserCaches();

      if ($user->wasChanged('is_blocked')) {
        app(ClearCacheService::class)->clearPublicContentCaches();
      }
    }
    public function deleting(User $user): void
    {
         DB::transaction(function () use ($user) {
            $user->post->each(fn ($post) => $post->delete());

            $user->notifications()->delete(); 
            CleanupNotifications::deleteRelatedNotifications($user);

             if ($user->avatar !== 'default.jpg' && Storage::disk('public')->exists("avatars/{$user->avatar}")) {
            Storage::disk('public')->delete("avatars/{$user->avatar}");
            }

             if ($user->cover_photo !=='sunset.jpg' && Storage::disk('public')->exists("covers/{$user->cover_photo}")) {
            Storage::disk('public')->delete("covers/{$user->cover_photo}");
            }

         $likedPostIds = $user->likes()->pluck('post_id');
         Post::whereIn('id', $likedPostIds)->decrement('likes_count');
        
         $reportsIds = PostReport::where('user_id', $user->id)->pluck('post_id');
         Post::whereIn('id', $reportsIds)->decrement('report_count');
         
         $postviewids = PostView::where('viewer_id',$user->id)->pluck('post_id');
         Post::whereIn('id',$postviewids)->decrement('views') ;
          
            $user->likes()->delete();
            $user->comments()->delete();
            $user->replies()->delete();
            $user->followers()->detach();
            $user->followings()->detach();

         });
    }

  public function deleted(User $user): void
  {
    // clear cache 
    app(ClearCacheService::class)->clearUserCaches();
  }
}
