<?php

namespace App\Actions;

use App\Enums\FollowerStatus;
use App\Events\FollowUserEvent;
use App\Models\User;

class FollowUserAction
{
  public function execute(User $follower, User $user): int
  {
    $status = $user->profile->is_public
      ? FollowerStatus::ACCEPTED
      : FollowerStatus::PENDING;

    $follower->allFollowings()->attach($user->id, [
      'status' => $status->value,
      'created_at' => now(),
    ]);

    event(new FollowUserEvent(
      $follower,
      $user,
      $status === FollowerStatus::ACCEPTED ? 'public' : 'private'
    ));

    return $status->value;
  }
}
