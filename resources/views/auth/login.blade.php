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
        <p class="font-mono text-[11px] uppercase tracking-[0.25em] text-primary">welcome back</p>
        <h1 class="mt-2 font-display text-2xl font-bold tracking-tight text-foreground">Login</h1>
        <p class="mt-2 text-sm text-muted-foreground">Sign in to continue to your reading list.</p>
      </header>

      <form id="recaptcha" class="space-y-5 px-6 py-6 sm:px-8" method="POST" action="{{ route('login.post') }}">
        @csrf
        @method('POST')

        <div>
          <label for="login" class="{{ $labelClass }}">Username or email</label>
          <input id="login" type="text" name="login" value="{{ old('login') }}" required autocomplete="username"
            class="{{ $inputClass }} @error('login') border-destructive @enderror"
            placeholder="you@example.com">
          @error('login')
            <p class="{{ $errorClass }}">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label for="password" class="{{ $labelClass }}">Password</label>
          <input id="password" type="password" name="password" required autocomplete="current-password"
            class="{{ $inputClass }} @error('password') border-destructive @enderror" placeholder="••••••••">
          @error('password')
            <p class="{{ $errorClass }}">{{ $message }}</p>
          @enderror
        </div>

        <div class="flex flex-wrap items-center justify-between gap-3">
          <label for="remember" class="flex cursor-pointer items-center gap-2 text-sm text-muted-foreground">
            <input id="remember" type="checkbox" name="remember"
              class="h-4 w-4 rounded border-border bg-background accent-primary focus:ring-ring">
            Remember me
          </label>

          <a href="{{ route('forgot.password') }}"
            class="font-mono text-[11px] text-primary transition-opacity hover:opacity-80">
            Forgot password?
          </a>
        </div>

        <button type="submit"
          class="w-full rounded-full bg-primary px-5 py-3 font-mono text-xs font-semibold text-primary-foreground transition-opacity hover:opacity-90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-60">
          Login
        </button>
      </form>
    </section>

    <p class="mt-6 text-center text-sm text-muted-foreground">
      Don't have an account?
      <a href="{{ route('register') }}" class="font-semibold text-primary transition-opacity hover:opacity-80">
        Create one
      </a>
    </p>

    @if(config('demo.enabled'))
      <div class="mt-4 rounded-2xl border border-border bg-card/90 p-4 backdrop-blur">
        <p class="font-mono text-[11px] uppercase tracking-widest text-primary">Admin credentials</p>
        <p class="mt-2 text-sm text-muted-foreground">Username:
          <span class="font-mono text-foreground">{{ config('demo.admin.username') }}</span>
        </p>
        <p class="text-sm text-muted-foreground">Password:
          <span class="font-mono text-foreground">{{ config('demo.admin.password') }}</span>
        </p>
      </div>

      <div class="mt-3 rounded-2xl border border-border bg-card/90 p-4 backdrop-blur">
        <p class="font-mono text-[11px] uppercase tracking-widest text-primary">User credentials</p>
        <p class="mt-2 text-sm text-muted-foreground">Username:
          <span class="font-mono text-foreground">{{ config('demo.user.username') }}</span>
        </p>
        <p class="text-sm text-muted-foreground">Password:
          <span class="font-mono text-foreground">{{ config('demo.user.password') }}</span>
        </p>
      </div>
    @endif
  </main>

  @push('scripts')
    <script>
      document.addEventListener('DOMContentLoaded', () => {
        const loginForm = document.getElementById('recaptcha');
        if (!loginForm) {
          return;
        }

        const submitButton = loginForm.querySelector('button[type="submit"]');
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        loginForm.addEventListener('submit', async (event) => {
          event.preventDefault();

          const login = loginForm.querySelector('#login')?.value.trim();
          const password = loginForm.querySelector('#password')?.value;
          const remember = loginForm.querySelector('#remember')?.checked ?? false;

          if (!login || !password) {
            return;
          }

          submitButton.disabled = true;
          const originalLabel = submitButton.textContent;
          submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

          try {
            const response = await fetch(loginForm.action, {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
              },
              body: JSON.stringify({ login, password, remember }),
            });

            const data = await response.json();

            if (!response.ok) {
              const message = data.message || Object.values(data.errors || {})?.flat()?.[0] || 'Login failed.';
              toastr.error(message);
              return;
            }

            if (data.redirect) {
              window.location.assign(data.redirect);
              if (!data.require_2fa) {
                toastr.success(data.message);
              }
              return;
            }

            window.location.reload();
          } catch (error) {
            console.error(error);
          } finally {
            submitButton.disabled = false;
            submitButton.textContent = originalLabel;
          }
        });
      });
    </script>
  @endpush
@endsection
