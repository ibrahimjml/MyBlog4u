@props([
   'trendingTags' => null,
   'isHomeFeed' => false,
   'isInnerFeed' => false,
   'isSidebar' => false,
   'headingLabel' => ''
])

@php
$trendingTagsAllTime = $trendingTags ?? collect();
@endphp

@if($trendingTagsAllTime->isNotEmpty())
  <section @class([
        'rounded-2xl p-6 border border-border',
        'bg-foreground text-background' => $isHomeFeed,
        'bg-card text-foreground' => !$isHomeFeed,
    ])>
    <h3 @class([
        'font-mono text-[11px] uppercase tracking-widest text-primary' => $isHomeFeed,
        'font-display text-lg font-semibold text-foreground' => $isInnerFeed || $isSidebar
      ])>{{ $headingLabel }}</h3>

     @if($isInnerFeed || $isSidebar)
     <div class="mt-2 h-0.5 w-10 bg-primary"></div>
     @endif

    <div class="mt-4 flex flex-col gap-1">
      @foreach($trendingTagsAllTime as $tag)
        @php($authorCards = $tag->relationLoaded('authorCards') ? $tag->authorCards : collect())

        <div @class([
               'group flex items-center justify-between gap-3 rounded-lg px-1 py-2 transition-colors',
               'hover:bg-background/5' => $isHomeFeed,
               'hover:bg-accent' => !$isHomeFeed
          ])>
          <a href="{{ route('viewhashtag', $tag->name) }}"
            @class([
                'min-w-0 flex-1 truncate font-mono text-sm font-semibold transition-colors',
                'text-background  group-hover:text-primary' => $isHomeFeed,
                'text-foreground hover:text-accent-foreground' => ! $isHomeFeed
              ])>
            # {{ $tag->name }}
          </a>

          <span class="flex shrink-0 items-center gap-3">
            @if($authorCards->isNotEmpty())
              <span class="flex -space-x-2">
                @foreach($authorCards as $authorCard)
                  <a href="{{ $authorCard['url'] }}" data-slot="hover-card-trigger"
                    data-avatar="{{ json_encode($authorCard) }}">
                    <img src="{{ $authorCard['avatar_url'] }}" alt="{{ $authorCard['name'] }}"
                    @class([
                       'h-7 w-7 rounded-full border-2 object-cover',
                       'border-foreground' => $isHomeFeed,
                       'border-primary/30' => !$isHomeFeed,
                      ])>
                  </a>
                @endforeach
              </span>
            @endif

            <span @class([
                'w-[68px] text-right font-mono text-[11px]',
                'text-background/60' => $isHomeFeed,
                'text-foreground' => !$isHomeFeed
              ])>
              {{ $tag->posts_count }} {{ Str::plural('post', $tag->posts_count) }}
            </span>
          </span>
        </div>
      @endforeach
    </div>
  </section>
@endif
