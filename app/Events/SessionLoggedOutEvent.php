<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class SessionLoggedOutEvent
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly User $user
    ){}

    public static function dispatchForCurrentUser(): void
    {
         if (! $user = Auth::user()) {
          return;
        }

        static::dispatch($user);
    }
}
