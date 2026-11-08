@props([
    'post',
])

@php
    $tag = $post->hashtags->first();
    $tagNames = $post->hashtags->pluck('name')->map(fn ($name) => Str::lower($name));
    $canPin = auth()->check() && auth()->user()->is($post->user);
@endphp

<article class="group" data-title="{{ Str::lower($post->title) }}" data-tags="{{ $tagNames->implode(' ') }}">
    <div class="relative ">
        <a href="{{ route('single.post', $post->slug) }}"
            class="block overflow-hidden rounded-xl border border-border bg-secondary">
            <img src="{{ $post->image_url }}" alt="{{ $post->title }}" loading="lazy"
                class=" aspect-[16/7] overflow-hidden w-full object-cover transition-transform duration-300 group-hover:scale-[1.03]">
        </a>

        @if($canPin)
            <button type="button" data-pin-id="{{ $post->id }}" data-pinned="{{ $post->is_pinned ? '1' : '0' }}"
                title="{{ $post->is_pinned ? 'Unpin' : 'Pin' }}"
                class="profile-pin-btn absolute right-2 top-2 flex h-7 w-7 items-center justify-center rounded-full bg-foreground/70 text-background opacity-0 transition-opacity hover:bg-foreground group-hover:opacity-100 focus-visible:opacity-100">
                <i class="fas fa-thumbtack text-[11px] {{ $post->is_pinned ? 'text-primary' : '' }}" aria-hidden="true"></i>
            </button>
        @endif
    </div>

    <div class="mt-3 flex flex-wrap items-center gap-2 font-mono text-[11px]">
        @if($tag)
            <span class="text-primary">#{{ $tag->name }}</span>
            <span class="text-border" aria-hidden="true">&middot;</span>
        @endif
        <span class="text-muted-foreground">{{ $post->created_at->format('M d, Y') }}</span>
    </div>

    <h3 class="mt-1 font-display text-base font-semibold leading-snug text-foreground">
        <a href="{{ route('single.post', $post->slug) }}" class="transition-colors hover:text-primary">
            {{ $post->title }}
        </a>
    </h3>
</article>
