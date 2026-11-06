<form comment-form="{{ $post->id }}" action="/comment/{{ $post->id }}" method="POST" class="space-y-3">
  @csrf

  <div class="flex items-center gap-3">
    <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}"
      class="h-8 w-8 rounded-full object-cover">

    <div class="min-w-0">
      <p class="truncate text-sm font-semibold text-foreground">{{ auth()->user()->name }}</p>
      <p class="font-mono text-[10px] uppercase tracking-[0.12em] text-muted-foreground">Commenting as {{ '@' . auth()->user()->username }}</p>
    </div>
  </div>

  <div
    class="overflow-hidden rounded-xl border bg-background transition-colors focus-within:border-primary/50 focus-within:ring-2 focus-within:ring-ring/20 @error('content') border-destructive @else border-border @enderror">
    <textarea
      class="block min-h-[96px] w-full resize-y border-0 bg-transparent px-3 py-3 text-sm text-foreground placeholder:text-muted-foreground focus:ring-0 focus:outline-none"
      placeholder="Write a thoughtful comment" name="content" id="content" rows="4" cols="80"></textarea>

    <div class="flex items-center justify-between gap-3 border-t border-border px-3 py-2">
      <span class="font-mono text-[10px] text-muted-foreground">Be kind and constructive</span>

      <button type="submit"
        class="inline-flex items-center justify-center rounded-full bg-primary px-4 py-1.5 font-mono text-[10px] font-semibold uppercase tracking-[0.12em] text-primary-foreground transition-opacity hover:opacity-90">
        Comment
      </button>
    </div>
  </div>

  @error('content')
    <p class="text-xs font-medium text-destructive">{{ $message }}</p>
  @enderror
</form>
