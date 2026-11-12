@extends('layouts.setting')

@push('header')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css" />
@endpush
@push('vite')
  @vite(['resources/js/tinymce.js'])
@endpush

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
        Profile Info
    </p>
</div>
@endsection


@section('content')
<div>
  <header class="border-b border-border pb-5">
    <h2 class="font-display text-xl font-bold text-foreground">Profile details<span class="text-primary">.</span></h2>
    <p class="mt-1 text-sm text-muted-foreground">Manage how you appear across the platform.</p>
  </header>

  <form id="phone-form" action="{{ route('update.info') }}" method="POST" enctype="multipart/form-data" class="mt-6">
    @csrf
    @method('PUT')

    <section class="relative">
      <div class="relative h-40 overflow-hidden rounded-2xl border border-border bg-secondary sm:h-48"
        style="background-image: url('{{ $user->cover }}'); background-position: center; background-size: cover;">
        <div class="absolute inset-0 bg-gradient-to-t from-foreground/50 via-transparent to-transparent"></div>

        <div class="absolute right-3 top-3 flex items-center gap-2">
          @if($user->cover_photo !== 'sunset.jpg')
            <button type="button"
              onclick="if (confirm('Are you sure you want to delete the cover?')) document.getElementById('delete-cover-form').submit();"
              class="flex h-9 w-9 items-center justify-center rounded-xl border border-white/30 bg-foreground/40 text-white backdrop-blur-sm transition-colors hover:bg-destructive"
              title="Delete cover" aria-label="Delete cover">
              <i class="fas fa-trash text-xs" aria-hidden="true"></i>
            </button>
          @endif

          <label for="cover"
            class="inline-flex cursor-pointer items-center gap-2 rounded-xl border border-white/30 bg-foreground/40 px-3 py-2 text-[11px] font-bold uppercase tracking-wide text-white backdrop-blur-sm transition-colors hover:bg-foreground/60">
            <i class="fas fa-image" aria-hidden="true"></i>
            Change cover
          </label>
          <input name="cover" id="cover" class="hidden" type="file">
        </div>
      </div>

      <div class="relative -mt-10 flex flex-col gap-5 px-2 sm:-mt-12 sm:flex-row sm:items-end sm:justify-between">
        <div class="flex items-end gap-4">
          <div class="group relative h-24 w-24 shrink-0">
            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
              class="h-full w-full rounded-full border-4 border-card object-cover shadow-sm">
            @if($user->avatar !== 'default.jpg')
              <button type="button"
                onclick="if (confirm('Are you sure you want to delete the avatar?')) document.getElementById('delete-avatar-form').submit();"
                class="absolute inset-1/2 flex h-9 w-9 -translate-x-1/2 -translate-y-1/2 items-center justify-center rounded-full bg-destructive text-white opacity-0 transition-opacity group-hover:opacity-100 hover:bg-destructive/90"
                title="Delete avatar" aria-label="Delete avatar">
                <i class="fas fa-trash text-xs" aria-hidden="true"></i>
              </button>
            @endif
          </div>

          <div class="pb-1">
            <p class="text-sm font-semibold text-foreground">{{ $user->name }}</p>
            <p class="font-mono text-[11px] text-muted-foreground">{{ '@' . $user->username }}</p>
          </div>
        </div>

        <div class="flex flex-col gap-1.5 pb-1">
          <label for="avatar"
            class="inline-flex w-fit cursor-pointer items-center gap-2 rounded-xl border border-border bg-card px-3 py-2 text-[11px] font-bold uppercase tracking-wide text-foreground transition-colors hover:border-primary hover:text-primary">
            <i class="fas fa-camera" aria-hidden="true"></i>
            Change avatar
          </label>
          <input id="avatar" name="avatar" type="file" class="hidden">
          <p class="font-mono text-[10px] uppercase tracking-wide text-muted-foreground">PNG, JPG, JPEG, WEBP &middot; 300 × 300 px</p>
        </div>
      </div>

      @error('avatar')
        <p class="{{ $errorClass }}">{{ $message }}</p>
      @enderror
    </section>

    <section class="mt-8 border-t border-border pt-6">
      <h3 class="font-display text-base font-semibold text-foreground">Basic information</h3>
      <p class="{{ $helpClass }}">The name and phone number shown on your profile.</p>

      <div class="mt-4 grid gap-5 sm:grid-cols-2">
        <div>
          <label for="name" class="{{ $labelClass }}">Name</label>
          <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}"
            class="{{ $inputClass }} @error('name') border-destructive @enderror">
          @error('name')
            <p class="{{ $errorClass }}">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label for="phone" class="{{ $labelClass }}">Phone</label>
          <input id="phone" type="tel" name="phone" value="{{ old('phone', $user->phone ?? '') }}" autocomplete="tel"
            class="{{ $inputClass }} @error('phone') border-destructive @enderror">
          @error('phone')
            <p class="{{ $errorClass }}">{{ $message }}</p>
          @enderror
        </div>
      </div>
    </section>

    <section class="mt-8 border-t border-border pt-6">
      <h3 class="font-display text-base font-semibold text-foreground">About you</h3>
      <p class="{{ $helpClass }}">A short bio and a longer description for your profile page.</p>

      <div class="mt-4 space-y-5">
        <div>
          <label for="bio" class="{{ $labelClass }}">Bio <span class="normal-case tracking-normal text-muted-foreground">(optional)</span></label>
          <input id="bio" type="text" name="bio" value="{{ old('bio', $user->bio) }}"
            placeholder="Full-stack developer, coffee enthusiast..."
            class="{{ $inputClass }} @error('bio') border-destructive @enderror">
          @error('bio')
            <p class="{{ $errorClass }}">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label for="textarea" class="{{ $labelClass }}">About me <span class="normal-case tracking-normal text-muted-foreground">(optional)</span></label>
          <textarea id="textarea" name="aboutme" rows="10" placeholder="Tell people more about yourself"
            class="{{ $inputClass }} min-h-48 resize-y @error('aboutme') border-destructive @enderror">{{ old('aboutme', $user->aboutme) }}</textarea>
          @error('aboutme')
            <p class="{{ $errorClass }}">{{ $message }}</p>
          @enderror
        </div>
      </div>
    </section>

    <section class="mt-8 border-t border-border pt-6">
      <h3 class="font-display text-base font-semibold text-foreground">Social profiles</h3>
      <p class="{{ $helpClass }}">These links will show up on your public profile.</p>

      <div class="mt-4 grid gap-5 sm:grid-cols-2">
        <div>
          <label for="github" class="{{ $labelClass }}"><i class="fab fa-github mr-1" aria-hidden="true"></i> Github <span class="normal-case tracking-normal text-muted-foreground">(optional)</span></label>
          <input id="github" type="text" name="github" value="{{ old('github', $user->github) }}"
            placeholder="https://github.com/username"
            class="{{ $inputClass }} @error('github') border-destructive @enderror">
          @error('github')
            <p class="{{ $errorClass }}">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label for="linkedin" class="{{ $labelClass }}"><i class="fab fa-linkedin mr-1" aria-hidden="true"></i> Linkedin <span class="normal-case tracking-normal text-muted-foreground">(optional)</span></label>
          <input id="linkedin" type="text" name="linkedin" value="{{ old('linkedin', $user->linkedin) }}"
            placeholder="https://linkedin.com/in/username"
            class="{{ $inputClass }} @error('linkedin') border-destructive @enderror">
          @error('linkedin')
            <p class="{{ $errorClass }}">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label for="twitter" class="{{ $labelClass }}"><i class="fab fa-x-twitter mr-1" aria-hidden="true"></i> Twitter <span class="normal-case tracking-normal text-muted-foreground">(optional)</span></label>
          <input id="twitter" type="text" name="twitter" value="{{ old('twitter', $user->twitter) }}"
            placeholder="https://twitter.com/username"
            class="{{ $inputClass }} @error('twitter') border-destructive @enderror">
          @error('twitter')
            <p class="{{ $errorClass }}">{{ $message }}</p>
          @enderror
        </div>
      </div>
    </section>

    <section class="mt-8 border-t border-border pt-6">
      <h3 class="font-display text-base font-semibold text-foreground">Custom links</h3>
      <p class="{{ $helpClass }}">Add links to your portfolio, blog, or any other platform.</p>

      <div id="custom-links" class="mt-4 space-y-2">
        @foreach(old('social_links', $user->socialLinks ?? []) as $i => $link)
          @php
            $platform = is_array($link) ? $link['platform'] ?? '' : $link->platform ?? '';
            $url = is_array($link) ? $link['url'] ?? '' : $link->url ?? '';
            $linkId = is_object($link) ? $link->id : null;
          @endphp

          <div class="flex flex-col gap-2 sm:flex-row" data-link-id="{{ $linkId }}">
            <input type="text" name="social_links[{{ $i }}][platform]" placeholder="Platform" value="{{ $platform }}"
              class="w-full rounded-xl border border-border bg-background px-3 py-2.5 text-sm font-medium text-foreground outline-none transition-colors placeholder:font-normal placeholder:text-muted-foreground focus:border-primary focus:ring-2 focus:ring-ring/30 sm:w-48 @error("social_links.{$i}.platform") border-destructive @enderror">
            <input type="url" name="social_links[{{ $i }}][url]" placeholder="https://example.com" value="{{ $url }}"
              class="w-full flex-1 rounded-xl border border-border bg-background px-3 py-2.5 text-sm font-medium text-foreground outline-none transition-colors placeholder:font-normal placeholder:text-muted-foreground focus:border-primary focus:ring-2 focus:ring-ring/30 @error("social_links.{$i}.url") border-destructive @enderror">

            @can('deleteSocial', is_object($link) ? $link : null)
              <button type="button"
                class="delete-link-btn inline-flex h-10 w-10 shrink-0 items-center justify-center self-end rounded-xl border border-border text-muted-foreground transition-colors hover:border-destructive hover:text-destructive sm:self-auto"
                data-id="{{ $linkId }}" aria-label="Delete link">
                <i class="fas fa-trash text-xs" aria-hidden="true"></i>
              </button>
            @endcan
          </div>
          @error("social_links.{$i}.url")
            <p class="{{ $errorClass }}">{{ $message }}</p>
          @enderror
          @error("social_links.{$i}.platform")
            <p class="{{ $errorClass }}">{{ $message }}</p>
          @enderror
        @endforeach
      </div>
    </section>

    <div class="sticky bottom-0 z-10 -mx-5 mt-8 flex flex-wrap justify-end gap-3 border-t border-border bg-card/95 px-5 py-4 backdrop-blur sm:-mx-7 sm:px-7 lg:-mx-8 lg:px-8">
      <button id="addCustomBtn" type="button" onclick="addCustomLink()"
        class="inline-flex items-center gap-2 rounded-xl border border-border bg-card px-4 py-2.5 text-xs font-bold uppercase tracking-wide text-foreground transition-colors hover:border-primary hover:text-primary">
        <i class="fas fa-plus" aria-hidden="true"></i>
        Add custom link
      </button>
      <button type="submit"
        class="inline-flex items-center gap-2 rounded-xl bg-primary px-4 py-2.5 text-xs font-bold uppercase tracking-wide text-primary-foreground shadow-sm transition-opacity hover:opacity-90">
        <i class="fas fa-check" aria-hidden="true"></i>
        Save changes
      </button>
    </div>
  </form>

  <form id="delete-avatar-form" action="{{ route('avatar.destroy') }}" method="POST" class="hidden">
    @csrf
    @method('DELETE')
  </form>
  <form id="delete-cover-form" action="{{ route('cover.destroy') }}" method="POST" class="hidden">
    @csrf
    @method('DELETE')
  </form>
