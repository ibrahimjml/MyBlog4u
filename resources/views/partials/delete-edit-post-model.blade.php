<button id="openmodel" type="button" title="Post options"
  class="absolute right-4 top-4 z-10 inline-flex h-9 w-9 items-center justify-center rounded-full border border-border/60 bg-background/90 text-muted-foreground backdrop-blur-sm transition-colors hover:bg-background hover:text-foreground">
  <i class="fas fa-ellipsis-h text-sm"></i>
</button>

<div id="model"
  class="absolute right-4 top-16 z-20 hidden w-40 space-y-0.5 rounded-xl border border-border bg-card p-1.5 shadow-xl">
  @can('update', $post)
    <a href="{{ route('edit.post', $post->slug) }}"
      class="block rounded-lg px-3 py-2 text-left text-sm font-medium text-foreground transition-colors hover:bg-secondary">
      Edit
    </a>
  @endcan

  @can('delete', $post)
    <form action="{{ route('delete.post', $post->slug) }}" method="POST"
      onsubmit="return confirm('Are you sure you want to delete this post?')">
      @csrf
      @method('DELETE')

      <button type="submit"
        class="block w-full rounded-lg px-3 py-2 text-left text-sm font-medium text-destructive transition-colors hover:bg-destructive/10">
        Delete
      </button>
    </form>
  @endcan
</div>

@push('scripts')
  <script>
    const OpenModel = document.getElementById('openmodel');
    const Model = document.getElementById('model');

    if (OpenModel && Model) {
      OpenModel.addEventListener('click', () => {
        Model.classList.toggle('hidden');
      });
    }
  </script>
@endpush
