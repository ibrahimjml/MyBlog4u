<?php

namespace App\Actions;

use App\Models\Comment;
use App\Notifications\SendMentionedUsersNotification;
use Illuminate\Notifications\DatabaseNotification;

class DeleteMentionNotificationAction
{
    public function execute(Comment $comment)
    {
      DatabaseNotification::where('type', SendMentionedUsersNotification::class)
        ->whereJsonContains('data->comment_id', $comment->id)
        ->delete();
    }
}
