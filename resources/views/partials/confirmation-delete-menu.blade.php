<div id="menu"
  class="fixed inset-0 z-[20] flex hidden items-center justify-center bg-foreground/40 p-4 backdrop-blur-sm">
  <div class="w-full max-w-md rounded-2xl border border-border bg-card shadow-2xl">
    <div class="flex items-start justify-between gap-4 border-b border-border px-5 py-4">
      <div class="flex items-center gap-3">
        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-destructive/10 text-destructive">
          <i class="fas fa-triangle-exclamation"></i>
        </span>
        <h2 class="font-display text-base font-semibold text-foreground">Delete your account</h2>
      </div>
      <button id="close-menu" type="button" aria-label="Close"
        class="flex h-9 w-9 items-center justify-center rounded-xl text-muted-foreground transition-colors hover:bg-secondary hover:text-foreground">
        <i class="fas fa-times"></i>
      </button>
    </div>

    <form action="{{ route('account.delete', $user->id) }}" method="POST" class="px-5 py-5">
      @csrf
      @method('DELETE')

      <p class="text-sm leading-relaxed text-muted-foreground">
        Are you sure you want to delete your account? Once your account is deleted, all of its resources and data will be permanently deleted.
      </p>

      <div class="mt-5">
        <label for="current_password" class="block font-mono text-[10px] uppercase tracking-[0.16em] text-muted-foreground">
          Password
        </label>
        <input id="current_password" type="password" name="current_password" placeholder="Enter your password"
          class="mt-2 w-full rounded-xl border border-border bg-background px-3 py-2.5 text-sm font-medium text-foreground outline-none transition-colors placeholder:font-normal placeholder:text-muted-foreground focus:border-primary focus:ring-2 focus:ring-ring/30 @error('current_password') border-destructive @enderror">
        @error('current_password')
          <p class="mt-1.5 text-xs font-medium text-destructive">{{ $message }}</p>
        @enderror
      </div>

      <div class="mt-4">
        <label for="deletion_sentence" class="block font-mono text-[10px] uppercase tracking-[0.16em] text-muted-foreground">
          Type "delete my account" to confirm
        </label>
        <input id="deletion_sentence" type="text" name="deletion_sentence" placeholder="delete my account"
          class="mt-2 w-full rounded-xl border border-border bg-background px-3 py-2.5 text-sm font-medium text-foreground outline-none transition-colors placeholder:font-normal placeholder:text-muted-foreground focus:border-primary focus:ring-2 focus:ring-ring/30 @error('deletion_sentence') border-destructive @enderror">
        @error('deletion_sentence')
          <p class="mt-1.5 text-xs font-medium text-destructive">{{ $message }}</p>
        @enderror
      </div>

      <div class="mt-6 flex flex-wrap justify-end gap-3">
        <button id="cancel-menu" type="button"
          class="inline-flex items-center gap-2 rounded-xl border border-border bg-card px-4 py-2.5 text-xs font-bold uppercase tracking-wide text-foreground transition-colors hover:bg-secondary">
          Cancel
        </button>
        <button type="submit"
          class="inline-flex items-center gap-2 rounded-xl bg-destructive px-4 py-2.5 text-xs font-bold uppercase tracking-wide text-destructive-foreground shadow-sm transition-opacity hover:opacity-90">
          <i class="fas fa-trash"></i>
          Delete account
        </button>
      </div>
    </form>
  </div>
</div>

@push('scripts')
  <script>
    const showmenu = document.getElementById('show-menu');
    const closemenu = document.getElementById('close-menu');
    const cancelmenu = document.getElementById('cancel-menu');
    const menu = document.getElementById('menu');

    if (showmenu && menu) {
      showmenu.addEventListener('click', () => {
        menu.classList.remove('hidden');
      });
    }

    [closemenu, cancelmenu].forEach((button) => {
      button?.addEventListener('click', () => {
        menu?.classList.add('hidden');
      });
    });
  </script>
@endpush
