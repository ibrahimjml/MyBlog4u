<?php

namespace App\Http\Controllers\User;

use App\DTOs\User\ProfileHomeDTO;
use App\Enums\ProfileArticleSort;
use App\Enums\UserActivityType;
use App\Http\Controllers\Controller;
use App\Http\Middleware\CheckIfBlocked;
use App\Http\Requests\App\ProfileHomeRequest;
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

  public function Home(ProfileHomeRequest $request, User $user)
{
    abort_if_user_not_activated($user);
    abort_if_user_blocked($user);

    if (! $request->ajax() && ! $request->expectsJson() && $request->query('sort') === ProfileArticleSort::RECENT->value) {
        return redirect()->route('profile.home', array_merge(
            $request->except('sort'),
            ['user' => $user->username]
        ));
    }

    if (auth()->check()) {
        $viewer = auth()->user();
        $this->view->createView($user, $viewer);
    }

    $dto = ProfileHomeDTO::fromRequest($request);
    $articles = $this->repo->getPaginatedArticles($user, $dto);

    if ($request->ajax() || $request->expectsJson()) {
        $html = view('profile.partials.home-ajax', [
            'articles' => $articles,
        ])->render();

        return infinite_scroll_response($html, $articles);
    }

    $articleItems = $articles->getCollection();

    $pinned = $articleItems->where('is_pinned', true)->values();
    $feed   = $articleItems->reject(fn ($post) => $post->is_pinned)->values();

    $articles->setCollection($feed);

    $feedCount = max(0, $articles->total() - $user->posts()->published()->where('is_pinned', true)->count());

    $tags = $feed->flatMap(fn ($post) => $post->hashtags->pluck('name'))
        ->unique()
        ->take(5);

    $pinnedTag = $pinned->mapWithKeys(fn ($post) => [
        $post->id => $post->hashtags->first()?->name,
    ]);

    return view('profile.profile', array_merge(
        [
            'articles'   => $articles,
            'pinned'     => $pinned,
            'pinnedTag' => $pinnedTag,
            'tags'       => $tags,
            'feedCount' => $feedCount,
            'sort'      => $dto->sort,
        ],
        $this->ProfileData($user, 'home')
    ));
}
  public function activity(Request $request, User $user)
  {

   abort_if_user_not_activated($user);
    abort_if_user_blocked($user);

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
  abort_if_user_blocked($user);
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
      'userSocials' => $user->newQuery()->socials(),
    ];
}


}
