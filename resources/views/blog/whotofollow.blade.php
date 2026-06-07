<div class="bg-white border border-gray-200 rounded-2xl p-6 mt-6">
  <h3 class="text-xl font-bold text-gray-800 mb-2">Who to Follow</h3>
  <div class="w-20 h-1 bg-red-600 mb-4"></div>
  @auth


    <div class="flex flex-wrap gap-2">
      @foreach($users as $user)
        <div
          class="rounded-xl flex items-center gap-2 mb-2 mt-4 w-full py-1 px-2 hover:bg-gray-200 transition-bg duration-200 ease-in-out">
          <a href="{{route('profile.home', $user->username)}}" class="flex items-center gap-3">
            <img src="{{$user->avatar_url}}"
              class="w-8 h-8 overflow-hidden flex justify-center items-center  shrink-0 grow-0 rounded-full ">
            <div class="flex flex-col gap-1">
              <strong>{{ $user->name }}</strong>
              <small>{{'@' . $user->username}}</small>
              <div>

              </div>
            </div>
          </a>
          @if(auth()->user()->isNot($user))
            <x-follow-button :status="$authFollowings[$user->id] ?? null" :user-id="$user->id" type="icon"
              onclick="follow(this)"
              class="follows w-5 h-5 text-xs ml-auto text-white rounded-full flex items-center justify-center" />
          @endif
        </div>
      @endforeach
    </div>
  @else
    <div class="flex flex-col items-center justify-center gap-4">
      <span class="h-16 w-16 px-5 py-5 flex place-content-center rounded-full border-2 border-black/50">
        <i class="fas fa-lock text-black/50"></i>
      </span>
      <p>Login to start following people.</p>
    </div>
  @endauth
</div>

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