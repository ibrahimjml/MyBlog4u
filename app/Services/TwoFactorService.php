<?php

namespace App\Services;

use App\Contracts\Services\TwoFactorService as TwoFactorContract;
use PragmaRX\Google2FA\Google2FA;
use SimpleSoftwareIO\QrCode\Generator;

class TwoFactorService implements TwoFactorContract
{
     public function __construct(
         protected Google2FA $pragma,
         protected Generator $generator
     ) {}

    public function generateSecretKey(): string
    {
        return $this->pragma->generateSecretKey();
    }

    public function generateQrcodeUrl(string $issueName, string $issueEmail, string $secret): string
    {
        $otpauth = $this->pragma->getQRCodeUrl(
            $issueName,
            $issueEmail,
            $secret
        );

        $logo = public_path('img/icon.png');

        $qrcode = $this->generator->format('png')
            ->size(220)
            ->merge($logo, 0.25, true)
            ->backgroundColor(250, 128, 114)
            ->color(0, 0, 0);

        return $qrcode->generate($otpauth);
    }

    public function verify(string $secret, string $code): bool
    {
        return $this->pragma->verifyKey($secret, $code);
    }
}
