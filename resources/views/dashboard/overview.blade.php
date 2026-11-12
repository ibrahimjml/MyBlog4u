@extends('layouts.dashboard')
@use(\App\Enums\PostStatus)

@section('content')
@php
  $stats = [
    [
      'label' => 'Published',
      'value' => $total_published_posts ?? 0,
      'icon' => 'fas fa-check-circle',
      'tone' => 'bg-emerald-50 text-emerald-600',
      'route' => [
          'url' => route('dashboard.posts') . '?sort=' . PostStatus::PUBLISHED->value,
          'label' => $total_published_posts > 0 ? 'view my published' : ''
      ],
    ],
    [
      'label' => 'Pending review',
      'value' => $total_pending_posts ?? 0,
      'icon' => 'fas fa-clock',
      'tone' => 'bg-amber-50 text-amber-600',
      'route' => [
          'url' => route('dashboard.posts') . '?sort=' . PostStatus::PENDING->value,
          'label' => $total_pending_posts > 0 ? 'view my pendings' : ''
      ],
    ],
    [
      'label' => 'Drafts',
      'value' => $total_draft_posts ?? 0,
      'icon' => 'fas fa-file',
      'tone' => 'bg-sky-50 text-sky-600',
      'route' => [
          'url' => route('dashboard.posts') . '?sort=' . PostStatus::DRAFT->value,
          'label' => $total_draft_posts > 0 ? 'view my drafts' : ''
      ],
    ],
    [
      'label' => 'Total likes',
      'value' => $total_likes ?? 0,
      'icon' => 'fas fa-heart',
      'tone' => 'bg-rose-50 text-rose-600',
    ],
    [
      'label' => 'Profile views',
      'value' => $user->profile_views_count ?? 0,
      'icon' => 'fas fa-id-badge',
      'tone' => 'bg-indigo-50 text-indigo-600',
    ],
    [
      'label' => 'Post views',
      'value' => $total_post_views ?? 0,
      'icon' => 'fas fa-signal',
      'tone' => 'bg-violet-50 text-violet-600',
    ],
    [
      'label' => 'Followers',
      'value' => $user->followers_count ?? 0,
      'icon' => 'fas fa-users',
      'tone' => 'bg-teal-50 text-teal-600',
      'route' => [
          'url' => route('dashboard.followers'),
          'label' => $user->followers_count > 0 ? 'view all followers' : ''
      ],
    ],
    [
      'label' => 'Pending followers',
      'value' => $user->pending_followers_count ?? 0,
      'icon' => 'fas fa-user-clock',
      'tone' => 'bg-orange-50 text-orange-600',
      'route' => [
          'url' => route('dashboard.pending.followers'),
          'label' => $user->pending_followers_count > 0 ? 'view all pendings followers' : ''
      ],
    ],
    [
      'label' => 'Following',
      'value' => $user->followings_count ?? 0,
      'icon' => 'fas fa-user-plus',
      'tone' => 'bg-cyan-50 text-cyan-600',
      'route' => [
          'url' => route('dashboard.followings'),
          'label' => $user->followings_count > 0 ? 'view all followings' : ''
      ],
    ],
  ];
@endphp

<div class="mb-6 flex flex-col gap-1">
  <p class="font-mono text-[11px] uppercase tracking-[0.16em] text-muted-foreground">Your activity and audience</p>
</div>

<div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
  @foreach($stats as $stat)
    <div
      class="group rounded-2xl border border-border bg-card p-5 transition-colors duration-300 hover:border-primary/40">
      <div class="flex items-center justify-between gap-4">
        <p class="font-mono text-[10px] uppercase tracking-[0.16em] text-muted-foreground">{{ $stat['label'] }}</p>
        <span
          class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl {{ $stat['tone'] }} transition-transform duration-300 group-hover:scale-105">
          <i class="{{ $stat['icon'] }} text-sm" aria-hidden="true"></i>
        </span>
      </div>
      <div class="flex items-end justify-between gap-4">
      <p class=" font-display text-3xl font-bold tracking-tight text-foreground">
        {{ number_format((int) $stat['value']) }}
      </p>
      @if(! empty($stat['route']))
          <a href="{{ $stat['route']['url'] }}"
            class="font-mono text-xs text-primary transition-opacity hover:opacity-80">
            {{ $stat['route']['label'] }}
          </a>
      @endif
    </div>
    </div>
  @endforeach
</div>

@endsection
