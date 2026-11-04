<section class="rounded-2xl border border-border bg-card p-6">
  <h3 class="font-display text-lg font-semibold text-foreground">Who to Follow</h3>
  <div class="mt-2 h-0.5 w-10 bg-primary"></div>

  @auth
    <div class="mt-4 flex flex-col gap-1">
      @foreach($users as $user)
        <div class="flex items-center gap-3 rounded-lg px-2 py-2 transition-colors hover:bg-secondary">
          <a href="{{ route('profile.home', $user->username) }}" class="flex min-w-0 flex-1 items-center gap-3">
            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }} profile"
              class="h-8 w-8 shrink-0 rounded-full object-cover">
            <div class="flex min-w-0 flex-col">
              <strong class="truncate text-sm font-semibold text-foreground">{{ $user->name }}</strong>
              <small class="truncate font-mono text-[11px] text-muted-foreground">{{ '@' . $user->username }}</small>
            </div>
          </a>

          @if(auth()->user()->isNot($user))
            <x-follow-button :status="$authFollowings[$user->id] ?? null" :user-id="$user->id" type="icon"
              onclick="follow(this)"
              class="follows flex h-6 w-6 shrink-0 items-center justify-center rounded-full text-xs text-white" />
          @endif
        </div>
      @endforeach
    </div>
  @else
    <div class="mt-6 flex flex-col items-center justify-center gap-3 text-center">
      <span class="flex h-14 w-14 items-center justify-center rounded-full border border-border bg-secondary">
        <i class="fas fa-lock text-muted-foreground"></i>
      </span>
      <p class="font-mono text-xs text-muted-foreground">Login to start following people.</p>
    </div>
  @endauth
</section>

@push('scripts')
  <script>
    async function follow(eo) {
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
        document.querySelectorAll(`button.follows[data-id="${userId}"]`).forEach(btn => {
          const icon = btn.querySelector('i');
          if (!icon) return;
          btn.classList.remove('bg-green-500', 'bg-yellow-500', 'bg-gray-500');
          icon.classList.remove('fa-check', 'fa-hourglass-half', 'fa-plus');


          if (data.status === 1) {
            btn.classList.add('bg-green-500');
            icon.classList.add('fa-check');
          }
          else if (data.status === 0) {
            btn.classList.add('bg-yellow-500');
            icon.classList.add('fa-hourglass-half');
          }
          else {
            btn.classList.add('bg-gray-500');
            icon.classList.add('fa-plus');
          }

        });


      } catch (error) {
        console.error(error);
      }
    }
  </script>
@endpush
