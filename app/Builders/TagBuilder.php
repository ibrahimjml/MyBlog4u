<?php

namespace App\Builders;

use App\Enums\TagStatus;
use App\Models\Post;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Fluent;

class TagBuilder extends Builder
{
    public function trending(?DateTimeInterface $from = null): self
    {
         return $this->active()
                 ->whereHas('posts', fn ($q) => $q
                     ->when($from, fn ($q) => $q->where('posts.created_at', '>=', $from))
                 )
                 ->addSelect([
                     'posts_count' => Post::query()
                         ->published()
                         ->join('post_hashtag', 'post_hashtag.post_id', '=', 'posts.id')
                         ->whereColumn('post_hashtag.hashtag_id', 'hashtags.id')
                         ->when($from, fn ($q) => $q->where('posts.created_at', '>=', $from))
                         ->selectRaw('COUNT(DISTINCT posts.id)'),
                 ])
                 ->orderByDesc('posts_count');
        }

      public function search(?string $search)
      {
        return $this->where(function ($query) use ($search) {
          $query->where('name','like','%'.$search.'%');
                
          });
      }
    public function sortBy(string $sort): self
    {
       match ($sort) {
            'latest' => $this->latest(),
            'oldest' => $this->oldest(),
            'active' => $this->filterByStatus(TagStatus::ACTIVE),
            'disabled' => $this->filterByStatus(TagStatus::DISABLED),
            default => $this->latest(),
        };

        return $this;
    }
      public function filterByStatus(TagStatus $status): self
       {
           return $this->where('status', $status->value);
           
       }

     public function featured(bool $featured = true): self
       {
           return $this->when($featured, fn($q) => $q->where('is_featured', 1));
       }
       
    public function filterTag(Fluent $filter): self
    {
        return $this
            ->when($filter->search, fn(TagBuilder $q, $search) 
                 => $q->search($search))
            ->when($filter->featured, fn(TagBuilder $q, $featured) 
                 => $q->featured($featured))
            ->when($filter->sort, fn(TagBuilder $q, $sort) 
                 => $q->sortBy($sort));
            
    }
}
