<?php

namespace App\Enums;

enum ProfileArticleSort: string
{
    case RECENT  = 'recent';
    case POPULAR = 'popular';

    public function label(): string
    {
        return match ($this) {
            self::RECENT  => 'Recent',
            self::POPULAR => 'Popular',
        };
    }

    public static function default(): self
    {
        return self::RECENT;
    }

    public function isDefault(): bool
    {
        return $this === self::default();
    }
}
