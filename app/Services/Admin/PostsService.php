<?php

namespace App\Services\Admin;

use App\Enums\PostStatus;
use App\Models\Post;
use Illuminate\Support\Fluent;

class PostsService
{
    public function getPosts(Fluent $filters)
    {
        return Post::query()
            ->with(['user', 'allHashtags', 'categories'])
            ->adminFilter($filters)
            ->withCount('totalcomments')
            ->paginate(7, ['*'], 'admin_posts')
            ->withQuerystring();
    }
    public function updateStatus(Post $post, PostStatus $status)
    {
        $post->published_at = null;
        $post->banned_at = null;
        $post->trashed_at = null;

        match ($status) {
            PostStatus::PUBLISHED => $post->published_at = now(),
            PostStatus::BANNED    => $post->banned_at = now(),
            default               => null,
        };

        $post->status = $status;
        $post->save();
    }

}
