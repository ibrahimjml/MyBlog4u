<?php
namespace App\Repositories\Eloquent;

use App\DTOs\BlogFilterDTO;
use App\Http\Resources\UserCardResource;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Hashtag;
use App\Models\Post;
use App\Models\User;
use App\Repositories\Interfaces\PostInterface;
use DateTimeInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;


class PostRepository implements PostInterface
{
    public function getPaginatedPosts(int $perPage, BlogFilterDTO $filter, int $page): LengthAwarePaginator
    {
       return Post::query()
                  ->published()
                  ->with([
                      'user' => function ($query) {
                          $query->select('id', 'name', 'username', 'avatar', 'bio')
                              ->withCardCounts();
                      },
                      'hashtags:id,name,is_featured', 
                      'categories:id,name,is_featured'
                  ])
                  ->withCount('likes', 'totalcomments')
                  ->blogFilter($filter)
                  ->paginate($perPage, ['*'], 'blog_page', $page)
                  ->appends($filter->query());
    }
    public function getBySearch($dto, int $limit): Collection
    {
        return Post::query()
                    ->published()
                    ->search($dto->search)
                    ->withCount(['likes', 'comments'])
                    ->with(['user:id,name,username,avatar','hashtags'])
                    ->limit($limit)
                    ->get();
    }
    public function getPopularTags(): Collection
    {
        return  Hashtag::query()
                     ->active()
                     ->has('posts')
                     ->withCount('posts')
                     ->orderByDesc('is_featured')
                     ->orderByDesc('posts_count')
                     ->get();
    }

    public function getCategories(): Collection
    {
      return  Category::query()
                    ->has('posts')
                    ->withCount('posts')
                    ->orderBy('is_featured','desc')
                    ->orderByDesc('posts_count')
                    ->get();
    }

    public function getWhoToFollow(int $userId): Collection
    {
       return User::query()
                   ->activated()
                   ->select('id','name','username','avatar','bio')
                   ->where('is_blocked', false)
                   ->where('id', '!=', $userId)
                   ->whereNotIn('id', auth()->user()->followings()->pluck('user_id'))
                   ->withCardCounts()
                   ->inRandomOrder()
                   ->take(5)
                   ->get();
    }

    public function getSavedPosts(array $ids, int $perPage = 5): LengthAwarePaginator
    {
      return Post::query()
                ->published()
                ->whereIn('id', $ids)
                ->withCount(['likes', 'comments'])
                ->with([
                    'user' => fn ($query) => $query->withCardCounts(),
                    'hashtags',
                ])
                ->paginate($perPage);
    }

    public function getRelatedArticles(Post $post): Collection
    {
         return Post::query()
                  ->published()
                  ->with(['user:id,name,username,avatar', 'categories:id,name', 'hashtags:id,name'])
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
      return Post::query()
                    ->published()
                    ->latest()
                    ->with(['user:id,name,username,avatar', 'categories:id,name', 'hashtags:id,name'])
                    ->withCount(['comments as total_comments_count' => function($query) {
                       $query->whereNull('parent_id'); 
                    }])
                   ->where('id', '!=', $post->id)
                   ->take(8)
                   ->get();
    }
    public function getLatestForHomeFeed()
    {
      return Post::query()
                    ->published()
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
        return Post::query()
                ->published()
                ->featured()
                ->with('hashtags:id,name')
                ->withCount( 'totalcomments')
                ->latest()
                ->take(7)
                ->get();
    }
    public function getMostTrendingTagFor(?DateTimeInterface $from = null): ?Hashtag
    {
        return Hashtag::query()
            ->trending($from)
            ->first();
    }

