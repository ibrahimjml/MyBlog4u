@php
  $isAuthor = $comment->user_id === $comment->post_user_id;
@endphp

<div class="flex items-start gap-3 pr-9">
  <a href="{{ route('profile.home', $comment->user->username) }}" class="shrink-0">
    <img src="{{ $comment->user->avatar_url }}" alt="{{ $comment->user->name }}"
      class="h-8 w-8 rounded-full object-cover" loading="lazy">
  </a>

  <div class="min-w-0">
    <div class="flex flex-wrap items-center gap-2">
      <a href="{{ route('profile.home', $comment->user->username) }}"
        class="text-sm font-semibold text-foreground transition-colors hover:text-primary">
        {{ $comment->user->name }}
      </a>

      @if($isAuthor)
        <span class="rounded-full bg-primary/10 px-2 py-0.5 font-mono text-[9px] font-semibold uppercase tracking-[0.12em] text-primary">Author</span>
      @endif
    </div>

    <span class="mt-0.5 block font-mono text-[10px] uppercase tracking-[0.12em] text-muted-foreground">
      {{ $comment->created_at->diffForHumans() }}
    </span>
  </div>
</div>
