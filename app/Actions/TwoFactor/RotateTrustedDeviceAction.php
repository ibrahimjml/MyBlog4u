<?php

namespace App\Actions\TwoFactor;

use App\Models\TwoFactorTrustedDevice;
use App\Models\User;

class RotateTrustedDeviceAction
{
    public function __construct(
        private readonly CreateDeviceCookieAction $createCookie,
    ) {}

    public function handle(User $user): void
    {
        $device = $this->currentDevice($user);

        if ($device === null || ! $device->isStale()) {
            return;
        }

        $hash = $this->createCookie->handle($device);

        $device->update([
            'token_hash' => $hash,
            'last_used_at' => now(),
        ]);
    }

    protected function currentDevice(User $user): ?TwoFactorTrustedDevice
    {
        $deviceId = session('2fa:current_device_id');

        return $deviceId
            ? $user->twoFactorTrustedDevice()->find($deviceId)
            : null;
    }
}
