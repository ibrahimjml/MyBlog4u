<a data-search-link href="{{ route('single.post', $item->slug) }}" class="search-result-row">
  <img src="{{ $item->image_url }}" alt="" class="h-10 w-10 rounded-lg object-cover">
  <span class="min-w-0 flex-1">
    <span class="block truncate font-display text-sm font-semibold text-foreground">{{ $item->title }}</span>
    <span class="block truncate font-mono text-[11px] text-muted-foreground">{{ $item->user->username ?? 'Unknown author' }}</span>
  </span>
  <i class="fas fa-arrow-right text-xs text-muted-foreground" aria-hidden="true"></i>
</a>