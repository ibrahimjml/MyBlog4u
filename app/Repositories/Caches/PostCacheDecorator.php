<?php

namespace App\Repositories\Caches;

use App\Repositories\Interfaces\PostInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class PostCacheDecorator implements PostInterface
{
   public function __construct(private PostInterface $repo){}

   public function getPaginatedPosts(int $perPage, string $sort, int $page): \Illuminate\Contracts\Pagination\LengthAwarePaginator
   {
      $key = "blog_posts:page:{$page}:perpage:{$perPage}:sort:{$sort}";
        return Cache::tags(["blog_posts_paginated"])->remember($key, 1800, fn() => $this->repo->getPaginatedPosts($perPage, $sort, $page));
   }
   
   public function getBySearch($dto, int $limit): Collection
   {
      $key = sprintf('posts:search:%s:%d', md5(strtolower(trim($dto->search))), $limit);
      return Cache::tags(["search_posts"])->remember($key,900, fn() => $this->repo->getBySearch($dto,$limit));
   }
   public function getCategories(): Collection
   {
      return Cache::remember('categories', 3600, fn() => $this->repo->getCategories());
   }

   public function getPopularTags(): Collection
   {
      return Cache::remember('active_hashtags', 3600, fn() => $this->repo->getPopularTags());
   }

   public function getWhoToFollow(int $userId): Collection
   {
       $key = "who_to_follow:{$userId}";
        return Cache::tags(["Not-following:{$userId}"])->remember($key, 1800, fn() => $this->repo->getWhoToFollow($userId));
   }

   public function getSavedPosts(array $ids, int $perPage = 5): \Illuminate\Contracts\Pagination\LengthAwarePaginator
   {
    return $this->repo->getSavedPosts($ids, $perPage);
   }
  
   public function getRelatedArticles(\App\Models\Post $post)
   {
       $Key = "post:{$post->id}:more_articles";
       return Cache::remember($Key, 1800, fn() => $this->repo->getRelatedArticles($post));
   }
   public function getLatestBlogs(\App\Models\Post $post)
   {
      $Key = "latest_blogs:except:{$post->id}";
      return Cache::remember($Key, 900, fn() => $this->repo->getLatestBlogs($post));
   }
   public function getLatestForHomeFeed()
   {
      $Key = "home_feed_latest";
      return Cache::remember($Key, 1600, fn() => $this->repo->getLatestForHomeFeed());
   }
   public function getPostLikes(\App\Models\Post $post)
   {
     return $this->repo->getPostLikes($post);
   }
   public function getFeaturedPosts()
   {
       return Cache::remember('featuredPosts', now()->addDay(),fn() => $this->repo->getFeaturedPosts());
   }
   public function getTrendingTagPosts()
   {
     return Cache::remember('trendingHashtag', now()->endOfDay(), fn() => $this->repo->getTrendingTagPosts());
   }
    public function getPaginatedComments(\App\Models\Post $post, int $page, int $perPage): \Illuminate\Contracts\Pagination\LengthAwarePaginator
   {
       $Key = "post:{$post->id}:comments:page:{$page}:perpage:{$perPage}";
        return  Cache::tags(["post_comments", "post:{$post->id}:comments"])->remember($Key, 1300, fn() => $this->repo->getPaginatedComments($post,$page,$perPage));
   }
}