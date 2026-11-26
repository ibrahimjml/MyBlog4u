<?php

namespace App\Services\User;

use App\Actions\TwoFactor\CreateDeviceCookieAction;
use App\Actions\TwoFactor\CreateTrustedDeviceAction;
use App\Actions\TwoFactor\RotateTrustedDeviceAction;
use App\Contracts\Services\TrustedDeviceService as TrustedDeviceContract;
use App\Models\TwoFactorTrustedDevice;
use App\Models\User;
use App\Support\TrustedDeviceTokenSupport;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Cookie as SymfonyCookie;

class TrustedDeviceService implements TrustedDeviceContract
{
    protected int $days = 30;

    public function __construct(
        protected CreateTrustedDeviceAction $createDevice,
        protected CreateDeviceCookieAction $createCookie,
        protected RotateTrustedDeviceAction $rotateDevice,
    ) {}

    public function issue(User $user, Request $request): SymfonyCookie
    {
        $expiresAt = now()->addDays($this->days);

        [$cookie, $hash] = $this->createCookie->create($expiresAt);

        $device = $this->createDevice->handle($user, $request, $hash, $expiresAt);

        session()->put(['2fa:current_device_id' => $device->id]);

        return $cookie;
    }

    public function isTrusted(Request $request, ?User $user): bool
    {
        if (!$user || !$raw = $request->cookie(TrustedDeviceTokenSupport::COOKIE)) {
            return false;
        }

        [$selector, $validator] = array_pad(explode(':', $raw, 2), 2, null);
        if (!$selector || !$validator) {
            return false;
        }

        $device = TwoFactorTrustedDevice::findValidForUser($user, $selector, $validator);

        if (!$device) {
            return false;
        }

        session()->put(['2fa:current_device_id' => $device->id]);

        if ($device->isStale()) {
            $this->rotateDevice->handle($user);
        } else {
            $device->update(['last_used_at' => now()]);
        }

        return true;
    }

    public function listFor(User $user): Collection
    {
        $currentDeviceId = session('2fa:current_device_id');

        return $user->twoFactorTrustedDevice()
            ->orderByDesc('last_used_at')
            ->get()
            ->map(function ($device) use ($currentDeviceId) {
                $device->isCurrent = $currentDeviceId !== null && $device->id === $currentDeviceId;

                return $device->makeHidden('token_hash');
            })
            ->values();
    }

    public function forgetOne(User $user, int $deviceId, ?Request $request = null): void
    {
        $device = $user->twoFactorTrustedDevice()->where('id', $deviceId)->first();

        if (!$device) {
            return;
        }

        $currentHash = $this->currentCookieHash($request);

        if ($currentHash !== null && hash_equals($device->token_hash, $currentHash)) {
            Cookie::queue(Cookie::forget(TrustedDeviceTokenSupport::COOKIE));
        }

        $device->delete();
    }

    public function forget(User $user): void
    {
        $user->twoFactorTrustedDevice()->delete();
        Cookie::queue(Cookie::forget(TrustedDeviceTokenSupport::COOKIE));
        session()->forget('2fa:current_device_id');
    }

    protected function currentCookieHash(?Request $request): ?string
    {
        if (!$request || !$raw = $request->cookie(TrustedDeviceTokenSupport::COOKIE)) {
            return null;
        }

        [$selector, $validator] = array_pad(explode(':', $raw, 2), 2, null);

        if (!$selector || !$validator) {
            return null;
        }

        return TrustedDeviceTokenSupport::hash($selector, $validator);
    }
}
