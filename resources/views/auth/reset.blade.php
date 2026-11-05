@extends('layouts.guest')

@section('content')
  @php
    $labelClass = 'mb-2 block font-mono text-[11px] uppercase tracking-widest text-muted-foreground';
    $inputClass = 'w-full rounded-xl border border-border bg-background px-4 py-3 text-sm text-foreground placeholder:text-muted-foreground/60 transition focus:border-primary focus:outline-none focus:ring-2 focus:ring-ring/30';
    $errorClass = 'mt-1.5 font-mono text-[11px] text-destructive';
  @endphp

  <main class="mx-auto w-full max-w-md">
    <div class="mb-6 flex justify-center">
      <x-logo class="text-2xl" />
    </div>

    <section
      class="overflow-hidden rounded-3xl border border-border bg-card/95 shadow-[0_24px_70px_-35px_oklch(var(--foreground)/0.35)] backdrop-blur">
      <header class="border-b border-border px-6 py-6 text-center sm:px-8">
        <p class="font-mono text-[11px] uppercase tracking-[0.25em] text-primary">password reset</p>
        <h1 class="mt-2 font-display text-2xl font-bold tracking-tight text-foreground">Choose a new password</h1>
        <p class="mt-2 text-sm text-muted-foreground">Pick a strong password you haven't used before.</p>
      </header>

      <form class="space-y-5 px-6 py-6 sm:px-8" method="POST" action="{{ route('reset.password.post', $token) }}">
        @csrf

        <div>
          <label for="password" class="{{ $labelClass }}">New password</label>
          <input id="password" type="password" name="password" required autocomplete="new-password"
            class="{{ $inputClass }} @error('password') border-destructive @enderror" placeholder="••••••••">
          @error('password')
            <p class="{{ $errorClass }}">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label for="password-confirm" class="{{ $labelClass }}">Confirm new password</label>
          <input id="password-confirm" type="password" name="password_confirmation" required
            autocomplete="new-password" class="{{ $inputClass }}" placeholder="••••••••">
        </div>

        <button type="submit"
          class="w-full rounded-full bg-primary px-5 py-3 font-mono text-xs font-semibold text-primary-foreground transition-opacity hover:opacity-90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
          Reset password
        </button>
      </form>
    </section>

    <p class="mt-6 text-center text-sm text-muted-foreground">
      Back to
      <a href="{{ route('login') }}" class="font-semibold text-primary transition-opacity hover:opacity-80">
        login
      </a>
    </p>
  </main>
@endsection
