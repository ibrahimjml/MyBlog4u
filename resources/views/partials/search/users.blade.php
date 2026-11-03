<a data-search-link href="{{ route('profile.home', $item->username) }}" class="search-result-row">
  <img src="{{ $item->avatar_url }}" alt="" class="h-10 w-10 rounded-full object-cover">
  <span class="min-w-0 flex-1">
    <span class="block truncate font-display text-sm font-semibold text-foreground">{{ $item->name }}</span>
    <span class="block truncate font-mono text-[11px] text-muted-foreground">{{ '@' . $item->username }}</span>
  </span>
  <i class="fas fa-arrow-right text-xs text-muted-foreground" aria-hidden="true"></i>
</a>