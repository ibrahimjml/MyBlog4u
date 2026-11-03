<a data-search-link href="{{ $item->link }}" target="_blank" rel="noopener noreferrer" class="search-result-row">
  <img src="{{ $item->image_url }}" alt="{{ $item->title }}"
    class="h-12 w-12 flex-none rounded-xl border border-border object-cover">
  <div class="min-w-0 flex-1">
    <h4 class="truncate font-display text-sm font-semibold text-foreground">
      {{ $item->title }}
    </h4>
    <div class="mt-1 flex items-center gap-2">
      <span onclick="window.location.href='{{ route('news') }}?source={{ $item->source->name }}'"
        class="inline-flex items-center rounded-full bg-accent px-2 py-0.5 font-mono text-[11px] text-accent-foreground">
        {{ $item->source->name }}
      </span>
    </div>
  </div>
  <i class="fas fa-arrow-right text-xs text-muted-foreground" aria-hidden="true"></i>
</a>