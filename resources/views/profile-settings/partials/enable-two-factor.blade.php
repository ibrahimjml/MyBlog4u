<div id="2faModel" class="hidden fixed inset-0 z-30 bg-black bg-opacity-50 flex items-center justify-center p-4">

  <!-- Modal Content -->
  <div class="bg-white rounded-lg shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col">
    <!-- Modal Header -->
    <div class="flex justify-between items-center p-4 border-b border-gray-200">
      <i class="text-md rounded-full border-2 border-black/40 px-2 py-2 fas fa-qrcode"></i>
      <h2 class="text-xl font-bold text-gray-800">Enable two factor authentication</h2>
      <button id="closeModel" class="text-gray-400 hover:text-gray-600 transition-colors">
        <i class="fas fa-times fa-lg"></i>
      </button>
    </div>

    <!-- Modal Body -->
    <div class="p-6 overflow-y-auto">
      <p class="text-sm text-blueGray-400 font-semibold">To finish enabling two-factor authentication, Scan the Qrcode
        or copy the setup key to authenticator app.</p>
      <div class="mt-4 flex flex-col items-center">
        <img src="data:image/png;base64,{{ base64_encode($qr) }}" class="mx-auto mt-4" />
        <button id="continue2fa" class="bg-black/70 mt-3 text-white  font-bold uppercase text-xs px-4 py-2 rounded shadow hover:shadow-md outline-none focus:outline-none mr-1 ease-linear transition-all duration-150">
          <small>Continue</small>
        </button>
        <div class="relative w-full my-4 flex items-center">
          <div class="flex-grow border-t border-gray-300"></div>
          <span class="flex-shrink mx-4 text-gray-500 text-xs capitalize">or, enter the code manually</span>
          <div class="flex-grow border-t border-gray-300"></div>
        </div>
        <div class="w-80 my-4 flex items-center rounded-lg p-2 border-2 border-gray-300">
          <div id="secret" class="flex-grow flex justify-center border-r-2 border-gray-300 px-2 font-mono text-sm select-all">
            {{ $secret }}
          </div>
          <div class="flex-shrink ml-2 cursor-pointer">
            <i class="fas fa-copy text-gray-500 hover:text-gray-700"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

