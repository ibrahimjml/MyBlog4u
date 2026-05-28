<?php

namespace App\Http\Controllers;

use App\Enums\FollowerStatus;
use App\Enums\NotificationType;
use App\Models\Post;
use App\Models\User;
use App\Http\Middleware\CheckIfBlocked;
use App\Notifications\FollowAcceptNotification;
use App\Notifications\FollowersNotification;
use App\Services\FollowService;
use App\Services\PostService;
use App\Traits\AdminNotificationGate;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class PublicController extends Controller
{
  use AdminNotificationGate;
  public function __construct(protected PostService $service)
  {
    $this->middleware(['auth','verified',CheckIfBlocked::class]);
  }

public function toggleFollow(User $user,FollowService $service){
  $follower = auth()->user();
  if ($follower->id === $user->id) {
    return response()->json(['error' => 'You cannot follow yourself.'], 400);
}

$status = $service->toggle($follower,$user);

return response()->json(['status'=>$status]);
}
public function accept(User $follower)
{
    $auth = auth()->user();

    $auth->pendingFollowers()->updateExistingPivot($follower->id, [
      'status' => FollowerStatus::ACCEPTED->value,
      'updated_at' => now()
      ]);

    DatabaseNotification::where('type', FollowersNotification::class)
         ->where('notifiable_id', $auth->id)
         ->whereJsonContains('data->follower_id', $follower->id)
         ->whereJsonContains('data->status', 'private')
         ->delete();

    $follower->notify(new FollowAcceptNotification($auth, $follower, 'accepted'));
    User::where('is_admin', true)->get()->each(function ($admin) use ($auth, $follower) {
if($admin && $this->allow($admin,NotificationType::FOLLOWACCEPT)){
  $admin->notify(new FollowAcceptNotification($auth, $follower, 'accepted'));
}
});
    toastr()->success('Follow request accepted');
    return back();
}
// reject follow request
public function reject(User $follower)
{
  $auth = auth()->user();
  $auth->pendingFollowers()->detach($follower->id);
  DatabaseNotification::where('type', FollowersNotification::class)
       ->where('notifiable_id', $auth->id)
       ->whereJsonContains('data->follower_id', $follower->id)
       ->whereJsonContains('data->status', 'private')
       ->delete();
    toastr()->success('Follow request rejected');
    return back(); 
}
// remove follower
public function removeFollower(User $follower)
{
  $auth = auth()->user();
  $auth->followers()->detach($follower->id);
    DatabaseNotification::where('type', FollowAcceptNotification::class)
       ->where('notifiable_id', $follower->id)
       ->whereJsonContains('data->follower_id', $auth->id)
       ->whereJsonContains('data->status', 'accepted')
       ->delete();
  toastr()->success('Follower removed');
  return back();
}
// unfollowing a user
public function unfollow(User $user, FollowService $service)
{  
  $follower = auth()->user();
  $follower->followings()->detach($user->id);
  $service->deleteFollowNotification($follower, $user);
  toastr()->success('Unfollowed successfully');
  return back();
}
  public function like(Post $post)
  {

    if ($post->is_liked()) {
    $like =  $post->likes()->where('user_id', auth()->user()->id)->first();
    if($like){
      $like->delete();
      $post->decrement('likes_count');
    }
  
      return response()->json(['liked' => false]);
    }
    $post->likes()->create(['user_id' => auth()->user()->id]);
    $post->increment('likes_count');

    return response()->json([
      'liked' => true,
      'likes_count' => $post->likes_count
    ]);
  }

  public function save(Request $request)
  {
    $fields = $request->validate([
      'post_id' => 'required|int'
    ]);
    $postId = $fields['post_id'];
    $savedposts = session('saved-to', []);
    if (in_array($postId, $savedposts)) {
      $savedposts = array_diff($savedposts, [$postId]);
      session(['saved-to' => $savedposts]);
      return response()->json(['status' => 'removed']);
    } else {
      $savedposts[] = $postId;
      session(['saved-to' => $savedposts]);
      return response()->json(['status' => 'added']);
    }
  }

  public function getsavedposts()
  {
    return $this->service->handleSaved();
  }

  


}
