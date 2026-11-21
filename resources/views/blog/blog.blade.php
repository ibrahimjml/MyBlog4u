<x-layout>

  <div class="mx-auto w-full max-w-6xl px-6 py-4">

    {{-- Page header --}}
    <header class="max-w-2xl">
      <p class="font-mono text-xs text-primary">~/blog</p>
      @include('blog.partials.trending-tag-header', [
          'filter' => $filter,
          'trendingTag' => $trendingTag,
      ])
    </header>

    {{--  filter tabs --}}
    <x-blog-filter :filter="$filter" />

    {{-- Feed + sidebar --}}
    <div class="mt-6 grid gap-8 lg:grid-cols-3">
      <div class="min-w-0 lg:col-span-2">
          {{-- Grid --}}
          <article id="posts-container" class="mt-8 grid gap-6 md:grid-cols-1">
            @include('blog.partials.posts-ajax', ['posts' => $posts])
          </article>

        <x-infinte-scroll container="posts-container" :route="route('blog')" page-name="blog_page"
          :current-page="$posts->currentPage()" :has-more="$posts->hasMorePages()" />
      </div>

      {{-- Desktop sidebar --}}
      <aside class="hidden mt-8 lg:block">
        <div class="sticky top-28 space-y-6">
          {{-- @include('blog.trending-tags-this-week') --}}
          @include('blog.popular-tags')
          @include('blog.categories')
          @include('blog.latest-news')
          @include('blog.whotofollow')
        </div>
      </aside>
    </div>

  </div>

  {{-- Mobile sidebar --}}
  @include('blog.partials.__sidebar')

  {{-- Open sidebar button --}}
  <button id="open-sidebar" type="button" aria-label="Open sidebar menu"
    class="fixed bottom-6 right-6 z-40 flex h-11 w-11 items-center justify-center rounded-full border border-border bg-card text-foreground shadow-lg transition-all duration-200 hover:border-primary/40 hover:text-primary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring lg:hidden">
    <i class="fa fa-bars text-sm" aria-hidden="true"></i>
  </button>

</x-layout>
