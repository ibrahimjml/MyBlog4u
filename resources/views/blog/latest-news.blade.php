<section class="rounded-2xl border border-border bg-card p-6">
  <h3 class="font-display text-lg font-semibold text-foreground">Latest News</h3>
  <div class="mt-2 h-0.5 w-10 bg-primary"></div>

  <div class="mt-4 flex flex-col gap-1">
    @foreach($news as $new)
      <a href="{{ route('news') }}?source={{ $new->name }}" rel="nofollow"
        class="flex items-center gap-3 rounded-lg px-2 py-1.5 font-mono text-xs text-muted-foreground transition-colors hover:bg-secondary hover:text-foreground">
        <img src="{{ $new->favicon_url }}" alt="{{ $new->name }}" width="20" height="20"
          class="rounded-full">
        <span class="flex-1 truncate font-semibold">{{ $new->name }}</span>
        <span class="text-[10px]">{{ $new->posts_count }}</span>
      </a>
    @endforeach
  </div>
</section>
