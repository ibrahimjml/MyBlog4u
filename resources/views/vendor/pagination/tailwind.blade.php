@if ($paginator->hasPages())
  <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}"
    class="flex flex-col items-center justify-between gap-3 sm:flex-row">

    <p class="font-mono text-[11px] text-muted-foreground">
      {!! __('Showing') !!}
      @if ($paginator->firstItem())
        <span class="font-semibold text-foreground">{{ $paginator->firstItem() }}</span>
        {!! __('to') !!}
        <span class="font-semibold text-foreground">{{ $paginator->lastItem() }}</span>
      @else
        {{ $paginator->count() }}
      @endif
      {!! __('of') !!}
      <span class="font-semibold text-foreground">{{ $paginator->total() }}</span>
      {!! __('results') !!}
    </p>

    <div class="flex flex-wrap items-center justify-center gap-1">
      @if ($paginator->onFirstPage())
        <span
          class="inline-flex h-8 min-w-8 cursor-not-allowed items-center justify-center rounded-full border border-border bg-card px-2.5 font-mono text-xs text-muted-foreground/40">
          &lt;
        </span>
      @else
        <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
          class="inline-flex h-8 min-w-8 items-center justify-center rounded-full border border-border bg-card px-2.5 font-mono text-xs text-foreground transition-colors hover:bg-secondary"
          aria-label="{{ __('pagination.previous') }}">
          &lt;
        </a>
      @endif

      @foreach ($elements as $element)
        @if (is_string($element))
          <span
            class="inline-flex h-8 min-w-8 cursor-default items-center justify-center rounded-full border border-border bg-card px-2.5 font-mono text-xs text-muted-foreground">
            {{ $element }}
          </span>
        @endif

        @if (is_array($element))
          @foreach ($element as $page => $url)
            @if ($page == $paginator->currentPage())
              <span aria-current="page"
                class="inline-flex h-8 min-w-8 items-center justify-center rounded-full border border-primary bg-primary px-2.5 font-mono text-xs font-semibold text-primary-foreground">
                {{ $page }}
              </span>
            @else
              <a href="{{ $url }}"
                class="inline-flex h-8 min-w-8 items-center justify-center rounded-full border border-border bg-card px-2.5 font-mono text-xs text-foreground transition-colors hover:bg-secondary"
                aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                {{ $page }}
              </a>
            @endif
          @endforeach
        @endif
      @endforeach

      @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" rel="next"
          class="inline-flex h-8 min-w-8 items-center justify-center rounded-full border border-border bg-card px-2.5 font-mono text-xs text-foreground transition-colors hover:bg-secondary"
          aria-label="{{ __('pagination.next') }}">
          &gt;
        </a>
      @else
        <span
          class="inline-flex h-8 min-w-8 cursor-not-allowed items-center justify-center rounded-full border border-border bg-card px-2.5 font-mono text-xs text-muted-foreground/40">
          &gt;
        </span>
      @endif
    </div>
  </nav>
@endif
