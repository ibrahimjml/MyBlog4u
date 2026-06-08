<div id="editLimitModel" class="hidden fixed inset-0 z-30 bg-black bg-opacity-50 flex items-center justify-center p-4">

  <!-- Modal Content -->
  <div class="bg-white rounded-lg shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col">
    <!-- Modal Header -->
    <div class="flex justify-between items-center p-4 border-b border-gray-200">
    <div class="flex-1 justify-start">
        <h2 class="text-xl font-bold text-gray-800">Edit api Limit <b>{{ $limit->route_name }}</b></h2>
        <p class="block text-sm text-gray-400 mt-2">Define how many times a user can hit a specific route. </p>
    </div>
      <button id="closeEditModel" class="text-gray-400 hover:text-gray-600 transition-colors">
        <i class="fas fa-times fa-lg"></i>
      </button>
    </div>
    <!-- Modal Body -->
    <div class="p-6 overflow-y-auto">
      <form id="editLimit" action="" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        <!-- Limit Name -->
        <div>
          <label for="route_name" class="block text-sm font-medium text-gray-700 mb-1">Route Name:</label>
          <input type="text" name="route_name" placeholder="Enter any route name e.g. login, api."
            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
          <p data-error-for="route_name" class="hidden text-red-500 text-xs italic mt-2"></p>
        </div>
        <!-- Limit Method -->
          <div>
          <label for="method" class="block text-sm font-medium text-gray-700 mb-1">Method:</label>
          <select name="method" class="pl-3 pr-8 appearance-none font-bold cursor-pointer bg-blueGray-200 text-blueGray-500 border-0 text-sm rounded-lg p-2.5">
            @foreach(\App\Enums\ApiLimits\ApiLimitMethod::cases() as $method)
              <option value="{{ $method->value }}">{{ $method->label() }}</option>
            @endforeach
          </select>
          <p data-error-for="method" class="hidden text-red-500 text-xs italic mt-2"></p>
        </div>
        <div class="flex justify-between items-center gap-4">
          <!-- Max attempt -->
          <div>
          <label for="max_attempts" class="block text-sm font-medium text-gray-700 mb-1">Max Attempts:</label>
          <input type="number" name="max_attempts" placeholder="Enter max attempts"
            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
          <p data-error-for="max_attempts" class="hidden text-red-500 text-xs italic mt-2"></p>
        </div>

          <p data-error-for="max_attempts" class="hidden text-red-500 text-xs italic mt-2"></p>
          <!-- Time Window (minutes) -->
          <div>
            <label for="time_window" class="block text-sm font-medium text-gray-700 mb-1">Time Window (minutes):</label>
            <input type="number" name="time_window" placeholder="Enter time window"
              class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            <p data-error-for="time_window" class="hidden text-red-500 text-xs italic mt-2"></p>
          </div>
        </div>
        <!-- Limit Description -->
        <div>
          <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description:</label>
          <textarea name="description" rows="3" placeholder="Enter a description for this API limit"
            class="block w-full rounded-md border-gray-300 p-2 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
          <p data-error-for="description" class="hidden text-red-500 text-xs italic mt-2"></p>
        </div>

         <!-- limit status -->
         <div class="p-2 rounded-lg border border-gray-300 w-fit flex items-center gap-4">
           <label for="status">Status:</label>
           <input type="hidden" name="status" value="{{ \App\Enums\ApiLimits\ApiLimitStatus::DISABLED->value }}">
           <x-toggle name="status" value="{{ \App\Enums\ApiLimits\ApiLimitStatus::ACTIVE->value }}" />
           <p data-error-for="status" class="hidden text-red-500 text-xs italic mt-2"></p>

         </div>
        <!-- Modal Footer -->
        <div class="flex justify-end items-center pt-4">
          <button id="submitlimit" type="submit"
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
    window.editLimitInitialized = true;
    const editLimitButtons = document.querySelectorAll('.limitEdit');
    const editModel = document.getElementById('editLimitModel');
    const closeEditLimitModal = document.getElementById('closeEditModel');
    const editForm = document.getElementById('editLimit');
    const submitLimit = document.getElementById('submitlimit');
    const ActiveLimit = "{{ \App\Enums\ApiLimits\ApiLimitStatus::ACTIVE->value }}";

  editLimitButtons.forEach(button => {
    button.addEventListener('click',() => {
      clearEditLimitErrors();
      editForm.reset();

      const limits = JSON.parse(button.dataset.apiLimits);
      editForm.action = button.dataset.updateUrl;
      editForm.querySelector('[name="route_name"]').value = limits.route_name ?? '';
      editForm.querySelector('[name="method"]').value = limits.method ?? '';
      editForm.querySelector('[name="max_attempts"]').value = limits.max_attempts ?? '';
      editForm.querySelector('[name="time_window"]').value = limits.time_window ?? '';
      editForm.querySelector('[name="description"]').value = limits.description ?? '';
      editForm.querySelector('input[type="checkbox"][name="status"]').checked = limits.status === ActiveLimit;

      editModel.classList.remove('hidden');
    })
  })
  editForm.addEventListener('submit', async (event) => {
    event.preventDefault();
    clearEditLimitErrors();
    submitLimit.disabled = true;
    submitLimit.textContent = 'Updating...';

    try {
      const formData = new FormData(editForm);
      formData.append('_method', 'PUT');

      const response = await fetch(editForm.action, {
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
          showEditLimitErrors(data.errors);
          return;
        }

        throw new Error(data.message || 'Unable to update api limit.');
      }

      if (window.toastr) {
        toastr.success(data.message);
      }

      editModel.classList.add('hidden');
      window.location.reload();
    } catch (error) {
      if (window.toastr) {
        toastr.error(error.message);
      } else {
        alert(error.message);
      }
    } finally {
      submitLimit.disabled = false;
      submitLimit.textContent = 'Update';
    }
  });

  closeEditLimitModal?.addEventListener('click', () => {
    editModel.classList.add('hidden');
  });

    function clearEditLimitErrors() {
    editForm.querySelectorAll('[data-error-for]').forEach((element) => {
      element.textContent = '';
      element.classList.add('hidden');
    });
  }

  function showEditLimitErrors(errors) {
    Object.entries(errors).forEach(([field, messages]) => {
      const errorElement = editForm.querySelector(`[data-error-for="${field}"]`);

      if (errorElement) {
        errorElement.textContent = messages[0];
        errorElement.classList.remove('hidden');
      }
    });
  }
  });
</script>
@endpush