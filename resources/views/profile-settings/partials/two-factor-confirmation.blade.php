<div id="confirmationModel"
  class="fixed inset-0 z-30 flex hidden items-center justify-center bg-foreground/40 p-4 backdrop-blur-sm">
  <div class="flex max-h-[90vh] w-full max-w-2xl flex-col overflow-hidden rounded-2xl border border-border bg-card shadow-2xl">
    <div class="flex items-center justify-between gap-4 border-b border-border px-5 py-4">
      <div class="flex items-center gap-3">
        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-accent text-accent-foreground">
          <i class="fas fa-qrcode"></i>
        </span>
        <h2 class="font-display text-base font-semibold text-foreground">Confirm two-factor authentication</h2>
      </div>
      <button id="closeConfirmation" type="button" aria-label="Close"
        class="flex h-9 w-9 items-center justify-center rounded-xl text-muted-foreground transition-colors hover:bg-secondary hover:text-foreground">
        <i class="fas fa-times"></i>
      </button>
    </div>

    <div class="overflow-y-auto p-5 sm:p-6">
      <p class="font-display text-base font-semibold text-foreground">Verify authentication code</p>
      <p class="mt-1 text-sm text-muted-foreground">Enter the 6-digit code from your authenticator app.</p>
      <form id="confirm2faBtn">
        @csrf
        <input type="hidden" name="code" id="twofa_code">
        <div class="mt-5 flex justify-center gap-2">
          @for ($i = 0; $i < 6; $i++)
            <input type="text" inputmode="numeric" maxlength="1"
              class="twofa-input h-12 w-12 rounded-xl border border-border bg-background text-center font-mono text-xl font-bold text-foreground outline-none transition-colors focus:border-primary focus:ring-2 focus:ring-ring/30">
          @endfor
        </div>
      </form>
      <div class="mt-5 flex flex-wrap gap-3">
        <button form="confirm2faBtn" type="submit"
          class="inline-flex items-center gap-2 rounded-xl bg-primary px-4 py-2.5 text-xs font-bold uppercase tracking-wide text-primary-foreground shadow-sm transition-opacity hover:opacity-90">
          <i class="fas fa-check"></i>
          Confirm
        </button>
        <button id="cancel" type="button" onclick="cancelTwoFactor()"
          class="inline-flex items-center gap-2 rounded-xl border border-destructive/30 bg-destructive/10 px-4 py-2.5 text-xs font-bold uppercase tracking-wide text-destructive transition-colors hover:bg-destructive/15">
          <i class="fas fa-times"></i>
          Cancel
        </button>
      </div>
    </div>
  </div>
</div>
@push('scripts')
  <script>
    const inputs = document.querySelectorAll('.twofa-input');
    const hidden = document.getElementById('twofa_code');

    inputs.forEach((input, index) => {
      input.addEventListener('input', () => {
        input.value = input.value.replace(/\D/, '');

        if (input.value && inputs[index + 1]) {
          inputs[index + 1].focus();
        }

        updateCode();
      });

      input.addEventListener('keydown', e => {
        if (e.key === 'Backspace' && !input.value && inputs[index - 1]) {
          inputs[index - 1].focus();
        }
      });
    });

    document.getElementById('confirm2faBtn').addEventListener('paste', e => {
      const paste = e.clipboardData.getData('text').replace(/\D/g, '').slice(0, 6);
      paste.split('').forEach((char, i) => {
        if (inputs[i]) inputs[i].value = char;
      });
      updateCode();
    });

    function updateCode() {
      hidden.value = [...inputs].map(i => i.value).join('');
    }
  </script>

  <script>
    document.querySelector('#confirm2faBtn')?.addEventListener('submit', async (eo) => {
      eo.preventDefault();
      const code = document.querySelector('#confirmationModel input[name="code"]').value;
      const inputs = document.querySelectorAll('.twofa-input');
      
      const res = await fetch('{{ route('confirm.2fa') }}', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: JSON.stringify({ code })
      });

      const data = await res.json();

      if (res.ok) {
        toastr.success('Two factor enabled successfully!');
        inputs.forEach((input, index) => {
          input.value = ''
        });
        document.getElementById('confirmationModel').classList.add('hidden');
        setTimeout(() => window.location.reload(), 1200);

      } else {
        inputs.forEach((input, index) => {
          input.value = ''
        });
        toastr.error(data.errors?.code || data.message || 'Invalid code');
      }
    });
  </script>
  <script>
    function cancelTwoFactor() {

      const confirmModal = document.getElementById('confirmationModel');
      const open2faBtn = document.getElementById('open2fa');

      if (confirmModal) confirmModal.classList.add('hidden');
      if (open2faBtn) {
        open2faBtn.disabled = false;
        open2faBtn.innerHTML =  `
               <i class="fas fa-user-shield text-xs"></i>
               <small>Enable Two Factor</small>`;
      }


    }
  </script>
@endpush