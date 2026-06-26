
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
              <a href="{{ route('profile.home', $username) }}">
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
                        @if(!auth()->user()->is_admin &&  $notification->data['type'] === \App\Enums\NotificationType::FOLLOW->value  && $notification->data['status']  === 'private')
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