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
        <p class="font-mono text-[11px] uppercase tracking-[0.25em] text-primary">secure area</p>
        <h1 class="mt-2 font-display text-2xl font-bold tracking-tight text-foreground">Confirm password</h1>
        <p class="mt-2 text-sm text-muted-foreground">
          This is a secure area. Please confirm your password before continuing.
        </p>
      </header>

      <form class="space-y-5 px-6 py-6 sm:px-8" action="{{ route('confirm.password') }}" method="POST">
        @csrf

        <div>
          <label for="password" class="{{ $labelClass }}">Password</label>
          <input id="password" type="password" name="password" required autocomplete="current-password"
            class="{{ $inputClass }} @error('password') border-destructive @enderror" placeholder="••••••••"
            autofocus>
          @error('password')
            <p class="{{ $errorClass }}">{{ $message }}</p>
          @enderror
        </div>

        <button type="submit"
          class="w-full rounded-full bg-primary px-5 py-3 font-mono text-xs font-semibold text-primary-foreground transition-opacity hover:opacity-90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
          Confirm
        </button>
      </form>
    </section>
  </main>
@endsection
