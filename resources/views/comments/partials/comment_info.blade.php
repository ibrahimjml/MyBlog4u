<div class=" flex items-center gap-2 mb-2 mt-2">

    <img src="{{$comment->user->avatar_url}}"  class="w-8 h-8 overflow-hidden flex justify-center items-center  shrink-0 grow-0 rounded-full ">
  
  <div class="flex flex-col">
    <div class="flex items-center gap-2">
      <a href="{{route('profile.home',$comment->user->username)}}" class="hover:underline">
        <strong>{{ $comment->user->name }}</strong>
      </a>
      <div class="items-center">
         @php
              $userRoles = $comment->user->roles->pluck('name')->toArray();
              $isAdmin = in_array('Admin', $userRoles);
              $isModerator = in_array('Moderator', $userRoles);
              $isAuthor = $comment->user_id === $comment->post_user_id;
          @endphp
        @if($isAdmin)
       <small class=" px-1 rounded-full bg-green-500 text-white font-semibold">Admin</small>
       @elseif($isModerator)
       <small class=" px-1 rounded-full bg-green-500 text-white font-semibold">Moderator</small>
       @elseif($isAuthor)
       <small class=" px-1 rounded-full bg-green-500 text-white font-semibold">Author</small>
       @endif
      </div>
    </div>
    <span class="text-sm text-gray-500">{{ $comment->created_at->diffForHumans()}}</span>
  </div>
</div>