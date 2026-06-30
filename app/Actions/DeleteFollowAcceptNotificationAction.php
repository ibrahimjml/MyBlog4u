<?php

namespace App\Actions;

use App\Models\User;
use App\Notifications\FollowAcceptNotification;
use Illuminate\Notifications\DatabaseNotification;

class DeleteFollowAcceptNotificationAction
{
    public static function execute(User $authUser, User $follower): void
    {
      DatabaseNotification::where('type', FollowAcceptNotification::class)
            ->where('notifiable_id', $follower->id)
            ->whereJsonContains('data->follower_id', $authUser->id)
            ->whereJsonContains('data->status', 'accepted')
            ->delete();
    }
}
