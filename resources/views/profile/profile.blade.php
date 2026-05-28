<x-layout>

  <main class="profile-page">
    <section class="relative block h-500-px">
      <div class="absolute top-0 w-full h-full bg-gray-200 bg-center bg-cover">
             <img src="{{ $user->cover }}" 
             alt="Cover image"
             class="w-full h-full object-cover blur-md transition-all duration-700 ease-out"
             onload="this.classList.remove('blur-md'); this.parentElement.classList.remove('bg-gray-200')">
      </div>
      <div class="top-auto bottom-0 left-0 right-0 w-full absolute pointer-events-none overflow-hidden h-70-px"
        style="transform: translateZ(0px)">
        <svg class="absolute bottom-0 overflow-hidden" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none"
          version="1.1" viewBox="0 0 2560 100" x="0" y="0">
          <polygon class="text-blueGray-200 fill-current" points="2560 0 2560 100 0 100"></polygon>
        </svg>
      </div>
      
    </section>
    <section class="relative py-16 bg-blueGray-200">
      <div class="container mx-auto px-4 lg:w-[80%]">
        <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-xl rounded-lg -mt-64">
          <div class="px-6">
          {{-- edit cover photo --}}
          @if($user->is(auth()->user()))
         <a href="{{route('profile.info')}}" class="absolute -top-10 right-0 lg:text-md text-xs text-black py-1 px-3 rounded-full bg-white">
          <i class="fas fa-camera"></i> cover</a>
          @endif
          <div class="flex flex-wrap justify-center">
              <div class="w-full lg:w-3/12 px-4 lg:order-2 flex justify-center">
                <div class="relative mb-5 ">
                  <img src="{{ $user->avatar_url }}" alt=""
                    class="shadow-xl rounded-full border-2 border-gray-700 h-auto align-middle absolute -m-16 max-w-150-px blur-md transition-all duration-700 ease-out"
                    onload="this.classList.remove('blur-md')">
            {{-- edit avatar  --}}
            @if($user->is(auth()->user()))
            <span
            class="absolute lg:bottom-0 lg:left-10 bottom-12 left-10 flex justify-center items-center w-6 h-6 shrink-0 grow-0 rounded-full bg-gray-600 text-white">
            <a href="{{route('profile.info')}}"><i class="fas fa-plus" aria-hidden="true"></i></a>
           </span>
          @endif


                </div>
              </div>
            <div class="w-full lg:w-4/12 px-4 lg:order-3 lg:text-right lg:self-center mt-14 lg:mt-0">
              <div class="py-6 px-3 flex justify-center items-center gap-x-2">
                @if(auth()->user()->is($user))
                <div class="relative">
                    <!-- button share -->
                    <button onclick="toggleShareMenu()" title="share" class="w-8 h-8 text-gray-500 hover:text-black text-sm transition-colors duration-150 ease-in flex items-center justify-center border border-black rounded-full">
                   <svg class="w-5 h-5" viewBox="0 0 500 500"><path d="M432.31 135.261h-47.672a17.595 17.595 0 0 0-12.442 30.039c3.3 3.3 7.775 5.154 12.442 5.154h30.075v294.353H86.193V170.454h30.085a17.595 17.595 0 0 0 12.442-30.039 17.595 17.595 0 0 0-12.442-5.154H68.596A17.61 17.61 0 0 0 51 152.858v329.546A17.597 17.597 0 0 0 68.596 500H432.31a17.586 17.586 0 0 0 17.596-17.596V152.858a17.597 17.597 0 0 0-17.596-17.597Z" fill="inherit"></path><path d="M204.521 95.101a17.553 17.553 0 0 0 12.81-5.53l26.083-27.652v206.13a17.595 17.595 0 0 0 30.039 12.442c3.3-3.3 5.154-7.775 5.154-12.442V61.809L304.75 89.59a17.609 17.609 0 0 0 12.332 5.711 17.6 17.6 0 0 0 16.733-10.43 17.61 17.61 0 0 0 .301-13.588 17.603 17.603 0 0 0-3.755-5.825L274.997 6.717a18.147 18.147 0 0 0-1.809-2.011A17.51 17.51 0 0 0 263.223.06h-.503l-.955-.06h-2.283c-.271 0-.543.06-.814.09-.272.03-.593.08-.885.141l-.845.171-.824.221c-.282.08-.553.161-.825.262-.271.1-.543.18-.804.291l-.784.332-.785.382-.744.413-.744.442-.724.503-.663.503c-.252.2-.493.402-.724.613l-.413.352-.17.18c-.232.222-.443.453-.664.695-.221.241-.372.392-.543.593-.171.201-.302.382-.453.583L191.771 65.49a17.59 17.59 0 0 0-3.321 18.98 17.588 17.588 0 0 0 16.071 10.632Z" fill="inherit"></path></svg>
                  </button>
                  {{-- share model --}}
                  @include('partials.share-menu')
                </div>
                <!-- button qrcode -->
              <button title="qrcode" onclick="openQrModal()" class="w-8 h-8 text-gray-500 hover:text-black transition-colors duration-150 ease-in flex items-center justify-center border border-black rounded-full">
               <i class="fas fa-qrcode"></i>
              </button>
              <!-- button who viewed -->
              <button id="open-viewed"
            class="w-8 h-8 text-gray-500 hover:text-black transition-colors duration-150 ease-in flex items-center justify-center border border-black rounded-full"
            title="see who viewed">
            <i class="fas fa-eye"></i>
          </button>
          <!-- button settings -->
          <button 
          onclick="window.location.href='{{route('profile.info')}}'"
          title="settings"  
          class="w-8 h-8 lg:hidden text-gray-500 hover:text-black transition-colors duration-150 ease-in flex items-center justify-center border border-black rounded-full">
         <i class="fas fa-cog"></i>
          </button>
              @endif
              @include('profile.partials.qrcode-model')
              @if(auth()->user()?->isNot($user))
                <!-- button report -->
               <button onclick="openReort()"
                       title="report" 
                       class="w-8 h-8 text-gray-600  transition-colors duration-150 ease-in flex items-center justify-center ">
               <i class="fas fa-exclamation-circle text-xl"></i>

               </button>
               {{-- report profile model  --}}
               @include('profile.partials.report-profile')
                 <!-- follow/unfollow -->
                    <x-follow-button
               :status="$authFollowings[$user->id] ?? null"
               :user-id="$user->id"
               type="label"
               onclick="follow(this)"
               class="follow px-3 py-1 rounded-lg text-xs font-bold"
           /> 
                  @endif
                </div>
              </div>
            {{-- posts-likes-follows-count --}}
            @include('profile.partials.posts-likes-follows-count')
            </div>
            {{-- edit profile | open viewed model --}}
              @if(auth()->user()->is($user))
          <div class="lg:flex lg:gap-1 lg:justify-center mb-3 hidden">
          <span class="flex justify-start items-center gap-4 bg-gray-500  text-white py-2 px-5 rounded-lg font-bold capitalize  hover:border-gray-700 transition duration-300">
            <i class="fas fa-cog"></i>
            <a href="{{route('profile.info')}}">settings</a>
          </span>
          </div>
        @endif
  <div class="text-center mt-2">
      <h3 class=" text-3xl font-bold text-center  tracking-wide text-gray-700">{{$user->name}} </h3>
      <span class="text-sm text-gray-400 text-center mb-2">@ {{$user->username}}</span>
    </h3>
    
      <div class="mb-2 text-blueGray-600">
    @if($user->bio == null)
    <p class="text-lg text-gray-400 text-center mb-2">Tell people about yourself</p>
    @else
      <i class="fas fa-info mr-2 text-lg text-blueGray-400"></i>{{$user->bio}}
    @endif
    </div>
  {{-- social links --}}
  <div class="flex justify-center items-center gap-3">
    @if($user->github)
    <a href="{{$user->github}}" target="_blank"><i class="fab fa-github"></i></a>
    @endif
    @if($user->linkedin)
    <a href="{{$user->linkedin}}" target="_blank"><i class="fab fa-linkedin"></i></a>
    @endif
    @if($user->twitter)
    <a href="{{$user->twitter}}" target="_blank">
      <svg fill="none" viewBox="0 0 20 20" width="20" height="20"><path stroke="currentColor" d="M8.87 11.122 3.556 4.057a.69.69 0 0 1 .551-1.103h1.907c.215 0 .418.1.548.271l4.192 5.498M8.87 11.122 4.328 17.09m4.541-5.97 4.3 5.61c.13.17.331.269.545.27l1.946.006a.69.69 0 0 0 .551-1.107l-5.456-7.177m0 0 4.427-5.799" stroke-linecap="round" stroke-width="1.25"></path></svg>
    </a>
    @endif
    @foreach ($user->socialLinks as $link)
    <a href="{{ $link->url }}" target="_blank"><i class="fas fa-link text-sm mr-1"></i>{{ $link->platform }}</a><br>
