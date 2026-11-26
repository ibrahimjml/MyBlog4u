<?php

namespace App\Actions\TwoFactor;

use App\Models\TwoFactorTrustedDevice;
use App\Models\User;
use App\Support\UserAgentSupport;
use Illuminate\Http\Request;

class CreateTrustedDeviceAction
{
    public function handle(
        User $user,
        Request $request,
        string $tokenHash,
        \DateTimeInterface $expiresAt,
    ): TwoFactorTrustedDevice {
        return $user->twoFactorTrustedDevice()->create([
            'token_hash' => $tokenHash,
            'device_name' => UserAgentSupport::describe($request->userAgent()),
            'user_agent' => substr((string) $request->userAgent(), 0, 255),
            'ip' => $request->ip(),
            'last_used_at' => now(),
            'expires_at' => $expiresAt,
        ]);
    }
}
