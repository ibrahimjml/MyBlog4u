<?php
namespace App\Services;

use App\Models\Category;
use App\Models\Hashtag;
use App\Models\Post;
use App\Services\Sitemap\SitemapManager;
use Illuminate\Support\Facades\Cache;

class ClearCacheService
{
  public function clearPostCaches(Post $post)
  {
    $this->clearSitemapCaches();

    Cache::forget('active_hashtags');
    Cache::tags(["tag_posts_paginated","tag_posts_sliders"])->flush();

    Cache::tags(['category_posts_paginated','category_posts_sliders'])->flush();
    Cache::forget('categories');
    
    Cache::tags(["Not-following"])->flush();
    
    Cache::tags(["blog_posts_paginated"])->flush();

    Cache::tags(["post:{$post->id}:comments"])->flush();
        
    Cache::forget("post:{$post->id}:more_articles");

    Cache::forget("latest_blogs:except:{$post->id}");

    Cache::forget("home_feed_latest");
    
    Cache::tags(["search_posts"])->flush();
    
  }
  public function clearTagsCaches(Hashtag $hashtag)
  {
    $this->clearSitemapCaches();
    Cache::tags(["tag_posts_paginated",'tags_results',"tag_posts_sliders"])->flush();
    Cache::forget('active_hashtags');
  }

  public function clearCategoriesCaches(Category $category)
  {
     $this->clearSitemapCaches();
     Cache::tags(['category_posts_paginated','categories_type_results','category_posts_sliders'])->flush();
     Cache::forget('categories');
  }
  public function clearUserCaches()
  {
     Cache::tags(['users_results'])->flush();
     $this->clearSitemapCaches();
  }

  public function clearScrapedDataNews()
  {
    collect(['latest-news','latest-sources'])->each(fn ($key) => Cache::forget($key));
    Cache::tags(['news_paginated','news_type_results'])->flush();
  }

  /** Clear every cached sitemap URL after model changes. */
  public function clearSitemapCaches(): void
  {
    $prefix = (string) config('sitemap.cache.key', 'sitemap.');

    foreach (SitemapManager::getKeys() as $key) {
      foreach (SitemapManager::allowedExtensions() as $extension) {
        Cache::forget($prefix . $key . '.' . $extension);
      }
    }
  }
}
