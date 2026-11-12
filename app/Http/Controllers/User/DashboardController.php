<?php

namespace App\Http\Controllers\User;

use App\Enums\FollowerStatus;
use App\Http\Controllers\Controller;
use App\Http\Middleware\CheckIfBlocked;
use App\Repositories\Interfaces\UserInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Fluent;

class DashboardController extends Controller
{
  public function __construct(
    protected UserInterface $repo
  ){
    $this->middleware(['auth', 'verified', CheckIfBlocked::class]);
   }

  public function overview(Request $request)
  {
    $posts = $request->user()->posts();

    $totallikes = $posts->sum('likes_count');
    $totalPostViews = $posts->sum('views');
    $totalPendingPosts   = $request->user()->posts()->pending()->count();
    $totalPublishedPosts = $request->user()->posts()->published()->count();
    $totalDraftPosts     = $request->user()->posts()->draft()->count();

    return view('dashboard.overview', $this->sharedData([
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
    $perPage = (int) request()->get('perpage', 10);
    $page = (int) request()->get('my_posts', 1);
    $user = request()->user();

    $posts = $this->repo->getAllPosts($user,$sort,$perPage,$page);

    return view('dashboard.myposts', $this->sharedData([
      'posts' => $posts,
    ]));
  }

  public function followers()
  {
    $followers = $this->baseQueryFollowers(FollowerStatus::ACCEPTED)
                      ->paginate(12);

    return view('dashboard.followers', $this->sharedData([
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
    return view('dashboard.pending-followers', $this->sharedData([
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

    return view('dashboard.followings', $this->sharedData([
      'people' => $followings,
    ]));
  }

  private function sharedData(array $data = [])
    {
      $user = request()->user();
      $user->loadCount(['posts', 'followers', 'followings','pendingFollowers','profileViews','likes','comments']);
      return array_merge([
        'user' => $user,
        'total_posts' => $user->posts_count,
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
