@if($isPrivate())
<div class="my-4">
      <span class="flex h-14 w-14 items-center justify-center rounded-full border border-border bg-secondary">
        <i class="fas fa-lock text-muted-foreground"></i>
      </span>
    <p class="text-md text-left text-black/50 font-semibold mt-2">
        {{ $user->name }}'s profile is private.
    </p>
</div>
@else
{{ $slot }}
@endif
