@extends('layouts.guest')

@section('content')
  @php
    $labelClass = 'mb-2 block font-mono text-[11px] uppercase tracking-widest text-muted-foreground';
    $inputClass = 'w-full rounded-xl border border-border bg-background px-4 py-3 font-mono text-sm text-foreground placeholder:text-muted-foreground/60 transition focus:border-primary focus:outline-none focus:ring-2 focus:ring-ring/30';
    $errorClass = 'mt-1.5 font-mono text-[11px] text-destructive';
  @endphp

  <main class="mx-auto w-full max-w-md">
    <div class="mb-6 flex justify-center">
      <x-logo class="text-2xl" />
    </div>

    <section
      class="overflow-hidden rounded-3xl border border-border bg-card/95 shadow-[0_24px_70px_-35px_oklch(var(--foreground)/0.35)] backdrop-blur">
      <header class="border-b border-border px-6 py-6 text-center sm:px-8">
        <p class="font-mono text-[11px] uppercase tracking-[0.25em] text-primary">two-factor recovery</p>
        <h1 class="mt-2 font-display text-2xl font-bold tracking-tight text-foreground">Recovery code</h1>
        <p class="mt-2 text-sm text-muted-foreground">
          Enter one of your saved recovery codes to continue.
        </p>
      </header>

      <form class="space-y-5 px-6 py-6 sm:px-8" action="{{ route('2fa.verify.recovery') }}" method="POST">
        @csrf

        <div>
          <label for="recovery_code" class="{{ $labelClass }}">Recovery code</label>
          <input id="recovery_code" type="text" name="recovery_code" required autocomplete="off"
            class="{{ $inputClass }} @error('recovery_code') border-destructive @enderror"
            placeholder="xxxx-xxxx-xxxx" autofocus>
          @error('recovery_code')
            <p class="{{ $errorClass }}">{{ $message }}</p>
          @enderror
        </div>

        <button type="submit"
          class="w-full rounded-full bg-primary px-5 py-3 font-mono text-xs font-semibold text-primary-foreground transition-opacity hover:opacity-90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
          Verify code
        </button>
      </form>
    </section>

    <p class="mt-6 text-center text-sm text-muted-foreground">
      Back to
      <a href="{{ route('2fa.confirmation') }}" class="font-semibold text-primary transition-opacity hover:opacity-80">
        authenticator verification
      </a>
    </p>
  </main>
@endsection
