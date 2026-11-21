<?php

namespace App\Builders;

use App\DTOs\BlogFilterDTO;
use App\Enums\Blog\BlogSort;
use App\Enums\Blog\BlogTab;
use App\Enums\PostStatus;
use App\Models\Hashtag;
use Carbon\CarbonInterface;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Fluent;

class PostBuilder extends Builder
{
    public function search(?string $search): Builder
    {
      if (empty($search)) return $this;

      return $this->where(function ($query) use ($search) {
            $query->where('title', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%")
                  ->orWhereHas('hashtags', function ($query) use ($search) {
                      $query->where('name', 'like', "%$search%");
                  })
                    ->orWhereHas('user', function ($query) use ($search) {
                      $query->where('name', 'like', "%$search%")
                            ->orWhere('username', 'like', "%$search%");
                  });
        });
    }
    // hashtags | categories sort
    public function blogSort(string $sort): self
    {
       match ($sort) {
            'recent' => $this->latest(),
            'popular' => $this->orderByDesc('likes_count')->orderByDesc('totalcomments_count'),
            'mostviewed' => $this->orderByDesc('views'),
            'followings' => $this->filterByFollowings(),
            'featured' => $this->where('is_featured', true),
            default => $this->latest(),
        };
        return $this;
    }
    // blog feed filter
    public function blogFilter(BlogFilterDTO $filter): self
    {
        $sort = $filter->resolvedSort();

        return $this->filterByTab($filter->tab, $sort)
                    ->applyBlogSort($sort)
                    ->filterByDateRange($filter->fromDate(), $filter->toDate());
    }

    protected function filterByTab(BlogTab $tab, BlogSort $sort): self
    {
        return match ($tab) {
            BlogTab::FOR_YOU => $this,
            BlogTab::FOLLOWING => $this->filterByFollowings(),
            BlogTab::TRENDING_TAG => $this->filterByTrendingHashtag($sort->periodStart()),
        };
    }

    protected function applyBlogSort(BlogSort $sort): self
    {
        match ($sort) {
            BlogSort::RECENT,
            BlogSort::ALL_TIME,
            BlogSort::THIS_WEEK,
            BlogSort::THIS_MONTH => $this->latest(),
            BlogSort::POPULAR => $this->orderByDesc('likes_count')->orderByDesc('totalcomments_count'),
            BlogSort::MOST_LIKED => $this->orderByDesc('likes_count'),
            BlogSort::MOST_VIEWED => $this->orderByDesc('views'),
            BlogSort::COMMENTED => $this->orderByDesc('totalcomments_count'),
        };

        return $this;
    }

    protected function filterByDateRange(?CarbonInterface $from, ?CarbonInterface $to): self
    {
        return $this
            ->when($from, fn ($query) => $query->where('posts.created_at', '>=', $from))
            ->when($to, fn ($query) => $query->where('posts.created_at', '<=', $to));
    }

    protected function filterByFollowings(): self
    {
        if (! auth()->check()) {
            return $this->whereRaw('0 = 1');
        }

        $followings = auth()->user()->followings->pluck('id');
        return $this->whereIn('user_id', $followings);
    }

    protected function filterByTrendingHashtag(?DateTimeInterface $from = null): self
    {
        return $this->trendingHashtag(null, $from)
            ->when($from, fn ($query) => $query->where('posts.created_at', '>=', $from));
    }

    public function trendingHashtag(?int $hashtagId = null, ?DateTimeInterface $from = null): self
    {
        $hashtagId ??= Hashtag::query()
            ->trending($from)
            ->first()?->id;

        if (! $hashtagId) {
            return $this->whereRaw('0 = 1');
        }

        return $this->whereHas('hashtags', fn ($query) =>
            $query->where('hashtags.id', $hashtagId)
        );
    }
    // admin sort
     public function adminSort(string $sort): self
    {
       match ($sort) {
            'latest' => $this->latest(),
            'oldest' => $this->oldest(),
            'published' => $this->filterByStatus(PostStatus::PUBLISHED),
            'banned' => $this->filterByStatus(PostStatus::BANNED),
            'draft' => $this->filterByStatus(PostStatus::DRAFT),
            'pending' => $this->filterByStatus(PostStatus::PENDING),
            default => $this->latest(),
        };

        return $this;
    }
    // my dashboard sort
     public function forMyDashboardSort(string $sort): self
    {
       match ($sort) {
            'latest' => $this->latest(),
            'oldest' => $this->oldest(),
            'published' => $this->filterByStatus(PostStatus::PUBLISHED),
            'draft' => $this->filterByStatus(PostStatus::DRAFT),
            'pending' => $this->filterByStatus(PostStatus::PENDING),
            default => $this->latest(),
        };

        return $this;
    }
      public function filterByStatus(PostStatus $status): self
    {
        return $this->where('status', $status->value);
        
    }
    public function featured(bool $featured = true): self
    {
        return $this->when($featured, fn($q) => $q->where('is_featured', 1));
    }
    public function reported(bool $reported = true): self
    {
        return $this->when($reported, fn($q) => $q->where('is_reported', 1));
    }

    public function adminFilter(Fluent $filter): self
    {
        return $this
            ->when($filter->search, fn(PostBuilder $q, $search) => $q->search($search))
            ->when($filter->sort, fn(PostBuilder $q, $sort) => $q->adminSort($sort))
            ->when($filter->featured, fn(PostBuilder $q) => $q->featured())
            ->when($filter->reported, fn(PostBuilder $q) => $q->reported());
    }
    public function DashboardFilter(Fluent $filter): self
    {
        return $this->when($filter->sort, fn(PostBuilder $q, $sort) => $q->forMyDashboardSort($sort));
    }

}
