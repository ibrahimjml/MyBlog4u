<section class="rounded-2xl border border-border bg-card p-6">
  <h3 class="font-display text-lg font-semibold text-foreground">Categories</h3>
  <div class="mt-2 h-0.5 w-10 bg-primary"></div>

  <div class="mt-4 flex flex-col gap-1">
    @foreach($categories->take(6) as $category)
      <a href="{{ route('viewcategory', $category->name) }}"
        class="flex items-center justify-between rounded-lg px-2 py-1.5 font-mono text-xs text-muted-foreground transition-colors hover:bg-secondary hover:text-foreground">
        <span>{{ $category->name }}</span>
        <span class="text-[10px] text-muted-foreground">{{ $category->posts_count }}</span>
      </a>
    @endforeach
  </div>
</section>
