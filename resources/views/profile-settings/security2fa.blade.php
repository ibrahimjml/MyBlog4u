@extends('layouts.setting')

@php
  $user = auth()->user();
  $twoFactorGloballyEnabled = $twoFactorGloballyEnabled ?? true;
@endphp

@section('header')
    <div class="inline-flex items-center gap-2 rounded-lg border border-primary/10 bg-primary/10 px-3 py-1.5">
    <span class="h-1.5 w-1.5 rounded-full bg-primary"></span>
    <p class="font-mono text-xs font-medium uppercase tracking-[0.18em] text-primary">
        TwoFactor
    </p>
</div>
@endsection

@section('content')
<div class="space-y-5"
  data-twofactor
  data-csrf="{{ csrf_token() }}"
  data-enable-url="{{ route('enable.2fa') }}"
  data-confirm-url="{{ route('confirm.2fa') }}"
  data-disable-url="{{ route('disable.2fa') }}"
  data-regenerate-url="{{ route('regenerate.recovery.codes') }}"
  data-download-url="{{ route('download.recovery.codes') }}"
  data-trusted-index="{{ route('trusted-devices.index') }}"
  data-trusted-destroy-all="{{ route('trusted-devices.destroy-all') }}"
  data-trusted-destroy="{{ route('trusted-devices.destroy', ['device' => '__DEVICE_ID__']) }}">
  <header class="flex flex-col gap-5 border-b border-border pb-5 sm:flex-row sm:items-center sm:justify-between">
    <div class="flex items-start gap-4">
      <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-primary text-primary-foreground shadow-sm">
        <i class="fas fa-shield-alt"></i>
      </span>
      <div>
        <h2 class="font-display text-xl font-bold text-foreground">Two-factor authentication<span class="text-primary">.</span></h2>
        <p class="mt-1 text-sm text-muted-foreground">Add an extra layer of protection to your account.</p>
      </div>
    </div>
    <span class="inline-flex w-fit items-center gap-2 rounded-full px-3 py-1.5 font-mono text-[10px] font-bold uppercase tracking-wide {{ $user->has_two_factor_enabled ? 'bg-emerald-50 text-emerald-700' : 'bg-destructive/10 text-destructive' }}">
      <i class="fas {{ $user->has_two_factor_enabled ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
      {{ $user->has_two_factor_enabled ? 'Protection is on' : 'Protection is off' }}
    </span>
  </header>

  @unless($twoFactorGloballyEnabled)
    <div class="flex items-start gap-3 rounded-2xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800">
      <i class="fas fa-info-circle mt-0.5"></i>
      <p>Two-factor authentication is currently disabled by the administrator.</p>
    </div>
  @endunless

  @if(!$user->has_two_factor_enabled)
    @if($twoFactorGloballyEnabled)
      <div class="rounded-2xl border border-dashed border-border bg-background p-5 sm:p-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
          <div>
            <h3 class="font-display text-base font-semibold text-foreground">Secure your sign-ins</h3>
            <p class="mt-1 max-w-xl text-sm leading-6 text-muted-foreground">After enabling two-factor authentication, you will enter a time-based code from an authenticator app when you sign in.</p>
          </div>
          <button id="open2fa" type="button" class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-primary px-4 py-2.5 text-xs font-bold uppercase tracking-wide text-primary-foreground shadow-sm transition-opacity hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-ring/40">
            <i class="fas fa-user-shield"></i>
            <small>Enable two-factor</small>
          </button>
        </div>
      </div>
      <div id="2faContainer"></div><!-- 2fa model -->
      @include('profile-settings.partials.two-factor-confirmation')
    @endif
  @else
    <div class="grid gap-5 lg:grid-cols-2">
      <div class="rounded-2xl border border-border p-5">
        <div class="flex items-start gap-3">
          <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-accent text-accent-foreground"><i class="fas fa-key"></i></span>
          <div>
            <h3 class="font-display text-base font-semibold text-foreground">Recovery codes</h3>
            <p class="mt-1 text-sm leading-6 text-muted-foreground">Store these codes somewhere safe. They let you regain access if your authenticator is unavailable.</p>
          </div>
        </div>
        <div class="mt-5 flex flex-wrap gap-3">
          @unless($user->recovery_codes_downloaded)
            <button type="button" data-twofactor-download class="inline-flex items-center gap-2 rounded-xl bg-primary px-4 py-2.5 text-xs font-bold uppercase tracking-wide text-primary-foreground transition-opacity hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-ring/40">
              <i class="fas fa-download"></i> Download codes
            </button>
          @endunless
          <button type="button" data-twofactor-regenerate class="inline-flex items-center gap-2 rounded-xl border border-amber-200 bg-amber-50 px-4 py-2.5 text-xs font-bold uppercase tracking-wide text-amber-700 transition-colors hover:bg-amber-100 focus:outline-none focus:ring-2 focus:ring-amber-300">
            <i class="fas fa-sync-alt"></i> Regenerate
          </button>
        </div>
      </div>

      <div class="rounded-2xl border border-destructive/30 bg-destructive/5 p-5">
        <div class="flex items-start gap-3">
          <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-destructive/10 text-destructive"><i class="fas fa-lock"></i></span>
          <div>
            <h3 class="font-display text-base font-semibold text-foreground">Turn off two-factor authentication</h3>
            <p class="mt-1 text-sm leading-6 text-muted-foreground">This removes the additional sign-in check and all trusted devices from your account.</p>
          </div>
        </div>
        <button type="button" data-twofactor-disable class="mt-5 inline-flex items-center gap-2 rounded-xl bg-destructive px-4 py-2.5 text-xs font-bold uppercase tracking-wide text-destructive-foreground transition-opacity hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-destructive/40">
          <i class="fas fa-user-lock"></i> Disable two-factor
        </button>
      </div>
    </div>

    <section class="rounded-2xl border border-border" aria-labelledby="trusted-devices-title">
      <div class="flex flex-col gap-3 border-b border-border px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
          <h3 id="trusted-devices-title" class="font-display text-base font-semibold text-foreground">Trusted devices</h3>
          <p class="mt-1 text-sm text-muted-foreground">These devices can sign in without a two-factor code for up to 30 days.</p>
        </div>
        <button id="removeAllTrustedDevices" type="button" class="hidden font-mono text-[10px] font-bold uppercase tracking-wide text-destructive transition-colors hover:text-destructive/80">Remove all</button>
      </div>
      <div id="trustedDevicesList" class="divide-y divide-border" aria-live="polite">
        <div class="flex items-center gap-3 px-5 py-5 text-sm text-muted-foreground"><i class="fas fa-circle-notch fa-spin"></i> Loading trusted devices…</div>
      </div>
    </section>

    @include('profile-settings.partials.disable-two-factor')
  @endif
</div>
@endsection

@push('scripts')
  @vite('resources/js/twofactor/twofactor.js')
@endpush
