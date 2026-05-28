<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="theme-color" content="#000000" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="shortcut icon" href="{{url('/img/icon.png')}}" />
  <link rel="apple-touch-icon" sizes="76x76" href="{{url('/img/apple-touch-icon.png')}}" />
  <link rel="stylesheet" href="{{asset('assets/vendor/@fortawesome/fontawesome-free/css/all.min.css')}}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css"/>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <title>@yield('title','Dashboard | Admin')</title>
  <style>
    .iti {
  width: 100% !important;
}
  </style>
</head>

<body class="text-blueGray-700 antialiased">
  
  <div id="root">
    @if(config('demo.enabled'))
    <div class="stick top-0 w-full p-4 bg-orange-400 text-white font-bold text-center uppercase">
      Demo mode is enabled. All actions are disabled for security.
    </div>
    @endif
    <nav
      class="md:left-0 md:block md:fixed md:top-0 md:bottom-0 md:overflow-y-auto md:flex-row md:flex-nowrap md:overflow-hidden shadow-xl bg-white flex flex-wrap items-center justify-between relative md:w-64 z-10 py-4 px-6">
      <div
        class="md:flex-col md:items-stretch md:min-h-full md:flex-nowrap px-0 flex flex-wrap items-center justify-between w-full mx-auto">
        <button
          class="cursor-pointer text-black opacity-50 md:hidden px-3 py-1 text-xl leading-none bg-transparent rounded border border-solid border-transparent"
          type="button" onclick="toggleNavbar('example-collapse-sidebar')">
          <i class="fas fa-bars"></i>
        </button>
        <a class="md:block text-left md:pb-2 text-blueGray-600 mr-0 inline-block whitespace-nowrap text-sm uppercase font-bold p-4 px-0"
          href="{{route('admin.panel')}}">
          @role('Admin')
          Admin Dashboard
          @endrole
          @role('Moderator')
          Moderator Dashboard
          @endrole
          @role('Editor')
          Moderator Dashboard
          @endrole
        </a>
        <ul class="md:hidden items-center flex flex-wrap list-none">
        
          <li class="inline-block relative">
            <a class="text-blueGray-500 block" href="#pablo" onclick="openDropdown(event,'user-responsive-dropdown')">
              <div class="items-center flex">
                <span
                  class="w-12 h-12 text-sm text-white bg-blueGray-200 inline-flex items-center justify-center rounded-full">
                  <img
                    alt="..." class="w-full rounded-full align-middle border-none shadow-lg"
                    src="{{auth()->user()->avatar_url}}" /></span>
              </div>
            </a>
        
          </li>
        </ul>
        <div
          class="md:flex md:flex-col md:items-stretch md:opacity-100 md:relative md:mt-4 md:shadow-none shadow absolute top-0 left-0 right-0 z-40 overflow-y-auto overflow-x-hidden h-auto items-center flex-1 rounded hidden"
          id="example-collapse-sidebar">
          <div class="md:min-w-full md:hidden block pb-4 mb-4 border-b border-solid border-blueGray-200">
            <div class="flex flex-wrap">
              <div class="w-6/12">
                <a class="md:block text-left md:pb-2 text-blueGray-600 mr-0 inline-block whitespace-nowrap text-sm uppercase font-bold p-4 px-0"
                  href="../../index.html">
                  Admin Dashboard
                </a>
              </div>
              <div class="w-6/12 flex justify-end">
                <button type="button"
                  class="cursor-pointer text-black opacity-50 md:hidden px-3 py-1 text-xl leading-none bg-transparent rounded border border-solid border-transparent"
                  onclick="toggleNavbar('example-collapse-sidebar')">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
          </div>
          <form class="mt-6 mb-4 md:hidden">
            <div class="mb-3 pt-0">
              <input type="text" placeholder="Search"
                class="border-0 px-3 py-2 h-12 border border-solid border-blueGray-500 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-base leading-snug shadow-none outline-none focus:outline-none w-full font-normal" />
            </div>
          </form>

          @include('admin.partials.admin-sidebar')
          <!-- Divider -->
          <hr class="my-4 md:min-w-full" />
        </div>
      </div>
    </nav>
      <div>
          @yield('content')
      </div>


  <script src="https://unpkg.com/@popperjs/core@2/dist/umd/popper.js"></script>
  <script src="https://code.highcharts.com/highcharts.js"></script>
  <script src="https://code.highcharts.com/modules/accessibility.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
 
  @if(Route::is('admin.posts.featured.page'))
    <script src="{{asset('js/hashtagsUI.js')}}" defer></script>
  @endif
  <script>
/* Sidebar - Side navigation menu on mobile/responsive mode */
      function toggleNavbar(collapseID) {
        document.getElementById(collapseID).classList.toggle("hidden");
        document.getElementById(collapseID).classList.toggle("bg-white");
        document.getElementById(collapseID).classList.toggle("m-2");
        document.getElementById(collapseID).classList.toggle("py-3");
        document.getElementById(collapseID).classList.toggle("px-6");
      }
      /* Function for dropdowns */
      function openDropdown(event, dropdownID) {
        let element = event.target;
        while (element.nodeName !== "A") {
          element = element.parentNode;
        }
        Popper.createPopper(element, document.getElementById(dropdownID), {
          placement: "bottom-start"
        });
        document.getElementById(dropdownID).classList.toggle("hidden");
        document.getElementById(dropdownID).classList.toggle("block");
      }
</script>
  <!-- push all scripts in pages -->
  @stack('scripts')
</body>
</html>