</div>

@endsection

@push('scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const phoneInputField = document.querySelector('#phone');

      const phoneInput = window.intlTelInput(phoneInputField, {
        utilsScript: 'https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js',
      });

      fetch('https://get.geojs.io/v1/ip/geo.json')
        .then((response) => response.json())
        .then((data) => phoneInput.setCountry(data.country_code))
        .catch((error) => console.error('Error fetching location:', error));

      const form = document.querySelector('#phone-form');
      form.addEventListener('submit', function (eo) {
        eo.preventDefault();

        const countryCode = phoneInput.getSelectedCountryData().dialCode;
        const phoneNumber = phoneInput.getNumber();

        const countryCodeInput = document.createElement('input');
        countryCodeInput.type = 'hidden';
        countryCodeInput.name = 'country_code';
        countryCodeInput.value = countryCode;
        form.appendChild(countryCodeInput);

        phoneInputField.value = phoneNumber;

        if (window.tinymce) {
          window.tinymce.triggerSave();
        }

        form.submit();
      });
    });

    function addCustomLink() {
      const container = document.getElementById('custom-links');
      const index = container.children.length;
      const div = document.createElement('div');
      div.classList.add('flex', 'flex-col', 'gap-2', 'sm:flex-row');
      div.innerHTML = `
        <input type="text" name="social_links[${index}][platform]" placeholder="Platform" class="w-full rounded-xl border border-border bg-background px-3 py-2.5 text-sm font-medium text-foreground outline-none transition-colors placeholder:font-normal placeholder:text-muted-foreground focus:border-primary focus:ring-2 focus:ring-ring/30 sm:w-48">
        <input type="url" name="social_links[${index}][url]" placeholder="https://example.com" class="w-full flex-1 rounded-xl border border-border bg-background px-3 py-2.5 text-sm font-medium text-foreground outline-none transition-colors placeholder:font-normal placeholder:text-muted-foreground focus:border-primary focus:ring-2 focus:ring-ring/30">
        <button type="button" class="inline-flex h-10 w-10 shrink-0 items-center justify-center self-end rounded-xl border border-border text-muted-foreground transition-colors hover:border-destructive hover:text-destructive sm:self-auto" aria-label="Delete link">
          <i class="fas fa-trash text-xs"></i>
        </button>
      `;
      div.querySelector('button').addEventListener('click', () => div.remove());
      container.appendChild(div);
    }

    document.addEventListener('DOMContentLoaded', function () {
      document.querySelectorAll('.delete-link-btn').forEach((button) => {
        button.addEventListener('click', function () {
          const linkId = this.dataset.id;
          const row = this.closest('[data-link-id]');

          if (!confirm('Are you sure you want to delete this link?')) return;

          fetch('{{ route('destroy.customlink', ['id' => '__ID__']) }}'.replace('__ID__', linkId), {
            method: 'DELETE',
            headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}',
              'Content-Type': 'application/json',
              'Accept': 'application/json',
            },
          })
            .then((res) => {
              if (res.ok) {
                row.remove();
                toastr.success('Link deleted successfully');
              } else {
                toastr.error('Failed to delete link');
              }
            })
            .catch((err) => {
              console.error(err);
              toastr.error('Error deleting link');
            });
        });
      });
    });
  </script>
@endpush
