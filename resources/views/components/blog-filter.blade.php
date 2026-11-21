@use(\App\Enums\Blog\BlogTab)

<div
  id="blog-filter"
  data-scroll-header
  class="group sticky top-20 z-20 mt-8 w-full max-w-full
         bg-background/95 backdrop-blur-sm supports-[backdrop-filter]:bg-background/80
         transition-all duration-200
         rounded-none ring-0 shadow-none border-y border-border
         data-[scrolled=true]:top-20
         data-[scrolled=true]:rounded-full
         data-[scrolled=true]:border-y-0
         data-[scrolled=true]:ring-1
         data-[scrolled=true]:ring-border
         data-[scrolled=true]:shadow-md
         sm:top-20 lg:w-[30vw]
         data-[scrolled=true]:lg:top-20"
>
  <form action="{{ url()->current() }}" method="GET" id="blog-filter-form">
    <input type="hidden" name="tab"  value="{{ $filter->tab->value }}" data-filter-tab>
    <input type="hidden" name="sort" value="{{ $filter->resolvedSort()->value }}" data-filter-sort>
    
    <div class="overflow-visible rounded-none
             group-data-[scrolled=true]:overflow-hidden
             group-data-[scrolled=true]:rounded-full">
  
    <nav class="grid w-full grid-cols-3 gap-1 sm:flex sm:items-stretch sm:gap-0" aria-label="Blog feeds">
      @foreach(BlogTab::cases() as $tab)
        @php($active = $tab === $filter->tab)

        <button
          type="button"
          data-filter-tab-button="{{ $tab->value }}"
          data-default-sort="{{ $tab->defaultSort()->value }}"
          data-active="{{ $active ? 'true' : 'false' }}"
          @if($active) aria-expanded="false" aria-controls="blog-filter-popover" @endif
          @class([
              'relative inline-flex w-full min-w-0 items-center justify-center gap-1.5 py-3
               font-mono text-[10px] transition-colors
               sm:flex-1 sm:justify-start sm:gap-2 sm:px-4 sm:text-xs',
              'border-b-2',
              'border-primary text-primary font-medium'                        => $active,
              'border-transparent text-muted-foreground hover:text-foreground' => ! $active
          ])
        >
          <i class="{{ $tab->icon() }} shrink-0 text-[10px]" aria-hidden="true"></i>
          <span class="truncate">{{ $tab->label() }}</span>
          <i
            class="fas fa-chevron-down shrink-0 text-[9px] transition-transform duration-150
                   {{ $active ? 'opacity-100' : 'opacity-0' }}"
            data-filter-chevron
            aria-hidden="true"
          ></i>
        </button>
      @endforeach
    </nav>
</div>

    <div id="blog-filter-popover"
      class="absolute left-0 top-full z-30 mt-3 hidden w-full max-w-md rounded-2xl border border-border bg-card p-5 text-card-foreground shadow-xl">
      <div class="flex items-center justify-between gap-3">
        <p class="font-mono text-[11px] uppercase tracking-[0.14em] text-muted-foreground">Sorts By</p>
        @php($resetQuery = $filter->tab === BlogTab::FOR_YOU ? '' : '?tab=' . $filter->tab->value)
        <a href="{{ url()->current() }}{{ $resetQuery }}" data-filter-reset
          class="font-mono text-[11px] text-primary transition-opacity hover:opacity-80">Reset</a>
      </div>

      <div class="mt-3 flex flex-wrap gap-2">
        @foreach($filter->tab->sorts() as $sort)
          @php($sortActive = $sort === $filter->resolvedSort())

          <button type="button" data-filter-sort-option="{{ $sort->value }}"
            @class([
                'rounded-full border px-3 py-1 font-mono text-[11px] transition-colors',
                'border-primary bg-primary text-primary-foreground' => $sortActive,
                'border-border bg-secondary text-muted-foreground hover:border-primary hover:text-primary' => ! $sortActive,
            ])>
            {{ $sort->label() }}
          </button>
        @endforeach
      </div>

      <p class="mt-5 font-mono text-[11px] uppercase tracking-[0.14em] text-muted-foreground">Filter By date range</p>

      <div class="mt-3 grid grid-cols-2 gap-2">
        <label class="flex flex-col gap-1">
          <span class="font-mono text-[10px] uppercase tracking-[0.14em] text-muted-foreground">From</span>
          <input type="date" name="from" value="{{ $filter->from }}"
            class="rounded-lg border border-border bg-background px-3 py-2 font-mono text-xs text-foreground focus:outline-none focus:ring-2 focus:ring-ring">
        </label>

        <label class="flex flex-col gap-1">
          <span class="font-mono text-[10px] uppercase tracking-[0.14em] text-muted-foreground">To</span>
          <input type="date" name="to" value="{{ $filter->to }}"
            class="rounded-lg border border-border bg-background px-3 py-2 font-mono text-xs text-foreground focus:outline-none focus:ring-2 focus:ring-ring">
        </label>
         <small data-error-for="to" class="hidden col-span-2 text-red-500 font-mono text-xs my-2"></small>
      </div>

      <div class="mt-5 flex justify-end">
        <button type="submit"
          class="rounded-full bg-primary px-4 py-2 font-mono text-xs text-primary-foreground transition-opacity hover:opacity-90">
          Apply
        </button>
      </div>
    </div>
  </form>
</div>

@push('vite')
  @vite('resources/js/blog-filter.js')
@endpush