    public function getMostTrendingTag(?int $postsLimit = 3): array
    {
        $trendingTag = $this->getMostTrendingTagFor();

        if (! $trendingTag) {
            return ['tag' => null, 'posts' => collect(), 'avatars' => []];
        }

        $posts = Post::query()
            ->published()
            ->trendingHashtag($trendingTag->id)
            ->with([
                'user' => function ($query) {
                    $query->select('id', 'name', 'username', 'avatar', 'bio')
                        ->withCardCounts();
                },
                'hashtags',
            ])
            ->withCount('totalcomments')
            ->latest()
            ->when($postsLimit !== null, fn ($query) => $query->limit($postsLimit))
            ->get()
            ->each(function ($post) use ($trendingTag) {
                $post->setRelation('hashtags', $post->hashtags
                    ->sortByDesc(fn ($hashtag) => $hashtag->id === $trendingTag->id)
                    ->values());
            });

        return [
            'tag' => $trendingTag,
            'posts' => $posts,
            'avatars' => UserCardResource::collection(
                $posts->pluck('user')->filter()->unique('id')->take(3)->values()
            )->resolve(),
        ];
    }

    public function getTrendingTagsAllTime(?array $excludeTagIds, int $limit): Collection
    {
        $excludeTagIds = array_values(array_filter($excludeTagIds ?? []));

        $tags = Hashtag::query()
            ->trending()
            ->when($excludeTagIds, fn ($query, $ids) => $query->whereNotIn('hashtags.id', $ids))
            ->take($limit)
            ->get();

        if ($tags->isEmpty()) {
            return $tags;
        }

        $tagIds = $tags->pluck('id');

        $posts = Post::query()
            ->published()
            ->whereHas('hashtags', fn ($query) => $query->whereIn('hashtags.id', $tagIds))
            ->when($excludeTagIds, fn ($query, $ids) => $query->whereDoesntHave(
                'hashtags',
                fn ($query) => $query->whereIn('hashtags.id', $ids)
            ))
            ->with([
                'user' => fn ($query) => $query
                    ->select('id', 'name', 'username', 'avatar', 'bio')
                    ->withCardCounts(),
                'hashtags:id,name',
            ])
            ->latest()
            ->get()
            ->filter(fn ($post) => $post->user !== null);

        $cardsByTag = $posts
            ->flatMap(fn ($post) => $post->hashtags
                ->filter(fn ($hashtag) => $tagIds->contains($hashtag->id))
                ->map(fn ($hashtag) => [
                    $hashtag->id,
                    UserCardResource::make($post->user)->resolve(),
                ]))
            ->groupBy(fn ($pair) => $pair[0])
            ->map(fn ($pairs) => $pairs->pluck(1)->unique('username')->take(3)->values());

        $tags->each(fn ($tag) => $tag->setRelation('authorCards', $cardsByTag->get($tag->id, collect())));

        return $tags;
    }
     public function getPaginatedComments(Post $post, int $page, int $perPage): LengthAwarePaginator
   {
         $comments = Comment::query()
                        ->withNestedReplies(3)
                        ->addSelect(['post_user_id' => Post::select('user_id')
                        ->whereColumn('id', 'comments.post_id')
                        ->limit(1)
                              ])
                      ->where('post_id', $post->id)
                      ->whereNull('parent_id') 
                      ->latest()
                      ->paginate($perPage, ['*'], 'comments_page', $page);

         $this->hydrateCommentRelations($comments->getCollection());

         return $comments;
   }

   /**
    * Load every comment author from loaded tree, prevent nested replies requery again
    */
   private function hydrateCommentRelations(Collection $comments): void
   {
       $all = collect();

       $flatten = function (Collection $items) use (&$flatten, &$all): void {
           foreach ($items as $comment) {
               $all->push($comment);

               if ($comment->relationLoaded('replies')) {
                   $flatten($comment->replies);
               }
           }
       };

       $flatten($comments);

       $users = User::query()
           ->whereIn('id', $all->pluck('user_id')->filter()->unique()->all())
           ->select('id', 'name', 'username', 'avatar', 'bio')
           ->withCardCounts()
           ->get()
           ->keyBy('id');

       $link = function (Collection $items, ?Comment $parent = null) use (&$link, $users): void {
           foreach ($items as $comment) {
               if ($user = $users->get($comment->user_id)) {
                   $comment->setRelation('user', $user);
               }

               if ($parent !== null) {
                   $comment->setRelation('parent', $parent);
               }

               if ($comment->relationLoaded('replies')) {
                   $link($comment->replies, $comment);
               }
           }
       };

       $link($comments);
   }
}
