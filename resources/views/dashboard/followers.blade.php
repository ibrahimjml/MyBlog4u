@extends('layouts.dashboard')

@section('content')
<div class="mb-6">
  <h1 class="font-display text-xl font-bold text-foreground">Followers<span class="text-primary">.</span></h1>
  <p class="mt-1 font-mono text-[11px] uppercase tracking-[0.16em] text-muted-foreground">People who follow you</p>
</div>

<div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
  @forelse($people as $person)
    <div
      class="group flex items-center gap-4 rounded-2xl border border-border bg-card p-4 transition-colors duration-300 hover:border-primary/40">
      <a href="{{ route('profile.home', $person->username) }}" class="shrink-0">
        <img src="{{ $person->avatar_url }}" alt="{{ $person->name }}"
          class="h-12 w-12 rounded-full border border-border object-cover">
      </a>

      <div class="min-w-0 flex-1">
        <div class="flex items-center gap-3">
          <p class="truncate text-sm font-semibold text-foreground">{{ $person->name }}</p>
          <x-follow-button :status="$authFollowings[$person->id] ?? null" :user-id="$person->id" type="label"
            onclick="fetchfollow(this)" class="follow rounded-lg px-3 py-1 text-[11px] font-bold" />
        </div>
        <p class="truncate font-mono text-[11px] text-muted-foreground">{{ '@' . $person->username }}</p>
      </div>

      <form action="{{ route('follower.remove', $person->id) }}" method="POST" class="shrink-0">
        @csrf
        @method('DELETE')
        <button type="submit"
          class="rounded-lg border border-border px-3 py-1.5 text-[11px] font-semibold text-muted-foreground transition-colors hover:border-destructive hover:text-destructive">
          Remove
        </button>
      </form>
    </div>
  @empty
    <div class="rounded-2xl border border-dashed border-border p-10 text-center sm:col-span-2 xl:col-span-3">
      <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-secondary text-muted-foreground">
        <i class="fas fa-users" aria-hidden="true"></i>
      </span>
      <p class="mt-3 font-display text-base font-semibold text-foreground">No followers yet</p>
      <p class="mt-1 text-sm text-muted-foreground">People who follow you will appear here.</p>
    </div>
  @endforelse
</div>

<div class="mt-6">
  {{ $people->links() }}
</div>

@endsection

@push('scripts')
  <script src="{{ asset('js/fetchfollow.js') }}"></script>
@endpush
