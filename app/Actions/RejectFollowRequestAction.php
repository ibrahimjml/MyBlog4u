<?php

namespace App\Actions;

use App\Models\User;

class RejectFollowRequestAction
{
    public function __construct(protected DeleteFollowRequestNotificationAction $deleteFollowRequestNotificationAction){}
    public function execute(User $authUser, User $follower): void
    {
      $authUser->pendingFollowers()->detach($follower->id);
      $this->deleteFollowRequestNotificationAction->execute($authUser,$follower);
    }
}
