@props([
    'post',
    'showAuthor' => false,
])

@php
    $tag = $post->hashtags->first();
@endphp

<article class="group flex h-full flex-col">
    @if($showAuthor && $post->user)
        <div class="mb-3 flex items-center gap-3">
            <a href="{{ route('profile.home', $post->user->username) }}" class="shrink-0">
                <img src="{{ $post->user->avatar_url }}" alt="{{ $post->user->name }}"
                    class="h-8 w-8 rounded-full object-cover" loading="lazy">
            </a>
            <a href="{{ route('profile.home', $post->user->username) }}"
                class="text-sm font-semibold text-foreground transition-colors hover:text-primary">
                {{ $post->user->username }}
            </a>
        </div>
    @endif

    <a href="{{ route('single.post', $post->slug) }}" class="block">
        <div class="aspect-[16/10] overflow-hidden rounded-xl border border-border bg-secondary">
            <img src="{{ $post->image_url }}" alt="{{ $post->title }}" loading="lazy"
                class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-[1.03]">
        </div>
    </a>

    <div class="mt-4 flex flex-wrap items-center gap-2 font-mono text-[11px] text-muted-foreground">
        @if($tag)
            <span class="rounded-full bg-accent px-2 py-0.5 text-accent-foreground">#{{ $tag->name }}</span>
        @endif
        <span>{{ $post->created_at->format('M d, Y') }}</span>
        <span aria-hidden="true">&middot;</span>
        <span>{{ $post->readingMinutes }} min</span>
    </div>

    <h3 class="mt-2 font-display text-base font-semibold leading-snug">
        <a href="{{ route('single.post', $post->slug) }}"
            class="transition-colors hover:text-primary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
            {{ $post->title }}
        </a>
    </h3>

    @if($post->short_excerpt)
        <p class="mt-2 line-clamp-2 text-sm leading-relaxed text-muted-foreground">
            {{ $post->short_excerpt }}
        </p>
    @endif
</article>
