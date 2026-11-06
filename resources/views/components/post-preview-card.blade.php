@props([
    'post',
    'showAuthor' => true,
])

@php
    $category = $post->categories->first() ?? $post->hashtags->first();
    $label = $category->name ?? 'Article';
@endphp

<article
    class="group flex h-full flex-col overflow-hidden rounded-2xl border border-border bg-card transition-colors duration-300 hover:border-primary/40">
    <a href="{{ route('single.post', $post->slug) }}" class="relative block aspect-[16/10] overflow-hidden bg-secondary">
        <img src="{{ $post->image_url }}" alt="{{ $post->title }}" loading="lazy"
            class="h-full w-full object-cover object-center transition-transform duration-500 group-hover:scale-[1.04]">

        <span
            class="absolute left-3 top-3 rounded-full border border-border/60 bg-background/90 px-2.5 py-1 font-mono text-[10px] font-medium uppercase tracking-[0.16em] text-foreground backdrop-blur-sm">
            {{ $label }}
        </span>
    </a>

    <div class="flex flex-1 flex-col p-4 sm:p-5">
        <div class="flex flex-wrap items-center gap-2 font-mono text-[10px] uppercase tracking-[0.14em] text-muted-foreground">
            <span>{{ $post->created_at->format('M d, Y') }}</span>
            <span class="text-border" aria-hidden="true">/</span>
            <span>{{ $post->readingMinutes }} min read</span>
        </div>

        <h3 class="mt-2 font-display text-lg font-semibold leading-snug">
            <a href="{{ route('single.post', $post->slug) }}"
                class="transition-colors hover:text-primary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
                {{ $post->title }}
            </a>
        </h3>

        @if($post->short_excerpt)
            <p class="mt-2 line-clamp-2 text-sm leading-relaxed text-muted-foreground">
                {{ Str::limit(strip_tags($post->short_excerpt), 110) }}
            </p>
        @endif

        <div class="mt-auto flex items-center justify-between gap-3 pt-5">
            @if($showAuthor && $post->user)
                <div class="flex min-w-0 items-center gap-2">
                    <img src="{{ $post->user->avatar_url }}" alt="{{ $post->user->name }}"
                        class="h-6 w-6 rounded-full object-cover" loading="lazy">
                    <a href="{{ route('profile.home', $post->user->username) }}"
                        class="truncate text-xs font-medium text-muted-foreground transition-colors hover:text-primary">
                        {{ '@' . $post->user->username }}
                    </a>
                </div>
            @else
                <span class="font-mono text-[10px] uppercase tracking-[0.14em] text-muted-foreground">
                    {{ $post->total_comments_count }} comments
                </span>
            @endif

            <a href="{{ route('single.post', $post->slug) }}" aria-label="Read {{ $post->title }}"
                class="inline-flex h-7 w-7 shrink-0 items-center justify-center rounded-full border border-border text-muted-foreground transition-colors group-hover:border-primary group-hover:text-primary">
                <i class="fas fa-arrow-right text-[10px]" aria-hidden="true"></i>
            </a>
        </div>
    </div>
</article>
