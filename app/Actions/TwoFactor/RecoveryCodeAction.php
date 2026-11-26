<?php

namespace App\Actions\TwoFactor;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RecoveryCodeAction
{
    public const COUNT = 8;

    public const LENGTH = 10;

    public function generate(): string
    {
        return strtoupper(Str::random(self::LENGTH));
    }

    public function generateSet(): array
    {
        return Collection::times(self::COUNT, fn (): string => $this->generate())->all();
    }

    public function hash(string $code): string
    {
        return hash('sha256', strtoupper(trim($code)));
    }

    public function hashSet(array $codes): array
    {
        return array_values(array_map(fn (string $code): string => $this->hash($code), $codes));
    }

    public function codes(User $user): array
    {
        return $user->two_factor_recovery_codes ?? [];
    }

    public function verify(User $user, string $code): bool
    {
        $hash = $this->hash($code);
        $codes = $this->codes($user);

        if (! in_array($hash, $codes, true)) {
            return false;
        }

        $user->update([
            'two_factor_recovery_codes' => array_values(array_diff($codes, [$hash])),
        ]);

        return true;
    }

    public function store(User $user, array $plainCodes): void
    {
        $user->update([
            'two_factor_recovery_codes' => $this->hashSet($plainCodes),
        ]);
    }

    public function download(User $user): StreamedResponse
    {
        abort_unless($user->has_two_factor_enabled, 403);

        $codes = $this->generateSet();

        $user->update([
            'two_factor_recovery_codes' => $this->hashSet($codes),
            'recovery_codes_downloaded' => true,
        ]);

        return response()->streamDownload(
            function () use ($codes): void {
                echo implode(PHP_EOL, $codes).PHP_EOL;
            },
            'recovery-codes.txt',
            [
                'Content-Type' => 'text/plain',
                'Cache-Control' => 'no-store, no-cache, must-revalidate',
            ]
        );
    }

    public function regenerate(User $user): void
    {
        abort_unless($user->has_two_factor_enabled, 403);

        $user->update([
            'two_factor_recovery_codes' => $this->hashSet($this->generateSet()),
            'recovery_codes_downloaded' => false,
        ]);
    }
}
