<?php

namespace App\Enums\Adplacements;

enum AdType: string
{
    case GOOGLE_ADSENSE = 'google_adsense';
    case CUSTOM_BANNER = 'custom_banner';

    public function label(): string
    {
        return match ($this) {
            self::GOOGLE_ADSENSE => 'HTML / Google Adsense',
            self::CUSTOM_BANNER => 'Custom Banner',
        };
    }
}

