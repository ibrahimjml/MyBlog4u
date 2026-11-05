@forelse($news as $item)
  <article
    class="group flex h-full flex-col overflow-hidden rounded-2xl border border-border bg-card transition-all duration-200 hover:-translate-y-0.5 hover:border-primary/40">
    <a href="{{ $item->link }}" target="_blank" rel="noopener noreferrer" class="flex h-full flex-col">

      <div class="relative aspect-video overflow-hidden bg-secondary">
        <img src="{{ $item->image_url }}" alt="{{ $item->title }}" loading="lazy"
          class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105">

        @if ($item->category)
          <div class="absolute left-2 top-2 flex flex-wrap gap-1">
            @foreach (explode(',', $item->category) as $cat)
              <span
                class="rounded-full bg-foreground/70 px-2 py-0.5 font-mono text-[9px] uppercase tracking-widest text-background backdrop-blur-sm">
                {{ trim($cat) }}
              </span>
            @endforeach
          </div>
        @endif
      </div>

      <div class="flex flex-1 flex-col p-5">
        <div class="flex flex-wrap items-center gap-2 font-mono text-[10px] uppercase tracking-widest text-muted-foreground">
          <span class="rounded-full bg-accent px-2 py-0.5 text-accent-foreground">
            {{ optional($item->source)->name ?? 'Unknown source' }}
          </span>
          <span>{{ optional($item->created_at)->diffForHumans() }}</span>
        </div>

        <h2 class="mt-3 font-display text-base font-semibold leading-snug line-clamp-2 transition-colors group-hover:text-primary">
          {{ $item->title }}
        </h2>

        @if($item->description)
          <p class="mt-2 font-sans text-sm leading-relaxed text-muted-foreground line-clamp-3">
            {{ Str::limit(strip_tags($item->description), 160) }}
          </p>
        @endif

        <span class="mt-auto pt-4 font-mono text-[11px] text-primary">read story &rarr;</span>
      </div>
    </a>
  </article>
@empty
  <div class="col-span-full rounded-2xl border border-dashed border-border bg-card px-6 py-16 text-center">
    <p class="font-display text-lg font-semibold text-foreground">No news found</p>
    <p class="mt-2 font-mono text-xs text-muted-foreground">Try another source filter.</p>
  </div>
@endforelse
