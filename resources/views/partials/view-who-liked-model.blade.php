<div id="view-liked" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">

  <div class="relative bg-white h-2/4 rounded-lg p-6 w-full max-w-2xl  overflow-y-auto">
    <p class="text-2xl font-bold">People who liked</p>
    @forelse($viewwholiked as $viewliked)
    <div class="rounded-xl flex items-center gap-2 mb-2 mt-4 w-full py-1 px-2 hover:bg-gray-200 transition-bg duration-200 ease-in-out">
    <a href="{{route('profile.home',$viewliked->user->username)}}" class="flex items-center gap-3">
      <img src="{{$viewliked->user->avatar_url}}"  class="w-8 h-8 overflow-hidden flex justify-center items-center  shrink-0 grow-0 rounded-full ">
        <div class="flex flex-col gap-1">
          <strong>{{ $viewliked->user->name }}</strong>
          <small>{{'@'.$viewliked->user->username}}</small>
        </div>
      </a> 
      @if(auth()->user()->isNot($viewliked->user))
      <x-follow-button
           :status="$authFollowings[$viewliked->user->id] ?? null"
           :user-id="$viewliked->user->id"
           type="icon"
           onclick="followss(this)"
           class="follow w-5 h-5 text-xs ml-auto text-white rounded-full flex items-center justify-center"
      />
      @endif
    </div>
    @empty
    <p class="text-lg text-center p-20 font-bold">No people who liked yet</p>
    @endforelse
    <button id="close-modal" class="absolute top-1 right-3 text-lg mt-4 text-black"><i class="fas fa-times"></i></button>
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
{{-- open view who liked model  --}}

  <script>
    const openmodel = document.getElementsByClassName('open-view-model')[0];
    const viewmodel = document.getElementById('view-liked');
    const closemodel = document.getElementById('close-modal');
    openmodel.addEventListener('click',()=>{
      if(viewmodel.classList.contains('hidden')) viewmodel.classList.remove('hidden');
      document.body.classList.add('no-scroll');
    })
    closemodel.addEventListener('click',()=>{
      viewmodel.classList.add('hidden');
      document.body.classList.remove('no-scroll');
    })
  </script>
@endpush