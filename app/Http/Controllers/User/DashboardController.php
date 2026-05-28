<?php

namespace App\Http\Controllers\User;

use App\Enums\FollowerStatus;
use App\Http\Controllers\Controller;
use App\Http\Middleware\CheckIfBlocked;
use App\Models\Post;
use Illuminate\Support\Fluent;

class DashboardController extends Controller
{
  public function __construct()
  {
    $this->middleware(['auth', 'verified', CheckIfBlocked::class]);
  }
  public function overview()
  {
    $user = request()->user()->loadCount(['post', 'followers', 'followings','pendingFollowers','profileViews','likes','comments']);
    $postsQuery = Post::query()->where('user_id', $user->id);
    $totallikes = (clone $postsQuery)->sum('likes_count');
    $totalPostViews = (clone $postsQuery)->sum('views');
    $totalPendingPosts = (clone $postsQuery)->pending()->count();
    $totalPublishedPosts = (clone $postsQuery)->published()->count();
    $totalDraftPosts = (clone $postsQuery)->draft()->count();

    return view('dashboard.index', $this->sharedData('overview', [
      'total_likes'           => $totallikes,
      'total_post_views'      => $totalPostViews,
      'total_pending_posts'   => $totalPendingPosts,
      'total_published_posts' => $totalPublishedPosts,
      'total_draft_posts'     => $totalDraftPosts,
    ]));
  }

  public function posts()
  {
    $sort = new Fluent(request()->only('sort'));

    $posts = request()->user()
      ->post()
      ->withCount(['likes', 'comments'])
      ->with(['hashtags','categories'])
      ->DashboardFilter($sort)
       ->paginate(10)->withQueryString();

    return view('dashboard.index', $this->sharedData('posts', [
      'posts' => $posts,
    ]));
  }

  public function followers()
  {
    $followers = $this->baseQueryFollowers(FollowerStatus::ACCEPTED)
                      ->paginate(12);

    return view('dashboard.index', $this->sharedData('followers', [
      'people' => $followers,
    ]));
  }

  public function pending_followers()
  {
    $pending_followers = request()->user()
      ->pendingFollowers()
      ->wherePivot('status', FollowerStatus::PENDING->value)
      ->latest('followers.created_at')
      ->paginate(12);
    return view('dashboard.index', $this->sharedData('pending_followers', [
      'people' => $pending_followers,
    ]));
  }

  public function followings()
  {
    $followings = request()->user()
      ->followings()
      ->wherePivot('status', FollowerStatus::ACCEPTED)
      ->latest('followers.created_at')
      ->paginate(12);

    return view('dashboard.index', $this->sharedData('followings', [
      'people' => $followings,
    ]));
  }

  private function sharedData(string $section, array $data = [])
    {
      $user = request()->user();
      return array_merge([
        'user' => $user,
        'section' => $section,
        'total_posts' => $user->post()->count(),
      ], $data);
    }

  private function baseQueryFollowers(FollowerStatus $status)
  {
    return request()->user()
      ->followers()
      ->wherePivot('status', $status->value)
      ->latest('followers.created_at');
  }
}
