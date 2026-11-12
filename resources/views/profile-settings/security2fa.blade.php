@extends('layouts.setting')

@php
  $user = auth()->user();
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
<div class="space-y-5">
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

  @if(!$user->has_two_factor_enabled)
    <div class="rounded-2xl border border-dashed border-border bg-background p-5 sm:p-6">
      <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
          <h3 class="font-display text-base font-semibold text-foreground">Secure your sign-ins</h3>
          <p class="mt-1 max-w-xl text-sm leading-6 text-muted-foreground">After enabling two-factor authentication, you will enter a time-based code from an authenticator app when you sign in.</p>
        </div>
        <button id="open2fa" class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-primary px-4 py-2.5 text-xs font-bold uppercase tracking-wide text-primary-foreground shadow-sm transition-opacity hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-ring/40">
          <i class="fas fa-user-shield"></i>
          <small>Enable two-factor</small>
        </button>
      </div>
    </div>
    <div id="2faContainer"></div><!-- 2fa model -->
    @include('profile-settings.partials.two-factor-confirmation')
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
            <button onclick="window.location.href='{{ route('download.recovery.codes') }}';setTimeout(()=>{window.location.reload();},2000)" class="inline-flex items-center gap-2 rounded-xl bg-primary px-4 py-2.5 text-xs font-bold uppercase tracking-wide text-primary-foreground transition-opacity hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-ring/40">
              <i class="fas fa-download"></i> Download codes
            </button>
          @endunless
          <button onclick="regenerate(this)" class="inline-flex items-center gap-2 rounded-xl border border-amber-200 bg-amber-50 px-4 py-2.5 text-xs font-bold uppercase tracking-wide text-amber-700 transition-colors hover:bg-amber-100 focus:outline-none focus:ring-2 focus:ring-amber-300">
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
        <button onclick="disable2fa(this)" class="mt-5 inline-flex items-center gap-2 rounded-xl bg-destructive px-4 py-2.5 text-xs font-bold uppercase tracking-wide text-destructive-foreground transition-opacity hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-destructive/40">
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
  @endif
</div>
@endsection

@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      let faModal = null;
      const open2faBtn = document.getElementById('open2fa');
      const header = document.getElementById('header');

      const originalOpen2faHtml = open2faBtn?.innerHTML;

      try {
        open2faBtn?.addEventListener('click', async () => {
          if (open2faBtn) {
            open2faBtn.innerHTML = `<i class="fas fa-spinner fa-spin"></i>`;
            open2faBtn.disabled = true;
          }
          header.classList.remove('sticky','top-0');
          const res = await fetch('{{ route('enable.2fa') }}', {
            method: 'POST',
            headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}',
              'X-Requested-With': 'XMLHttpRequest',
              'Accept': 'application/json'
            }
          });
          const contentType = res.headers.get('content-type');

          if (contentType && contentType.includes('application/json')) {

            const data = await res.json();

            if (data.demo_mode) {
              toastr.error(data.message);
              return;
            }

          } else {

            const html = await res.text();

            document.getElementById('2faContainer').innerHTML = html;

            faModal = document.getElementById('2faModel');

            if (!faModal) return;

            faModal.classList.remove('hidden');

            // Setup event listeners after modal is shown
            setTimeout(() => {
              const continueBtn = faModal.querySelector('#continue2fa');
              if (continueBtn) {
                continueBtn.addEventListener('click', () => {
                  faModal.classList.add('hidden'); // hide 2FA modal

                  const confirmModal = document.getElementById('confirmationModel');
                  if (confirmModal){
                    confirmModal.classList.remove('hidden'); // show confirmation modal
                  } 
                });
              }

              closeTwoFaModal();

              // copy button
              const copyDiv = faModal.querySelector('#secret + div');
              if (copyDiv) {
                copyDiv.addEventListener('click', () => {
                  const secret = faModal.querySelector('#secret').innerText;
                  navigator.clipboard.writeText(secret).then(() => {
                    toastr.options = {
                      closeButton: true,
                      progressBar: true,
                      positionClass: "toast-bottom-right",
                      timeOut: 2000
                    };
                    toastr.success("Copied To Clipboard");
                  });
                });
              }
            }, 50);
          }
        });
      } catch (error) {
        alert(error.message);
      } finally {
        if (open2faBtn) {
          open2faBtn.disabled = false;
          open2faBtn.innerHTML = originalOpen2faHtml || `
              <i class="fas fa-user-shield text-xs"></i>
              <small>Enable Two Factor</small>`;
        }
      }


      closeConfirmationModal();


      function closeTwoFaModal() {
        if (!faModal) return;
        const closeModelBtn = faModal.querySelector('#closeModel');
        if (closeModelBtn) {
          closeModelBtn.addEventListener('click', () => {
            header.classList.add('sticky','top-0');
            faModal.classList.add('hidden');
            if (open2faBtn) {
              open2faBtn.disabled = false;
              open2faBtn.innerHTML = originalOpen2faHtml || `
           <i class="fas fa-user-shield text-xs"></i>
           <small>Enable Two Factor</small>`;
            }

          });
        }
      }
      function closeConfirmationModal() {
        const closeConfirmBtn = document.getElementById('closeConfirmation');
        closeConfirmBtn?.addEventListener('click', () => {
          const confirmModal = document.getElementById('confirmationModel');
          header.classList.add('sticky','top-0');
          if (confirmModal) confirmModal.classList.add('hidden');
          if (open2faBtn) {
            open2faBtn.disabled = false;
            open2faBtn.innerHTML = originalOpen2faHtml || `
           <i class="fas fa-user-shield text-xs"></i>
           <small>Enable Two Factor</small>`;
          }
        });
      }
    });
  </script>
  <script>
    // regenerate codes
    async function regenerate(eo) {
      const res = await fetch('{{ route('regenerate.recovery.codes') }}', {
        method: 'PUT',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        }
      });
      const data = await res.json();
      if (res.ok) {
        if (data.success) {
          toastr.success(data.message);
          setTimeout(() => window.location.reload(), 1200);
        }
      } else {
        toastr.error('something went wrong');
      }

    }
  </script>
  <script>
    // disable 2fa
    async function disable2fa(eo) {
      const res = await fetch('{{ route('disable.2fa') }}', {
        method: 'PUT',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        }
      });
      const data = await res.json();
      if (res.ok) {
        if (data.success) {
          toastr.success(data.message);
          setTimeout(() => window.location.reload(), 1200);
        }
      } else {
        toastr.error('something went wrong');
      }

    }
  </script>
