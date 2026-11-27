<?php

namespace App\Actions\ActiveSession;

use App\Events\SessionLoggedOutEvent;
use App\Models\User;

class MarkActiveSessionLoggedOutAction
{
    public function handle(User $user): void
    {
        event(new SessionLoggedOutEvent($user));
    }
}
