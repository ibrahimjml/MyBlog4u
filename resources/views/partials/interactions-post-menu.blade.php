{{-- start trigger observer Interactions menu --}}
<div id="action-bar-trigger" class="h-px w-full"></div>

<div id="action-bar"
  class="mx-auto mb-8 mt-10 flex w-[calc(100%-2rem)] max-w-full flex-nowrap items-center justify-center gap-0.5 rounded-full border border-border bg-card/95 px-1.5 py-2 text-foreground shadow-sm backdrop-blur sm:w-fit sm:gap-2 sm:px-3">

  @if($post->status === \App\Enums\PostStatus::PUBLISHED)
    @auth
      <span onclick="fetchLike(this)" role="button" tabindex="0" title="Like"
        class="inline-flex h-9 w-9 cursor-pointer items-center justify-center rounded-full text-muted-foreground transition-colors hover:bg-secondary hover:text-foreground">
        <i class="fa-heart like-icon {{ $post->is_liked() ? 'fas text-primary' : 'far' }}" data-id="{{ $post->id }}"></i>
      </span>
    @else
      <span onclick="window.location.href='{{ route('login') }}'" role="button" tabindex="0" title="Like"
        class="inline-flex h-9 w-9 cursor-pointer items-center justify-center rounded-full text-muted-foreground transition-colors hover:bg-secondary hover:text-foreground">
        <i class="far fa-heart like-icon"></i>
      </span>
    @endauth

    <span title="View who liked" id="likes-count"
      class="open-view-model cursor-pointer px-1 font-mono text-xs text-muted-foreground transition-colors hover:text-foreground">
      {{ $post->likes_count }}
    </span>
  @else
    <span class="px-2 font-mono text-xs text-muted-foreground">--</span>
  @endif

  @if($post->allow_comments && $post->status === \App\Enums\PostStatus::PUBLISHED)
    <div class="mx-0.5 h-4 w-px bg-border sm:mx-1"></div>
    <div class="flex items-center justify-center">
      @auth
        <span title="Write a comment" id="openModel" role="button" tabindex="0"
          class="inline-flex h-9 w-9 cursor-pointer items-center justify-center rounded-full text-muted-foreground transition-colors hover:bg-secondary hover:text-foreground">
          <i class="far fa-comment"></i>
        </span>
      @else
        <span onclick="window.location.href='{{ route('login') }}'" role="button" tabindex="0" title="Write a comment"
          class="inline-flex h-9 w-9 cursor-pointer items-center justify-center rounded-full text-muted-foreground transition-colors hover:bg-secondary hover:text-foreground">
          <i class="far fa-comment"></i>
        </span>
      @endauth
      <span class="px-1 font-mono text-xs text-muted-foreground">{{ $post->totalcomments_count }}</span>
    </div>
  @else
    <div class="mx-0.5 h-4 w-px bg-border sm:mx-1"></div>
    <span class="inline-flex h-9 w-9 items-center justify-center rounded-full text-muted-foreground/40" title="Comments are closed">
      <i class="far fa-comment"></i>
    </span>
  @endif

  @if(auth()->check() && auth()->user()->is($post->user) && $post->status === \App\Enums\PostStatus::PUBLISHED)
    <div class="mx-0.5 h-4 w-px bg-border sm:mx-1"></div>
    <span id="openviewsmodel" role="button" tabindex="0" title="Post views"
      class="inline-flex h-9 cursor-pointer items-center justify-center gap-1 rounded-full px-1.5 text-muted-foreground transition-colors hover:bg-secondary hover:text-foreground">
      <i class="far fa-eye"></i>
      <span class="font-mono text-xs">{{ $post->views }}</span>
    </span>
  @endif

  <div id="divider" class="mx-0.5 hidden h-4 w-px bg-border sm:mx-1 lg:hidden"></div>
  <button type="button" title="Table of contents"
    class="open-tocmodel hidden h-9 w-9 cursor-pointer items-center justify-center rounded-full text-muted-foreground transition-colors hover:bg-secondary hover:text-foreground lg:hidden inline-flex">
    <i class="fas fa-list"></i>
  </button>

  <div class="mx-0.5 h-4 w-px bg-border sm:mx-1"></div>

  @auth
    <span title="Save" role="button" tabindex="0" onclick="savedTo(this,{{ $post->id }})"
      class="inline-flex h-9 w-9 cursor-pointer items-center justify-center rounded-full text-muted-foreground transition-colors hover:bg-secondary hover:text-foreground">
      <i class="fa-bookmark bookmark-icon {{ in_array($post->id, session('saved-to', [])) ? 'fas text-primary' : 'far' }}"></i>
    </span>
  @else
    <span title="Save" role="button" tabindex="0" onclick="window.location.href='{{ route('login') }}'"
      class="inline-flex h-9 w-9 cursor-pointer items-center justify-center rounded-full text-muted-foreground transition-colors hover:bg-secondary hover:text-foreground">
      <i class="far fa-bookmark bookmark-icon"></i>
    </span>
  @endauth

  <div class="mx-0.5 h-4 w-px bg-border sm:mx-1"></div>

  <div class="relative">
    <span onclick="toggleShareMenu()" role="button" tabindex="0" title="Share"
      class="inline-flex h-9 w-9 cursor-pointer items-center justify-center rounded-full text-muted-foreground transition-colors hover:bg-secondary hover:text-foreground">
      <i class="far fa-share-square"></i>
    </span>
    @include('partials.share-menu', ['top' => '-top-44', 'right' => 'right-0'])
  </div>

  @auth
    <div @class([
      'relative flex items-center',
      'hidden' => auth()->user()->cannot('report', $post) && auth()->user()->is($post->user),
    ])>
      <div class="mx-0.5 h-4 w-px bg-border sm:mx-1"></div>
      <span id="openmoremodel" role="button" tabindex="0" title="More options"
        class="inline-flex h-9 w-9 cursor-pointer items-center justify-center rounded-full text-muted-foreground transition-colors hover:bg-secondary hover:text-foreground">
        <i class="fas fa-ellipsis-v"></i>
      </span>
      @include('partials.more-menu')
    </div>
  @endauth
</div>

<div id="action-bar-end"></div>

@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const actionBar = document.getElementById('action-bar');
      const trigger = document.getElementById('action-bar-trigger');
      const endTrigger = document.getElementById('action-bar-end');

      if (!actionBar || !trigger || !endTrigger) return;

      const positionObserver = new IntersectionObserver(entries => {
        entries.forEach(entry => {
          if (!entry.isIntersecting) {
            actionBar.classList.add('fixed-bottom');
            actionBar.classList.remove('hidden-bottom');
          } else {
            actionBar.classList.remove('fixed-bottom', 'hidden-bottom');
          }
        });
      }, { threshold: 0 });

      const endObserver = new IntersectionObserver(entries => {
        entries.forEach(entry => {
          if (!entry.isIntersecting && entry.boundingClientRect.top > 0) {
            return;
          }

          if (!entry.isIntersecting && entry.boundingClientRect.top <= 0) {
            actionBar.classList.add('hidden-bottom');
          } else {
            actionBar.classList.remove('hidden-bottom');
          }
        });
      }, { threshold: 0 });

      positionObserver.observe(trigger);
      endObserver.observe(endTrigger);
    });
  </script>
@endpush
