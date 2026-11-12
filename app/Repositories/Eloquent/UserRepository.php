<?php

namespace App\Repositories\Eloquent;

use App\Enums\UserActivityType;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use Carbon\Carbon;
use App\Repositories\Interfaces\UserInterface;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Fluent;

class UserRepository implements UserInterface
{
   public function getBySearch( $dto, int $limit): Collection 
    {
        return User::query()
            ->activated()
            ->where('is_blocked',false)
            ->search($dto->search)
            ->limit($limit)
            ->get();
            
    }

    public function getPaginatedArticles(User $user, int $perPage, int $page): LengthAwarePaginator
    {
        return $user->posts()
               ->published()
               ->selectRaw('
                   posts.*,
                   CASE
                       WHEN posts.is_pinned = 1 THEN posts.pinned_at
                       ELSE posts.created_at
                   END AS sort_date
               ')
               ->with(
                   'user:id,name,username,avatar',
                   'hashtags:id,name'
               )
               ->orderByDesc('sort_date')
               ->paginate($perPage, ['*'], 'home_page', $page);
    }

    public function getUserActivities(
        User $user,
        ?UserActivityType $type,
        int $perPage,
        int $page
    ): LengthAwarePaginator {

        $posts = collect();

        if ($type === null || $type === UserActivityType::Posted) {
            $posts = Post::query()
                ->published()
                ->where('user_id', $user->getKey())
                ->select('id', 'title', 'slug', 'image_path', 'created_at', 'updated_at')
                ->get()
                ->map(fn (Post $post) => [
                    'type'      => UserActivityType::Posted,
                    'title'     => $post->title,
                    'slug'      => $post->slug,
                    'image_url' => $post->image_url,
                    'date'      => $post->created_at,
                ]);
        }

        $comments = collect();

        if ($type === null || $type === UserActivityType::Commented || $type === UserActivityType::Replied) {
            $comments = Comment::query()
                ->where('user_id', $user->getKey())
                ->whereHas('post')
                ->with('post:id,slug,title,image_path')
                ->select('id', 'created_at', 'parent_id', 'post_id')
                ->when($type === UserActivityType::Commented, fn ($q) => $q->whereNull('parent_id'))
                ->when($type === UserActivityType::Replied,   fn ($q) => $q->whereNotNull('parent_id'))
                ->get()
                ->filter(fn (Comment $comment) => $comment->post !== null)
                ->map(fn (Comment $comment) => [
                    'type'       => $comment->parent_id
                        ? UserActivityType::Replied
                        : UserActivityType::Commented,
                    'comment_id' => $comment->id,
                    'slug'       => $comment->post->slug,
                    'post_title' => $comment->post->title,
                    'image_url'  => $comment->post->image_url,
                    'date'       => $comment->created_at,
                ]);
        }

        $all = $posts->concat($comments)
            ->sortByDesc(fn (array $activity) => $activity['date']->getTimestamp())
            ->values();

        $total  = $all->count();
        $offset = max(0, ($page - 1) * $perPage);
        $items  = $all->slice($offset, $perPage)->values();

        $grouped = $items->groupBy(
        fn (array $activity) => Carbon::parse($activity['date'])->format('M j Y')
       );

        return new Paginator(
            $grouped,
            $total,
            $perPage,
            $page,
            [
                'path'     => request()->url(),
                'query'    => request()->query(),
                'pageName' => 'activity_page',
            ]
        );
    }

    public function getAllPosts(User $user, Fluent $sort): LengthAwarePaginator
    {
        return $user->posts()
             ->withCount(['likes', 'comments'])
             ->with(['hashtags','categories'])
             ->DashboardFilter($sort)
             ->paginate(10)
             ->withQueryString();
    }
}