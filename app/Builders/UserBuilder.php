<?php

namespace App\Builders;


use App\Models\SocialLink;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Fluent;

class UserBuilder extends Builder
{
  public const BUILTIN_SOCIALS = ['github', 'linkedin', 'twitter'];

  public const MAX_CUSTOM_SOCIAL_LINKS = 4;

  public function search(?string $search)
  {
    return $this->where(function ($query) use ($search) {
      $query->where('name', 'like', '%' . $search . '%')
        ->orWhere('username', 'like', '%' . $search . '%');
    });
  }

  public function builtinSocialLinks(): array
  {
    $user = $this->getModel();

    return collect(self::BUILTIN_SOCIALS)
      ->mapWithKeys(fn ($platform) => [$platform => $user->{$platform}])
      ->filter(fn ($url) => filled($url))
      ->all();
  }

  public function filledBuiltinSocialPlatforms(): array
  {
    return array_map('strtolower', array_keys($this->builtinSocialLinks()));
  }

  public function filledBuiltinSocialUrls(): array
  {
    return array_map(
      fn ($url) => $this->normalizeSocialUrl($url),
      array_values($this->builtinSocialLinks())
    );
  }

  public function isBuiltinSocialPlatform(string $platform): bool
  {
    return in_array(strtolower(trim($platform)), $this->filledBuiltinSocialPlatforms(), true);
  }

  public function isBuiltinSocialUrl(string $url): bool
  {
    return in_array($this->normalizeSocialUrl($url), $this->filledBuiltinSocialUrls(), true);
  }

  public function normalizeSocialUrl(string $url): string
  {
    return rtrim(strtolower(trim($url)), '/');
  }

  public function socials(): array
  {
    $user = $this->getModel();
    $builtin = collect($this->builtinSocialLinks());
    $builtinUrls = $this->filledBuiltinSocialUrls();

    $others = $user->socialLinks
      ->reject(fn (SocialLink $link) =>
        $builtin->has(strtolower(trim($link->platform)))
        || in_array($this->normalizeSocialUrl($link->url), $builtinUrls, true)
      )
      ->take(self::MAX_CUSTOM_SOCIAL_LINKS)
      ->map(fn (SocialLink $link) => [
        'url'      => $link->url,
        'platform' => $link->platform,
      ])
      ->values()
      ->all();

    return [
      'builtin' => $builtin->all(),
      'others'  => $others,
    ];
  }

  public function activated(): self
  {
    return $this->whereHas('activation', fn ($query) => $query->where('completed', true));
  }

  public function hasPublishedPosts(): self
  {
    return $this->whereHas('posts', fn ($query) => $query->published());
  }

  public function withCardCounts(): self
  {
    return $this->withCount([
        'followers',
        'followings',
        'posts as published_posts_count' => fn ($q) => $q->published(),
      ])
      ->withSum([
        'posts as posts_likes_count' => fn ($q) => $q->published(),
      ], 'likes_count');
  }

  public function publicProfile(): self
  {
    return $this->whereHas('profile', fn ($query) => $query->where('is_public', true));
  }

  public function privateProfile(): self
  {
    return $this->whereHas('profile', fn ($query) => $query->where('is_public', false));
  }

  /**
    * admin users page sorts, and filters
    *
    */
  public function sortBy(string $sort): self
  {
    $roleNames = \App\Models\Role::pluck('name')->toArray();
    match ($sort) {
      'latest' => $this->latest(),
      'oldest' => $this->oldest(),
      default => in_array($sort, $roleNames)
      ? $this->filterByRole($sort)
      : $this->latest()
    };

    return $this;
  }

  public function filterByRole(string $role): self
  {
    return $this->whereHas(
      'roles',
      fn($query) =>
      $query->where('name', $role)
    );
  }

  public function blocked(bool $onlyBlocked = true): self
  {
    return $this->when($onlyBlocked, fn($q) => $q->where('is_blocked', 1));
  }

  public function requireActivation(): self
  {
    return $this->whereHas('activation', function ($q) {
      $q->where('completed', 0);
    });
  }
 // admin users filters
  public function filter(Fluent $filter): self
  {
    return $this
      ->when($filter->search, fn(UserBuilder $q, $search) => $q->search($search))
      ->when($filter->sort, fn(UserBuilder $q, $sort) => $q->sortBy($sort))
      ->when($filter->blocked, fn(UserBuilder $q) => $q->blocked())
      ->when($filter->activate, fn(UserBuilder $q) => $q->requireActivation());
  }
}
