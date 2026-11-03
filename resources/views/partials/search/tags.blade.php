<a data-search-link href="{{ route('viewhashtag', $item->name) }}" class="search-result-row">
  <span class="search-result-icon"><i class="fas {{$icon}}" aria-hidden="true"></i></span>
  <span class="min-w-0 flex-1 truncate font-mono text-xs font-semibold text-foreground">{{ $item->name }}</span>
  <i class="fas fa-arrow-right text-xs text-muted-foreground" aria-hidden="true"></i>
</a>