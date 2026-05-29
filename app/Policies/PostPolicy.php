<?php

namespace App\Policies;

use App\Enums\PostStatus;
use App\Models\Post;
use App\Models\PostModeration;
use App\Models\User;
use Illuminate\Auth\Access\Response;


class PostPolicy
{
  public function before(User $user, string $ability, mixed $model = null): bool|null
    {   
      if($ability === 'report'){
        return null;
      }
        if ($user->hasRole(\App\Enums\UserRole::ADMIN->value) ) {
            return true; 
        }
      if ($model instanceof Post && $model->user->hasRole(\App\Enums\UserRole::ADMIN->value)) {
           return in_array($ability, ['update', 'delete', 'feature', 'updateAny', 'deleteAny'], true)
               ? false
               : null;
         }
        return null; 
    }
    public function viewAny(User $user, Post $post): bool
    {
      return $user->hasPermission('post.viewAny');
    }
    public function view(User $user, Post $post): bool
    {
        if ($post->status !== PostStatus::PUBLISHED) {
        return $user->id === $post->user_id
            || $user->hasPermission('post.viewAny');
      }

    return true;
    }
    public function create(User $user, ?Post $post = null): bool
    {
      $rules = PostModeration::rules();

      if ($rules->enable_post_submission) {
        return true;
      }

      return $user->hasPermission('post.create');
    }

    public function bypassModeration(User $user): bool
    {
      return $user->hasPermission('post.create');
    }
    
    public function updateAny(User $user, Post $post): bool
    {
      return  $user->hasPermission('post.update');
    }
    public function update(User $user, Post $post): bool
    {
      return $user->hasPermission('post.update') || $user->id === $post->user_id;
    }
  public function feature(User $user, Post $post): bool
    {
      return  $user->hasPermission('post.feature');
    }
  public function report(User $user, Post $post): bool
  {
    return $user->id !== $post->user_id;
  }
    public function deleteAny(User $user, Post $post): bool
    {
      return  $user->hasPermission('post.delete');
    }
    public function delete(User $user, Post $post): bool
    {
      return $user->hasPermission('post.delete') || $user->id === $post->user_id;
    }

}
