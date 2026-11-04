<section class="rounded-2xl border border-border bg-card p-6">
  <h3 class="font-display text-lg font-semibold text-foreground">Popular Tags</h3>
  <div class="mt-2 h-0.5 w-10 bg-primary"></div>

  <div class="mt-4 flex flex-wrap gap-2">
    @foreach($tags as $tag)
      <a href="{{ route('viewhashtag', $tag->name) }}"
        @class([
            'rounded-full px-2.5 py-1 font-mono text-[11px] transition-colors',
            'bg-accent text-accent-foreground hover:opacity-80' => $tag->is_featured,
            'border border-border bg-secondary text-foreground hover:bg-accent hover:text-accent-foreground' => ! $tag->is_featured,
        ])>
        #{{ $tag->name }}
      </a>
    @endforeach
  </div>
</section>
