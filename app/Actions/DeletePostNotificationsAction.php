<?php

namespace App\Actions;

use App\Models\Post;
use App\Notifications\FollowingPostCreatedNotification;
use Illuminate\Notifications\DatabaseNotification;

class DeletePostNotificationsAction
{
    public function execute(Post $post): void
    {
        DatabaseNotification::where('type', FollowingPostCreatedNotification::class)
            ->whereJsonContains('data->postedby_id', $post->user_id)
            ->whereJsonContains('data->post_id', $post->id)
            ->delete();
    }
}
