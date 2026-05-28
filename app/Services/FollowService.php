<?php
namespace App\Services;

use App\Enums\FollowerStatus;
use App\Events\FollowUserEvent;
use App\Models\User;
use App\Notifications\FollowersNotification;
use Illuminate\Notifications\DatabaseNotification;

class FollowService
{
 public function toggle(User $follower, User $user): ?int
    {
        $existing = $follower->allFollowings()
            ->where('user_id', $user->id)
            ->first();

        if ($existing) {
            $follower->allFollowings()->detach($user->id);
            $this->deleteFollowNotification($follower, $user);
            return null;
        }

        $status = $user->profile->is_public 
        ? FollowerStatus::ACCEPTED
        : FollowerStatus::PENDING;

        $follower->allFollowings()->attach($user->id, [
            'status' => $status->value,
            'created_at' => now(),
        ]);

        event(new FollowUserEvent($follower,$user,$status->value === FollowerStatus::ACCEPTED->value ? 'public' : 'private'));

        return $status->value;
    }
public function deleteFollowNotification($follower, $user){

    $notifiableIds = User::where('is_admin', true)
    ->pluck('id')
    ->push($user->id)
    ->unique();
 
      DatabaseNotification::where('type',FollowersNotification::class)
      ->whereIn('notifiable_id', $notifiableIds)
      ->whereJsonContains('data->follower_id', $follower->id)
      ->delete();
}
}