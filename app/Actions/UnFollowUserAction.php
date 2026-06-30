<?php

namespace App\Actions;

use App\Models\User;

class UnFollowUserAction
{
    public function __construct(protected DeleteFollowNotificationAction $deleteNotification) {}
    public function execute(User $follower, User $user): void
    {
      $follower->allFollowings()->detach($user->id);
      $this->deleteNotification->execute($follower, $user);
    }
}
