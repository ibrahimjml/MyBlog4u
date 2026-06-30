<?php

namespace App\Actions;

use App\Models\User;
use App\Notifications\FollowersNotification;
use Illuminate\Notifications\DatabaseNotification;

class DeleteFollowNotificationAction
{
  public function execute(User $follower, User $user): void
  {
    $notifiableIds = User::where('is_admin', true)
      ->pluck('id')
      ->push($user->id)
      ->unique();

    DatabaseNotification::where('type', FollowersNotification::class)
      ->whereIn('notifiable_id', $notifiableIds)
      ->whereJsonContains('data->follower_id', $follower->id)
      ->delete();
  }
}
