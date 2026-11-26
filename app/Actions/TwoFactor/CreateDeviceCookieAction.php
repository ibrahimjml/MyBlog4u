<?php

namespace App\Actions\TwoFactor;

use App\Models\TwoFactorTrustedDevice;
use App\Support\TrustedDeviceTokenSupport;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Cookie as SymfonyCookie;

class CreateDeviceCookieAction
{
    public function handle(TwoFactorTrustedDevice $device): string
    {
        [$cookie, $hash] = $this->create($device->expires_at);

        Cookie::queue($cookie);

        return $hash;
    }

    /**
     * @return array{0: SymfonyCookie, 1: string}
     */
    public function create(\DateTimeInterface $expiresAt): array
    {
        [$selector, $validator] = TrustedDeviceTokenSupport::generate();

        $minutes = max(1, (int) now()->diffInMinutes($expiresAt));

        return [
            $this->cookie($selector, $validator, $minutes),
            TrustedDeviceTokenSupport::hash($selector, $validator),
        ];
    }

    private function cookie(string $selector, string $validator, int $minutes): SymfonyCookie
    {
        return cookie(
            TrustedDeviceTokenSupport::COOKIE,
            "{$selector}:{$validator}",
            $minutes,
            null,
            null,
            true,
            true,
            false,
            'strict'
        );
    }
}
