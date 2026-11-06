<x-layout>
  @push('vite')
    @vite(['resources/js/prism.js', 'resources/js/medium-zoom.js'])
  @endpush

  <x-status-banner :post="$post"/> <!-- status banner 'pending|draft'-->

  <x-ad-placement :ads="$ads" :position="\App\Enums\Adplacements\AdPosition::TOP_ARTICLE" />

  <div id="sticky-trigger" class="h-px w-full"></div> <!-- sticky nav observer trigger-->

  <div id="stickyBar" class="sticky-bar">
    <div class="mx-auto flex max-w-6xl items-center justify-center gap-2 px-4 py-3">
      <a href="{{ route('blog') }}"
        class="shrink-0 font-mono text-[11px] font-semibold uppercase tracking-[0.18em] text-primary transition-opacity hover:opacity-75">
        Blog
      </a>
      <span class="text-muted-foreground/60" aria-hidden="true">/</span>
      <a href="{{ route('single.post', $post->slug) }}"
        class="max-w-[62vw] truncate font-mono text-[11px] uppercase tracking-[0.12em] text-foreground transition-colors hover:text-primary">
        {{ $post->title }}
      </a>
    </div>
  </div>

  <div class="mx-auto max-w-6xl px-4 sm:px-6">
    <nav aria-label="Breadcrumb"
      class="flex flex-wrap items-center justify-center gap-2 pt-6 font-mono text-[10px] uppercase tracking-[0.18em] text-muted-foreground sm:text-[11px]">
      <span class="text-primary/60" aria-hidden="true">~</span>
      <a href="{{ route('blog') }}" class="transition-colors hover:text-primary">Blog</a>
      <span class="text-border" aria-hidden="true">/</span>
      <span class="max-w-[70vw] truncate text-foreground">{{ $post->title }}</span>
      <span class="text-primary/60" aria-hidden="true">~</span>
    </nav>

    <header class="mx-auto max-w-4xl pb-2 pt-7 text-center sm:pt-10">
      @if($post->hashtags->isNotEmpty())
        <div class="flex flex-wrap items-center justify-center gap-2">
          @foreach($post->hashtags as $tag)
            <a href="{{ route('viewhashtag', $tag->name) }}"
              class="rounded-full border border-primary/20 bg-primary/10 px-2.5 py-1 font-mono text-[10px] tracking-[0.14em] text-primary transition-colors hover:bg-primary hover:text-primary-foreground">
              #{{ $tag->name }}
            </a>
          @endforeach
        </div>
      @elseif($post->categories->isNotEmpty())
        <div class="flex flex-wrap items-center justify-center gap-2">
          @foreach($post->categories->take(3) as $category)
            <span
              class="rounded-full border border-primary/20 bg-primary/10 px-2.5 py-1 font-mono text-[10px] tracking-[0.14em] text-primary">
              {{ $category->name }}
            </span>
          @endforeach
        </div>
      @endif

      <h1
        class="mt-5 text-balance font-display text-3xl font-bold leading-[1.08] tracking-[-0.035em] text-foreground sm:text-4xl lg:text-5xl xl:text-[3.5rem]">
        {{ $post->title }}
      </h1>

      @if($post->short_excerpt)
        <p class="mx-auto mt-5 max-w-2xl text-pretty text-base leading-relaxed text-muted-foreground sm:text-lg">
          {!! $post->short_excerpt !!}
        </p>
      @endif

      <div
        class="mt-7 flex flex-wrap items-center justify-center gap-x-3 gap-y-2 font-mono text-[10px] uppercase tracking-[0.15em] text-muted-foreground sm:text-[11px]">
        <a href="{{ route('profile.home', $post->user->username) }}"
          class="flex items-center gap-2 normal-case tracking-normal text-foreground transition-colors hover:text-primary">
          <img src="{{ $post->user->avatar_url }}" alt="{{ $post->user->name }}"
            class="h-7 w-7 rounded-full border border-border object-cover" loading="lazy">
          <span class="text-xs font-semibold">{{ '@' . $post->user->username }}</span>
        </a>
        <span class="text-border" aria-hidden="true">/</span>
        <span title="Published {{ $post->created_at->format('F d, Y h:i A') }}">
          {{ $post->created_at->format('M d, Y') }}
        </span>
        <span class="text-border" aria-hidden="true">/</span>
        <span>{{ $post->readingMinutes }} min read</span>
      </div>
    </header>

    <figure class="relative mx-auto mt-9 overflow-hidden rounded-2xl border border-border bg-secondary">
      <img src="{{ $post->image_url }}" alt="{{ $post->title }}"
        class="aspect-[16/9] max-h-[34rem] w-full object-cover object-center sm:aspect-[16/8]">
       @if($post->is_featured)
             <span
               class="absolute left-3 top-3 rounded-full bg-primary px-2.5 py-1 font-mono text-[10px] font-semibold uppercase tracking-widest text-primary-foreground">
               Featured
             </span>
        @endif
      @can('update', $post)
        @include('partials.delete-edit-post-model')
      @endcan
    </figure>
  </div>

  <div class="mx-auto my-12 grid max-w-6xl grid-cols-1 gap-10 px-4 sm:px-6 lg:my-14 lg:grid-cols-[15rem_minmax(0,1fr)] lg:gap-14">
      <!-- begin -desktop TOC -->
    <aside class="hidden lg:block">
      <div class="max-h-[calc(100vh-4rem)] overflow-y-auto pr-2 lg:sticky lg:top-8">
        <div id="toc-aside" class="hidden">
          <p class="font-mono text-[10px] font-semibold uppercase tracking-[0.22em] text-muted-foreground">
            On this page
          </p>
          <nav class="mt-4" aria-label="Table of contents">
            <ul id="toc-aside-links" class="space-y-0.5"></ul>
          </nav>
        </div>
      </div>
    </aside>  <!-- end -desktop TOC -->

    <div class="min-w-0">
      <article id="description" class="published-content">
        @php
          $paragraphs = preg_split('/<\/p>/', $post->description, -1, PREG_SPLIT_NO_EMPTY);
        @endphp

        @foreach ($paragraphs as $index => $part)
          {!! $part !!}

          @if($index === 6)
            <x-ad-placement :ads="$ads" :position="\App\Enums\Adplacements\AdPosition::INNER_ARTICLE" />
          @endif
        @endforeach
      </article>

      <div class="mt-10">
        <x-ad-placement :ads="$ads" :position="\App\Enums\Adplacements\AdPosition::BOTTOM_ARTICLE" />
      </div>

      @include('partials.interactions-post-menu')

      <section class="mt-12 overflow-hidden rounded-2xl bg-foreground p-5 text-background sm:p-7">
        <div class="flex flex-col gap-5 sm:flex-row sm:items-start sm:justify-between">
          <div class="flex items-start gap-4">
            <a href="{{ route('profile.home', $post->user->username) }}" class="shrink-0">
              <img src="{{ $post->user->avatar_url }}" alt="{{ $post->user->name }}"
                class="h-14 w-14 rounded-full border border-background/20 object-cover" loading="lazy">
            </a>
            <div>
              <p class="font-mono text-[10px] uppercase tracking-[0.22em] text-background/50">Written by</p>
              <a href="{{ route('profile.home', $post->user->username) }}"
                class="mt-1 block font-display text-xl font-semibold text-background transition-colors hover:text-primary">
                {{ $post->user->name }}
              </a>
              <p class="mt-1 text-xs text-background/50">
                {{ '@' . $post->user->username }} &middot; member since {{ $post->user->created_at->format('Y') }}
              </p>
            </div>
          </div>

          <a href="{{ route('profile.home', $post->user->username) }}"
            class="inline-flex w-fit items-center gap-2 rounded-full border border-background/20 px-4 py-2 font-mono text-[10px] uppercase tracking-[0.16em] text-background/75 transition-colors hover:border-primary hover:text-primary">
            View profile <i class="fas fa-arrow-right text-[9px]" aria-hidden="true"></i>
          </a>
        </div>

        @if($post->user->bio)
          <p class="mt-5 max-w-2xl text-sm leading-relaxed text-background/70">
            {{ Str::limit(strip_tags($post->user->bio), 220) }}
          </p>
        @endif
      </section>

      @if($morearticles->isNotEmpty())
        <section class="mt-14 border-t border-border pt-10">
          <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
              <p class="font-mono text-[10px] font-semibold uppercase tracking-[0.22em] text-primary">From the author</p>
              <h2 class="mt-2 font-display text-2xl font-bold tracking-tight text-foreground sm:text-3xl">
                More by {{ '@' . $post->user->username }}
              </h2>
            </div>
            <a href="{{ route('profile.home', $post->user->username) }}"
              class="font-mono text-[10px] uppercase tracking-[0.16em] text-muted-foreground transition-colors hover:text-primary">
              View profile
            </a>
          </div>

          <div class="mt-6 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($morearticles as $article)
              <x-article-card :post="$article" :show-author="true" />
            @endforeach
          </div>
        </section>
      @endif

      @if($latestblogs->isNotEmpty())
        <section class="mt-14 border-t border-border pt-10">
          <div class="flex flex-wrap items-end justify-between gap-4">
          <div>
            <p class="font-mono text-[10px] font-semibold uppercase tracking-[0.22em] text-primary">Continue exploring</p>
            <h2 class="mt-2 font-display text-2xl font-bold tracking-tight text-foreground sm:text-3xl">Keep Reading</h2>
          </div>
          <div>
             <a href="{{ route('blog') }}"
              class="font-mono text-[10px] uppercase tracking-[0.16em] text-muted-foreground transition-colors hover:text-primary">
              View All
            </a>
          </div>
        </div>
          <div class="mt-6 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($latestblogs->take(3) as $blog)
              <x-article-card :post="$blog" />
            @endforeach
          </div>
        </section>
      @endif
    </div>
  </div>

  <div id="containerheart"></div>

  @auth
    @include('partials.comments-model')
  @endauth

  @include('partials.table-of-contents-model')

  @auth
    @include('partials.view-who-liked-model')
    @include('partials.who-viewedpost-model')
    @include('partials.reports-model')
  @endauth

  @push('styles')
    <style>
      .sticky-bar {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 50;
        background-color: oklch(var(--background) / 0.94);
        border-bottom: 1px solid oklch(var(--border) / 0.7);
        box-shadow: 0 1px 0 oklch(var(--foreground) / 0.04);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s ease;
      }

      .sticky-bar::after {
        content: "";
        position: absolute;
        bottom: 0;
        left: 0;
        height: 2px;
        width: var(--progress-width, 0%);
        background-color: oklch(var(--primary));
        transition: width 0.1s linear;
      }

      .sticky-bar.visible {
        opacity: 1;
        pointer-events: auto;
      }

      #action-bar {
        transition: transform 0.3s ease, opacity 0.3s ease;
      }

      #action-bar.fixed-bottom {
        position: fixed;
        bottom: max(1rem, env(safe-area-inset-bottom));
        left: 50%;
        transform: translateX(-50%);
        box-shadow: 0 18px 50px -12px rgb(0 0 0 / 0.35);
        z-index: 50;
      }

      #action-bar.hidden-bottom {
        transform: translate(-50%, 120%);
        opacity: 0;
        pointer-events: none;
      }

    </style>
  @endpush

  @push('scripts')
    <script>
      async function follows(eo) {
        const userId = eo.dataset.id;
        let options = {
          method: "POST",
          headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
            "Accept": "application/json"
          },
        };

        try {
          const res = await fetch(`/user/${userId}/togglefollow`, options);
          const data = await res.json();

          eo.classList.remove('text-primary', 'text-muted-foreground', 'text-destructive');

          if (data.status === 1) {
            eo.textContent = 'Unfollow';
            eo.classList.add('text-destructive');
          } else if (data.status === 0) {
            eo.textContent = 'Requested';
            eo.classList.add('text-muted-foreground');
          } else {
            eo.textContent = 'Follow';
            eo.classList.add('text-primary');
          }
        } catch (error) {
          console.error(error);
        }
      }
    </script>

    <script>
      const stickyBar = document.getElementById('stickyBar');
      const trigger = document.getElementById('sticky-trigger');
      const description = document.getElementById('description');

      if (stickyBar && trigger) {
        const observer = new IntersectionObserver(
          ([entry]) => {
            stickyBar.classList.toggle('visible', !entry.isIntersecting);
          },
          { threshold: 0 }
        );

        observer.observe(trigger);
      }

      if (stickyBar && description) {
        const updateProgress = () => {
          const maxScroll = Math.max(description.scrollHeight - window.innerHeight, 1);
          const progress = Math.min(Math.max((window.scrollY - description.offsetTop) / maxScroll, 0), 1) * 100;
          stickyBar.style.setProperty('--progress-width', progress + '%');
        };

        window.addEventListener('scroll', updateProgress, { passive: true });
        updateProgress();
      }
    </script>
  @endpush
</x-layout>
