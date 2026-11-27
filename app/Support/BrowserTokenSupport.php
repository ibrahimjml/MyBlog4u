<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Cookie;

final class BrowserTokenSupport
{
    public const COOKIE = 'active_session_browser';

    private const LIFETIME_MINUTES = 60 * 24 * 365;

    public static function fromRequest(Request $request): ?string
    {
        $token = $request->cookie(self::COOKIE);

        return is_string($token) && $token !== '' ? $token : null;
    }

    public static function generate(): string
    {
        return Str::random(64);
    }

    public static function hash(?string $token): ?string
    {
        return $token !== null && $token !== '' ? hash('sha256', $token) : null;
    }

    public static function cookie(string $token): Cookie
    {
        return cookie(
            self::COOKIE,
            $token,
            self::LIFETIME_MINUTES,
            config('session.path'),
            config('session.domain'),
            config('session.secure'),
            true,
            false,
            config('session.same_site'),
        );
    }

    public static function forgetCookie(): Cookie
    {
        return cookie()->forget(self::COOKIE);
    }
}
