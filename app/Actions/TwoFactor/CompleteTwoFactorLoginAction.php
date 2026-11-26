<?php

namespace App\Actions\TwoFactor;

use App\Models\User;

class CompleteTwoFactorLoginAction
{
    public function handle(User $user, bool $remember): void
    {
        auth()->login($user, $remember);

        session()->regenerate();
    }
}
