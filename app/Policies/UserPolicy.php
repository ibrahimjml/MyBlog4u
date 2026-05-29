<?php

namespace App\Policies;

use App\Models\User;


class UserPolicy
{
   public function before(User $user, string $ability, ?User $model = null): bool|null
    {
        if ($user->hasRole(\App\Enums\UserRole::ADMIN->value)) {
            return true;
        }

        if ($model instanceof User && $model->hasRole(\App\Enums\UserRole::ADMIN->value)) {
            return in_array($ability, ['update', 'delete', 'block', 'role', 'updateAny', 'deleteAny'], true)
               ? false
               : null;
        }

        return null;
    }
    public function view(User $user, User $model): bool
    {
       return true; 
    }
    public function updateAny(User $user, User $model): bool
    {
      return $user->hasPermission('user.update');
    }
    public function update(User $user, User $model): bool
    {
      return $user->hasPermission('user.update') ||  $user->id === $model->id ;
    }
    public function updateImage(User $user, User $model): bool
    {
    return $user->hasPermission('user.updateImage') || $user->id === $model->id;
    }
    public function role(User $user, User $model): bool
    {
        return $user->hasPermission('user.role') && $user->id !== $model->id;
    }
   public function block(User $user, User $model): bool
    {
        return $user->hasPermission('user.block') && $user->id !== $model->id;
    }
    public function deleteAny(User $user, User $model): bool
    {
        return $user->hasPermission('user.delete') && $user->id !== $model->id;
    }
    public function delete(User $user, User $model): bool
    {
        return  $user->id === $model->id ;
    }
  

}
