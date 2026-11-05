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
        <p class="font-mono text-[11px] uppercase tracking-[0.25em] text-primary">identity check</p>
        <h1 class="mt-2 font-display text-2xl font-bold tracking-tight text-foreground">Verify your identity</h1>
        <p class="mt-2 text-sm text-muted-foreground">
          Enter the verification code sent to your registered email address.
        </p>
      </header>

      <form class="space-y-5 px-6 py-6 sm:px-8" action="{{ route('verify.code') }}" method="POST">
        @csrf

        <div>
          <label for="code" class="{{ $labelClass }}">Verification code</label>
          <input id="code" type="text" name="code" inputmode="numeric" autocomplete="one-time-code"
            class="{{ $inputClass }} font-mono tracking-[0.35em] @error('code') border-destructive @enderror"
            placeholder="000000" autofocus>
          @error('code')
            <p class="{{ $errorClass }}">{{ $message }}</p>
          @enderror
        </div>

        @recaptcha_enabled
          <div class="g-recaptcha" data-sitekey="{{ config('services.captcha.sitekey') }}"></div>
          @error('g-recaptcha-response')
            <p class="{{ $errorClass }}">{{ $message }}</p>
          @enderror
        @endrecaptcha_enabled

        <button type="submit"
          class="w-full rounded-full bg-primary px-5 py-3 font-mono text-xs font-semibold text-primary-foreground transition-opacity hover:opacity-90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
          Verify identity
        </button>
      </form>
    </section>
  </main>
@endsection
