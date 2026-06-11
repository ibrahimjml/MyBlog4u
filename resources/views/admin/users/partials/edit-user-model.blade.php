<div id="editUserModal" class="hidden fixed inset-0 z-30 bg-black bg-opacity-50 flex items-center justify-center p-4">

  <!-- Modal Content -->
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col overflow-hidden">
    <!-- Modal Header -->
    <div class="flex justify-between items-center p-4 border-b border-gray-200">
      <div class="flex-1">
        <h2 class="text-xl font-bold text-gray-800">Edit User</h2>
        <p class="block text-sm text-gray-400 mt-2">Update user details, role and permissions.</p>
      </div>
      <button id="closeEditUserModal" class="text-gray-400 hover:text-gray-600 transition-colors">
        <i class="fas fa-times fa-lg"></i>
      </button>
    </div>

    <!-- Modal Body -->
    <div class="p-6 overflow-y-auto">
      <form id="editUserForm" action="" method="POST" class="phone-form-edit space-y-6">
        @csrf
        @method('PUT')

        <div>
          <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name:</label>
          <input type="text" name="name" value="" class="block w-full rounded-2xl border border-gray-300 bg-gray-50 p-3 text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
          <p data-error-for="name" class="hidden text-red-500 text-xs italic mt-2"></p>
        </div>

        <div>
          <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username:</label>
          <input type="text" name="username" value="" class="block w-full rounded-2xl border border-gray-300 bg-gray-50 p-3 text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
          <p data-error-for="username" class="hidden text-red-500 text-xs italic mt-2"></p>
        </div>

        <div>
          <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email:</label>
          <input type="email" name="email" value="" class="block w-full rounded-2xl border border-gray-300 bg-gray-50 p-3 text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
          <p data-error-for="email" class="hidden text-red-500 text-xs italic mt-2"></p>
        </div>

        <div>
          <label for="age" class="block text-sm font-medium text-gray-700 mb-1">Age:</label>
          <input type="number" name="age" value="" class="block w-full rounded-2xl border border-gray-300 bg-gray-50 p-3 text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
          <p data-error-for="age" class="hidden text-red-500 text-xs italic mt-2"></p>
        </div>

        <div>
          <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone:</label>
          <input id="edit-phone" type="tel" name="phone" value="" class="block w-full rounded-2xl border border-gray-300 bg-gray-50 p-3 text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
          <p data-error-for="phone" class="hidden text-red-500 text-xs italic mt-2"></p>
        </div>

        <div>
          <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password:</label>
          <input type="password" name="password" class="block w-full rounded-2xl border border-gray-300 bg-gray-50 p-3 text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
          <p data-error-for="password" class="hidden text-red-500 text-xs italic mt-2"></p>
        </div>

        <div>
          <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm password:</label>
          <input type="password" name="password_confirmation" class="block w-full rounded-2xl border border-gray-300 bg-gray-50 p-3 text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
          <p data-error-for="password_confirmation" class="hidden text-red-500 text-xs italic mt-2"></p>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Roles:</label>
          <div class="mt-2 space-x-4">
            @foreach ($roles as $role)
              <label class="inline-flex items-center text-gray-600">
                <input type="radio" name="roles" value="{{ $role->name }}" class="mr-1 text-indigo-600 focus:ring-indigo-500">
                {{ ucfirst($role->name) }}
              </label>
            @endforeach
          </div>
          <p data-error-for="roles" class="hidden text-red-500 text-xs italic mt-2"></p>
        </div>

        <div id="edit-permissions-section" class="hidden">
          <label class="block text-sm font-medium text-gray-700">Permissions:</label>
          <div class="mt-2 p-3 border border-gray-200 rounded-2xl max-h-52 overflow-y-auto space-y-4">
            @foreach ($permissions as $module => $modulePermissions)
              <div>
                <h3 class="text-sm font-semibold text-gray-800 mb-2">{{ $module }}</h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                  @foreach ($modulePermissions as $permission)
                    <label class="flex items-center text-sm text-gray-600 font-normal">
                      <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" class="edit-permission-checkbox h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 mr-2">
                      {{ $permission->name }}
                    </label>
                  @endforeach
                </div>
              </div>
            @endforeach
          </div>
          <p data-error-for="permissions" class="hidden text-red-500 text-xs italic mt-2"></p>
        </div>

        <div class="flex justify-end items-center pt-4">
          <button id="submitEditUser" type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-2xl text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            Edit User
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

@push('scripts')

<script>
let phoneInputField;
let phoneInput;

