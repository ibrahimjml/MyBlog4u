@extends('layouts.dashboard')

@section('content')
@php
  $button = 'inline-flex h-9 w-9 items-center justify-center rounded-xl border border-border bg-background text-muted-foreground transition-colors';
@endphp

<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
  <div>
    <h1 class="font-display text-xl font-bold text-foreground">Your articles<span class="text-primary">.</span></h1>
    <p class="mt-1 font-mono text-[11px] uppercase tracking-[0.16em] text-muted-foreground">Write, review and manage your posts</p>
  </div>

  <div class="flex flex-wrap items-center gap-2">
    <a href="{{ route('createpage') }}"
      class="inline-flex items-center gap-2 rounded-xl bg-primary px-4 py-2.5 text-xs font-bold uppercase tracking-wide text-primary-foreground shadow-sm transition-colors hover:opacity-90">
      <i class="fas fa-plus" aria-hidden="true"></i>
      Write an article
    </a>

    <x-forms.filter-form exclude="sort" class="flex items-center gap-2">
      <div class="relative w-fit">
        <select id="sort" name="sort" aria-label="Sort posts"
          class="cursor-pointer appearance-none rounded-xl border border-border bg-background py-2.5 pl-3 pr-9 font-mono text-xs font-medium text-foreground outline-none transition-colors hover:border-primary/40 focus:border-primary focus:ring-2 focus:ring-ring/30"
          onchange="this.form.submit()">
          <option value="">All posts</option>
          <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Latest</option>
          <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest</option>
          @foreach (\App\Enums\PostStatus::forMyDashboard() as $status)
            <option value="{{$status->value}}" {{request('sort') === $status->value ? 'selected' : ''}}>
              {{ $status->label() }}
            </option>
          @endforeach
        </select>
        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
          <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M19 9l-7 7-7-7" />
          </svg>
        </div>
      </div>
    </x-forms.filter-form>

    @if(request()->Filled('sort'))
      <a href="{{ url()->current() }}"
        class="inline-flex items-center gap-2 rounded-xl border border-destructive/30 bg-destructive/10 px-3 py-2.5 text-xs font-bold uppercase tracking-wide text-destructive transition-colors hover:bg-destructive/15">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
        Clear
      </a>
    @endif
  </div>
</div>

<div class="space-y-4">
  @forelse($posts as $post)
