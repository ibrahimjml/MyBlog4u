@php
  $type = $notification->data['type'];
  $message = $notification->data['message'] ?? '';
  $url = route('notifications.read', $notification->id);
  $username = null;
  foreach ($notification->data as $key => $value) {
      if (!$username && str_contains($key, 'username')) {
          $username = $value;
          break;
      }
  }
  $user = $users[$username] ?? null;
  $avatar = $user?->avatar_url ?? asset('storage/avatars/default.jpg');
@endphp

<li class="notification-item flex items-start gap-2 rounded-lg p-2 transition-colors hover:bg-secondary sm:gap-3"
  data-type="{{ $type }}">
  {{-- icon badge --}}
  <span class="mt-2">
    @if($notification->read_at === null)
      <i class="fas fa-circle text-[8px] text-primary"></i>
    @else
      <i class="fas fa-circle text-[8px] text-border"></i>
    @endif
  </span>

  @if($user)
    <a href="{{ route('profile.home', $username) }}" class="shrink-0">
      <img src="{{ $avatar }}?v={{ $user?->updated_at->timestamp ?? time() }}"
        class="h-8 w-8 rounded-full object-cover" alt="">
    </a>
  @endif

  <div class="min-w-0 flex-1 text-left">
    <a href="{{ $url }}"
      class="block break-words text-xs font-medium leading-5 text-foreground transition-colors hover:text-primary">
      {!! $message !!}
    </a>
    <div class="mt-1 flex flex-wrap items-center gap-2">
      <small class="text-[11px] text-muted-foreground">{{ $notification->created_at->diffForHumans() }}</small>

      <form action="{{ route('notifications.delete', $notification->id) }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit" title="delete notification"
          class="flex h-5 w-5 items-center justify-center rounded-full bg-destructive text-[10px] text-destructive-foreground transition-opacity hover:opacity-80">
          x
        </button>
      </form>

      {{-- accept follow status if notification type = follow --}}
      @if(!auth()->user()->is_admin && $notification->data['type'] === \App\Enums\NotificationType::FOLLOW->value && $notification->data['status'] === 'private')
        <form action="{{ route('follow.accept', $notification->data['follower_id']) }}" method="POST">
          @csrf
          <button type="submit"
            class="rounded-full bg-primary px-2.5 py-1 text-[10px] text-primary-foreground transition-opacity hover:opacity-80">
            Accept
          </button>
        </form>
      @endif
    </div>
  </div>
</li>
