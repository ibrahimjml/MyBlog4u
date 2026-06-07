<div class="flex items-center gap-2 mb-2">
  <img src="{{$reply->user->avatar_url}}"  class="w-8 h-8 overflow-hidden flex justify-center items-center shrink-0 grow-0 rounded-full ">

<div class="flex flex-col">
  <div class="flex gap-1 items-center">
    <a href="{{route('profile.home',$reply->user->username)}}" class="hover:underline">
      <strong>{{ $reply->user->name }}</strong>
    </a>
      <div class="items-center">
         @php            
            $userRoles = $reply->user->roles->pluck('name')->toArray();
            $isAdmin = in_array('Admin', $userRoles);
            $isModerator = in_array('Moderator', $userRoles);
            $isAuthor = $reply->user_id === $reply->post_user_id;
        @endphp
        @if($isAdmin)
       <small class=" px-1 rounded-full bg-green-500 text-white font-semibold">Admin</small>
       @elseif($isModerator)
       <small class=" px-1 rounded-full bg-green-500 text-white font-semibold">Moderator</small>
       @elseif($isAuthor)
       <small class=" px-1 rounded-full bg-green-500 text-white font-semibold">Author</small>
       @endif
      </div>
    @if($reply->parent)
    <p class="text-sm text-gray-600">&gt; {{ $reply->parent->user->name }} </p>
    @endif
  </div>
  <span class="text-xs">{{ $reply->created_at->diffForHumans() }}</span>
</div>
</div>