<?php

namespace App\Http\Controllers\User;

use App\Enums\UserActivityType;
use App\Http\Controllers\Controller;
use App\Http\Middleware\CheckIfBlocked;
use App\Models\ProfileView;
use App\Models\User;
use App\Services\ProfileViewService;
use App\Repositories\Interfaces\UserInterface;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
  public function __construct(
       protected ProfileViewService $view,
       protected UserInterface $repo
  ){
      $this->middleware(CheckIfBlocked::class);
   }

  public function Home(Request $request, User $user)
{
    abort_if_user_not_activated($user);

    if (auth()->check()) {
        $viewer = auth()->user();
        $this->view->createView($user, $viewer);
    }

    $perPage = (int) $request->get('perpage', 5);
    $page    = (int) $request->get('home_page', 1);

    $articles = $this->repo->getPaginatedArticles($user, $perPage, $page);

    $articleItems = $articles->getCollection();

    $pinned    = $articleItems->where('is_pinned', true)->values();
    $pinnedIds = $pinned->modelKeys();

    $feed = $pinned->isEmpty()
        ? $articleItems
        : $articleItems->reject(fn ($post) => in_array($post->id, $pinnedIds, true))->values();
        
    $feedCount = $pinned 
       ? $user->posts()->published()->where('is_pinned', false)->count()
       : $user->posts()->published()->where('is_pinned', true)->count();
    // set to $feed for non pinned
    $articles->setCollection($feed);

    $tags = $feed->flatMap(fn ($post) => $post->hashtags->pluck('name'))
        ->unique()
        ->take(5);

    $pinnedTag = $pinned->mapWithKeys(fn ($post) => [
        $post->id => $post->hashtags->first()?->name,
    ]);

    if ($request->ajax() || $request->expectsJson()) {
        $html = view('profile.partials.home-ajax', [
            'articles' => $articles,
        ])->render();

        return infinite_scroll_response($html, $articles);
    }

    return view('profile.profile', array_merge(
        [
            'articles'   => $articles,
            'pinned'     => $pinned,
            'pinnedTag' => $pinnedTag,
            'tags'       => $tags,
            'feedCount' => $feedCount
        ],
        $this->ProfileData($user, 'home')
    ));
}
  public function activity(Request $request, User $user)
  {

   abort_if_user_not_activated($user);

    $perPage = (int) $request->get('perpage', 5);
    $page    = (int) $request->get('activity_page', 1);
    $type    = UserActivityType::tryFrom($request->get('type', ''));

  $activities = $this->repo->getUserActivities($user, $type, $perPage, $page);

  if ($request->ajax() || $request->expectsJson()) {
        $html = view('profile.partials.activity-ajax', [
            'activities' => $activities,
        ])->render();

        return infinite_scroll_response($html, $activities);
    }

  return view('profile.profile', array_merge(
    ['activities' => $activities],
    $this->ProfileData($user, 'activity')
  ));
}
public function aboutme(User $user){
  abort_if_user_not_activated($user);
  return view('profile.profile', $this->ProfileData($user, 'about'));
}
private function ProfileData(User $user, string $section)
{
   $user->loadCount(['followers','followings']);
  return [
      'user' => $user,
      'section' => $section,
      'postcount' => $user->posts()->published()->count(),
      'likescount' => $user->posts()->withCount('likes')->get()->sum('likes_count'),
      'commentscount' => $user->posts()->withCount('comments')->get()->sum('comments_count'),
      'profileviews' => ProfileView::query()->where('profile_id', $user->id)->with('viewer')->get(),
      'userSocials' => [
             'builtin' => collect([
                 'github'   => $user->github,
                 'linkedin' => $user->linkedin,
                 'twitter'  => $user->twitter,
             ])->filter(fn ($v) => filled($v))->all(),
         
             'others'  => $user->socialLinks()->get()->map(fn ($link) => [
                 'url'      => $link->url,
                 'platform' => $link->platform,
             ])->all(),
         ],
    ];
}


}
