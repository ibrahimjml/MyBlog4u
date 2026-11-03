@props([
    'isFeed' => false
  ])

@if($isFeed)
<!-- desktop -->
<div class="search-button hidden lg:flex w-fit cursor-pointer items-center gap-1 p-1 rounded-md text-xs text-foreground transition-colors hover:bg-secondary">
        <i class="fas fa-search text-xs"></i>
        <kbd class="rounded-md bg-secondary">
            Ctrl
        </kbd>
        <kbd class="rounded-md bg-secondary">
            K
        </kbd>
</div>
@else
<div class="search-button hidden lg:flex w-96 cursor-pointer items-center justify-between rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm shadow-sm transition hover:border-slate-300 hover:shadow-md">
    <div class="flex items-center gap-3 text-slate-400">
        <i class="fas fa-search text-sm"></i>
        <span class="text-xs font-mono">
            Search posts, tags, users, categories...
        </span>
    </div>

    <div class="flex items-center gap-2 text-xs">
        <kbd class="rounded-md border border-slate-200 bg-slate-50 px-2 py-0.5 text-[11px] font-medium text-slate-500">
            Ctrl
        </kbd>
        <small>&plus;</small>
        <kbd class="rounded-md border border-slate-200 bg-slate-50 px-2 py-0.5 text-[11px] font-medium text-slate-500">
            K
        </kbd>
    </div>
</div>
@endif

<!-- mobile -->
<div class="search-button flex w-full max-w-sm cursor-pointer items-center gap-1 p-1 rounded-md text-xs text-foreground transition-colors hover:bg-secondary lg:hidden">
<i class="fas fa-search text-xs"></i>
        <kbd class="rounded-md bg-secondary">
            search
        </kbd>
</div>

@once
@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const searchButtons = document.querySelectorAll('.search-button');
      const searchContainer = document.getElementById('search-list-container');

      if (!searchContainer) {
        return;
      }

      const openSearch = () => {
        searchContainer.classList.remove('hidden');
        document.documentElement.classList.add('no-scroll');
      };

      const closeSearch = () => {
        searchContainer.classList.add('hidden');
        document.documentElement.classList.remove('no-scroll');
      };

      searchButtons.forEach((button) => {
        button.addEventListener('click', (event) => {
          event.preventDefault();
          openSearch();
        });
      });

      document.addEventListener('keydown', (event) => {
        if ((event.ctrlKey || event.metaKey) && event.key.toLowerCase() === 'k') {
          event.preventDefault();
          openSearch();
        }

        if (event.key === 'Escape') {
          closeSearch();
        }
      });

      searchContainer.addEventListener('click', (event) => {
        if (event.target === searchContainer) {
          closeSearch();
        }
      });
    });
  </script>
@endpush
@endonce
