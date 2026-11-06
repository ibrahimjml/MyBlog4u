<div id="viewsmodel" class="fixed inset-0 z-[80] hidden flex items-center justify-center bg-foreground/60 p-4 backdrop-blur-sm">
  <div class="relative max-h-[75vh] w-full max-w-lg overflow-y-auto rounded-2xl border border-border bg-background p-5 shadow-2xl">
    <div class="pr-10">
      <p class="font-mono text-[10px] uppercase tracking-[0.22em] text-muted-foreground">Analytics</p>
      <h2 class="mt-1 font-display text-xl font-semibold text-foreground">People who viewed your post</h2>
    </div>

    <div class="mt-4 space-y-1">
      @forelse($post->viewers as $viewer)
        <div class="flex items-center gap-3 rounded-xl px-2 py-2 transition-colors hover:bg-secondary">
          <a href="{{ route('profile.home', $viewer->username) }}" class="flex min-w-0 items-center gap-3">
            <img src="{{ $viewer->avatar_url }}" alt="{{ $viewer->name }}"
              class="h-9 w-9 rounded-full object-cover" loading="lazy">
            <div class="min-w-0">
              <strong class="block truncate text-sm font-semibold text-foreground">{{ $viewer->name }}</strong>
              <small class="block truncate font-mono text-[10px] text-muted-foreground">{{ '@' . $viewer->username }}</small>
            </div>
          </a>

          @if(auth()->user()->isNot($viewer))
            @php
              $isFollowing = in_array($viewer->id, $authFollowings);
            @endphp

            <button data-id="{{ $viewer->id }}" onclick="follow(this)"
              class="ml-auto flex h-6 w-6 items-center justify-center rounded-full text-xs text-primary-foreground {{ $isFollowing ? 'bg-primary' : 'bg-foreground' }}">
              <i class="fas fa-{{ $isFollowing ? 'check' : 'plus' }}"></i>
            </button>
          @endif
        </div>
      @empty
        <p class="py-16 text-center text-sm text-muted-foreground">No viewers yet.</p>
      @endforelse
    </div>

    <button id="close-views-modal" type="button" title="Close"
      class="absolute right-3 top-3 inline-flex h-8 w-8 items-center justify-center rounded-full text-muted-foreground transition-colors hover:bg-secondary hover:text-foreground">
      <i class="fas fa-times"></i>
    </button>
  </div>
</div>

@push('scripts')
  <script>
    async function follow(eo) {
      const icon = eo.querySelector('i');
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

        icon.classList.toggle('fa-check', data.attached);
        icon.classList.toggle('fa-plus', !data.attached);

        eo.classList.toggle('bg-primary', data.attached);
        eo.classList.toggle('bg-foreground', !data.attached);
      } catch (error) {
        console.error(error);
      }
    }

    @if(auth()->user()->is($post->user))
      const openviewsmodel = document.getElementById('openviewsmodel');
      const viewsmodel = document.getElementById('viewsmodel');
      const closeviewsmodel = document.getElementById('close-views-modal');

      if (openviewsmodel && viewsmodel && closeviewsmodel) {
        openviewsmodel.addEventListener('click', () => {
          viewsmodel.classList.remove('hidden');
          document.body.classList.add('no-scroll');
        });

        closeviewsmodel.addEventListener('click', () => {
          viewsmodel.classList.add('hidden');
          document.body.classList.remove('no-scroll');
        });

        viewsmodel.addEventListener('click', event => {
          if (event.target === viewsmodel) {
            viewsmodel.classList.add('hidden');
            document.body.classList.remove('no-scroll');
          }
        });
      }
    @endif
  </script>
@endpush
