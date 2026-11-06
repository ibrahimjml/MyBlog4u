@extends('layouts.guest')

@section('content')
  <main class="mx-auto w-full max-w-md">
    <div class="mb-6 flex justify-center">
      <x-logo class="text-2xl" />
    </div>

    <section
      class="overflow-hidden rounded-3xl border border-border bg-card/95 shadow-[0_24px_70px_-35px_oklch(var(--foreground)/0.35)] backdrop-blur">
      <header class="border-b border-border px-6 py-6 text-center sm:px-8">
        <p class="font-mono text-[11px] uppercase tracking-[0.25em] text-primary">email verification</p>
        <h1 class="mt-2 font-display text-2xl font-bold tracking-tight text-foreground">Verify your email</h1>
        <p class="mt-2 text-sm text-muted-foreground">
          We already sent a verification link to
          @auth
            <span class="font-mono text-foreground">{{ auth()->user()->masked_email }}</span>.
          @else
            your inbox.
          @endauth
        </p>
      </header>

      <div class="space-y-5 px-6 py-6 sm:px-8">
        @if(session()->has('message'))
          <p class="rounded-xl border border-primary/30 bg-accent/60 px-4 py-3 font-mono text-[11px] text-accent-foreground">
            {{ session('message') }}
          </p>
        @endif

        <p class="text-sm leading-relaxed text-muted-foreground">
          Click the link in that email to activate your account. If it expired or never arrived, send a fresh one below.
        </p>

        <form action="{{ route('verification.send') }}" method="POST">
          @csrf
          <button type="submit"
            class="w-full rounded-full bg-primary px-5 py-3 font-mono text-xs font-semibold text-primary-foreground transition-opacity hover:opacity-90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
            Resend verification email
          </button>
        </form>
      </div>
    </section>

    <p class="mt-6 text-center text-sm text-muted-foreground">
      <a href="{{ route('home') }}" class="font-semibold text-primary transition-opacity hover:opacity-80">
        Back to home
      </a>
    </p>
  </main>
@endsection
