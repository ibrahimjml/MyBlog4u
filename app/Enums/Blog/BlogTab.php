<?php

namespace App\Enums\Blog;

enum BlogTab: string
{
    case FOR_YOU = 'for-you';
    case FOLLOWING = 'following';
    case TRENDING_TAG = 'trending-tag';

    public function label(): string
    {
        return match ($this) {
            self::FOR_YOU      => 'For You',
            self::FOLLOWING    => 'Following',
            self::TRENDING_TAG => 'Trending Tag',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::FOR_YOU      => 'fas fa-compass',
            self::FOLLOWING    => 'fas fa-user-friends',
            self::TRENDING_TAG => 'fas fa-hashtag',
        };
    }

    /** @return array<int, BlogSort> */
    public function sorts(): array
    {
        return match ($this) {
            self::FOR_YOU => [
                BlogSort::RECENT,
                BlogSort::POPULAR,
                BlogSort::MOST_LIKED,
                BlogSort::MOST_VIEWED,
                BlogSort::COMMENTED,
            ],
            self::FOLLOWING => [
                BlogSort::RECENT,
                BlogSort::POPULAR,
            ],
            self::TRENDING_TAG => [
                BlogSort::ALL_TIME,
                BlogSort::THIS_WEEK,
                BlogSort::THIS_MONTH,
            ],
        };
    }

    public function defaultSort(): BlogSort
    {
        return $this->sorts()[0];
    }
}
