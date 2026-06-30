<?php

namespace App\Actions;

use App\Enums\NotificationType;
use App\Models\User;
use App\Notifications\FollowAcceptNotification;
use App\Traits\AdminNotificationGate;

class AcceptFollowAction
{
    use AdminNotificationGate;
    public function __construct(protected DeleteFollowRequestNotificationAction $deleteFollowRequestNotification){}
    public function execute(User $authUser, User $follower): void
    {
       $authUser->pendingFollowers()->updateExistingPivot($follower->id,
          [
            'status' => 1,
            'updated_at' => now(),
          ]);
        $this->deleteFollowRequestNotification->execute($authUser,$follower);
        $follower->notify( new FollowAcceptNotification($authUser, $follower, 'accepted'));
        User::where('is_admin', true)
            ->get()
            ->each(function ($admin) use ($authUser, $follower) {
                if ($this->allow($admin, NotificationType::FOLLOWACCEPT)) {
                    $admin->notify(new FollowAcceptNotification($authUser, $follower, 'accepted'));
                }
            });

    }
}