@endforeach
  </div>
  </div>
{{-- home | Activity | About me --}}
<div class="flex items-center gap-2 ml-4">
  <a href="{{route('profile',['user'=>$user->username])}}" class="nav-link p-1 font-bold text-gray-400  rounded-lg">Home</a>
  <div class="h-4 w-px bg-gray-400"></div>
  <a href="{{route('profile.activity',['user'=>$user->username])}}"  class="nav-link p-1 font-bold text-gray-400 rounded-lg">Activity</a>
  <div class="h-4 w-px bg-gray-400"></div>
  <a href="{{route('profile.aboutme',['user'=>$user->username])}}"  class="nav-link p-1 font-bold text-gray-400  rounded-lg">About me</a>
</div>

{{-- home | activity | aboutme sections --}}
<div class=" py-10 border-t border-blueGray-200 text-center">
<div id="profile-content">
  @switch($section)
  @case('home')
    @include('profile.home', ['posts' => $posts])
    @break

  @case('activity')
    @include('profile.activity', ['user' => $user,'activities' => $activities])
    @break

  @case('about')
    @include('profile.aboutme', ['user' => $user])
    @break
@endswitch
</div>
          </div>
        </div>
      </div>
    </section>
  </main>
  {{-- open  who viewed profile model  --}}