@endpush

@if($user->has_two_factor_enabled)
  @push('scripts')
    <script>
      document.addEventListener('DOMContentLoaded', () => {
        const list = document.getElementById('trustedDevicesList');
        const removeAllButton = document.getElementById('removeAllTrustedDevices');

        if (!list || !removeAllButton) return;

        const endpoints = {
          index: '{{ route('trusted-devices.index') }}',
          destroyAll: '{{ route('trusted-devices.destroy-all') }}',
          destroy: '{{ route('trusted-devices.destroy', ['device' => '__DEVICE_ID__']) }}'
        };

        const csrfToken = '{{ csrf_token() }}';
        const requestHeaders = {
          'X-CSRF-TOKEN': csrfToken,
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json'
        };

        const formatDate = (value) => {
          if (!value) return 'Unknown';
          const date = new Date(value);
          return Number.isNaN(date.getTime()) ? 'Unknown' : date.toLocaleString();
        };

        const deviceIcon = (name = '') => /iphone|ipad|android/i.test(name) ? 'fa-mobile-screen-button' : 'fa-desktop';

        const emptyState = () => {
          list.innerHTML = '<div class="px-5 py-8 text-center"><span class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-secondary text-muted-foreground"><i class="fas fa-laptop"></i></span><p class="mt-3 text-sm font-medium text-foreground">No other trusted devices</p><p class="mt-1 text-xs text-muted-foreground">Devices you trust after signing in will appear here.</p></div>';
          removeAllButton.classList.add('hidden');
        };

        const renderDevices = (devices) => {
          list.replaceChildren();

          if (!devices.length) {
            emptyState();
            return;
          }

          removeAllButton.classList.remove('hidden');
          devices.forEach((device) => {
            const row = document.createElement('div');
            row.className = 'flex flex-col gap-3 px-5 py-4 sm:flex-row sm:items-center sm:justify-between';

            const details = document.createElement('div');
            details.className = 'flex min-w-0 items-start gap-3';
            details.innerHTML = `<span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-secondary text-muted-foreground"><i class="fas ${deviceIcon(device.device_name)}"></i></span><div class="min-w-0"><p class="truncate text-sm font-medium text-foreground"></p><p class="hidden mt-0.5 text-xs text-emerald-600">your current device</p><p class="mt-0.5 text-xs text-muted-foreground"></p></div>`;
            details.querySelector('p').textContent = device.device_name || 'Unknown device';
            if(device.isCurrent){
              details.querySelectorAll('p')[1].classList.remove('hidden');
            }            
            details.querySelectorAll('p')[2].textContent = `Last used ${formatDate(device.last_used_at)} - ${device.ip || 'Unknown IP'}`;

            const removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.className = 'self-start rounded-lg border border-border px-3 py-1.5 font-mono text-[10px] font-bold uppercase tracking-wide text-destructive transition-colors hover:border-destructive sm:self-auto';
            removeButton.textContent = 'Remove';
            removeButton.addEventListener('click', () => removeDevice(device.id, removeButton));

            row.append(details, removeButton);
            list.appendChild(row);
          });
        };
        // load devices
        const loadDevices = async () => {
          try {
            const response = await fetch(endpoints.index, { headers: requestHeaders });
            if (!response.ok) throw new Error('Could not load trusted devices.');
            const data = await response.json();
            renderDevices(data.devices || []);
          } catch (error) {
            list.innerHTML = '<div class="px-5 py-5 text-sm text-destructive">Unable to load trusted devices. Please refresh and try again.</div>';
          }
        };
        // remove device
        const removeDevice = async (id, button) => {
          button.disabled = true;
          button.textContent = 'Removing...';

          try {
            const response = await fetch(endpoints.destroy.replace('__DEVICE_ID__', id), {
              method: 'DELETE',
              headers: requestHeaders
            });
            if (!response.ok) throw new Error('Could not remove device.');
            toastr.success('Trusted device removed.');
            loadDevices();
          } catch (error) {
            button.disabled = false;
            button.textContent = 'Remove';
            toastr.error('Unable to remove this device.');
          }
        };
         // remove all devices
        removeAllButton.addEventListener('click', async () => {
          if (!window.confirm('Remove all trusted devices? You will need a two-factor code the next time you sign in on them.')) return;

          removeAllButton.disabled = true;
          removeAllButton.textContent = 'Removing...';

          try {
            const response = await fetch(endpoints.destroyAll, { method: 'DELETE', headers: requestHeaders });
            if (!response.ok) throw new Error('Could not remove devices.');
            toastr.success('All trusted devices removed.');
            emptyState();
          } catch (error) {
            toastr.error('Unable to remove trusted devices.');
          } finally {
            removeAllButton.disabled = false;
            removeAllButton.textContent = 'Remove all';
          }
        });

        loadDevices();
      });
    </script>
  @endpush
@endif
