@extends('layouts.guest')

@section('content')
  @php
    $errorClass = 'mt-1.5 font-mono text-[11px] text-destructive';
  @endphp

  <main class="mx-auto w-full max-w-md">
    <div class="mb-6 flex justify-center">
      <x-logo class="text-2xl" />
    </div>

    <section
      class="overflow-hidden rounded-3xl border border-border bg-card/95 shadow-[0_24px_70px_-35px_oklch(var(--foreground)/0.35)] backdrop-blur">
      <header class="border-b border-border px-6 py-6 text-center sm:px-8">
        <p class="font-mono text-[11px] uppercase tracking-[0.25em] text-primary">two-factor</p>
        <h1 class="mt-2 font-display text-2xl font-bold tracking-tight text-foreground">Authentication code</h1>
        <p class="mt-2 text-sm text-muted-foreground">
          Enter the 6-digit code from your authenticator app.
        </p>
      </header>

      <form class="space-y-5 px-6 py-6 sm:px-8" action="{{ route('2fa.verify') }}" method="POST" data-twofactor-challenge>
        @csrf

        <input type="hidden" name="code" id="twofa_code">

        <div class="flex justify-center gap-1.5 sm:gap-2">
          @for ($i = 0; $i < 6; $i++)
            <input type="text" inputmode="numeric" maxlength="1" autocomplete="{{ $i === 0 ? 'one-time-code' : 'off' }}"
              class="twofa-input h-11 w-8 rounded-xl border border-border bg-background text-center font-mono text-base font-bold text-foreground transition focus:border-primary focus:outline-none focus:ring-2 focus:ring-ring/30 sm:h-12 sm:w-11 sm:text-lg">
          @endfor
        </div>

        @error('code')
          <p class="{{ $errorClass }} text-center">{{ $message }}</p>
        @enderror

        <label for="trust_device"
          class="flex cursor-pointer items-center gap-2 text-sm text-muted-foreground">
          <input id="trust_device" type="checkbox" name="trust_device" value="1"
            class="h-4 w-4 rounded border-border bg-background accent-primary focus:ring-ring">
          Trust this device for 30 days
        </label>

        <button type="submit"
          class="w-full rounded-full bg-primary px-5 py-3 font-mono text-xs font-semibold text-primary-foreground transition-opacity hover:opacity-90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
          Verify
        </button>
      </form>
    </section>

    <p class="mt-6 text-center text-sm text-muted-foreground">
      Having trouble?
      <a href="{{ route('2fa.recovery') }}" class="font-semibold text-primary transition-opacity hover:opacity-80">
        Verify with a recovery code
      </a>
    </p>
  </main>

  @push('scripts')
    @vite('resources/js/twofactor/twofactor.js')
  @endpush
@endsection
