@use(\App\Enums\Blog\BlogTab)

@php($showTrendingTag = $filter->tab === BlogTab::TRENDING_TAG && $trendingTag)

<p id="trending-tag-header" @class([
    'mt-3 font-mono text-xs text-muted-foreground',
    'hidden' => ! $showTrendingTag,
])>
  @if($showTrendingTag)
    Trending
    <a href="{{ route('viewhashtag', $trendingTag->name) }}"
      class="font-semibold text-primary hover:underline">#{{ $trendingTag->name }}</a>
    &middot; {{ $trendingTag->posts_count }} {{ Str::plural('post', $trendingTag->posts_count) }}
  @endif
</p>
