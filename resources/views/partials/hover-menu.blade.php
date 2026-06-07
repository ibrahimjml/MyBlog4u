<ul id="hiddenul2" style="display: none;" class="w-[250px]">
        
  <li class="border-b-2 hover:border-b-red-500 text-gray-500 hover:text-black hover:font-semibold transition duration-300 flex justify-between gap-2 mb-2">
    <a href="{{route('profile.home',auth()->user()->username)}}" >
      <img src="{{auth()->user()->avatar_url}}"   class="w-[23px] h-[23px] overflow-hidden flex justify-center items-center  shrink-0 grow-0 rounded-full " >
    </a>
    <span>
      <a href="{{route('profile.home',auth()->user()->username)}}"  >Profile</a>
    </span></li>
    <li class="border-b-2 text-gray-500 hover:text-black hover:font-semibold transition duration-300 hover:border-b-red-500 flex justify-between items-center mb-2 ">
    <i class="fas fa-tachometer-alt mr-1"></i>
    <a href="{{route('dashboard.index')}}" class="ml-2">My Dashboard</a>
  </li>
    <li class="border-b-2 text-gray-500 hover:text-black hover:font-semibold transition duration-300 hover:border-b-red-500 flex justify-between items-center mb-2 ">
    <i class="fas fa-image "></i>
    <a href="{{route('createpage')}}" class="ml-2">Create Post</a>
  </li>
  <li class="border-b-2 text-gray-500 hover:text-black hover:font-semibold transition duration-300 hover:border-b-red-500 flex justify-between items-center mb-2 ">
    <i class="fas fa-edit "></i>
    <a href="{{route('info')}}" class="ml-2">Profile Info</a>
  </li>
  <li class="border-b-2 hover:border-b-red-500 text-gray-500 hover:text-black hover:font-semibold transition duration-300 flex justify-between items-center gap-2 mb-2">
    <i class="fas fa-cog"></i>
    <a href="{{route('account')}}" class="ml-2">Account settings</a>
  </li>
  <form id="logoutForm" action="{{ route('logout') }}" method="POST">@csrf</form>
  <li class="border-b-2 hover:border-b-red-500 text-gray-500 hover:text-black hover:font-semibold transition duration-300 flex justify-between items-center gap-8">
    <i class="fas fa-sign-out-alt"></i>
  <button form="logoutForm" type="submit">Logout</button>
  </li>
</ul>