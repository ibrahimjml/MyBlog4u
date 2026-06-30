<?php

namespace App\Http\Controllers;

use App\Actions\AcceptFollowAction;
use App\Actions\DeleteFollowAcceptNotificationAction;
use App\Actions\FollowUserAction;
use App\Actions\RejectFollowRequestAction;
use App\Actions\UnFollowUserAction;
use App\Models\Post;
use App\Models\User;
use App\Http\Middleware\CheckIfBlocked;
use App\Services\PostService;
use App\Traits\AdminNotificationGate;
use Illuminate\Http\Request;

class PublicController extends Controller
{
  public function __construct(
    protected PostService $service,
    protected FollowUserAction $followUserAction,
    protected UnFollowUserAction $unFollowUserAction,
    protected AcceptFollowAction $acceptFollowAction,
    protected RejectFollowRequestAction $rejectFollowRequestAction,
  ) {
    $this->middleware(['auth', 'verified', CheckIfBlocked::class]);
  }

  public function toggleFollow(User $user)
  {
    $follower = auth()->user();
    if ($follower->is($user)) {
      return response()->json(['error' => 'You cannot follow yourself.'], 400);
    }

    if ($follower->allFollowings()->where('user_id', $user->id)->exists()) {
      $this->unFollowUserAction->execute($follower, $user);
      return response()->json([
        'status' => null,
      ]);
    }
    $status = $this->followUserAction->execute($follower, $user);
    return response()->json(['status' => $status]);
  }
  public function accept(User $follower)
  {
    $this->acceptFollowAction->execute(auth()->user(), $follower);
    toastr()->success('Follow request accepted');
    return back();
  }
  // reject follow request
  public function reject(User $follower)
  {
    $this->rejectFollowRequestAction->execute(auth()->user(), $follower);
    toastr()->success('Follow request rejected');
    return back();
  }
  // remove follower
  public function removeFollower(User $follower)
  {
    $auth = auth()->user();
    $auth->followers()->detach($follower->id);
    DeleteFollowAcceptNotificationAction::execute($auth, $follower);

    toastr()->success('Follower removed');
    return back();
  }
  // unfollowing a user
  public function unfollow(User $user)
  {
    $follower = auth()->user();
    $this->unFollowUserAction->execute($follower, $user);
    toastr()->success('Unfollowed successfully');
    return back();
  }
  public function like(Post $post)
  {

    if ($post->is_liked()) {
      $like = $post->likes()->where('user_id', auth()->user()->id)->first();
      if ($like) {
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
