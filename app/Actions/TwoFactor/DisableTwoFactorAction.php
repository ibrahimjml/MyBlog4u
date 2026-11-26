<?php

namespace App\Actions\TwoFactor;

use App\Contracts\Services\TrustedDeviceService as TrustedDeviceContract;
use App\Models\User;
use Illuminate\Http\Request;

class DisableTwoFactorAction
{
    public function __construct(
        protected ConfirmTwoFactorAction $confirm,
        protected TrustedDeviceContract $trustedDevices,
    ) {}

    public function handle(User $user, string $code, Request $request): void
    {
        abort_unless($user->has_two_factor_enabled, 403);

        $this->confirm->handle($user, $code);

        $user->forceFill([
            'has_two_factor_enabled' => false,
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'recovery_codes_downloaded' => false,
        ])->save();

        $this->trustedDevices->forget($user);

    }
}
