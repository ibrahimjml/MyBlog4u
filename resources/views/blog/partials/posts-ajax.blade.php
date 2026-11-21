@if ($posts->isEmpty())
  <div class="mt-8 rounded-2xl border border-dashed border-border bg-card px-6 py-16 text-center" data-empty-state>
    <h3 class="font-display text-xl font-semibold text-foreground">
      No posts for {{ $filter->tab->label() }} &middot; {{ $filter->resolvedSort()->label() }}
    </h3>
    <p class="mt-2 font-mono text-xs text-muted-foreground">
      @if($filter->from || $filter->to)
        Try another sort or clear the date range to see more posts.
      @else
        Try another sort or check back later for new posts.
      @endif
    </p>
  </div>
@else
  @foreach ($posts as $post)
    <x-postcard :post="$post" />
    <x-ad-placement :ads="$ads" :position="\App\Enums\Adplacements\AdPosition::INNER_FEED" /><!-- Inner feed ads replace with google adsense-->

   @unless($filter->tab === \App\Enums\Blog\BlogTab::TRENDING_TAG)
    @if($posts->currentPage() === 1 && $loop->iteration == 3)
      @include('blog.trending-tags-this-week', [
           'isInnerFeed' => true,
           'headingLabel' => 'Browse Trending Tags',
           'trendingTags' => $trendingTags ?? collect(),
        ])
    @endif
    @endunless

  @endforeach
@endif
