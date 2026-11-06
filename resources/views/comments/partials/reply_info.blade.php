@php
  $isAuthor = $reply->user_id === $reply->post_user_id;
@endphp

<div class="flex items-start gap-3 pr-9">
  <a href="{{ route('profile.home', $reply->user->username) }}" class="shrink-0">
    <img src="{{ $reply->user->avatar_url }}" alt="{{ $reply->user->name }}"
      class="h-7 w-7 rounded-full object-cover" loading="lazy">
  </a>

  <div class="min-w-0">
    <div class="flex flex-wrap items-center gap-x-2 gap-y-1">
      <a href="{{ route('profile.home', $reply->user->username) }}"
        class="text-sm font-semibold text-foreground transition-colors hover:text-primary">
        {{ $reply->user->name }}
      </a>

      @if($isAuthor)
        <span class="rounded-full bg-primary/10 px-2 py-0.5 font-mono text-[9px] font-semibold uppercase tracking-[0.12em] text-primary">Author</span>
      @endif

      @if($reply->parent)
        <span class="font-mono text-[10px] text-muted-foreground">
          to <span class="text-primary">{{ '@' . $reply->parent->user->username }}</span>
        </span>
      @endif
    </div>

    <span class="mt-0.5 block font-mono text-[10px] uppercase tracking-[0.12em] text-muted-foreground">
      {{ $reply->created_at->diffForHumans() }}
    </span>
  </div>
</div>
