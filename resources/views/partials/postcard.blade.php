@php
  $isSaved = in_array($post->id, session('saved-to', []));
@endphp

<article
  class="group grid h-full mb-4 overflow-hidden rounded-2xl border border-border bg-card transition-all duration-200 hover:-translate-y-0.5 hover:border-primary/40 sm:grid-cols-5">

  {{-- Media --}}
  <div class="relative min-h-[220px] bg-secondary sm:col-span-2">
    <a href="{{ route('single.post', $post->slug) }}" class="absolute inset-0 block overflow-hidden">
      <img loading="lazy" src="{{ $post->image_url }}" alt="{{ $post->title }}"
           class="h-full w-full object-center blur-md bg-gray-200 transition-transform duration-500 ease-out group-hover:scale-105"
           onload="this.classList.remove('blur-md', 'bg-gray-200')"
        >
    </a>

    @if($post->is_featured)
      <span
        class="absolute left-3 top-3 rounded-full bg-primary px-2.5 py-1 font-mono text-[10px] font-semibold uppercase tracking-widest text-primary-foreground">
        Featured
      </span>
    @endif
  </div>

  {{-- Content --}}
  <div class="flex min-w-0 flex-col gap-3 p-4 sm:col-span-3 sm:p-5">

    {{-- Author + follow + save --}}
    <div class="flex items-start justify-between gap-3">
      <div class="flex min-w-0 items-center gap-3">
        <a href="{{ route('profile.home', $post->user->username) }}" class="shrink-0">
          <img src="{{ $post->user->avatar_url }}" alt="{{ $post->user->name }}"
            class="h-8 w-8 rounded-full border border-border object-cover">
        </a>
        <div class="min-w-0">
          <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('profile.home', $post->user->username) }}"
              class="truncate font-display text-sm font-semibold text-foreground transition-colors hover:text-primary">
              {{ $post->user->username }}
            </a>
            @if(auth()->check() && auth()->id() !== $post->user_id)
              <x-follow-button :status="$authFollowings[$post->user_id] ?? null" :user-id="$post->user_id" type="label"
                onclick="fetchfollow(this)"
                class="follow rounded-full px-2.5 py-0.5 font-mono text-[10px] font-semibold transition-colors" />
            @endif
          </div>
          <p class="font-mono text-[11px] text-muted-foreground">{{ $post->created_at->diffForHumans() }}</p>
        </div>
      </div>

      <div class="shrink-0">
        @if (!empty($showSaveButton))
          <button type="button" onclick="unsavedposts({{ $post->id }})"
            class="rounded-full border border-destructive/40 px-3 py-1 font-mono text-[11px] text-destructive transition-colors hover:bg-destructive/10">
            Remove
          </button>
        @else
          @auth
            <button type="button" onclick="save(this,{{ $post->id }})"
              class="relative flex h-8 w-8 cursor-pointer items-center justify-center rounded-full border border-border bg-secondary transition-colors hover:text-primary {{ $isSaved ? 'text-primary' : 'text-muted-foreground' }}">
              <i class="{{ $isSaved ? 'fas' : 'far' }} fa-bookmark text-xs"></i>
              <span
                class="pointer-events-none absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 text-[11px] leading-none {{ $isSaved ? 'text-primary' : 'text-muted-foreground' }}">
                {{ $isSaved ? '✓' : '+' }}
              </span>
            </button>
          @else
            <a href="{{ route('login') }}" title="Login to save"
              class="relative flex h-8 w-8 items-center justify-center rounded-full border border-border bg-secondary text-muted-foreground transition-colors hover:text-primary">
              <i class="far fa-bookmark text-xs"></i>
              <span
                class="pointer-events-none absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 text-[11px] leading-none">+</span>
            </a>
          @endauth
        @endif
      </div>
    </div>

    {{-- Title + excerpt --}}
    <a href="{{ route('single.post', $post->slug) }}" class="block">
      <h2 class="font-display text-lg font-semibold leading-snug line-clamp-2 transition-colors group-hover:text-primary">
        {{ $post->title }}
      </h2>
      @if($post->short_excerpt)
        <p class="mt-2 font-sans text-sm leading-relaxed text-muted-foreground line-clamp-3">
          {!! $post->short_excerpt !!}
        </p>
      @endif
    </a>

    {{-- Categories + hashtags --}}
    @if($post->categories->isNotEmpty() || $post->hashtags->isNotEmpty())
      <div class="flex flex-wrap items-center gap-2">
        @foreach($post->categories as $category)
          @php
            $isActive = isset($currentCategory) && $currentCategory->name == $category->name;
          @endphp
          <a href="{{ route('viewcategory', $category->name) }}" @class([
              'rounded-full px-2 py-0.5 font-mono text-[10px] transition-colors',
              'bg-primary text-primary-foreground' => $isActive,
              'bg-accent text-accent-foreground' => !$isActive && $category->is_featured,
              'border border-border bg-secondary text-muted-foreground hover:text-foreground' =>
                  !$isActive && !$category->is_featured,
          ])>
            {{ $category->name }}
          </a>
        @endforeach

        @foreach($post->hashtags as $hashtag)
          @php
            $isActive = isset($currentHashtag) && $currentHashtag->name == $hashtag->name;
          @endphp
          <a href="{{ route('viewhashtag', $hashtag->name) }}" @class([
              'rounded-full px-2 py-0.5 font-mono text-[10px] transition-colors',
              'bg-primary text-primary-foreground' => $isActive,
              'bg-accent text-accent-foreground' => !$isActive && $hashtag->is_featured,
              'border border-border bg-secondary text-muted-foreground hover:text-foreground' =>
                  !$isActive && !$hashtag->is_featured,
          ])>
            #{{ $hashtag->name }}
          </a>
        @endforeach
      </div>
    @endif

    {{-- Stats --}}
    <div
      class="mt-auto flex flex-wrap items-center gap-x-3 gap-y-1 border-t border-border pt-3 font-mono text-[11px] text-muted-foreground">
      @if($post->likes_count)
        <span>{{ $post->likes_count }} {{ Str::plural('like', $post->likes_count) }}</span>
      @endif
      @if($post->totalcomments_count ?? $post->comments_count)
        <span>{{ $post->totalcomments_count ?? $post->comments_count }}
          {{ Str::plural('comment', $post->totalcomments_count ?? $post->comments_count) }}</span>
      @endif
      <span>{{ $post->views ?? 0 }} {{ Str::plural('view', $post->views ?? 0) }}</span>
      <span class="ml-auto">{{ $post->readingMinutes }} min</span>
    </div>

  </div>
</article>

@once
  @push('scripts')
    <script src="{{ asset('js/savepost.js') }}"></script>
  @endpush
@endonce
