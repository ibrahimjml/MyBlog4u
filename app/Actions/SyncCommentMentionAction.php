<?php

namespace App\Actions;

use App\Models\Comment;
use App\Models\User;

class SyncCommentMentionAction
{
  public function execute(Comment $comment): bool
  {
    if (empty($comment->content)) {
      return false;
    }

    preg_match_all('/@\[(\w[\w.-]+)\]/', $comment->content, $matches);

    if (empty($matches[1])) {
      $comment->mentions()->sync([]);

      return false;
    }

    $userIds = User::whereIn('username', array_unique($matches[1]))
      ->pluck('id')
      ->all();

    $comment->mentions()->sync($userIds);
    return true;
  }
}
