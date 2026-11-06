<div id="view-liked" class="fixed inset-0 z-[80] hidden flex items-center justify-center bg-foreground/60 p-4 backdrop-blur-sm">
  <div class="relative max-h-[75vh] w-full max-w-lg overflow-y-auto rounded-2xl border border-border bg-background p-5 shadow-2xl">
    <div class="pr-10">
      <p class="font-mono text-[10px] uppercase tracking-[0.22em] text-muted-foreground">Engagement</p>
      <h2 class="mt-1 font-display text-xl font-semibold text-foreground">People who liked</h2>
    </div>

    <div class="mt-4 space-y-1">
      @forelse($viewwholiked as $viewliked)
        <div class="flex items-center gap-3 rounded-xl px-2 py-2 transition-colors hover:bg-secondary">
          <a href="{{ route('profile.home', $viewliked->user->username) }}" class="flex min-w-0 items-center gap-3">
            <img src="{{ $viewliked->user->avatar_url }}" alt="{{ $viewliked->user->name }}"
              class="h-9 w-9 rounded-full object-cover" loading="lazy">
            <div class="min-w-0">
              <strong class="block truncate text-sm font-semibold text-foreground">{{ $viewliked->user->name }}</strong>
              <small class="block truncate font-mono text-[10px] text-muted-foreground">{{ '@' . $viewliked->user->username }}</small>
            </div>
          </a>

          @if(auth()->user()->isNot($viewliked->user))
            <x-follow-button :status="$authFollowings[$viewliked->user->id] ?? null" :user-id="$viewliked->user->id"
              type="icon" onclick="followss(this)"
              class="follow ml-auto flex h-6 w-6 items-center justify-center rounded-full text-xs" />
          @endif
        </div>
      @empty
        <p class="py-16 text-center text-sm text-muted-foreground">No likes yet.</p>
      @endforelse
    </div>

    <button id="close-modal" type="button" title="Close"
      class="absolute right-3 top-3 inline-flex h-8 w-8 items-center justify-center rounded-full text-muted-foreground transition-colors hover:bg-secondary hover:text-foreground">
      <i class="fas fa-times"></i>
    </button>
  </div>
</div>

@push('scripts')
  <script>
    async function followss(eo) {
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

        document.querySelectorAll(`button.follow[data-id="${userId}"]`).forEach(btn => {
          const icon = btn.querySelector('i');
          if (!icon) return;

          btn.classList.remove('bg-primary', 'bg-accent', 'bg-muted');
          icon.classList.remove('fa-check', 'fa-hourglass-half', 'fa-plus');

          if (data.status === 1) {
            btn.classList.add('bg-primary');
            icon.classList.add('fa-check');
          } else if (data.status === 0) {
            btn.classList.add('bg-accent');
            icon.classList.add('fa-hourglass-half');
          } else {
            btn.classList.add('bg-muted');
            icon.classList.add('fa-plus');
          }
        });
      } catch (error) {
        console.error(error);
      }
    }

    const openmodel = document.getElementsByClassName('open-view-model')[0];
    const viewmodel = document.getElementById('view-liked');
    const closemodel = document.getElementById('close-modal');

    if (openmodel && viewmodel && closemodel) {
      openmodel.addEventListener('click', () => {
        viewmodel.classList.remove('hidden');
        document.body.classList.add('no-scroll');
      });

      closemodel.addEventListener('click', () => {
        viewmodel.classList.add('hidden');
        document.body.classList.remove('no-scroll');
      });

      viewmodel.addEventListener('click', event => {
        if (event.target === viewmodel) {
          viewmodel.classList.add('hidden');
          document.body.classList.remove('no-scroll');
        }
      });
    }
  </script>
@endpush