@php
         $published = $post->status === \App\Enums\PostStatus::PUBLISHED;
         $pending = $post->status === \App\Enums\PostStatus::PENDING;
         $draft = $post->status === \App\Enums\PostStatus::DRAFT;
     @endphp
    <article
      class="group flex flex-col items-start gap-4 rounded-2xl border border-border bg-card p-4 transition-colors duration-300 hover:border-primary/40 sm:flex-row sm:items-center sm:gap-6 sm:p-5">
      <div class="relative flex h-40 w-full shrink-0 items-center justify-center overflow-hidden rounded-xl border border-border bg-secondary sm:h-24 sm:w-32">
        <img src="{{ $post->image_url }}" alt="{{ $post->title }}" class="h-full w-full object-cover">
      </div>

      <div class="flex min-w-0 flex-1 flex-col justify-center gap-2">
        <div @class([
            'text-xs font-mono flex items-center gap-2 rounded-lg w-fit px-2 py-[2px]',
            'text-emerald-500 bg-emerald-500/10' => $published,
            'text-amber-500 bg-amber-500/10' => $pending,
            'text-sky-500 bg-sky-500/10' => $draft,
          ])>
          <span @class([
            'h-2 w-2 rounded-full',
            'bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.6)]' => $published,
            'bg-amber-500 shadow-[0_0_8px_rgba(245,158,11,0.6)]' => $pending,
            'bg-sky-500 shadow-[0_0_8px_rgba(14,165,233,0.6)]' => $draft,
          ])></span>
          <small>{{ Str::lower($post->status->value)}}</small>
        </div>

        <a class="font-display text-lg font-semibold leading-snug text-foreground transition-colors line-clamp-2 hover:text-primary sm:text-xl"
          href="{{ route('single.post', $post->slug) }}">
          {{ $post->title }}
        </a>

        <div class="flex flex-wrap items-center gap-3 font-mono text-[11px] text-muted-foreground">
          <span class="flex items-center gap-1.5">
            <i class="fas fa-clock" aria-hidden="true"></i>
            {{ $post->created_at->diffForHumans() }}
          </span>
        </div>
      </div>

      <div
        class="flex w-full flex-row items-center justify-between gap-4 border-t border-border pt-4 sm:w-auto sm:flex-col sm:items-end sm:border-none sm:pt-0">
        <div class="flex items-center gap-4 rounded-full border border-border bg-background px-3 py-1.5 font-mono text-[11px] text-muted-foreground">
          <span class="flex items-center gap-1.5 font-semibold" title="Views">
            <i class="fas fa-eye text-primary" aria-hidden="true"></i>
            {{ number_format($post->views) }}
          </span>
          <span class="flex items-center gap-1.5 font-semibold" title="Likes">
            <i class="fas fa-heart text-rose-500" aria-hidden="true"></i>
            {{ number_format($post->likes_count) }}
          </span>
          <span class="flex items-center gap-1.5 font-semibold" title="Comments">
            <i class="fas fa-comment text-sky-500" aria-hidden="true"></i>
            {{ number_format($post->comments_count) }}
          </span>
        </div>

        <div class="flex items-center gap-1.5">
          
          <button id="dropdownMenu-{{ $post->id }}" data-dropdown-toggle="dropdown-{{ $post->id }}" type="button"
            aria-label="More actions"
            class="{{ $button }} hover:border-primary hover:text-primary">
            <i class="fas fa-ellipsis-h text-xs" aria-hidden="true"></i>
          </button>

          <div id="dropdown-{{ $post->id }}"
            class="z-10 hidden w-44 divide-y divide-border rounded-xl border border-border bg-card shadow-lg">
            <ul class="py-1 text-sm text-card-foreground" aria-labelledby="dropdownMenu-{{ $post->id }}">
             <li>
              <a href="{{ route('single.post', $post->slug) }}" aria-label="View {{ $post->title }}"
                 class="flex w-full items-center gap-3 px-4 py-2 text-left text-xs font-medium text-secondary-foreground transition-colors hover:bg-foreground/10">
                <i class="fas fa-eye text-xs" aria-hidden="true"></i>
                View post
              </a></li>
             <li>
             <a href="{{ route('edit.post', $post->slug) }}" aria-label="Edit {{ $post->title }}"
               class="flex w-full items-center gap-3 px-4 py-2 text-left text-xs font-medium text-blue-400 transition-colors hover:bg-blue-400/10">
               <i class="fas fa-pen text-xs" aria-hidden="true"></i>
               Edit post
             </a></li>
              <li>
                <form method="POST" action="{{ route('delete.post', $post->slug) }}"
                  onsubmit="return confirm('Are you sure you want to delete this post?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit"
                    class="flex w-full items-center gap-3 px-4 py-2 text-left text-xs font-medium text-destructive transition-colors hover:bg-destructive/10">
                    <i class="fas fa-trash" aria-hidden="true"></i>
                    Delete post
                  </button>
                </form>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </article>
  @empty
    <div class="rounded-2xl border border-dashed border-border p-10 text-center">
      <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-secondary text-muted-foreground">
        <i class="fas fa-image" aria-hidden="true"></i>
      </span>
      <p class="mt-3 font-display text-base font-semibold text-foreground">No posts yet</p>
      <p class="mt-1 text-sm text-muted-foreground">Your articles will appear here once you write one.</p>
    </div>
  @endforelse
</div>

<div class="mt-6">
  {{ $posts->links() }}
</div>

@endsection
