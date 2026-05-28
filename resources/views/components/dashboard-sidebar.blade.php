  <div class="flex flex-col w-fit  gap-2">
        <a href="{{route('dashboard.index')}}" class="py-2 px-4 text-gray-500  rounded-xl flex items-center gap-4 {{request()->routeIs('dashboard.index') ? 'bg-gray-200':''}}">
          <i class="fas fa-tachometer-alt mr-1"></i>
        Overview
        </a>
	        <a href="{{route('dashboard.posts')}}" class="py-2 px-4 text-gray-500  rounded-xl flex items-center gap-4 {{request()->routeIs('dashboard.posts') ? 'bg-gray-200':''}}">
	          <i class="fas fa-book"></i>
	          My posts
            <span class="font-bold text-black ml-auto">{{ $total_posts }}</span>
	        </a>
	        <a href="{{route('dashboard.followers')}}" class="py-2 px-4 text-gray-500  rounded-xl flex items-center gap-4 {{request()->routeIs('dashboard.followers') ? 'bg-gray-200':''}}">
	          <i class="fas fa-users"></i>
	          Followers
            <span class="font-bold text-black ml-auto">{{ $user->followers_count }}</span>
	        </a>
	        <a href="{{route('dashboard.pending.followers')}}" class="py-2 px-4 text-gray-500  rounded-xl flex items-center gap-4 {{request()->routeIs('dashboard.pending.followers') ? 'bg-gray-200':''}}">
	          <i class="fas fa-users"></i>
	          Pending Followers
            <span class="font-bold text-black ml-auto">{{ $user->pending_followers_count }}</span>
	        </a>
	        <a href="{{route('dashboard.followings')}}" class="py-2 px-4 text-gray-500  rounded-xl flex items-center gap-4 {{request()->routeIs('dashboard.followings') ? 'bg-gray-200':''}}">
	          <i class="fas fa-user-plus"></i>
	          Following users
            <span class="font-bold text-black ml-auto">{{ $user->followings_count }}</span>
	        </a>
        <a href="{{route('bookmarks')}}" class="py-2 px-4 text-gray-500  rounded-xl flex items-center gap-4 {{request()->routeIs('account.privacy') ? 'bg-gray-200':''}}">
          <i class="fas fa-bookmark"></i>
          Bookmarks
        </a>
        <a href="{{route('profile.account')}}" class="py-2 px-4 text-gray-500  rounded-xl flex items-center gap-4 {{request()->routeIs('two.factor.view') ? 'bg-gray-200':''}}">
          <i class="fas fa-cog"></i>
          Settings
        </a>
      </div>
      <div class="mt-auto py-4 border-t border-gray-300 w-full">
        <button 
        onclick="window.location.href='{{route('profile',$user->username)}}'"
        class="block py-2 px-4 text-gray-500 rounded-xl w-full hover:bg-gray-200">
          <i class="fas fa-arrow-left"></i> Back To Profile
        </button>
      </div>