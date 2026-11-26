<?php

namespace App\Support;

final class TrustedDeviceTokenSupport
{
    public const COOKIE = '2fa_trusted';

    /**
     * @return array{string, string} [$selector, $validator]
     */
    public static function generate(): array
    {
        return [bin2hex(random_bytes(16)), bin2hex(random_bytes(32))];
    }

    public static function hash(string $selector, string $validator): string
    {
        return hash('sha256', $selector . $validator);
    }
}
