<?php

namespace App\Builders;

use App\Enums\PostStatus;
use App\Models\Hashtag;
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
    // blog sort
    public function blogSort(string $sort): self
    {
       match ($sort) {
            'latest' => $this->latest(),
            'oldest' => $this->oldest(),
            'mostliked' => $this->orderByDesc('likes_count'),
            'mostviewed' => $this->orderByDesc('views'),
            'followings' => $this->filterByFollowings(),
            'featured' => $this->where('is_featured', true),
            'hashtagtrend' => $this->filterByTrendingHashtag(),
            default => $this->latest(),
        };

        return $this;
    }
    protected function filterByFollowings(): self
    {
        $followings = auth()->user()->followings->pluck('id');
        return $this->whereIn('user_id', $followings);
    }

    protected function filterByTrendingHashtag(): self
    {
        $trending = Hashtag::withCount('posts')
            ->having('posts_count', '>', 0)
            ->orderByDesc('posts_count')
            ->first();

        if ($trending) {
            return $this->whereHas('hashtags', fn ($query) =>
                $query->where('hashtags.id', $trending->id)
            );
        }

        return $this->whereRaw('0 = 1');
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
