@extends('layouts.setting')

@section('header')
    <div class="inline-flex items-center gap-2 rounded-lg border border-primary/10 bg-primary/10 px-3 py-1.5">
    <span class="h-1.5 w-1.5 rounded-full bg-primary"></span>
    <p class="font-mono text-xs font-medium uppercase tracking-[0.18em] text-primary">
        Active Sessions
    </p>
</div>
@endsection

@section('content')
<div class="space-y-5">
  <header class="flex flex-col gap-5 border-b border-border pb-5 sm:flex-row sm:items-center sm:justify-between">
    <div class="flex items-start gap-4">
      <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-primary text-primary-foreground shadow-sm"><i class="fas fa-laptop"></i></span>
      <div>
        <h2 class="font-display text-xl font-bold text-foreground">Active sessions<span class="text-primary">.</span></h2>
        <p class="mt-1 text-sm text-muted-foreground">Review browsers signed in to your account and log out any session you do not recognize.</p>
      </div>
    </div>
    <span class="inline-flex w-fit items-center gap-2 rounded-full bg-accent px-3 py-1.5 font-mono text-[10px] font-bold uppercase tracking-wide text-accent-foreground"><i class="fas fa-desktop"></i> {{ $sessions->count() }} {{ Str::plural('session', $sessions->count()) }}</span>
  </header>

  <section class="overflow-hidden rounded-2xl border border-border" aria-labelledby="active-sessions-title">
    <div class="border-b border-border px-5 py-4">
      <h3 id="active-sessions-title" class="font-display text-base font-semibold text-foreground">Signed-in browsers</h3>
      <p class="mt-1 text-sm text-muted-foreground">Remove access from any browser or device you no longer use.</p>
    </div>
    <div class="divide-y divide-border">
      @forelse ($sessions as $session)
        <article class="flex flex-col gap-4 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
          <div class="flex min-w-0 items-start gap-3">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl {{ $session->is_current ? 'bg-emerald-50 text-emerald-600' : 'bg-secondary text-muted-foreground' }}"><i class="fas fa-desktop"></i></span>
            <div class="min-w-0">
              <div class="flex flex-wrap items-center gap-2">
                <h4 class="text-sm font-semibold text-foreground">{{ $session->browser }} on {{ $session->platform }}</h4>
                @if ($session->is_current)
                  <span class="rounded-full bg-emerald-50 px-2 py-0.5 font-mono text-[10px] font-bold uppercase tracking-wide text-emerald-700">Current browser</span>
                @endif
              </div>
              <p class="mt-1 font-mono text-[11px] text-muted-foreground">{{ $session->ip_address ?? 'Unknown IP' }} &middot; Last active {{ $session->last_active_at->diffForHumans() }}</p>
            </div>
          </div>
          <form method="POST" action="{{ route('active.sessions.destroy', $session) }}" class="shrink-0">
            @csrf
            @method('DELETE')
            <button type="submit" class="inline-flex items-center gap-2 rounded-xl border border-destructive/30 bg-destructive/10 px-4 py-2.5 text-xs font-bold uppercase tracking-wide text-destructive transition-colors hover:bg-destructive/15 focus:outline-none focus:ring-2 focus:ring-destructive/40" onclick="return confirm('Log out this browser session?')"><i class="fas fa-right-from-bracket"></i> {{ $session->is_current ? 'Log out' : 'Log out browser' }}</button>
          </form>
        </article>
      @empty
        <div class="px-5 py-10 text-center">
          <span class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-secondary text-muted-foreground"><i class="fas fa-laptop"></i></span>
          <p class="mt-3 text-sm font-medium text-foreground">No active browser sessions</p>
          <p class="mt-1 text-xs text-muted-foreground">Signed-in browsers will appear here.</p>
        </div>
      @endforelse
    </div>
  </section>
</div>
@endsection
