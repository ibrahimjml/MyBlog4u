<?php

namespace App\Builders;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Fluent;

class UserBuilder extends Builder
{
  public function search(?string $search)
  {
    return $this->where(function ($query) use ($search) {
      $query->where('name', 'like', '%' . $search . '%')
        ->orWhere('email', 'like', '%' . $search . '%');
    });
  }
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

  public function filter(Fluent $filter): self
  {
    return $this
      ->when($filter->search, fn(UserBuilder $q, $search) => $q->search($search))
      ->when($filter->sort, fn(UserBuilder $q, $sort) => $q->sortBy($sort))
      ->when($filter->blocked, fn(UserBuilder $q) => $q->blocked())
      ->when($filter->activate, fn(UserBuilder $q) => $q->requireActivation());
  }
}
