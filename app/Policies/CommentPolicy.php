<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;


class CommentPolicy
{
   public function before(User $user, string $ability, mixed $model = null): bool|null
    {
        if (
            $ability === 'report'
            && $model instanceof Comment
            && (int) $model->user_id === (int) $user->id
        ) {
            return false;
        }

        if ( $user->hasRole(\App\Enums\UserRole::ADMIN->value)) {
            return true; 
        }

        if($model instanceof Comment && $model->user->hasRole(\App\Enums\UserRole::ADMIN->value)) {
            return in_array($ability, ['edit', 'delete','report'], true)
               ? false
               : null;
         }

        return null; 
    }
    public function edit(User $user, Comment $comment): bool
    {
      return $user->hasPermission('comment.edit') || $user->id === $comment->user_id;
    }

    public function delete(User $user, Comment $comment): bool
    {
      return $user->hasPermission('comment.delete') || $user->id === $comment->user_id;
    }
    
    public function report(User $user, Comment $comment): bool
    {
       return $user->id !== $comment->user_id;
    }
  
}
