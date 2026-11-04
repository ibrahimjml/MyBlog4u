@php
    $featured_one = $featuredPosts->first();
    $more_featured = $featuredPosts->slice(1, 3);

    $newsItems = $moreNews->isNotEmpty() ? $moreNews : [];
    $featuredTag = $featured_one?->hashtags->first();
    $stats = [
        ['value' => '# ' . $trendingHashtag->name, 'label' => 'Trending'],
        ['value' => $trendingHashtag->posts_count, 'label' => 'Articles']
    ];
@endphp

<x-layout>
  <div class="mx-auto w-full max-w-6xl px-6">

    {{-- Hero slides --}}
    @if($slides->isNotEmpty())
      <section class="py-10 md:py-14" aria-label="Highlights">
        <div class="hero-swiper swiper overflow-hidden rounded-3xl border border-border bg-card">
          <div class="swiper-wrapper">
            @foreach($slides as $slide)
              <div class="swiper-slide">
                <div class="grid md:grid-cols-2">
                  <div class="flex flex-col justify-center p-8 md:p-12">
                    <h2 class="mt-4 font-display text-4xl font-bold leading-[1.05] tracking-tight md:text-5xl">
                      {{ $slide->title }}
                    </h2>
                    @if($slide->description)
                      <p class="mt-4 max-w-md text-sm leading-relaxed text-muted-foreground">
                        {{ $slide->description }}
                      </p>
                    @endif
                    <div class="mt-8 flex flex-wrap items-center gap-3">
                      <a href="{{ $slide->link ?: route('blog') }}"
                        @if($slide->link) target="_blank" rel="noopener noreferrer" @endif
                        class="inline-flex items-center gap-2 rounded-full bg-primary px-5 py-2.5 font-mono text-xs text-primary-foreground shadow-glow transition-transform hover:-translate-y-0.5 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
                        Read <span aria-hidden="true">&rarr;</span>
                      </a>
                    </div>
                  </div>

                  <div class="relative min-h-[260px] md:min-h-[440px]">
                    <img src="{{ $slide->image }}" alt="{{ $slide->title }}"
                      class="absolute inset-0 h-full w-full object-cover" loading="eager">
                    <div class="absolute inset-0 bg-gradient-to-t from-card/70 to-transparent md:bg-gradient-to-r md:from-card md:to-transparent"></div>
                  </div>
                </div>
              </div>
            @endforeach
          </div>

          @if($slides->count() > 1)
            <div class="swiper-pagination"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
          @endif
        </div>
      </section>
    @endif

    {{-- Featured + latest news --}}
    <section class="pb-12 md:pb-16" aria-label="Featured and latest news">
      <div class="grid gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2">
          @if($featured_one)
            <article
              class="group h-full overflow-hidden rounded-2xl border border-border bg-card transition-all duration-200 hover:-translate-y-0.5 hover:border-primary/40">
              <a href="{{ route('single.post', $featured_one->slug) }}" class="flex h-full flex-col">
                <div class="aspect-video overflow-hidden bg-foreground">
                  <img src="{{ $featured_one->image_url }}" alt="{{ $featured_one->title }}" loading="lazy"
                    class="h-full w-full object-scale-down transition-transform duration-300 group-hover:scale-[1.02]">
                </div>
                <div class="flex flex-1 flex-col p-6 bg-white">
                  <p class="font-mono text-[11px] uppercase tracking-widest text-primary">
                    Featured @if($featuredTag) &middot; #{{ $featuredTag->name }} @endif
                  </p>
                  <h2 class="mt-3 font-display text-2xl font-semibold leading-snug md:text-3xl">
                    {{ $featured_one->title }}
                  </h2>
                  @if($featured_one->short_excerpt)
                    <p class="mt-3 text-sm leading-relaxed text-muted-foreground">
                      {{ Str::limit($featured_one->short_excerpt, 160) }}
                    </p>
                  @endif
                </div>
              </a>
            </article>
          @endif
        </div>

        <aside class="rounded-2xl bg-foreground p-6 text-background">
          <p class="font-mono text-[11px] uppercase tracking-widest text-primary">&bull; Latest news</p>

          <div class="mt-5 divide-y divide-background/10">
            @foreach($newsItems as $item)
              <article class="py-4 first:pt-0 last:pb-0">
                <div class="flex items-center gap-2">
                <img class="w-10 h-10 object-cover rounded-md border shrink-0" src="{{ $item->image_url }}" alt="Preview">
                <div class="flex flex-col gap-1">
                <p class="font-mono text-[11px] text-background/50">
                  {{ $item->source?->name ?? 'News' }}
                  @if($item->category)
                    &middot; #{{ trim(explode(',', $item->category)[0]) }}
                  @endif
                </p>
                <a href="{{ $item->link }}" target="_blank" rel="nofollow noopener noreferrer"
                  class="mt-1 block font-display text-sm font-semibold leading-snug text-background transition-colors hover:text-primary">
                  {{ $item->title }}
                </a>
                </div>
                </div>
              </article>
            @endforeach
          </div>
           
          <a href="{{ route('news') }}" rel="nofollow"
            class="mt-6 inline-block font-mono text-xs text-primary transition-opacity hover:opacity-80">
            more news &rarr;
          </a>
        </aside>
      </div>
    </section>

    {{-- Featured articles --}}
    <section class="pb-12 md:pb-16" aria-labelledby="fresh-articles-title">
      <div class="flex items-end justify-between border-b border-border pb-3">
        <h2 id="fresh-articles-title" class="font-display text-2xl font-semibold">Featured articles</h2>
        <a href="{{ route('blog') }}"
          class="font-mono text-xs text-primary transition-opacity hover:opacity-80">
          view all &rarr;
        </a>
      </div>

      @if($more_featured->isNotEmpty())
        <div class="mt-6 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
          @foreach($more_featured as $post)
            <x-article-card :post="$post" />
          @endforeach
        </div>
      @endif
    </section>

  {{-- latest articles --}}
    <section class="pb-12 md:pb-16" aria-labelledby="fresh-articles-title">
      <div class="flex items-end justify-between border-b border-border pb-3">
        <h2 id="fresh-articles-title" class="font-display text-2xl font-semibold">Latest articles</h2>
        <a href="{{ route('blog') }}"
          class="font-mono text-xs text-primary transition-opacity hover:opacity-80">
          view all &rarr;
        </a>
      </div>

      @if($latest_posts->isNotEmpty())
        <div class="mt-6 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
          @foreach($latest_posts as $post)
            <x-article-card :post="$post" :show-author="true"/>
          @endforeach
        </div>
      @endif
    </section>
    {{-- Trending hashtag --}}
    @if($trendingHashtag && $latestTrend->isNotEmpty())
      <section class="pb-12 md:pb-16" aria-labelledby="trending-title">
        <div class="flex items-end justify-between border-b border-border pb-3">
          <h2 id="trending-title" class="font-display text-2xl font-semibold">
            Trending <span class="text-primary">#{{ $trendingHashtag->name }}</span>
          </h2>
          <a href="{{ route('viewhashtag', $trendingHashtag->name) }}"
            class="font-mono text-xs text-primary transition-opacity hover:opacity-80">
            view all &rarr;
          </a>
        </div>

        <div class="mt-6 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
          @foreach($latestTrend as $post)
            <x-article-card :post="$post" :show-author="true" />
          @endforeach
        </div>
      </section>
    @endif

    {{-- Stats band --}}
    <section class="pb-16" aria-label="Blog statistics">
      <div class="grid grid-cols-2 gap-6 rounded-2xl bg-accent p-8 text-accent-foreground">
        @foreach($stats as $stat)
          <div>
            <p class="font-display text-3xl font-bold">{{ $stat['value'] }}</p>
            <p class="mt-1 font-mono text-[11px] text-accent-foreground/70">{{ $stat['label'] }}</p>
          </div>
        @endforeach
      </div>
    </section>

  </div>

  @push('scripts')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        const hero = document.querySelector('.hero-swiper');

        if (!hero || typeof Swiper === 'undefined') {
          return;
        }

        const multipleSlides = hero.querySelectorAll('.swiper-slide').length > 1;

        new Swiper(hero, {
          loop: multipleSlides,
          speed: 600,
          autoplay: multipleSlides ? { delay: 5000, disableOnInteraction: false, pauseOnMouseEnter: true } : false,
          pagination: multipleSlides ? { el: '.swiper-pagination', clickable: true } : false,
          navigation: multipleSlides ? { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' } : false,
          on: {
            init(swiper) {
              if (!multipleSlides) {
                swiper.allowTouchMove = false;
              }
            }
          }
        });
      });
    </script>
  @endpush
</x-layout>
