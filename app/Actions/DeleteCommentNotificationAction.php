<?php

namespace App\Actions;

use App\Models\Comment;
use App\Notifications\CommentNotification;
use App\Notifications\RepliedCommentNotification;
use Illuminate\Notifications\DatabaseNotification;

class DeleteCommentNotificationAction
{
    public function execute(Comment $comment)
    {
      // auto delete comment when get deleted
    DatabaseNotification::where('type', CommentNotification::class)
      ->whereJsonContains('data->comment_id', $comment->id)
      ->delete();

    // auto delete reply notification if replier deleted his reply
    DatabaseNotification::where('type', RepliedCommentNotification::class)
      ->whereJsonContains('data->reply_id', $comment->id)
      ->orWhereJsonContains('data->comment_id', $comment->id)
      ->delete();
    }
}
