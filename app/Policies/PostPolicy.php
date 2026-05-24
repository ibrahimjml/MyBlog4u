<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;


class PostPolicy
{
  public function before(User $user, string $ability,?Post $model): bool|null
    {   
      if($ability === 'report'){
        return null;
      }
        if ($user->hasRole(\App\Enums\UserRole::ADMIN->value) ) {
            return true; 
        }
      if ($model instanceof Post && $model->user->hasRole(\App\Enums\UserRole::ADMIN->value)) {
           return false;
         }
        return null; 
    }
    public function viewAny(User $user, Post $post): bool
    {
      return $user->hasPermission('post.viewAny');
    }
    public function view(User $user, Post $post): bool
    {
      return $user->hasPermission('post.viewAny') || $user->id === $post->user_id;
    }
    public function create(User $user, Post $post): bool
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
