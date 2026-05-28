@extends('layouts.dashboard')

@section('content')
  <main class="min-h-screen w-full lg:flex lg:gap-6 p-0 lg:p-6">

    <aside id="mobileSidebar" class="flex flex-col items-center bg-white p-6 shadow h-screen fixed top-0 left-0 w-80 z-30 lg:sticky lg:top-6 lg:h-[calc(100vh-3rem)] lg:w-80 lg:shrink-0 lg:z-auto lg:rounded-lg transition-transform duration-300 ease-in-out -translate-x-full lg:translate-x-0">
      @include('components.dashboard-sidebar') <!-- Sidebar content -->
    </aside>

	    <section class="w-full min-w-0 bg-white shadow h-screen lg:flex-1 lg:h-[calc(100vh-3rem)] lg:rounded-lg overflow-y-auto">
      <div class="sticky top-0 z-20 flex h-20 items-center justify-between gap-4 border-b border-gray-200 bg-white px-4 sm:px-6">
        <div class="flex min-w-0 items-center gap-4">
          <button id="toggleSidebar" class="text-lg text-gray-400 hover:text-gray-600 transition cursor-pointer lg:hidden">
            <i class="fas fa-bars"></i>
          </button>
          <p class="truncate text-2xl font-bold text-gray-900">{{ auth()->user()->name }}'s Studio</p>
        </div>

        <div class="flex items-center gap-6">
          <div class="relative">
            <div data-notification-trigger class="relative cursor-pointer text-lg text-gray-700">
              <span class="absolute -top-0 left-3 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 p-1 text-xs font-semibold text-white">
                {{ auth()->user()->unreadNotifications->count() }}
              </span>
              <i class="fas fa-bell"></i>
            </div>
            @include('partials.notifications-menu')
          </div>

          <a href="{{route('profile',$user->username)}}" class="block h-10 w-10 shrink-0 overflow-hidden rounded-full border border-gray-200">
            <img src="{{$user->avatar_url}}" alt="{{$user->name}}" class="h-full w-full object-cover">
          </a>
        </div>
      </div>

	      <div class="px-4 py-6 sm:px-6">
	        @switch($section)
	          @case('overview')
	          @include('dashboard.overview')
	          @break

	          @case('posts')
	          @include('dashboard.myposts')
	          @break

	          @case('followers')
            @include('dashboard.followers')
            @break
	          @case('followings')
            @include('dashboard.followings')
            @break
	          @case('pending_followers')
            @include('dashboard.pending-followers')
            @break
	        @endswitch
	      </div>
    </section>

  </main>

@endsection
