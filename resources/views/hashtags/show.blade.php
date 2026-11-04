<x-layout>

  <div class="mx-auto w-full max-w-6xl px-6 py-8 md:py-12">

    <div class="flex flex-wrap items-center justify-between gap-3">
      <a href="{{ route('blog') }}"
        class="inline-flex items-center gap-2 rounded-full border border-border bg-card px-4 py-2 font-mono text-xs text-foreground transition-colors hover:bg-secondary">
        <i class="fas fa-arrow-left text-primary" aria-hidden="true"></i>
        Back to blog
      </a>
    </div>

    <x-sliders :model="$currentHashtag" :sliders="$sliders" type="Hashtag" />

    <div class="mt-8 flex flex-wrap items-end justify-between gap-4 border-b border-border pb-4">
      <div>
        <p class="font-mono text-xs text-primary">~/tag</p>
        <h2 class="mt-2 font-display text-2xl font-bold tracking-tight md:text-3xl">Articles</h2>
      </div>

      <div class="flex items-center gap-3">
        <span class="font-mono text-xs text-muted-foreground">{{ $posts->total() }} posts</span>
        @include('blog.partials.filter')
      </div>
    </div>

    @if($posts->count() == 0)
      <div class="mt-8 rounded-2xl border border-dashed border-border bg-card px-6 py-16 text-center">
        @if($currentHashtag->posts_count === 0)
          <h3 class="font-display text-xl font-semibold text-foreground">No posts yet in #{{ $currentHashtag->name }}</h3>
          <p class="mt-2 font-mono text-xs text-muted-foreground">Check back later for new articles with this tag.</p>
        @elseif(request()->has('sort') && request('sort') !== 'latest')
          <h3 class="font-display text-xl font-semibold text-foreground">No posts match your filter</h3>
          <p class="mt-2 font-mono text-xs text-muted-foreground">Try another filter or clear the selection to see all tag posts.</p>
          <a href="{{ route('viewhashtag', $currentHashtag) }}"
            class="mt-6 inline-flex items-center gap-2 rounded-full bg-foreground px-4 py-2 font-mono text-xs text-background transition-opacity hover:opacity-80">
            Clear filter
          </a>
        @else
          <h3 class="font-display text-xl font-semibold text-foreground">No posts available</h3>
        @endif
      </div>
    @else
      <div id="hashtags-container" class="mt-4 lg:w-[60vw] lg:mx-auto">
        @include('hashtags.hashtags-ajax')
      </div>
    @endif

    <x-infinte-scroll container="hashtags-container" :route="route('viewhashtag', $currentHashtag)"
      page-name="hashtags_page" :current-page="$posts->currentPage()" :has-more="$posts->hasMorePages()" />

  </div>

</x-layout>
