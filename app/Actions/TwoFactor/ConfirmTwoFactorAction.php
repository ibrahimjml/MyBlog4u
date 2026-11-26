<?php

namespace App\Actions\TwoFactor;

use App\Models\User;
use Illuminate\Validation\ValidationException;
use App\Contracts\Services\TwoFactorService;

class ConfirmTwoFactorAction
{
    public function __construct(
        protected RecoveryCodeAction $recovery,
        protected TwoFactorService $twoFactor,
    ) {}

    public function handle(User $user, string $code): void
    {
        if (! $user->two_factor_secret) {
            throw ValidationException::withMessages([
                'code' => __('Two-factor authentication is not set up.'),
            ]);
        }

        if ($this->twoFactor->verify($user->two_factor_secret, $code)) {
            return;
        }

        if ($this->recovery->verify($user, $code)) {
            return;
        }

        throw ValidationException::withMessages([
            'code' => __('The provided two-factor authentication code is invalid.'),
        ]);
    }
}
