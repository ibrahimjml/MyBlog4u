<form edit-comment="{{ $comment->id }}" class="edit-form mt-3 hidden rounded-xl border border-border bg-background p-3"
  action="{{ route('edit.comment', $comment) }}" method="POST">
  @csrf
  @method('PUT')

  @if($comment->parent_id)
    <input type="hidden" name="parent_id" value="{{ $comment->parent_id }}">
  @endif

  <textarea name="content" id="content" cols="40"
    class="min-h-[72px] w-full resize-y rounded-lg border border-border bg-background px-3 py-2 text-sm text-foreground focus:border-primary focus:outline-none focus:ring-2 focus:ring-ring/20 @error('content') border-destructive @enderror">{{ $comment->content }}</textarea>

  @error('content')
    <p class="mt-2 text-xs font-medium text-destructive">{{ $message }}</p>
  @enderror

  <div class="mt-2 flex justify-end">
    <button type="submit"
      class="inline-flex items-center justify-center rounded-full bg-primary px-4 py-1.5 font-mono text-[10px] font-semibold uppercase tracking-[0.12em] text-primary-foreground transition-opacity hover:opacity-90">
      Save
    </button>
  </div>
</form>
