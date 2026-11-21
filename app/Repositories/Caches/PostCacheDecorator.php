<?php

namespace App\Repositories\Caches;

use App\DTOs\BlogFilterDTO;
use App\Enums\Blog\BlogTab;
use App\Models\Hashtag;
use App\Models\Post;
use App\Repositories\Interfaces\PostInterface;
use DateTimeInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class PostCacheDecorator implements PostInterface
{
   public function __construct(private PostInterface $repo){}

   public function getPaginatedPosts(int $perPage, BlogFilterDTO $filter, int $page): LengthAwarePaginator
   {
      $viewer = $filter->tab === BlogTab::FOLLOWING
         ? 'user:' . (auth()->id() ?? 'guest')
         : 'public';
      $key = "blog_posts:page:{$page}:perpage:{$perPage}:viewer:{$viewer}:{$filter->cacheKey()}";
        return Cache::tags(["blog_posts_paginated"])->remember($key, 1800, fn() => $this->repo->getPaginatedPosts($perPage, $filter, $page));
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
        return Cache::tags(['who_to_follow', "Not-following:{$userId}"])->remember($key, 1800, fn() => $this->repo->getWhoToFollow($userId));
   }

   public function getSavedPosts(array $ids, int $perPage = 5): LengthAwarePaginator
   {
    return $this->repo->getSavedPosts($ids, $perPage);
   }
  
   public function getRelatedArticles(Post $post)
   {
       $Key = "post:{$post->id}:more_articles";
       return Cache::tags(['more_articles'])->remember($Key, 1800, fn() => $this->repo->getRelatedArticles($post));
   }
   public function getLatestBlogs(Post $post)
   {
      $Key = "latest_blogs:except:{$post->id}";
      return Cache::tags(['latest_blogs'])->remember($Key, 900, fn() => $this->repo->getLatestBlogs($post));
   }
   public function getLatestForHomeFeed()
   {
      $Key = "home_feed_latest";
      return Cache::remember($Key, 1600, fn() => $this->repo->getLatestForHomeFeed());
   }
   public function getPostLikes(Post $post)
   {
     return $this->repo->getPostLikes($post);
   }
   public function getFeaturedPosts()
   {
       return Cache::remember('featuredPosts', now()->addDay(),fn() => $this->repo->getFeaturedPosts());
   }
   public function getMostTrendingTag(?int $postsLimit = 3): array
   {
      $key = 'trending_tag:limit:' . ($postsLimit ?? 'all');
      return Cache::tags(['trending_tags'])->remember($key, now()->endOfDay(), fn() => $this->repo->getMostTrendingTag($postsLimit));
   }

   public function getMostTrendingTagFor(?DateTimeInterface $from = null): ?Hashtag
   {
      $key = 'trending_tag:most:' . ($from?->format('Y-m-d H:i:s') ?? 'all-time');
      return Cache::tags(['trending_tags'])->remember($key, now()->endOfDay(), fn() => $this->repo->getMostTrendingTagFor($from));
   }

   public function getTrendingTagsAllTime(?array $excludeTagIds, int $limit): Collection
   {
      $excludeTagIds = array_values(array_filter($excludeTagIds ?? []));
      sort($excludeTagIds);

      $key = sprintf('trending_tags_all_time:%d:%s', $limit, md5(implode(',', $excludeTagIds)));
      return Cache::tags(['trending_tags'])->remember($key, now()->endOfDay(), fn() => $this->repo->getTrendingTagsAllTime($excludeTagIds ?: null, $limit));
   }

    public function getPaginatedComments(Post $post, int $page, int $perPage): LengthAwarePaginator
   {
       $Key = "post:{$post->id}:comments:page:{$page}:perpage:{$perPage}";
        return  Cache::tags(["post_comments", "post:{$post->id}:comments"])->remember($Key, 1300, fn() => $this->repo->getPaginatedComments($post,$page,$perPage));
   }
}