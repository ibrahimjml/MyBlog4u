<form delete-comment="{{ $comment->id }}" action="{{ route('delete.comment', $comment->id) }}" method="POST">
  @csrf
  @method('delete')

  <button type="submit"
    class="block w-full rounded-lg px-3 py-2 text-left text-sm font-medium text-destructive transition-colors hover:bg-destructive/10">
    Delete
  </button>
</form>
