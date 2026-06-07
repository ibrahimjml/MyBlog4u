<?php

namespace App\Services\User;

use App\Models\User;
use Carbon\Carbon;
use App\Models\Post;
use App\Models\Comment;

class UserActivityService
{
    public function getUserActivities(User $user)
    {
       if (! $user instanceof User) {
        throw new \InvalidArgumentException('UserActivityService expects a User model.');
    }
        $posts = Post::published()->where('user_id', $user->getKey())
                    ->select('title', 'created_at', 'slug')
                    ->get()
                    ->map(function ($post) {
                        return [
                            'type' => 'Posted',
                            'title' => $post->title,
                            'slug' => $post->slug,
                            'date' => $post->created_at,
                        ];
                    });

        $comments = Comment::where('user_id', $user->getKey())
                    ->with('post:id,slug,title')
                    ->select('id', 'created_at', 'parent_id', 'post_id')
                    ->get()
                    ->map(function ($comment) {

                        if (!isset($comment->post) || empty($comment->post) || !isset($comment->post->slug)) {
                            return null;
                        }

                        return [
                            'type' => $comment->parent_id ? 'Replied' : 'Commented',
                            'comment_id' => $comment->id,
                            'slug' => $comment->post->slug,
                            'post_title' => $comment->post->title,
                            'date' => $comment->created_at,
                        ];
                    })->filter();

        return $posts->merge($comments)
                    ->sortByDesc(fn ($activity) => $activity['date'] instanceof Carbon ? $activity['date']->getTimestamp() : strtotime($activity['date']))
                    ->groupBy(fn ($activity) => Carbon::parse($activity['date'])->format('M j Y'));
    }
}
