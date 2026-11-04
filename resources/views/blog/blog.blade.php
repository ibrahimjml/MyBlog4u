<x-layout>

  <div class="mx-auto w-full max-w-6xl px-6 py-4">

    {{-- Page header --}}
    <header class="max-w-2xl">
      <p class="font-mono text-xs text-primary">~/blog</p>
    </header>

    {{--  sort + sources --}}
    <div
      class="mt-8 flex flex-col gap-4 border-b border-border pb-6 lg:flex-row lg:items-center lg:justify-between">

      <div class="flex flex-wrap items-center gap-3">
        @include('blog.partials.filter')

        @if($news->isNotEmpty())
          <button id="show-news" type="button"
            class="inline-flex w-fit items-center gap-2 rounded-full border border-border bg-card px-4 py-2 font-mono text-xs text-foreground transition-colors hover:bg-secondary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
            <i class="fas fa-rss text-primary" aria-hidden="true"></i>
            <span class="label">Sources</span>
          </button>
        @endif
      </div>
    </div>

    {{-- News sources --}}
    <div id="news-container"
      class="mt-4 flex h-0 w-full flex-wrap gap-2 overflow-hidden transition-all duration-500 ease-in-out">
      @foreach($news as $new)
        <a href="{{ route('news') }}?source={{ $new->name }}" rel="nofollow"
          class="flex items-center gap-3 rounded-full border border-border bg-card px-4 py-2 font-mono text-xs text-foreground transition-colors hover:border-primary/40 hover:bg-secondary">
          <img src="{{ $new->favicon_url }}" alt="{{ $new->name }}" width="22" height="22"
            class="rounded-full">
          <span class="font-semibold">{{ $new->name }}</span>
          <span class="text-muted-foreground">({{ $new->posts_count }})</span>
        </a>
      @endforeach
    </div>

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

  @push('scripts')
    <script>
      document.addEventListener('DOMContentLoaded', () => {
        const newsContainer = document.getElementById('news-container');
        const showNews = document.getElementById('show-news');

        if (!showNews || !newsContainer) return;

        const label = showNews.querySelector('.label');
        let expanded = false;

        showNews.addEventListener('click', () => {
          if (!expanded) {
            newsContainer.style.height = `${newsContainer.scrollHeight}px`;
            expanded = true;
            label.textContent = 'Hide Sources';
          } else {
            newsContainer.style.height = '0';
            expanded = false;
            label.textContent = 'Sources';
          }
        });
      });
    </script>
  @endpush

</x-layout>
