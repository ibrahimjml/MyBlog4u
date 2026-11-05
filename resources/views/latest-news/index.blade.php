<x-layout>

  @php
    $activeSource = $sourceName ?? '';
  @endphp

  <div class="mx-auto w-full max-w-6xl px-6 py-6">

    <header class="max-w-2xl">
      <p class="font-mono text-xs text-primary">~/news</p>
      <h1 class="mt-3 font-display text-4xl font-bold tracking-tight md:text-5xl">Latest News</h1>
      <p class="mt-3 text-sm leading-relaxed text-muted-foreground">
        Recent stories from every active source, collected in one feed.
      </p>
    </header>

    <div class="mt-8 flex flex-col gap-4 border-b border-border pb-6 lg:flex-row lg:items-center lg:justify-between">
      <div class="flex flex-wrap gap-2">
        <button type="button" data-source="" aria-pressed="{{ $activeSource === '' ? 'true' : 'false' }}"
          @class([
              'source-filter-btn inline-flex items-center gap-2 rounded-full border px-3.5 py-1.5 font-mono text-xs transition-colors',
              'border-primary bg-primary text-primary-foreground' => $activeSource === '',
              'border-border bg-card text-foreground hover:bg-secondary' => $activeSource !== '',
          ])>
          All
        </button>

        @foreach($sources as $source)
          <button type="button" data-source="{{ $source->name }}"
            aria-pressed="{{ $activeSource === $source->name ? 'true' : 'false' }}"
            @class([
                'source-filter-btn inline-flex items-center gap-2 rounded-full border px-3.5 py-1.5 font-mono text-xs transition-colors',
                'border-primary bg-primary text-primary-foreground' => $activeSource === $source->name,
                'border-border bg-card text-foreground hover:bg-secondary' => $activeSource !== $source->name,
            ])>
            <img src="{{ $source->favicon_url }}" alt="{{ $source->title }}" width="20" height="20"
              class="h-5 w-5 rounded-full">
            <span>{{ $source->name }}</span>
            <span class="rounded-full bg-foreground/10 px-1.5 py-0.5 text-[10px]">{{ $source->posts_count ?? 0 }}</span>
          </button>
        @endforeach
      </div>

      <p class="shrink-0 font-mono text-xs text-muted-foreground">
        {{ $news->total() }} {{ Str::plural('story', $news->total()) }}
        &middot;
        {{ $sources->count() }} {{ Str::plural('source', $sources->count()) }}
      </p>
    </div>

    <div id="news-grid-container" class="mt-8 grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
      @include('latest-news.partials.news-ajax', ['news' => $news])
    </div>

    <x-load-more button-id="load-more-news"
                 container="news-grid-container" 
                :route="route('news')"
                page-name="news_page" 
                :current-page="$news->currentPage()" 
                :has-more="$news->hasMorePages()"
                button-text="Load More News" 
                />

  </div>

  @push('scripts')
    <script>
      document.addEventListener('DOMContentLoaded', () => {
        const newsGridContainer = document.getElementById('news-grid-container');
        const sourceFilterButtons = document.querySelectorAll('.source-filter-btn');

        const newsRoute = new URL('{{ route('news') }}', window.location.origin).toString();
        const currentSource = '{{ $activeSource }}';

        const activeClasses = ['border-primary', 'bg-primary', 'text-primary-foreground'];
        const inactiveClasses = ['border-border', 'bg-card', 'text-foreground', 'hover:bg-secondary'];

        function setActiveSourceButton(source) {
          sourceFilterButtons.forEach((button) => {
            const isActive = (button.dataset.source || '') === source;

            activeClasses.forEach((className) => button.classList.toggle(className, isActive));
            inactiveClasses.forEach((className) => button.classList.toggle(className, !isActive));
            button.setAttribute('aria-pressed', isActive ? 'true' : 'false');
          });
        }

        function buildNewsUrl(source, pageUrl = null) {
          if (pageUrl) {
            return new URL(pageUrl, window.location.origin).toString();
          }

          const url = new URL(newsRoute);
          if (source) {
            url.searchParams.set('source', source);
          } else {
            url.searchParams.delete('source');
          }
          url.searchParams.delete('news_page');
          return url.toString();
        }

        async function loadNews(url, source) {
          try {
            const response = await fetch(url, {
              headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
              },
            });

            if (!response.ok) {
              throw new Error('Unable to load news.');
            }

            const json = await response.json();
            newsGridContainer.innerHTML = json.html;
            setActiveSourceButton(source ?? '');

            const loadMoreButton = document.getElementById('load-more-news');
            const loadMoreWrapper = document.getElementById('load-more-news-container');
            const loadMoreSpinner = document.getElementById('load-more-news-spinner');
            const reachEnd = document.getElementById('reach-end');

            if (json.hasMore) {
              if (loadMoreButton) loadMoreButton.dataset.nextPage = json.nextPage;
              if (loadMoreWrapper) loadMoreWrapper.classList.remove('hidden');
              if (reachEnd) reachEnd.classList.add('hidden');
            } else {
              if (loadMoreWrapper) loadMoreWrapper.classList.add('hidden');
              if (reachEnd) reachEnd.classList.remove('hidden');
            }

            window.history.replaceState(null, '', new URL(url, window.location.origin).toString());
          } catch (error) {
            console.error(error);
            newsGridContainer.innerHTML = `
              <div class="col-span-full rounded-2xl border border-dashed border-destructive/40 bg-card px-6 py-16 text-center">
                <p class="font-display text-lg font-semibold text-destructive">Unable to load news</p>
                <p class="mt-2 font-mono text-xs text-muted-foreground">Please refresh the page and try again.</p>
              </div>`;
          }
        }

        sourceFilterButtons.forEach((button) => {
          button.addEventListener('click', () => {
            const source = button.dataset.source || '';
            loadNews(buildNewsUrl(source), source);
          });
        });

        setActiveSourceButton(currentSource);
      });
    </script>
  @endpush

</x-layout>
