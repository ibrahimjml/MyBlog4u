@extends('layouts.setting')

@section('header')
    <div class="inline-flex items-center gap-2 rounded-lg border border-primary/10 bg-primary/10 px-3 py-1.5">
    <span class="h-1.5 w-1.5 rounded-full bg-primary"></span>
    <p class="font-mono text-xs font-medium uppercase tracking-[0.18em] text-primary">
        Privacy
    </p>
</div>
@endsection

@section('content')
  <div>
    <header class="border-b border-border pb-5">
      <h2 class="font-display text-xl font-bold text-foreground">Account privacy<span class="text-primary">.</span></h2>
      <p class="mt-1 text-sm text-muted-foreground">Control who can see your profile and activity.</p>
    </header>

    <form id="profileVisibilityForm" action="{{ route('profile.visibility') }}" method="POST" class="hidden">
      @csrf
      <input type="hidden" name="visibility" value="{{ $user->profile->is_public ? 1 : 0 }}" />
    </form>

    <div class="mt-6 rounded-2xl border border-border bg-background p-5">
      <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div class="flex items-start gap-3">
          <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-accent text-accent-foreground">
            <i class="fas fa-user-lock" aria-hidden="true"></i>
          </span>
          <div>
            <h3 class="font-display text-base font-semibold text-foreground">Private account</h3>

          </div>
        </div>

        <x-toggle :checked="!$user->profile->is_public" label="Private" onchange="
          const form = document.getElementById('profileVisibilityForm');
          form.querySelector('[name=visibility]').value = this.checked ? 0 : 1;
          form.submit();" />
      </div>

      <div class="mt-5 space-y-2 border-t border-border pt-4">
        <p class="flex items-start gap-2 text-sm text-muted-foreground">
          <i class="fas fa-globe mt-1 text-xs text-primary" aria-hidden="true"></i>
          When your account is public, your profile and posts can be seen by anyone.
        </p>
        <p class="flex items-start gap-2 text-sm text-muted-foreground">
          <i class="fas fa-lock mt-1 text-xs text-primary" aria-hidden="true"></i>
          When your account is private, only approved followers can see your content.
        </p>
      </div>
    </div>
  </div>
@endsection
