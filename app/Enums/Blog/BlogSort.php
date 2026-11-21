<?php

namespace App\Enums\Blog;

use Carbon\CarbonInterface;

enum BlogSort: string
{
    case RECENT = 'recent';
    case POPULAR = 'popular';
    case MOST_LIKED = 'most-liked';
    case MOST_VIEWED = 'most-viewed';
    case COMMENTED = 'commented';
    case ALL_TIME = 'all-time';
    case THIS_WEEK = 'this-week';
    case THIS_MONTH = 'this-month';

    public function label(): string
    {
        return match ($this) {
            self::RECENT      => 'Recent',
            self::POPULAR     => 'Popular',
            self::MOST_LIKED  => 'Most liked',
            self::MOST_VIEWED => 'Most viewed',
            self::COMMENTED   => 'Commented',
            self::ALL_TIME    => 'All time',
            self::THIS_WEEK   => 'This week',
            self::THIS_MONTH  => 'This month',
        };
    }

    public function periodStart(): ?CarbonInterface
    {
        return match ($this) {
            self::THIS_WEEK => now()->startOfWeek(),
            self::THIS_MONTH => now()->startOfMonth(),
            default => null,
        };
    }
}
