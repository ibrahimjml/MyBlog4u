<div id="view-profile" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">

  <div class="relative bg-white h-2/4 rounded-lg p-6 w-full max-w-2xl  overflow-y-auto">
    <p class="text-2xl font-bold">People who viewed your profile</p>
    @forelse($profileviews as $view)
    <div class="rounded-xl flex items-center gap-2 mb-2 mt-4 w-full py-1 px-2 hover:bg-gray-200 transition-bg duration-200 ease-in-out">
    <a href="{{route('profile.home',['user'=>$view->viewer->username])}}" class="flex items-center gap-3">
      <img src="{{$view->viewer->avatar_url}}"  class="w-8 h-8 overflow-hidden flex justify-center items-center  shrink-0 grow-0 rounded-full ">
        <div class="flex gap-4 items-center">
        <div class="flex flex-col">
            <strong>{{ $view->viewer->name }}</strong>
            <small>{{'@ '.$view->viewer->username}}</small>
        </div>
          <p class="text-gray-700 font-semibold text-center">viewed your profile</p>
          <small>{{$view->created_at->diffForHumans()}}</small>
        </div>
      </a> 
    
    </div>
    @empty
    <p class="text-lg text-center p-20 font-bold">No people who viewd yet</p>
    @endforelse
    <button id="close-modal" class="absolute top-1 right-3 text-lg mt-4 text-black"><i class="fas fa-times"></i></button>
  </div>
</div>
