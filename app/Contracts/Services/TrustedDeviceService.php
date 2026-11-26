<?php

namespace App\Contracts\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Cookie;

interface TrustedDeviceService
{
    public function issue(User $user, Request $request): Cookie;

    public function isTrusted(Request $request, ?User $user): bool;

    public function listFor(User $user): Collection;

    public function forgetOne(User $user, int $deviceId, ?Request $request = null): void;

    public function forget(User $user): void;
}
