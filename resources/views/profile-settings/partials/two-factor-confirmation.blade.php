<div id="confirmationModel"
  class="hidden fixed inset-0 z-30 bg-black bg-opacity-50 flex items-center justify-center p-4">

  <!-- Modal Content -->
  <div class="bg-white rounded-lg shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col">
    <!-- Modal Header -->
    <div class="flex justify-between items-center p-4 border-b border-gray-200">
      <i class="text-md rounded-full border-2 border-black/40 px-2 py-2 fas fa-qrcode"></i>
      <h2 class="text-xl font-bold text-gray-800">Confirmation two factor</h2>
      <button id="closeConfirmation" class="text-gray-400 hover:text-gray-600 transition-colors">
        <i class="fas fa-times fa-lg"></i>
      </button>
    </div>

    <!-- Modal Body -->
    <div class="p-6 overflow-y-auto">
      <p>Verify Authentication Code</p>
      <p>Enter the 6-digits code from your authenticator app.</p>
      <form id="confirm2faBtn">
        @csrf
        <input type="hidden" name="code" id="twofa_code">
        <div class="flex gap-2 justify-center mt-3">
          @for ($i = 0; $i < 6; $i++)
            <input type="text" inputmode="numeric" maxlength="1"
              class="twofa-input w-12 h-12 text-center text-xl font-bold border rounded-lg focus:ring-2 focus:ring-black/70 outline-none">
          @endfor
        </div>
      </form>
      <button form="confirm2faBtn" type="submit"
        class="bg-black/60 mt-3 text-white  font-bold uppercase text-xs px-4 py-2 rounded shadow hover:shadow-md outline-none focus:outline-none mr-1 ease-linear transition-all duration-150">
        Confirm
      </button>
      <button id="cancel" onclick="cancelTwoFactor()"
        class="bg-red-500/70 mt-3 text-white  font-bold uppercase text-xs px-4 py-2 rounded shadow hover:shadow-md outline-none focus:outline-none mr-1 ease-linear transition-all duration-150">
        Cancel
      </button>
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
        document.getElementById('confirmationModel').classList.add('hidden');
        window.location.reload();

      } else {
        toastr.error(data.errors?.code || 'Invalid code');
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