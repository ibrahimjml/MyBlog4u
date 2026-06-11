<?php

namespace App\Listeners;

use App\Enums\NotificationType;
use App\Events\MentionedUserEvent;
use App\Models\User;
use App\Notifications\SendMentionedUsersNotification;
use App\Traits\AdminNotificationGate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendMentionedNotification implements ShouldQueue
{
    use AdminNotificationGate;
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
  public function handle(MentionedUserEvent $event): void
{
    $comment = $event->comment;
    $commenter = $comment->user;

    $mentionedUsers = $comment->mentions()
        ->where('users.id', '!=', $commenter->id)
        ->get();

    foreach ($mentionedUsers as $user) {
        $user->notify(new SendMentionedUsersNotification($comment, $commenter, $user, $comment->post));
    }

    $admins = User::where('is_admin', true)->get();

    foreach ($admins as $admin) {

        if ($this->allow($admin, NotificationType::MENTION)) {

            $admin->notify(new SendMentionedUsersNotification($comment, $commenter, null, $comment->post));
        }
    }
}

}
