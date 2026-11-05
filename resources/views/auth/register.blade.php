@extends('layouts.guest')

@section('content')
  @php
    $labelClass = 'mb-2 block font-mono text-[11px] uppercase tracking-widest text-muted-foreground';
    $inputClass = 'w-full rounded-xl border border-border bg-background px-4 py-3 text-sm text-foreground placeholder:text-muted-foreground/60 transition focus:border-primary focus:outline-none focus:ring-2 focus:ring-ring/30';
    $errorClass = 'mt-1.5 font-mono text-[11px] text-destructive';
    $inputErrorClass = 'mt-1.5 hidden font-mono text-[11px] text-destructive';
  @endphp

  <main class="mx-auto w-full max-w-lg">
    <div class="mb-6 flex justify-center">
      <x-logo class="text-2xl" />
    </div>

    <section
      class="overflow-hidden rounded-3xl border border-border bg-card/95 shadow-[0_24px_70px_-35px_oklch(var(--foreground)/0.35)] backdrop-blur">
      <header class="border-b border-border px-6 py-6 text-center sm:px-8">
        <p class="font-mono text-[11px] uppercase tracking-[0.25em] text-primary">join the archive</p>
        <h1 class="mt-2 font-display text-2xl font-bold tracking-tight text-foreground">Create account</h1>
        <p class="mt-2 text-sm text-muted-foreground">Start publishing, saving, and following other writers.</p>
      </header>

      <form id="registerForm" class="space-y-5 px-6 py-6 sm:px-8" method="POST" action="{{ route('register.post') }}">
        @csrf

        <div class="grid gap-4 sm:grid-cols-2">
          <div>
            <label for="name" class="{{ $labelClass }}">Name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus
              class="{{ $inputClass }} @error('name') border-destructive @enderror" placeholder="Your full name">
            @error('name')
              <p class="{{ $errorClass }}">{{ $message }}</p>
            @enderror
            <p data-error-for="name" class="{{ $inputErrorClass }}"></p>
          </div>

          <div>
            <label for="username" class="{{ $labelClass }}">Username</label>
            <input id="username" type="text" name="username" value="{{ old('username') }}" required
              autocomplete="username" class="{{ $inputClass }} @error('username') border-destructive @enderror"
              placeholder="yourhandle">
            @error('username')
              <p class="{{ $errorClass }}">{{ $message }}</p>
            @enderror
            <p data-error-for="username" class="{{ $inputErrorClass }}"></p>
          </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
          <div>
            <label for="email" class="{{ $labelClass }}">Email address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email"
              class="{{ $inputClass }} @error('email') border-destructive @enderror" placeholder="you@example.com">
            @error('email')
              <p class="{{ $errorClass }}">{{ $message }}</p>
            @enderror
            <p data-error-for="email" class="{{ $inputErrorClass }}"></p>
          </div>

          <div>
            <label for="phone" class="{{ $labelClass }}">Phone</label>
            <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" required autocomplete="tel"
              class="{{ $inputClass }} @error('phone') border-destructive @enderror" placeholder="Phone number">
            @error('phone')
              <p class="{{ $errorClass }}">{{ $message }}</p>
            @enderror
            <p data-error-for="phone" class="{{ $inputErrorClass }}"></p>
          </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
          <div>
            <label for="password" class="{{ $labelClass }}">Password</label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
              class="{{ $inputClass }} @error('password') border-destructive @enderror" placeholder="••••••••">
            @error('password')
              <p class="{{ $errorClass }}">{{ $message }}</p>
            @enderror
            <p data-error-for="password" class="{{ $inputErrorClass }}"></p>
          </div>

          <div>
            <label for="password-confirm" class="{{ $labelClass }}">Confirm password</label>
            <input id="password-confirm" type="password" name="password_confirmation" required
              autocomplete="new-password" class="{{ $inputClass }}" placeholder="••••••••">
            <p data-error-for="password_confirmation" class="{{ $inputErrorClass }}"></p>
          </div>
        </div>

        @if($terms && $privacy)
          <div>
            <div class="flex items-start gap-2">
              <input id="accept" type="checkbox" name="accept"
                class="mt-0.5 h-4 w-4 rounded border-border bg-background accent-primary focus:ring-ring">
              <label for="accept" class="text-sm leading-relaxed text-muted-foreground">
                I agree to the
                <a class="text-primary underline underline-offset-2 hover:opacity-80"
                  href="{{ route('custom.page', $terms) }}">terms</a>
                and
                <a class="text-primary underline underline-offset-2 hover:opacity-80"
                  href="{{ route('custom.page', $privacy) }}">privacy policy</a>.
              </label>
            </div>
            @error('accept')
              <p class="{{ $errorClass }}">{{ $message }}</p>
            @enderror
            <p data-error-for="accept" class="{{ $inputErrorClass }}"></p>
          </div>
        @endif

        @recaptcha_enabled
          <div class="g-recaptcha" data-sitekey="{{ config('services.captcha.sitekey') }}"></div>
          @error('g-recaptcha-response')
            <p class="{{ $errorClass }}">{{ $message }}</p>
          @enderror
          <p data-error-for="g-recaptcha-response" class="{{ $inputErrorClass }}"></p>
        @endrecaptcha_enabled

        <button type="submit"
          class="w-full rounded-full bg-primary px-5 py-3 font-mono text-xs font-semibold text-primary-foreground transition-opacity hover:opacity-90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-60">
          Create account
        </button>
      </form>
    </section>

    <p class="mt-6 text-center text-sm text-muted-foreground">
      Already have an account?
      <a href="{{ route('login') }}" class="font-semibold text-primary transition-opacity hover:opacity-80">
        Login
      </a>
    </p>
  </main>

  @push('scripts')
    <script>
      document.addEventListener('DOMContentLoaded', () => {
        const registerForm = document.getElementById('registerForm');
        if (!registerForm) {
          return;
        }

        const submitButton = registerForm.querySelector('button[type="submit"]');
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const phoneInputField = document.querySelector('#phone');
        const phoneInput = phoneInputField && typeof window.intlTelInput === 'function'
          ? (window.intlTelInputGlobals?.getInstance?.(phoneInputField) || window.intlTelInput(phoneInputField, {
            utilsScript: 'https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js',
          }))
          : null;

        if (phoneInput) {
          fetch('https://get.geojs.io/v1/ip/geo.json')
            .then((response) => response.json())
            .then((data) => {
              const country = data.country_code;
              phoneInput.setCountry(country);
            })
            .catch((error) => console.error('Error fetching location:', error));
        }

        function clearRegisterErrors() {
          registerForm.querySelectorAll('[data-error-for]').forEach((element) => {
            element.textContent = '';
            element.classList.add('hidden');
          });

          registerForm.querySelectorAll('input, textarea, select').forEach((input) => {
            input.classList.remove('border-destructive');
            input.classList.add('border-border');
          });
        }

        function showRegisterErrors(errors) {
          Object.entries(errors).forEach(([field, messages]) => {
            const errorElement = registerForm.querySelector(`[data-error-for="${field}"]`);
            const input = registerForm.querySelector(`[name="${field}"]`);

            if (input) {
              input.classList.remove('border-border');
              input.classList.add('border-destructive');
            }

            if (errorElement) {
              const message = Array.isArray(messages) ? messages[0] : messages;
              errorElement.textContent = message;
              errorElement.classList.remove('hidden');
            }
          });
        }

        registerForm.addEventListener('submit', async (event) => {
          event.preventDefault();
          clearRegisterErrors();

          submitButton.disabled = true;
          const originalLabel = submitButton.textContent;
          submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

          try {
            if (phoneInput && phoneInputField) {
              const countryCodeInput = registerForm.querySelector('input[name="country_code"]') ?? document.createElement('input');
              countryCodeInput.type = 'hidden';
              countryCodeInput.name = 'country_code';
              countryCodeInput.value = phoneInput.getSelectedCountryData()?.dialCode || '';
              if (!countryCodeInput.parentElement) {
                registerForm.appendChild(countryCodeInput);
              }
              phoneInputField.value = phoneInput.getNumber();
            }

            const response = await fetch(registerForm.action, {
              method: 'POST',
              headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
              },
              body: new FormData(registerForm),
            });

            const data = await response.json();

            if (!response.ok) {
              if (response.status === 422 && data.errors) {
                showRegisterErrors(data.errors);
                return;
              }

              const message = data.message || Object.values(data.errors || {})?.flat()?.[0] || 'Registration failed.';
              if (window.toastr) {
                toastr.error(message);
              } else {
                alert(message);
              }
              return;
            }

            if (window.toastr) {
              toastr.success(data.message || 'Registered successfully.');
            }

            registerForm.reset();
            clearRegisterErrors();

            if (data.redirect) {
              window.location.assign(data.redirect);
            } else {
              window.location.assign('/login');
            }
          } catch (error) {
            if (window.toastr) {
              toastr.error(error.message || 'Registration failed.');
            } else {
              alert(error.message || 'Registration failed.');
            }
          } finally {
            submitButton.disabled = false;
            submitButton.textContent = originalLabel;
          }
        });
      });
    </script>
  @endpush
@endsection
