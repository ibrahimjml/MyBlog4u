<?php

namespace App\Listeners;

use App\Enums\NotificationType;
use App\Events\NewRegistered;
use App\Models\User;
use App\Notifications\NewRegisteredNotification;
use App\Traits\AdminNotificationGate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyAdminNewUser implements ShouldQueue
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
    public function handle(NewRegistered $event): void
    {
        $admin = User::where('is_admin',true)->first();
        if($admin && $this->allow($admin,NotificationType::NEWUSER)){
          $admin->notify(new NewRegisteredNotification($event->user));
        }
    }
}
