@extends('admin.partials.layout')
@section('title','Notifications | Dashboard')
@section('content')
{{-- Notifications section --}}
  <div class="relative md:ml-64 bg-blueGray-50">

  <div class="flex gap-1 border-b-2 justify-between border-gray-600 w-full py-2 items-center">
  <div class="flex items-center gap-2">
        <p class="text-xl font-bold text-gray-700 ">All Notifications</p>
        <span class="flex items-center">
          (<span class="h-4 w-4 bg-blue-500 mt-1 text-white font-medium flex justify-center items-center rounded-full p-1 text-xs">
            {{ $unreadCount }}
          </span>)
        </span>
        
  </div>
<form  action="{{url()->current()}}" method="GET" class="flex gap-2 items-center">
{{-- sort Read/Unread --}}
<div class="relative w-full">
  <select id="sort" name="sort"
    class="pl-3 pr-8 appearance-none font-bold cursor-pointer bg-gray-600 text-white text-sm rounded-lg w-full p-2.5"
    onchange="this.form.submit()">
    <option value="">Sort</option>
    <option value="read" {{ request('sort') === 'read' ? 'selected' : '' }}>Read</option>
    <option value="unread" {{ request('sort') === 'unread' ? 'selected' : '' }}>Unread</option>
  </select>
  <!-- Custom white arrow -->
  <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
      <path d="M19 9l-7 7-7-7" />
    </svg>
  </div>
</div>

{{-- sort by type --}}
<div class="relative w-full">
  <select name="type" class="pl-3 pr-8 appearance-none font-bold cursor-pointer bg-gray-600 text-white border border-gray-300 text-sm rounded-lg p-2.5" onchange="this.form.submit()">
    <option value="">Types</option>
    @foreach (\App\Enums\NotificationType::cases() as $type)
    <option value="{{$type->value}}" {{ request('type') === $type->value ? 'selected' : '' }}>{{$type->name}}</option>
    @endforeach
  </select>
  <!-- Custom white arrow -->
    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
      <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path d="M19 9l-7 7-7-7" />
      </svg>
    </div>
</div>
      </form>
  </div>

  @forelse ($notifications as $notification)
      <div class="flex items-start gap-2 p-3 rounded-md hover:bg-gray-100 transition w-full ">
    
      <span class="mt-2 text-sm text-gray-500">
          @if($notification->read_at === null)
              <i class="fas fa-circle text-blue-500 text-[10px]"></i>
          @else
              <i class="fas fa-circle text-gray-300 text-[10px]"></i>
          @endif
      </span>

    {{-- Notification Content --}}
    <li class="flex items-start gap-3 list-none w-full">
      @php
      $type = $notification->data['type'];
      $message = $notification->data['message'] ?? '';
      $url = route('admin.notifications.read', $notification->id);
      $username = null;

      foreach ($notification->data as $key => $value) {
      if (!$username && str_contains($key, 'username')) {
      $username = $value;
      break;
        }
      }

       $notifyUser = $notifiedUsers[$username] ?? null;
  
       $avatar = $notifyUser?->avatar_url;
      @endphp

    @if($notifyUser)
    <a href="{{ route('profile', $username) }}">
        <img src="{{ $avatar }}?v={{ $notifyUser?->updated_at->timestamp ?? time() }}" class="w-8 h-8 rounded-full object-cover" alt="">
    </a>
    @endif

      <div class="flex-1">
          <a href="{{ $url }}" class="text-sm text-gray-700 hover:text-black font-medium block">
              {!! $message !!}
          </a>
          <div class="flex justify-between items-center gap-4 mt-1">
              <small class="text-xs text-gray-400">{{ $notification->created_at->diffForHumans() }}</small>
              <form action="{{ route('notifications.delete', $notification->id) }}" method="POST">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="text-xs text-white bg-red-500 px-2 rounded-full">x</button>
              </form>
          </div>
      </div>
    </li>
  </div>
  @empty
    @php  
    $sort = request('sort');
    $type = request('type');
     @endphp
    <div class="grid place-items-center h-40 w-full">
      <p class="text-xl font-bold">
        No 
        @if($type)
        {{ ucfirst($type)}}
        @endif
        @if($sort == 'read')
        Read
        @elseif($sort === 'unread')
        Unread
        @endif
        Notifications
      </p>
    </div>
@endforelse
{!! $notifications->links() !!}
</div>

@push('scripts')
<script>
  document.addEventListener("DOMContentLoaded", () => {
    const url = new URL(window.location.href);
    const params = url.searchParams;

    // Remove empty parameters
    ['sort', 'type'].forEach(key => {
      if (!params.get(key)) {
        params.delete(key);
      }
    });

    // Update the URL without reloading
    const newUrl = url.pathname + (params.toString() ? '?' + params.toString() : '');
    window.history.replaceState({}, '', newUrl);
  });
  </script>
@endpush
@endsection


