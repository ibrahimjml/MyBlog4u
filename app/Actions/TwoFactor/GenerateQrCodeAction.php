<?php

namespace App\Actions\TwoFactor;

use App\Contracts\Services\TwoFactorService;
use App\Models\User;

class GenerateQrCodeAction
{
    public function __construct(protected TwoFactorService $twoFactor) {}

    public function handle(User $user, string $secret): string
    {
        return $this->twoFactor->generateQrcodeUrl(
            config('app.name'),
            $user->email,
            $secret
        );
    }
}
