<x-layout>

  <div class="mx-auto w-full max-w-4xl px-6 py-10 md:py-14">

    <div class="flex flex-wrap items-end justify-between gap-4">
      <div>
        <p class="font-mono text-xs text-primary">~/bookmarks</p>
        <h1 class="mt-2 font-display text-3xl font-bold tracking-tight md:text-4xl">Saved Posts</h1>
        <p class="mt-2 font-mono text-xs text-muted-foreground">
          {{ $posts->total() }} {{ Str::plural('post', $posts->total()) }} in your reading list
        </p>
      </div>

      <a href="{{ route('blog') }}"
        class="inline-flex items-center gap-2 rounded-full border border-border bg-card px-4 py-2 font-mono text-xs text-foreground transition-colors hover:bg-secondary">
        <i class="fas fa-arrow-left text-primary" aria-hidden="true"></i>
        Back to blog
      </a>
    </div>

    <div id="savedPostsContainer" class="mt-6">
      @foreach ($posts as $post)
        <div id="post-{{ $post->id }}" class="saved-post">
          <x-postcard :post="$post" :showSaveButton="true" />
        </div>
      @endforeach

      <div id="noSavedMessage"
        class="{{ $posts->isEmpty() ? '' : 'hidden' }} rounded-2xl border border-dashed border-border bg-card px-6 py-16 text-center">
        <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-full border border-border bg-secondary">
          <i class="far fa-bookmark text-xl text-muted-foreground" aria-hidden="true"></i>
        </span>
        <h2 class="mt-5 font-display text-xl font-semibold text-foreground">No saved posts yet</h2>
        <p class="mt-2 font-mono text-xs text-muted-foreground">
          Bookmark articles while browsing and they will show up here.
        </p>
        <a href="{{ route('blog') }}"
          class="mt-6 inline-flex items-center gap-2 rounded-full bg-foreground px-4 py-2 font-mono text-xs text-background transition-opacity hover:opacity-80">
          Browse the blog
        </a>
      </div>
    </div>

    @if($posts->hasPages())
      <div class="mt-8">
        {!! $posts->links() !!}
      </div>
    @endif

  </div>

</x-layout>
