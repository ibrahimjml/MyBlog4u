<?php

namespace App\Actions;

use App\Models\User;
use App\Notifications\FollowersNotification;
use Illuminate\Notifications\DatabaseNotification;

class DeleteFollowRequestNotificationAction
{
    public function execute(User $authUser, User $follower): void
    {
      DatabaseNotification::where('type', FollowersNotification::class)
            ->where('notifiable_id', $authUser->id)
            ->whereJsonContains('data->follower_id', $follower->id)
            ->whereJsonContains('data->status', 'private')
            ->delete();
    }
}
