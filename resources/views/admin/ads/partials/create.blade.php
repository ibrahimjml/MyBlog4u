<div id="AdModel" class="hidden fixed inset-0 z-30 bg-black bg-opacity-50 flex items-center justify-center p-4">

  <!-- Modal Content -->
  <div class="bg-white rounded-lg shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col">
    <!-- Modal Header -->
    <div class="flex justify-between items-center p-4 border-b border-gray-200">
      <h2 class="text-xl font-bold text-gray-800">Create New Ad</h2>
      <button id="closeModel" class="text-gray-400 hover:text-gray-600 transition-colors">
        <i class="fas fa-times fa-lg"></i>
      </button>
    </div>
    <!-- Modal Body -->
    <div class="p-6 overflow-y-auto">
      <form id="addAd" action="{{route('admin.ads.store')}}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        <!-- ad name -->
        <div>
          <label for="ad_name" class="block text-sm font-medium text-gray-700 mb-1">Ad Name:</label>
          <input type="text" name="ad_name" placeholder="Enter ad name"
            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
          <p data-error-for="ad_name" class="hidden text-red-500 text-xs italic mt-2"></p>
        </div>
        <div class="flex justify-between items-center gap-4">
          <!-- ad position -->
          <div>
          <label for="ad_position" class="block text-sm font-medium text-gray-700 mb-1">Ad Position:</label>
          <select name="ad_position" class="pl-3 pr-8 appearance-none font-bold cursor-pointer bg-blueGray-200 text-blueGray-500 border-0 text-sm rounded-lg p-2.5">
            @foreach(\App\Enums\Adplacements\AdPosition::cases() as $position)
              <option value="{{ $position->value }}">{{ $position->label() }}</option>
            @endforeach
          </select>
          <p data-error-for="ad_position" class="hidden text-red-500 text-xs italic mt-2"></p>
        </div>
        <!-- ad type -->
        <div>
          <label for="ad_type" class="block text-sm font-medium text-gray-700 mb-1">Ad Type:</label>
          <select id="adType" name="ad_type" class="pl-3 pr-8 appearance-none font-bold cursor-pointer bg-blueGray-200 text-blueGray-500 border-0 text-sm rounded-lg p-2.5">
            @foreach(\App\Enums\Adplacements\AdType::cases() as $type)
              <option value="{{ $type->value }}">{{ $type->label() }}</option>
            @endforeach
          </select>
          <p data-error-for="ad_type" class="hidden text-red-500 text-xs italic mt-2"></p>
        </div>
        </div>
        <!-- ad dimension -->
         <div>
          <label for="ad_dimension" class="block text-sm font-medium text-gray-700 mb-1">Ad Dimension:</label>
          <input type="text" name="ad_dimension" placeholder="Enter ad dimension"
            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
          <p data-error-for="ad_dimension" class="hidden text-red-500 text-xs italic mt-2"></p>
         </div>
         <!--  show ad code when ad type is HTML /code -->
         <div id="showCode" class="hidden">
          <label for="ad_code" class="block text-sm font-medium text-gray-700 mb-1">Ad Code / script:</label>
          <textarea name="ad_code" rows="4" placeholder="<script>...</script>"
            class="block w-full rounded-md border-gray-300 placeholder:text-green-500 bg-black/80 text-green-500 shadow-sm focus:border-green-500 focus:ring-green-500 p-3 sm:text-sm "></textarea>
          <p data-error-for="ad_code" class="hidden text-red-500 text-xs italic mt-2"></p>
         </div>
         <!-- show image and link target  when ad type is Custom banner image -->
         <div id="showCustomBanner" class="hidden">
           <div>
            <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Upload Image (optional):</label>
            <input type="file" name="image" accept="image/*"
              class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            <p data-error-for="image" class="hidden text-red-500 text-xs italic mt-2"></p>
         </div>
         <div>
          <label for="link_url" class="block text-sm font-medium text-gray-700 mb-1">Destination URL (optional):</label>
          <input type="url" name="link_url" placeholder="https://example.com"
            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
          <p data-error-for="link_url" class="hidden text-red-500 text-xs italic mt-2"></p>
         </div>
         </div>
         <!-- ad status -->
         <div class="p-2 rounded-lg border border-gray-300 w-fit flex items-center gap-4">
           <label for="status">Status:</label>
           <input type="hidden" name="status" value="disabled">
           <x-toggle name="status" value="active" :checked="old('status') === 'active'"/>
           <p data-error-for="status" class="hidden text-red-500 text-xs italic mt-2"></p>

         </div>
        <!-- Modal Footer -->
        <div class="flex justify-end items-center pt-4">
          <button id="submitAd" type="submit"
            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            Create
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script>
  const adTypeSelect = document.getElementById('adType');
  const showCode = document.getElementById('showCode');
  const showCustomBanner = document.getElementById('showCustomBanner');

  const GOOGLE_ADSENSE = "{{ \App\Enums\Adplacements\AdType::GOOGLE_ADSENSE->value }}";
  const CUSTOM_BANNER = "{{ \App\Enums\Adplacements\AdType::CUSTOM_BANNER->value }}";

  function toggleAdFields() {
    const selectedType = adTypeSelect.value;

    if (selectedType === GOOGLE_ADSENSE) {
      showCode.classList.remove('hidden');
      showCustomBanner.classList.add('hidden');
    } else if (selectedType === CUSTOM_BANNER) {
      showCustomBanner.classList.remove('hidden');
      showCode.classList.add('hidden');
    } else {
      showCode.classList.add('hidden');
      showCustomBanner.classList.add('hidden');
    }
  }

  adTypeSelect.addEventListener('change', toggleAdFields);

  toggleAdFields();

  const addAdForm = document.getElementById('addAd');
  const submitAd = document.getElementById('submitAd');

  function clearAdErrors() {
    addAdForm.querySelectorAll('[data-error-for]').forEach((element) => {
      element.textContent = '';
      element.classList.add('hidden');
    });
  }

  function showAdErrors(errors) {
    Object.entries(errors).forEach(([field, messages]) => {
      const errorElement = addAdForm.querySelector(`[data-error-for="${field}"]`);

      if (errorElement) {
        errorElement.textContent = messages[0];
        errorElement.classList.remove('hidden');
      }
    });
  }

  addAdForm.addEventListener('submit', async (event) => {
    event.preventDefault();
    clearAdErrors();
    submitAd.disabled = true;
    submitAd.textContent = 'Creating...';

    try {
      const response = await fetch(addAdForm.action, {
        method: 'POST',
        headers: {
          'Accept': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: new FormData(addAdForm),
      });

      const data = await response.json();

      if (!response.ok) {
        if (response.status === 422 && data.errors) {
          showAdErrors(data.errors);
          return;
        }

        throw new Error(data.message || 'Unable to create ad.');
      }

      if (window.toastr) {
        toastr.success(data.message);
      }

      addAdForm.reset();
      toggleAdFields();
      document.getElementById('AdModel').classList.add('hidden');
      window.location.reload();
    } catch (error) {
      if (window.toastr) {
        toastr.error(error.message);
      } else {
        alert(error.message);
      }
    } finally {
      submitAd.disabled = false;
      submitAd.textContent = 'Create';
    }
  });
</script>
@endpush
