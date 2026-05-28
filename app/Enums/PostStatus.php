<?php

namespace App\Enums;

enum PostStatus:string
{
    case PENDING = 'pending';
    case DRAFT = 'draft';
    case PUBLISHED = 'published';
    case BANNED = 'banned';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::DRAFT => 'Draft',
            self::PUBLISHED => 'Published',
            self::BANNED => 'Banned',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'bg-yellow-500',
            self::DRAFT => 'bg-blue-500',
            self::PUBLISHED => 'bg-green-500',
            self::BANNED => 'bg-red-500',
        };
    }
    public static function forMyDashboard()
    {
        return [
            self::PENDING,
            self::DRAFT,
            self::PUBLISHED,
        ];
    }
    public static function forUserCreation()
    {
        return [
            self::PUBLISHED->value => 'publish (public)',
            self::DRAFT->value => 'save as draft (private)',
        ];
    }
}