document.addEventListener('DOMContentLoaded', () => {
  phoneInputField = document.querySelector("#edit-phone");
  if (phoneInputField && window.intlTelInput) {
    phoneInput = window.intlTelInput(phoneInputField, {
      utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
    });

    fetch('https://get.geojs.io/v1/ip/geo.json')
      .then(response => response.json())
      .then(data => {
        const country = data.country_code;
        phoneInput.setCountry(country);
      })
      .catch(error => console.error('Error fetching location:', error));
  }

  const editButtons = document.querySelectorAll('.editusers');
  const modal = document.getElementById('editUserModal');
  const closeModal = document.getElementById('closeEditUserModal');
  const editForm = document.getElementById('editUserForm');
  const submitButton = document.getElementById('submitEditUser');
  const permissionSection = document.getElementById('edit-permissions-section');
  const roleRadios = editForm.querySelectorAll('input[name="roles"]');
  const permissionCheckboxes = editForm.querySelectorAll('input[name="permissions[]"]');
  const userRoleValue = '{{ \App\Enums\UserRole::USER->value }}';
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

  function togglePermissionSection(role) {
    if (role === userRoleValue) {
      permissionSection.classList.remove('hidden');
    } else {
      permissionSection.classList.add('hidden');
    }
  }

  roleRadios.forEach(radio => {
    radio.addEventListener('change', (event) => togglePermissionSection(event.target.value));
  });

  function clearErrorMessages() {
    editForm.querySelectorAll('[data-error-for]').forEach(element => {
      element.textContent = '';
      element.classList.add('hidden');
    });
  }

  function showErrors(errors) {
    Object.entries(errors).forEach(([field, messages]) => {
      const errorElement = editForm.querySelector(`[data-error-for="${field}"]`);
      if (errorElement) {
        errorElement.textContent = messages[0];
        errorElement.classList.remove('hidden');
      }
    });
  }

  editButtons.forEach(button => {
    button.addEventListener('click', () => {
      const user = JSON.parse(button.dataset.user);
      editForm.action = button.dataset.updateUrl;
      editForm.querySelector('[name="name"]').value = user.name || '';
      editForm.querySelector('[name="username"]').value = user.username || '';
      editForm.querySelector('[name="email"]').value = user.email || '';
      editForm.querySelector('[name="age"]').value = user.age || '';
      editForm.querySelector('[name="phone"]').value = user.phone || '';
      editForm.querySelector('[name="password"]').value = '';
      editForm.querySelector('[name="password_confirmation"]').value = '';

      roleRadios.forEach(radio => {
        radio.checked = radio.value === user.selectedRole;
      });

      permissionCheckboxes.forEach(checkbox => {
        checkbox.checked = Array.isArray(user.permissions) && user.permissions.includes(Number(checkbox.value));
      });

      togglePermissionSection(user.selectedRole);
      clearErrorMessages();
      modal.classList.remove('hidden');
    });
  });

  closeModal?.addEventListener('click', () => modal.classList.add('hidden'));

  editForm.addEventListener('submit', async (event) => {
    event.preventDefault();
    clearErrorMessages();
    submitButton.disabled = true;
    submitButton.textContent = 'Updating...';

    if (phoneInputField && phoneInput) {
      const countryCode = phoneInput.getSelectedCountryData().dialCode;
      const phoneNumber = phoneInput.getNumber();
      phoneInputField.value = phoneNumber;

      const countryCodeInput = document.createElement('input');
            countryCodeInput.type = 'hidden';
            countryCodeInput.name = 'country_code';
            countryCodeInput.value = countryCode;
        editForm.appendChild(countryCodeInput);
      
      countryCodeInput.value = countryCode;
    }

    const formData = new FormData(editForm);
    const payload = {
      name: formData.get('name'),
      username: formData.get('username'),
      email: formData.get('email'),
      age: formData.get('age'),
      phone: formData.get('phone'),
      password: formData.get('password'),
      password_confirmation: formData.get('password_confirmation'),
      roles: formData.get('roles'),
      permissions: formData.getAll('permissions[]'),
    };

    try {
      const response = await fetch(editForm.action, {
        method: 'PUT',
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken || '',
        },
        body: JSON.stringify(payload),
      });

      const data = await response.json();

      if (!response.ok) {
        if (response.status === 422 && data.errors) {
          showErrors(data.errors);
          return;
        }

        throw new Error(data.message || 'Unable to update user.');
      }

      if (window.toastr) {
        toastr.success(data.message || 'User updated successfully');
      }

      modal.classList.add('hidden');
      window.location.reload();
    } catch (error) {
      if (window.toastr) {
        toastr.error(error.message);
      } else {
        alert(error.message);
      }
    } finally {
      submitButton.disabled = false;
      submitButton.textContent = 'Edit User';
    }
  });
});
</script>

@endpush