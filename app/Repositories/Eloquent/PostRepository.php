<?php
namespace App\Repositories\Eloquent;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Hashtag;
use App\Models\Post;
use App\Models\User;
use App\Repositories\Interfaces\PostInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

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
    public function getBySearch( $dto,int $page, int $perpage): LengthAwarePaginator
    {
        $postsid = Post::search($dto->search)->get()->pluck('id');
        return  Post::published()
                    ->whereIn('id',$postsid)
                    ->withCount(['likes', 'comments'])
                    ->with(['user','hashtags'])
                    ->blogSort($dto->sort)
                    ->paginate($perpage,['*'],'search',$page)
                    ->withQueryString();
    }
    public function getPopularTags(): \Illuminate\Support\Collection
    {
        return  Hashtag::active()
                     ->withCount('posts')
                     ->get();
    }

    public function getCategories(): \Illuminate\Support\Collection
    {
      return  Category::withCount('posts')->get();
    }

    public function getWhoToFollow(int $userId): \Illuminate\Support\Collection
    {
       return User::where('id', '!=', $userId)
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

    public function getRelatedArticles(Post $post): \Illuminate\Support\Collection
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
                ->withCount( 'totalcomments')
                ->latest()
                ->take(7)
                ->get();
    }
    public function getTrendingTagPosts()
    {
      $trendingHashtag = Hashtag::active()
        ->withCount('posts')
        ->orderByDesc('posts_count')
        ->first();

    $latesttrend = collect();

    if ($trendingHashtag) {
        $latesttrend = Post::published()
            ->with(['user', 'hashtags', 'comments'])
            ->withCount('totalcomments')
            ->whereHas('hashtags', function ($query) use ($trendingHashtag) {
                $query->where('hashtags.id', $trendingHashtag->id);
            })
            ->oldest()
            ->take(3)
            ->get();
    }

    return [
        'trendingHashtag' => $trendingHashtag,
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