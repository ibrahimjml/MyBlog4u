<div id="editAdModal" class="hidden fixed inset-0 z-30 bg-black bg-opacity-50 flex items-center justify-center p-4">

  <!-- Modal Content -->
  <div class="bg-white rounded-lg shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col">
    <!-- Modal Header -->
    <div class="flex justify-between items-center p-4 border-b border-gray-200">
      <h2 class="text-xl font-bold text-gray-800">Edit Ad</h2>
      <button id="closeEditAdModal" type="button" class="text-gray-400 hover:text-gray-600 transition-colors">
        <i class="fas fa-times fa-lg"></i>
      </button>
    </div>
    <!-- Modal Body -->
    <div class="p-6 overflow-y-auto">
      <form id="editAd" action="" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')
        <!-- ad name -->
        <div>
          <label for="edit_ad_name" class="block text-sm font-medium text-gray-700 mb-1">Ad Name:</label>
          <input id="edit_ad_name" type="text" name="ad_name" placeholder="Enter ad name"
            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
          <p data-error-for="ad_name" class="hidden text-red-500 text-xs italic mt-2"></p>
        </div>
        <div class="flex justify-between items-center gap-4">
          <!-- ad position -->
          <div>
          <label for="edit_ad_position" class="block text-sm font-medium text-gray-700 mb-1">Ad Position:</label>
          <select id="edit_ad_position" name="ad_position" class="pl-3 pr-8 appearance-none font-bold cursor-pointer bg-blueGray-200 text-blueGray-500 border-0 text-sm rounded-lg p-2.5">
            @foreach(\App\Enums\Adplacements\AdPosition::cases() as $position)
              <option value="{{ $position->value }}">{{ $position->label() }}</option>
            @endforeach
          </select>
          <p data-error-for="ad_position" class="hidden text-red-500 text-xs italic mt-2"></p>
        </div>
        <!-- ad type -->
        <div>
          <label for="editAdType" class="block text-sm font-medium text-gray-700 mb-1">Ad Type:</label>
          <select id="editAdType" name="ad_type" class="pl-3 pr-8 appearance-none font-bold cursor-pointer bg-blueGray-200 text-blueGray-500 border-0 text-sm rounded-lg p-2.5">
            @foreach(\App\Enums\Adplacements\AdType::cases() as $type)
              <option value="{{ $type->value }}">{{ $type->label() }}</option>
            @endforeach
          </select>
          <p data-error-for="ad_type" class="hidden text-red-500 text-xs italic mt-2"></p>
        </div>
        </div>
        <!-- ad dimension -->
         <div>
          <label for="edit_ad_dimension" class="block text-sm font-medium text-gray-700 mb-1">Ad Dimension:</label>
          <input id="edit_ad_dimension" type="text" name="ad_dimension" placeholder="Enter ad dimension"
            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
          <p data-error-for="ad_dimension" class="hidden text-red-500 text-xs italic mt-2"></p>
         </div>
         <!--  show ad code when ad type is HTML /code -->
         <div id="editShowCode" class="hidden">
          <label for="edit_ad_code" class="block text-sm font-medium text-gray-700 mb-1">Ad Code / script:</label>
          <textarea id="edit_ad_code" name="ad_code" rows="4" placeholder="<script>...</script>"
            class="block w-full rounded-md border-gray-300 placeholder:text-green-500 bg-black/80 text-green-500 shadow-sm focus:border-green-500 focus:ring-green-500 p-3 sm:text-sm "></textarea>
          <p data-error-for="ad_code" class="hidden text-red-500 text-xs italic mt-2"></p>
         </div>
         <!-- show image and link target  when ad type is Custom banner image -->
         <div id="editShowCustomBanner" class="hidden space-y-4">
           <div>
            <label for="edit_image" class="block text-sm font-medium text-gray-700 mb-1">Upload Image (optional):</label>
            <input id="removeAdImage" type="hidden" name="remove_image" value="0">
            <input id="edit_image" type="file" name="image" accept="image/*"
              class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            <div id="currentAdImage" class="hidden relative mt-3 p-2 bg-blueGray-100 rounded-md">
              <p class="text-xs font-semibold text-blueGray-500 mb-2">Current image:</p>
              <img src="" alt="Current ad image" class="w-28 h-16 object-cover rounded">
              <button id="removeAdImageButton" type="button" class="text-red-500 rounded-full p-2 absolute top-0 right-0 hover:text-red-300">
                <i class="fas fa-trash"></i>
              </button>
            </div>
            <p data-error-for="image" class="hidden text-red-500 text-xs italic mt-2"></p>
         </div>
         <div>
          <label for="edit_link_url" class="block text-sm font-medium text-gray-700 mb-1">Destination URL (optional):</label>
          <input id="edit_link_url" type="url" name="link_url" placeholder="https://example.com"
            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
          <p data-error-for="link_url" class="hidden text-red-500 text-xs italic mt-2"></p>
         </div>
         </div>
         <!-- ad status -->
         <div class="p-2 rounded-lg border border-gray-300 w-fit flex items-center gap-4">
           <label>Status:</label>
           <input type="hidden" name="status" value="disabled">
           <x-toggle name="status" value="active"/>
           <p data-error-for="status" class="hidden text-red-500 text-xs italic mt-2"></p>

         </div>
        <!-- Modal Footer -->
        <div class="flex justify-end items-center pt-4">
          <button id="submitEditAd" type="submit"
            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            Update
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  const editAdButtons = document.querySelectorAll('.adEdit');
  const editAdModal = document.getElementById('editAdModal');
  const closeEditAdModal = document.getElementById('closeEditAdModal');
  const editAdForm = document.getElementById('editAd');
  const submitEditAd = document.getElementById('submitEditAd');
  const editAdTypeSelect = document.getElementById('editAdType');
  const editShowCode = document.getElementById('editShowCode');
  const editShowCustomBanner = document.getElementById('editShowCustomBanner');
  const currentAdImage = document.getElementById('currentAdImage');
  const currentAdImagePreview = currentAdImage?.querySelector('img');
  const removeAdImage = document.getElementById('removeAdImage');
  const removeAdImageButton = document.getElementById('removeAdImageButton');
  if (!editAdForm || !editAdModal) return;

  const GOOGLE_ADSENSE = "{{ \App\Enums\Adplacements\AdType::GOOGLE_ADSENSE->value }}";
  const CUSTOM_BANNER = "{{ \App\Enums\Adplacements\AdType::CUSTOM_BANNER->value }}";
  const ACTIVE_STATUS = "{{ \App\Enums\Adplacements\AdStatus::ACTIVE->value }}";

  function toggleEditAdFields() {
    const selectedType = editAdTypeSelect.value;

    if (selectedType === GOOGLE_ADSENSE) {
      editShowCode.classList.remove('hidden');
      editShowCustomBanner.classList.add('hidden');
    } else if (selectedType === CUSTOM_BANNER) {
      editShowCustomBanner.classList.remove('hidden');
      editShowCode.classList.add('hidden');
    } else {
      editShowCode.classList.add('hidden');
      editShowCustomBanner.classList.add('hidden');
    }
  }

  function clearEditAdErrors() {
    editAdForm.querySelectorAll('[data-error-for]').forEach((element) => {
      element.textContent = '';
      element.classList.add('hidden');
    });
  }

  function showEditAdErrors(errors) {
    Object.entries(errors).forEach(([field, messages]) => {
      const errorElement = editAdForm.querySelector(`[data-error-for="${field}"]`);

      if (errorElement) {
        errorElement.textContent = messages[0];
        errorElement.classList.remove('hidden');
      }
    });
  }

  function setCurrentImage(imageUrl) {
    if (!currentAdImage || !currentAdImagePreview) return;

    if (imageUrl) {
      currentAdImagePreview.src = imageUrl;
      if (removeAdImage) removeAdImage.value = '0';
      currentAdImage.classList.remove('hidden');
      return;
    }

    currentAdImagePreview.src = '';
    if (removeAdImage) removeAdImage.value = '0';
    currentAdImage.classList.add('hidden');
  }

  editAdTypeSelect.addEventListener('change', toggleEditAdFields);

  removeAdImageButton?.addEventListener('click', () => {
    if (removeAdImage) removeAdImage.value = '1';
    if (currentAdImagePreview) currentAdImagePreview.src = '';
    currentAdImage?.classList.add('hidden');
  });

  editAdButtons.forEach((button) => {
    button.addEventListener('click', () => {
      clearEditAdErrors();
      editAdForm.reset();

      const ad = JSON.parse(button.dataset.ads);
      editAdForm.action = button.dataset.updateUrl;

      editAdForm.querySelector('[name="ad_name"]').value = ad.ad_name ?? '';
      editAdForm.querySelector('[name="ad_position"]').value = ad.ad_position ?? '';
      editAdForm.querySelector('[name="ad_type"]').value = ad.ad_type ?? '';
      editAdForm.querySelector('[name="ad_dimension"]').value = ad.ad_dimension ?? '';
      editAdForm.querySelector('[name="ad_code"]').value = ad.ad_code ?? '';
      editAdForm.querySelector('[name="link_url"]').value = ad.link_url ?? '';
      editAdForm.querySelector('input[type="checkbox"][name="status"]').checked = ad.status === ACTIVE_STATUS;

      setCurrentImage(ad.image_url);
      toggleEditAdFields();
      editAdModal.classList.remove('hidden');
    });
  });

  closeEditAdModal?.addEventListener('click', () => {
    editAdModal.classList.add('hidden');
  });

  editAdForm.addEventListener('submit', async (event) => {
    event.preventDefault();
    clearEditAdErrors();
    submitEditAd.disabled = true;
    submitEditAd.textContent = 'Updating...';

    try {
      const formData = new FormData(editAdForm);
      formData.append('_method', 'PUT');

      const response = await fetch(editAdForm.action, {
        method: 'POST',
        headers: {
          'Accept': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: formData,
      });

      const data = await response.json();

      if (!response.ok) {
        if (response.status === 422 && data.errors) {
          showEditAdErrors(data.errors);
          return;
        }

        throw new Error(data.message || 'Unable to update ad.');
      }

      if (window.toastr) {
        toastr.success(data.message);
      }

      editAdModal.classList.add('hidden');
      window.location.reload();
    } catch (error) {
      if (window.toastr) {
        toastr.error(error.message);
      } else {
        alert(error.message);
      }
    } finally {
      submitEditAd.disabled = false;
      submitEditAd.textContent = 'Update';
    }
  });
});
</script>
@endpush
