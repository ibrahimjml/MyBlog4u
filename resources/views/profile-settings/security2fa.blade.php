@php
  $user = auth()->user();
@endphp
<main class=" relative lg:w-4/6 w-full  lg:mx-auto mx-0 mt-5">
  <p class="text-blueGray-400 text-lg font-bold">Two Factor Authentication</p>
  <p class="text-blueGray-300 text-xs mb-4">Manage your two factor authentication setting</p>
  <span
    class="{{ $user->has_two_factor_enabled ? 'bg-green-500' : 'bg-red-500'}} text-white  font-bold uppercase text-xs px-4 py-2 rounded">
    <i class="fas {{ $user->has_two_factor_enabled ? 'fa-check' : 'fa-times'}} text-xs mr-1"></i>
    {{ $user->has_two_factor_enabled ? 'Enabled' : 'Disabled'}}
  </span>
  <p class="text-blueGray-400 text-xs mt-4">When you enable two factor authentication, you will be prompted for a secure
    pin during login, this pin can be retreived from TOTP-supported application on your phone.</p>
  @if(!$user->has_two_factor_enabled)
    <button id="open2fa"
      class="bg-black/70 mt-3 text-white  font-bold uppercase text-xs px-4 py-2 rounded shadow hover:shadow-md outline-none focus:outline-none mr-1 ease-linear transition-all duration-150">
      <i class="fas fa-user-shield text-xs"></i>
      <small>Enable Two Factor</small>
    </button>
    <div id="2faContainer"></div><!-- 2fa model -->
    @include('profile-settings.partials.two-factor-confirmation')
  @else
    <div class="mt-2 w-96 border-2 border-gray-300 rounded-md p-2">
      <span class="text-blueGray-400 text-md font-semibold mb-1"><i class="fas fa-lock mr-1 text-sm"></i>Two Factor
        Codes</span>
      <p class="text-gray-500/50 text-xs mb-1">Recovery codes let you regain access if you lose 2fa device.Store them
        carefully.</p>
      <div class="flex justify-between items-center">
        @unless($user->recovery_codes_downloaded)
          <button
            onclick="window.location.href='{{ route('download.recovery.codes') }}';setTimeout(()=>{window.location.reload();},2000)"
            class="bg-blue-600/80 mt-3 text-white  font-bold uppercase text-xs px-4 py-2 rounded shadow hover:shadow-md outline-none focus:outline-none mr-1 ease-linear transition-all duration-150">
            <i class="fas fa-download text-xs mr-1"></i>
            Download Codes
          </button>
        @endunless
        <button onclick="regenerate(this)"
          class="bg-yellow-600/80 mt-3 text-white  font-bold uppercase text-xs px-4 py-2 rounded shadow hover:shadow-md outline-none focus:outline-none mr-1 ease-linear transition-all duration-150">
          <i class="fas fa-rotate text-xs mr-1"></i>
          Regenrate Codes
        </button>
      </div>
    </div>
    <button onclick="disable2fa(this)"
      class="bg-red-600/80 mt-3 text-white  font-bold uppercase text-xs px-4 py-2 rounded shadow hover:shadow-md outline-none focus:outline-none mr-1 ease-linear transition-all duration-150">
      <i class="fas fa-user-shield text-xs"></i>
      <small>Disable Two Factor</small>
    </button>
  @endif
</main>

@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      let faModal = null;
      const open2faBtn = document.getElementById('open2fa');
      const originalOpen2faHtml = open2faBtn?.innerHTML;

      try {
        open2faBtn?.addEventListener('click', async () => {
          if (open2faBtn) {
            open2faBtn.innerHTML = `<i class="fas fa-spinner fa-spin"></i>`;
            open2faBtn.disabled = true;
          }

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
                  if (confirmModal) confirmModal.classList.remove('hidden'); // show confirmation modal
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
        const closeModelBtn = faModal.querySelector('#closeModel');
        if (closeModelBtn) {
          closeModelBtn.addEventListener('click', () => {
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
          window.location.reload();
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
          window.location.reload();
        }
      } else {
        toastr.error('something went wrong');
      }

    }
  </script>
@endpush