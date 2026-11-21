<?php

namespace App\Http\Controllers;

use App\Http\Middleware\CheckIfBlocked;
use App\Models\Hashtag;
use App\Repositories\Interfaces\TagInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;


class HashtagController extends Controller
{
  public function __construct()
  {
    $this->middleware(CheckIfBlocked::class);
  }
  public function __invoke(Hashtag $hashtag,Request $request, TagInterface $repo): View|JsonResponse
  {

    abort_if($hashtag->status !== \App\Enums\TagStatus::ACTIVE, 404);
    $perPage = (int) $request->get('perpage', 3);
    $page = (int) $request->get('hashtags_page', 1);
    $sorts = (string) $request->get('sort', 'recent');

    guest_not_allowed_filter_following($sorts);

    $hashtag->loadCount('posts');
    $posts = $repo->getPostsByTag($hashtag,$sorts,$perPage,$page);
    attach_author_cards($posts);
    $sliders = $repo->getSliderImages($hashtag);
    
    if ($request->ajax()) {
      $html = view('hashtags.hashtags-ajax', [
        'posts' => $posts, 
        'currentHashtag' => $hashtag
      ])->render();
      return infinite_scroll_response($html,$posts);
    };

    return view('hashtags.show', [
      'posts' => $posts,
      'currentHashtag' => $hashtag,
      'sorts' => $sorts,
      'sliders' => $sliders
    ]);
  }
}