@include('profile.partials.who-viewd-profile-model',['profileviews'=>$profileviews])

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
  
  
     eo.textContent = data.status === 1
            ? "Following"
            : (data.status === 0 ? "Requested" : "Follow");
      const followerscount = document.querySelector('#followers-count');
      let count = parseInt(followerscount.textContent);
       if (data.status === 1) {
                followerscount.textContent = count + 1;
            } else if (data.status === null) {
                followerscount.textContent = Math.max(0, count - 1);
            }
     eo.classList.remove(
          "bg-gray-200",
          "bg-gray-600",
          "bg-yellow-500",
          "text-black",
          "text-white"
        );
  
     if (data.status === 1) {
          eo.classList.add("bg-gray-200", "text-black");
        } else if (data.status === 0) {
          eo.classList.add("bg-yellow-500", "text-white");
        } else {
          eo.classList.add("bg-gray-600", "text-white");
        }
  
    } catch (error) {
      console.error(error);
    }
  }
  
  </script>
  {{-- open who view profile model   --}}
  @can('update',$user)
  <script>
    const openmodel = document.getElementById('open-viewed');
    const viewmodel = document.getElementById('view-profile');
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
@endcan
  <script>
document.addEventListener('DOMContentLoaded', () => {
  function attachNavLinks() {
    document.querySelectorAll('a.nav-link').forEach(link => {
      const url = link.href;
      link.onclick = (e) => {
        e.preventDefault();
        loadPage(url);
      };
    });
  }

  function loadPage(url, push = true) {
    fetch(url)
      .then(res => res.text())
      .then(html => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');

        // Replace only #profile-content section
        const newContent = doc.querySelector('#profile-content');
        const currentContent = document.querySelector('#profile-content');
        if (newContent && currentContent) {
          currentContent.innerHTML = newContent.innerHTML;
        }

        const newTitle = doc.querySelector('title');
        if (newTitle) document.title = newTitle.innerText;

        if (push) history.pushState(null, '', url);

        // Reattach events (recursive)
        attachNavLinks();
  
        updateActiveLink();
      })
      .catch(err => console.error('Navigation error', err));
  }


  function updateActiveLink() {
    const currentPath = window.location.pathname;
    document.querySelectorAll('a.nav-link').forEach(link => {
      const linkUrl = link.getAttribute('href');
      
  
      if (currentPath === new URL(linkUrl).pathname) {
        link.classList.add('activate');
        link.classList.remove('text-gray-400');
        link.classList.add('text-black');
      } else {
        link.classList.remove('activate');
        link.classList.remove('text-black');
        link.classList.add('text-gray-400');
      }
    });
  }


  attachNavLinks();
  updateActiveLink();

  // Handle back/forward navigation
  window.addEventListener('popstate', () => {
    const currentUrl = window.location.href; 
    loadPage(currentUrl, push = false);
    updateActiveLink();
  });
});

  </script>
  
@endpush

</x-layout>