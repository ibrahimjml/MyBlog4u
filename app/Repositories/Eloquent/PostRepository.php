<?php
namespace App\Repositories\Eloquent;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Hashtag;
use App\Models\Post;
use App\Models\User;
use App\Repositories\Interfaces\PostInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;


class PostRepository implements PostInterface
{
    public function getPaginatedPosts(int $perPage, string $sort, int $page): LengthAwarePaginator
    {
       return Post::published()
                  ->with(['user:id,username,avatar', 'hashtags:id,name,is_featured', 'categories:id,name,is_featured'])
                  ->withCount('likes', 'totalcomments')
                  ->blogSort($sort)
                  ->paginate($perPage, ['*'], 'blog_page', $page)
                  ->withQueryString();
    }
    public function getBySearch($dto, int $limit): Collection
    {
        return Post::published()
                    ->search($dto->search)
                    ->withCount(['likes', 'comments'])
                    ->with(['user:id,name,username,avatar','hashtags'])
                    ->limit($limit)
                    ->get();
    }
    public function getPopularTags(): Collection
    {
        return  Hashtag::active()
                     ->has('posts')
                     ->withCount('posts')
                     ->orderByDesc('is_featured')
                     ->orderByDesc('posts_count')
                     ->get();
    }

    public function getCategories(): Collection
    {
      return  Category::has('posts')
                    ->withCount('posts')
                    ->orderBy('is_featured','desc')
                    ->orderByDesc('posts_count')
                    ->get();
    }

    public function getWhoToFollow(int $userId): Collection
    {
       return User::activated()
                   ->where('id', '!=', $userId)
                   ->whereNotIn('id', auth()->user()->followings()->pluck('user_id'))
                   ->inRandomOrder()
                   ->take(5)
                   ->get();
    }

    public function getSavedPosts(array $ids, int $perPage = 5): LengthAwarePaginator
    {
      return Post::published()
                ->whereIn('id', $ids)
                ->withCount(['likes', 'comments'])
                ->with(['user', 'hashtags'])
                ->paginate($perPage);
    }

    public function getRelatedArticles(Post $post): Collection
    {
         return Post::query()
                  ->published()
                  ->with(['user:id,name,username,avatar'])
                   ->withCount(['comments as total_comments_count' => function($query) {
                         $query->whereNull('parent_id'); 
                      }])
                  ->where('user_id', $post->user_id)
                  ->where('id', '!=', $post->id)
                  ->take(3)
                  ->get();
          
    }
    public function getLatestBlogs(Post $post)
    {
      return Post::published()
                    ->latest()
                    ->with(['user:id,name,username,avatar'])
                    ->withCount(['comments as total_comments_count' => function($query) {
                       $query->whereNull('parent_id'); 
                    }])
                   ->where('id', '!=', $post->id)
                   ->take(8)
                   ->get();
    }
    public function getLatestForHomeFeed()
    {
      return Post::published()
                    ->latest()
                    ->with(['user:id,name,username,avatar', 'hashtags:id,name'])
                   ->take(3)
                   ->get();
    }

    public function getPostLikes(Post $post)
    {
       return $post->likes()
            ->with(['user' => function($query) {
                $query->select('id', 'name', 'username', 'avatar');
                 }])
            ->get();
    }
    public function getFeaturedPosts()
    {
        return Post::published()
                ->featured()
                ->with('hashtags:id,name')
                ->withCount( 'totalcomments')
                ->latest()
                ->take(7)
                ->get();
    }
    public function getTrendingTagPosts()
    {
        $trendingHashtags = Hashtag::active()
               ->withCount('posts')
               ->orderByDesc('posts_count')
               ->take(5) 
               ->get();

         $trendingHashtagIds = $trendingHashtags->pluck('id')->toArray();

         $latesttrend = collect();

       if (!empty($trendingHashtagIds)) {
           $latesttrend = Post::published()
               ->with(['user', 'hashtags', 'comments'])
               ->withCount('totalcomments')
               ->whereHas('hashtags', function ($query) use ($trendingHashtagIds) {
                   $query->whereIn('hashtags.id', $trendingHashtagIds);
               })
               // Order posts by the priority
               ->orderByRaw('FIELD((SELECT hashtag_id FROM post_hashtag WHERE post_id = posts.id AND hashtag_id IN (' . implode(',', $trendingHashtagIds) . ') LIMIT 1), ' . implode(',', $trendingHashtagIds) . ')')
               ->take(3)
               ->get();
       }
       
       return [
           'trendingHashtag' => $trendingHashtags->first(),
           'latestTrend' => $latesttrend,
       ];
    }
     public function getPaginatedComments(Post $post, int $page, int $perPage): LengthAwarePaginator
   {
         return Comment::withNestedReplies(3)
                        ->addSelect(['post_user_id' => Post::select('user_id')
                        ->whereColumn('id', 'comments.post_id')
                        ->limit(1)
                              ])
                      ->where('post_id', $post->id)
                      ->whereNull('parent_id') 
                      ->latest()
                      ->paginate($perPage, ['*'], 'comments_page', $page);
   }
} 