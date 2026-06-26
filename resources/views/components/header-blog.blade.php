<!DOCTYPE html>
<html lang="en">

<head>
  @include('components.head')
</head>

<body class="min-h-screen m-0 ">
  <nav @class([
    'w-screen px-6 py-5 h-20',
    'bg-opacity-0 absolute z-50' => Route::is(['profile.*', 'home']),
    'bg-white shadow-[0_2px_5px_rgba(0,0,0,0.1)]' => !Route::is(['profile.*', 'home']),
    'bg-white shadow-[0_2px_5px_rgba(0,0,0,0.1)] fixed top-0 z-50' => Route::is(['blog']),
  ])>
    <div class="flex justify-between items-center ">
      <div class="flex items-center cursor-pointer group" onclick="window.location.href='/'">
        <div class="text-2xl font-black tracking-tight flex">
          <span class="px-2 py-1 bg-gradient-to-r from-white to-gray-100 text-black rounded-l-md shadow-sm">
            My
          </span>
          <span class="px-2 py-1 bg-gradient-to-r from-black to-gray-800 text-white rounded-r-md shadow-sm">
            Blog4U
          </span>
        </div>
      </div>

      <div class="md:flex md:items-center  space-x-6 {{ hasCompleted2FA() ? 'hidden' : '' }}">
        <ul class="flex items-center space-x-6">

          {{-- Hover Menu --}}
          @auth
            @if(hasCompleted2FA())
              <li id="dropdown" @class([
                'text-lg pt-2  relative',
                'text-white' => Route::is(['profile.*', 'home']),
                'text-gray-700' => !Route::is(['profile.*', 'home'])
              ])>
                @include('partials.hover-menu')
                <span class="cursor-pointer">
                  {{auth()->user()->name}}
                  <i class="fas fa-angle-down ml-1"></i>
                </span>
              </li>
            @endif
          @endauth
          @if(!auth()->user())
            @unless(request()->routeIs('login'))
              <li @class([
                'font-bold text-lg p-1 px-4 pt-2 uppercase rounded-xl font-semibold',
                'text-black bg-white' => Route::is(['profile.*', 'home']),
                'text-white text-lg  bg-black ' => !Route::is(['profile.*', 'home'])
              ])>
                <a href="{{route('login')}}">sign in</a>
              </li>
            @endunless
          @else
            @if(hasCompleted2FA())
              <li @class([
                'font-bold text-xl' => Route::is('blog'),
                'text-white text-lg pt-2' => Route::is(['profile.*', 'home']),
                'text-gray-700 text-lg  pt-2' => !Route::is(['profile.*', 'home'])
              ])>
                <a href="/blog">Blog</a>
              </li>
              @unless(request()->is('admin*'))
                <li id="hover-notification" data-notification-trigger
                  class="text-lg relative pt-2 cursor-pointer text-gray-700 ">
                  <span
                    data-notification-count
                    class="notification-count absolute top-2 left-3 h-4 w-4 bg-red-500 text-white flex justify-center items-center rounded-full p-1 text-xs font-semibold">
                    {{ auth()->user()->unreadNotifications->count() }}
                  </span>
                  <i class="fas fa-bell {{Route::is(['profile.*', 'home']) ? 'text-white' : 'text-gray-700'}}"></i>
                </li>

                @include('partials.notifications-menu')
              @endunless
              <li @class([
                'font-bold text-xl' => Route::is('bookmarks'),
                'text-white text-lg pt-2' => Route::is(['profile.*', 'home']),
                'text-gray-700 text-lg  pt-2' => !Route::is(['profile.*', 'home'])
              ])>
                <a href="{{route('bookmarks')}}"><i class="far fa-bookmark"></i></a>
              </li>
              @if(auth()->user()->hasAnyRole(['Admin', 'Moderator']) || auth()->user()->hasPermission('Access'))
                <li @class([
                  'text-white text-lg pt-2' => Route::is(['profile.*', 'home']),
                  'text-gray-700 text-lg  pt-2' => !Route::is(['profile.*', 'home'])
                ])>
                  <a href="{{route('admin.panel')}}">
                    Admin Panel
                  </a>
                </li>
              @endif
              @unless(request()->routeIs('home'))
                <li @class([
                  'text-white text-lg pt-2' => Route::is(['profile.*', 'home']),
                  'text-gray-700 text-lg pt-2' => !Route::is(['profile.*', 'home'])
                ])>
                  <a href="/">Home</a>
                </li>
              @endunless
            @else
              <form id="logoutFRM" action="{{ route('logout') }}" method="POST">@csrf</form>
              <button form="logoutFRM" type="submit"
                class="bg-red-500/80 mt-3 text-white  font-bold uppercase text-xs px-4 py-2 rounded shadow hover:shadow-md outline-none focus:outline-none mr-1 ease-linear transition-all duration-150">
                Logout
              </button>
            @endif
          @endif
        </ul>
      </div>
      @if(hasCompleted2FA())

          <div class="lg:hidden flex items-center gap-6">
            @unless(request()->is('admin*'))
              <div data-notification-trigger class="text-lg  relative  cursor-pointer text-gray-700 ">
                <span
                  data-notification-count
                  class="notification-count absolute -top-0 left-3 h-4 w-4 bg-red-500 text-white flex justify-center items-center rounded-full p-1 text-xs font-semibold">
                  {{ auth()->user()->unreadNotifications->count() }}
                </span>
                <i class="fas fa-bell {{Route::is(['profile.*', 'home']) ? 'text-white' : 'text-gray-700'}}"></i>
              </div>

              @include('partials.notifications-menu')
            @endunless
            <img id="mobile-btn" src="{{auth()->user()->avatar_url}}"
              class="md:hidden w-[26px] h-[26px] overflow-hidden flex justify-center items-center  shrink-0 grow-0 rounded-full ">

          </div>

        </div>

        {{-- Burger Menu --}}
        <div class="md:hidden">
          @include('partials.burger-menu')
        </div>
      @endif
  </nav>