<?php

namespace App\Http\Controllers;


use App\Models\Slide;
use App\Repositories\Interfaces\NewsInterface;
use App\Repositories\Interfaces\PostInterface;
use Illuminate\Support\Facades\Cache;


class HomeController extends Controller
{
  public function __invoke(PostInterface $repo, NewsInterface $news)
  {
    $featuredPosts = $repo->getFeaturedPosts();
    $news = $news->getAllNewsWithSources();
    $result = $repo->getTrendingTagPosts();
    $latestPosts = $repo->getLatestForHomeFeed();
    

  return view('index', [
      'slides' => Cache::rememberForever('slides_home_page', function () {
                    return Slide::published()
                             ->latest()
                             ->get()
                             ->take(4);
                    }),
       'latest_posts'    => $latestPosts,
       'featuredPosts'   => $featuredPosts,
       'latestTrend'     => $result['latestTrend'],
       'trendingHashtag' => $result['trendingHashtag'],
       'moreNews'        => $news['moreNews'],
       ]);

  }
}
