<footer class="mt-auto w-full bg-foreground text-background">
  <div class="mx-auto grid w-full max-w-6xl gap-10 px-6 py-12 md:grid-cols-2 lg:grid-cols-3">

    <div>
      <a href="{{ route('home') }}" class="inline-flex items-baseline font-display text-xl font-semibold tracking-tight">
        myblog<span class="text-primary">4u</span>
      </a>

      <div class="mt-5 flex items-center gap-4 text-background/60">
        <a href="https://github.com/ibrahimjml" target="_blank" rel="noopener noreferrer"
          class="transition-colors hover:text-background" aria-label="GitHub">
          <i class="fab fa-github"></i>
        </a>
        <a href="https://linkedin.com/in/ibrahimjamal222" target="_blank" rel="noopener noreferrer"
          class="transition-colors hover:text-background" aria-label="LinkedIn">
          <i class="fab fa-linkedin"></i>
        </a>
      </div>
    </div>

    <nav aria-labelledby="footer-categories-title">
      <p id="footer-categories-title"
        class="font-mono text-[11px] uppercase tracking-widest text-background/50">Categories</p>
      <ul class="mt-3 flex flex-col gap-2 font-mono text-xs">
        @foreach($categories as $cat)
          <li>
            <a href="{{ route('viewcategory', $cat->name) }}"
              class="text-background/70 transition-colors hover:text-background">{{ $cat->name }}</a>
          </li>
        @endforeach
      </ul>
    </nav>

    <nav aria-labelledby="footer-site-title">
      <p id="footer-site-title" class="font-mono text-[11px] uppercase tracking-widest text-background/50">Site</p>
      <ul class="mt-3 flex flex-col gap-2 font-mono text-xs">
        @foreach($footerPages as $page)
          <li>
            <a href="{{ route('custom.page', $page->slug) }}"
              class="text-background/70 transition-colors hover:text-background">{{ $page->title }}</a>
          </li>
        @endforeach
        <li>
          <a href="{{ route('public.sitemap.index', ['key' => 'sitemap', 'extension' => 'rss']) }}"
            class="text-background/70 transition-colors hover:text-background">RSS</a>
        </li>
        <li>
          <a href="{{ route('public.sitemap.index', ['key' => 'sitemap', 'extension' => 'xml']) }}"
            class="text-background/70 transition-colors hover:text-background">Sitemap</a>
        </li>
      </ul>
    </nav>

{{--     <div>
      <p class="font-mono text-[11px] uppercase tracking-widest text-background/50">Newsletter</p>
      <form class="mt-3 flex items-center gap-2 rounded-full border border-background/20 p-1 pl-4"
        onsubmit="event.preventDefault(); this.reset();">
        <label for="footer-newsletter-email" class="sr-only">Email address</label>
        <input id="footer-newsletter-email" type="email" name="email" required placeholder="you@email.com"
          class="min-w-0 flex-1 bg-transparent py-1.5 text-sm text-background placeholder:text-background/40 focus:outline-none">
        <button type="submit"
          class="rounded-full bg-primary px-4 py-1.5 font-mono text-xs text-primary-foreground transition-opacity hover:opacity-90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary">
          Join
        </button>
      </form>
    </div> --}}

  </div>

  <div class="border-t border-background/10">
    <div
      class="mx-auto flex w-full max-w-6xl flex-col gap-3 px-6 py-6 font-mono text-xs text-background/50 sm:flex-row sm:items-center sm:justify-between">
      <p class="font-mono whitespace-nowrap">&copy; {{ date('Y') }} myblog4u.site</p>
      <div class="flex flex-wrap items-center gap-4">
        <div class="flex items-center gap-2">
          <span class="font-medium">Built on</span>
          <span class="inline-flex h-4 w-4 items-center justify-center">@svg('digital-ocean')</span>
          <span>DigitalOcean</span>
        </div>
        <div class="flex items-center gap-2">
          <i class="fas fa-code"></i>
          <span>Developed by Ibrahim Jamal</span>
        </div>
      </div>
    </div>
  </div>
</footer>
