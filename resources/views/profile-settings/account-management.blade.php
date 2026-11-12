@extends('layouts.setting')

@php
  $inputClass = 'mt-2 w-full rounded-xl border border-border bg-background px-3 py-2.5 text-sm font-medium text-foreground outline-none transition-colors placeholder:font-normal placeholder:text-muted-foreground focus:border-primary focus:ring-2 focus:ring-ring/30';
  $labelClass = 'block font-mono text-[10px] uppercase tracking-[0.16em] text-muted-foreground';
  $helpClass = 'mt-1 text-xs text-muted-foreground';
  $errorClass = 'mt-1.5 text-xs font-medium text-destructive';
@endphp

@section('header')
    <div class="inline-flex items-center gap-2 rounded-lg border border-primary/10 bg-primary/10 px-3 py-1.5">
    <span class="h-1.5 w-1.5 rounded-full bg-primary"></span>
    <p class="font-mono text-xs font-medium uppercase tracking-[0.18em] text-primary">
        Account Management
    </p>
</div>
@endsection

@section('content')
<div>
  <header class="border-b border-border pb-5">
    <h2 class="font-display text-xl font-bold text-foreground">Account management<span class="text-primary">.</span></h2>
    <p class="mt-1 text-sm text-muted-foreground">Configure your account credentials and password.</p>
  </header>

  <form id="myform" action="{{ route('update.account') }}" method="POST" enctype="multipart/form-data" class="mt-6">
    @csrf
    @method('PUT')

    <section>
      <h3 class="font-display text-base font-semibold text-foreground">Account</h3>
      <p class="{{ $helpClass }}">Update your username and email address.</p>

      <div class="mt-4 grid gap-5 sm:grid-cols-2">
        <div>
          <label for="username" class="{{ $labelClass }}">Username</label>
          <input id="username" type="text" name="username" value="{{ old('username', $user->username) }}"
            class="{{ $inputClass }} @error('username') border-destructive @enderror">
          <p class="mt-1 font-mono text-[10px] uppercase tracking-wide text-muted-foreground">You can change your username only once.</p>
          @error('username')
            <p class="{{ $errorClass }}">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label for="email" class="{{ $labelClass }}">Email</label>
          <input id="email" type="text" name="email" value="{{ old('email', $user->email) }}"
            class="{{ $inputClass }} @error('email') border-destructive @enderror">
          <p class="mt-1 font-mono text-[10px] uppercase tracking-wide text-muted-foreground">you need to verify your email after modifying it.</p>
          @error('email')
            <p class="{{ $errorClass }}">{{ $message }}</p>
          @enderror
        </div>
      </div>

      @if(is_null($user->email_verified_at))
        <div class="mt-4 flex flex-col gap-2 rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800 sm:flex-row sm:items-center sm:justify-between">
          <span class="flex items-center gap-2">
            <i class="fas fa-circle-exclamation" aria-hidden="true"></i>
            Your email is unverified. Please verify using the link sent to your inbox.
          </span>
          <button type="button"
            class="shrink-0 rounded-lg border border-amber-300 bg-white px-3 py-1.5 text-xs font-bold uppercase tracking-wide text-amber-800 transition-colors hover:bg-amber-100"
            onclick="document.getElementById('resend-email').submit();">
            Resend verification
          </button>
        </div>
      @endif
    </section>

    <section class="mt-8 border-t border-border pt-6">
      <h3 class="font-display text-base font-semibold text-foreground">Password</h3>
      <p class="{{ $helpClass }}">Modify your password. Leave the fields empty to keep your current password.</p>

      <div class="mt-4 grid gap-5 sm:grid-cols-2">
        <div class="sm:col-span-2">
          <label for="current_password" class="{{ $labelClass }}">Current password</label>
          <input id="current_password" type="password" name="current_password" autocomplete="current-password"
            class="{{ $inputClass }} @error('current_password') border-destructive @enderror">
          @error('current_password')
            <p class="{{ $errorClass }}">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label for="password" class="{{ $labelClass }}">New password</label>
          <input id="password" type="password" name="password" autocomplete="new-password"
            class="{{ $inputClass }} @error('password') border-destructive @enderror">
          @error('password')
            <p class="{{ $errorClass }}">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label for="password_confirmation" class="{{ $labelClass }}">Confirm new password</label>
          <input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password"
            class="{{ $inputClass }} @error('password_confirmation') border-destructive @enderror">
          @error('password_confirmation')
            <p class="{{ $errorClass }}">{{ $message }}</p>
          @enderror
        </div>
      </div>
    </section>

    <div class="sticky bottom-0 z-10 -mx-5 mt-8 flex justify-end border-t border-border bg-card/95 px-5 py-4 backdrop-blur sm:-mx-7 sm:px-7 lg:-mx-8 lg:px-8">
      <button type="submit"
        class="inline-flex items-center gap-2 rounded-xl bg-primary px-4 py-2.5 text-xs font-bold uppercase tracking-wide text-primary-foreground shadow-sm transition-opacity hover:opacity-90">
        <i class="fas fa-check" aria-hidden="true"></i>
        Save changes
      </button>
    </div>
  </form>
  
<!-- resend email form -->
 @if(is_null($user->email_verified_at))
  <form id="resend-email" action="{{ route('verification.send') }}" method="POST" class="hidden">
    @csrf
    @method('POST')
  </form>
@endif

  <section class="mt-8 rounded-2xl border border-destructive/30 bg-destructive/5 p-5">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
      <div>
        <h3 class="font-display text-base font-semibold text-destructive">Danger zone</h3>
        <p class="mt-1 text-sm text-muted-foreground">Once your account is deleted, all of its resources and data will be permanently deleted.</p>
      </div>
      <button id="show-menu" type="button"
        class="inline-flex shrink-0 items-center gap-2 rounded-xl bg-destructive px-4 py-2.5 text-xs font-bold uppercase tracking-wide text-destructive-foreground shadow-sm transition-opacity hover:opacity-90">
        <i class="fas fa-trash" aria-hidden="true"></i>
        Delete account
      </button>
    </div>
  </section>

  @include('partials.confirmation-delete-menu')
</div>
@endsection
