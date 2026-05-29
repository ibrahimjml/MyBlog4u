@php
  use App\Enums\NotificationType;
@endphp

<div style="display: none;" class="notification-menu flex flex-col w-[calc(100vw-1rem)] h-[400px] overflow-y-auto max-w-[500px] bg-white shadow-[0_10px_15px_rgba(0,0,0,0.4)] rounded-xl px-2 py-3 sm:w-[500px]">  <!-- delete all / mark all as read -->
    <div class="flex gap-3 p-3 justify-between items-center">
      <p class="text-xl font-bold text-left">Notifications</p>
      <div class="flex justify-end items-center gap-2">
  <form id="marksall" action="{{ route('notifications.readall') }}" method="GET" class="text-right mb-2">
      </form> 
      <span title="mark all read" class="text-blue-500 p-2 text-center">
        
      <button form="marksall" type="submit" ><i class="fas fa-check mr-1"></i></button>
      </span>
      @if(auth()->user()->unreadNotifications->count() > 0)
     <form action="{{ route('notifications.deleteAll') }}" method="POST" class="text-center  sm:text-right sm:w-fit sm:ml-auto">
          @csrf
          @method('DELETE')
          <button title="delete all" type="submit" class="text-sm p-2 rounded-lg text-red-500 ">
            <i class="fas fa-trash mr-1"></i>
       </button>
      </form> 
      @endif
      </div>
    
  </div>
<div class="p-2 border-b-2 border-b-gray-200">
   <div id="notification-filters" class="flex flex-wrap gap-2 mt-4">
      <button class="filter-btn active px-3 py-1 rounded-full bg-blue-500 text-white text-sm" data-type="all">All</button>
      <button class="filter-btn px-3 py-1 rounded-full bg-gray-200 hover:bg-blue-100 text-sm" data-type="{{NotificationType::LIKE->value}}">Like</button>
      <button class="filter-btn px-3 py-1 rounded-full bg-gray-200 hover:bg-blue-100 text-sm" data-type="{{NotificationType::COMMENTS->value}}">Comment</button>
      <button class="filter-btn px-3 py-1 rounded-full bg-gray-200 hover:bg-blue-100 text-sm" data-type="{{NotificationType::FOLLOW->value}}">Follow</button>
      <button class="filter-btn px-3 py-1 rounded-full bg-gray-200 hover:bg-blue-100 text-sm" data-type="{{NotificationType::REPORT->value}}">Report</button>
      <button class="filter-btn px-3 py-1 rounded-full bg-gray-200 hover:bg-blue-100 text-sm" data-type="{{NotificationType::VIEWEDPROFILE->value}}">Viewed</button>
    </div>
</div>
  <!-- notification section -->
<ul id="notification-list" class="flex-1 overflow-y-auto space-y-3 min-h-12">      
      @forelse($notifications as $notification)
      @php
        $type = $notification->data['type'];
        $message = $notification->data['message'] ?? '';
        $url = route('notifications.read', $notification->id);
        $username = null;
          foreach ($notification->data as $key => $value) {
              if (!$username && str_contains($key, 'username')) {
                  $username = $value;
                  break;
                 }
            }
        $user =  $users[$username] ?? null;;
        $avatar = $user?->avatar_url ?? asset('storage/avatars/default.jpg');
       @endphp

      <li class="notification-item flex items-start gap-2 p-2 rounded-md hover:bg-gray-100 transition sm:gap-3" data-type="{{$type}}">
      {{-- icon badge --}}
        <span class="mt-2 text-sm text-gray-500">
            @if($notification->read_at === null)
                <i class="fas fa-circle text-blue-500 text-[10px]"></i>
            @else
                <i class="fas fa-circle text-gray-300 text-[10px]"></i>
            @endif
        </span>
    
              @if($user)
              <a href="{{ route('profile', $username) }}">
                  <img src="{{$avatar}}?v={{ $user?->updated_at->timestamp ?? time() }}"
                       class="w-8 h-8 rounded-full object-cover" alt="">
                      </a>
                  @endif
                      <div class="min-w-0 flex-1 text-left">
                      <a href="{{$url}}"
                         class="text-sm text-gray-700 hover:text-black font-medium block leading-5 break-words">
                          {!! $message !!}
                      </a>
                      <div class="flex flex-wrap items-center gap-2 sm:justify-center sm:gap-4">
                        <small class="text-xs text-gray-400">{{ $notification->created_at->diffForHumans() }}</small>
                        
                          <form action="{{ route('notifications.delete',$notification->id) }}" method="POST" class="text-right mb-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-sm text-white hover:underline px-2 rounded-full bg-red-500">x</button>
                        </form>
                        {{-- accept  follow status if notification type = follow --}}
                        @if(!auth()->user()->is_admin &&  $notification->data['type'] === NotificationType::FOLLOW->value  && $notification->data['status']  === 'private')
                        <div class="flex gap-2 mt-2">
                        <form action="{{ route('follow.accept', $notification->data['follower_id']) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-2 py-1 bg-blue-500 rounded-md text-white">Accept</button>
                        </form>
                         </div>
                         @endif
                      </div>
                  </div>
                  
              </li>
        @empty
        <li class="text-center text-gray-500 py-6">No new notifications.</li>
      @endforelse
  </ul>

</div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  const buttons = document.querySelectorAll('.filter-btn');
  const items = document.querySelectorAll('.notification-item');

  buttons.forEach(btn => {
    btn.addEventListener('click', () => {

      buttons.forEach(b => b.classList.remove('bg-blue-500', 'text-white', 'active'));
      btn.classList.add('bg-blue-500', 'text-white', 'active');

      const filter = btn.dataset.type;
      items.forEach(item => {
        if (filter === 'all' || item.dataset.type === filter) {
          item.style.display = 'flex';
        } else {
          item.style.display = 'none';
        }
      });
    });
  });
});
</script>
@endpush
