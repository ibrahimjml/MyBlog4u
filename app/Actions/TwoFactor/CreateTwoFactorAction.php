<?php

namespace App\Actions\TwoFactor;

use App\Contracts\Services\TwoFactorService;
use App\Models\User;

class CreateTwoFactorAction
{
    public function __construct(
        protected TwoFactorService $twoFactor,
        protected RecoveryCodeAction $recovery,
    ) {}

    public function handle(User $user): string
    {
        abort_if($user->has_two_factor_enabled, 403);

        $secret = $this->twoFactor->generateSecretKey();

        $user->forceFill([
            'two_factor_secret' => $secret,
            'two_factor_recovery_codes' => $this->recovery->hashSet($this->recovery->generateSet()),
            'recovery_codes_downloaded' => false,
        ])->save();

        return $secret;
    }
}
