<?php

namespace App\Enums\Adplacements;

enum AdPosition: string
{
    case HEADER = 'global_header';
    case LEFT_SIDEBAR = 'left_sidebar';
    case RIGHT_SIDEBAR = 'right_sidebar';
    case INNER_FEED = 'inner_feed';
    case TOP_ARTICLE = 'top_article';
    case BOTTOM_ARTICLE = 'bottom_article';
    case INNER_ARTICLE = 'inner_article';
    case FOOTER = 'bottom_footer';

    public function label(): string
    {
        return match ($this) {
            self::HEADER => 'Header',
            self::LEFT_SIDEBAR => 'Left Sidebar',
            self::RIGHT_SIDEBAR => 'Right Sidebar',
            self::INNER_FEED => 'Inner Feed',
            self::TOP_ARTICLE => 'Top Article',
            self::BOTTOM_ARTICLE => 'Bottom Article',
            self::INNER_ARTICLE => 'Inner Article',
            self::FOOTER => 'Footer',
        };
    }
}

