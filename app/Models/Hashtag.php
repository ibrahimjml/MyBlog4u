<?php

namespace App\Models;

use App\Builders\TagBuilder;
use App\Enums\TagStatus;
use App\Services\ClearCacheService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Hashtag extends Model
{
  use HasFactory,Searchable;

  protected $fillable = ['name', 'is_featured', 'status'];
  protected $attributes = [
    'status' => TagStatus::ACTIVE->value,
  ];
  protected $casts = [
    'is_featured' => 'boolean',
    'status' => TagStatus::class
  ];

  public function newEloquentBuilder($query)
  {
    return new TagBuilder($query);
  }
  protected static function booted(): void
  {
    $clearCache = function (Hashtag $hashtag) {
      app(ClearCacheService::class)->clearTagsCaches($hashtag);
    };

    static::created($clearCache);
    static::updated($clearCache);
    static::deleted($clearCache);
  }

  public function posts()
  {
    return $this->belongsToMany(Post::class, 'post_hashtag')->published();
  }
  public function allPosts()
  {
    return $this->belongsToMany(Post::class, 'post_hashtag');
  }
  public function scopeStatus($query, $status)
  {
    if (!$status || $status === 'all') {
      return $query;
    }
    return $query->where('status', $status);
  }
  public function scopeActive($query)
  {
    return $query->where('status', TagStatus::ACTIVE->value);
  }
  public function scopeDisabled($query)
  {
    return $query->where('status', TagStatus::DISABLED->value);
  }
}
