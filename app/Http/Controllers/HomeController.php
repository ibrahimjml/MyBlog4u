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
    $trending = $repo->getMostTrendingTag(3);
    $relatedTrendTags = $repo->getTrendingTagsAllTime(
        array_filter([$trending['tag']?->id]), 4
    );
    $latestPosts = $repo->getLatestForHomeFeed();
    $slides = Cache::rememberForever('slides_home_page', function () {
                    return Slide::published()
                             ->latest()
                             ->get()
                             ->take(4);
                    });

  return view('index', [
       'slides'            => $slides,
       'latest_posts'      => $latestPosts,
       'featuredPosts'     => $featuredPosts,
       'trendingTag'       => $trending['tag'],
       'trendingPosts'     => $trending['posts'],
       'trendingAvatars'   => $trending['avatars'],
       'relatedTrendTags'  => $relatedTrendTags,
       'moreNews'          => $news['moreNews'],
       ]);

  }
}
