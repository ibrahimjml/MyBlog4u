@php
  use App\Enums\NotificationType;
@endphp

<div id="hidden-notification" style="display: none;" class="w-[500px] bg-white shadow-xl rounded-xl px-2 py-3 ">

<div class="p-2 h-24 border-b-2 border-b-gray-200">
  <p class="text-xl font-bold text-left">Notifications</p>
   <div id="notification-filters" class="flex gap-2 mt-4">
      <button class="filter-btn active px-3 py-1 rounded-full bg-blue-500 text-white text-sm" data-type="all">All</button>
      <button class="filter-btn px-3 py-1 rounded-full bg-gray-200 hover:bg-blue-100 text-sm" data-type="{{NotificationType::LIKE->value}}">Like</button>
      <button class="filter-btn px-3 py-1 rounded-full bg-gray-200 hover:bg-blue-100 text-sm" data-type="{{NotificationType::COMMENTS->value}}">Comment</button>
      <button class="filter-btn px-3 py-1 rounded-full bg-gray-200 hover:bg-blue-100 text-sm" data-type="{{NotificationType::FOLLOW->value}}">Follow</button>
      <button class="filter-btn px-3 py-1 rounded-full bg-gray-200 hover:bg-blue-100 text-sm" data-type="{{NotificationType::REPORT->value}}">Report</button>
      <button class="filter-btn px-3 py-1 rounded-full bg-gray-200 hover:bg-blue-100 text-sm" data-type="{{NotificationType::VIEWEDPROFILE->value}}">Viewed</button>
    </div>
</div>
  <!-- notification section -->
  <ul id="notification-list" class=" max-h-[500px] overflow-y-auto space-y-3 ">
      
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

      <li class="notification-item flex items-start gap-3 p-2 rounded-md hover:bg-gray-100 transition" data-type="{{$type}}">
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
                      <div class="flex-1">
                      <a href="{{$url}}"
                         class="text-sm text-gray-700 hover:text-black font-medium block">
                          {!! $message !!}
                      </a>
                      <div class="flex justify-center items-center gap-4">
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
  <!-- delete all / mark all as read -->
    <div class="flex items-center p-4 h-20 border-t-2 border-t-black">
      <form id="marksall" action="{{ route('notifications.readall') }}" method="GET" class="text-right mb-2">
      </form> 
      <span class="text-blue-500 p-2 rounded-full  hover:border border-blue-600 ">
        <i class="fas fa-check mr-1"></i>
      <button form="marksall" type="submit" >Mark all as read</button>
      </span>
     <form action="{{ route('notifications.deleteAll') }}" method="POST" class="text-right mb-2 w-fit ml-auto">
          @csrf
          @method('DELETE')
          <button type="submit" class="text-sm p-2 rounded-lg text-white bg-red-400 hover:bg-red-600 transition-colors duration-150 ease">Delete All</button>
      </form> 
  </div>
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