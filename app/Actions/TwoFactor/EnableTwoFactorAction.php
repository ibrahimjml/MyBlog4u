<?php

namespace App\Actions\TwoFactor;

use App\Models\User;
use Illuminate\Validation\ValidationException;

class EnableTwoFactorAction
{
    public function __construct(protected ConfirmTwoFactorAction $confirm) {}

    public function handle(User $user, string $code): void
    {
        if (! $user->two_factor_secret) {
            throw ValidationException::withMessages([
                'code' => __('Start the two-factor authentication setup again.'),
            ]);
        }

        $this->confirm->handle($user, $code);

        $user->forceFill([
            'has_two_factor_enabled' => true,
        ])->save();
    }
}
