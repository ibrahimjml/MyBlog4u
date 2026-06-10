<!-- Modal Backdrop -->
<div id="Model" class="hidden fixed inset-0 z-30 bg-black bg-opacity-50 flex items-center justify-center p-4">

  <!-- Modal Content -->
  <div class="bg-white rounded-lg shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col">
    <!-- Modal Header -->
    <div class="flex justify-between items-center p-4 border-b border-gray-200">
      <h2 class="text-xl font-bold text-gray-800">Create New User</h2>
      <button id="closeModel" class="text-gray-400 hover:text-gray-600 transition-colors">
        <i class="fas fa-times fa-lg"></i>
      </button>
    </div>

    <!-- Modal Body -->
    <div class="p-6 overflow-y-auto">
        @if($errors->any())
        <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded text-sm text-red-700">
          <ul class="list-disc ml-5">
            @foreach($errors->all() as $err)
              <li>{{ $err }}</li>
            @endforeach
          </ul>
        </div>
      @endif
      <form id="phone-form" action="{{route('admin.users.create')}}" method="POST" class="space-y-6">
        @csrf
        @method("POST")

        <!-- Form Fields -->
        <div>
          <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name:</label>
          <input type="text" name="name" class="block w-full rounded-2xl border border-gray-300 bg-gray-50 p-3 text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
          @error('name')<p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>@enderror
        </div>

        <div>
          <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username:</label>
          <input type="text" name="username" class="block w-full rounded-2xl border border-gray-300 bg-gray-50 p-3 text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
          @error('username')<p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>@enderror
        </div>

        <div>
          <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email:</label>
          <input type="email" name="email" class="block w-full rounded-2xl border border-gray-300 bg-gray-50 p-3 text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
          @error('email')<p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>@enderror
        </div>

        <div>
          <label for="age" class="block text-sm font-medium text-gray-700 mb-1">Age:</label>
          <input type="number" name="age" class="block w-full rounded-2xl border border-gray-300 bg-gray-50 p-3 text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
          @error('age')<p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>@enderror
        </div>

        <div>
          <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone:</label>
          <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" autocomplete="tel" class="block w-full rounded-2xl border border-gray-300 bg-gray-50 p-3 text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
          @error('phone')<p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>@enderror
        </div>

        <div>
          <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password:</label>
          <input type="password" name="password" class="block w-full rounded-2xl border border-gray-300 bg-gray-50 p-3 text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
          @error('password')<p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>@enderror
        </div>

        <div>
          <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password:</label>
          <input type="password" name="password_confirmation" class="block w-full rounded-2xl border border-gray-300 bg-gray-50 p-3 text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
        </div>

        <!-- Roles -->
        <div>
          <label class="block text-sm font-medium text-gray-700">Roles:</label>
          <div class="mt-2 space-x-4">
            @foreach (\App\Enums\UserRole::cases() as $role)
              <label class="inline-flex items-center text-gray-600">
                <input type="radio" name="roles" value="{{ $role->value }}" class="mr-1 text-indigo-600 focus:ring-indigo-500">
                {{ $role->value }}
              </label>
            @endforeach
          </div>
        </div>

        <!-- Permissions -->
        <div>
          <label class="block text-sm font-medium text-gray-700">Permissions:</label>
          <div class="mt-2 p-3 border border-gray-200 rounded-md max-h-52 overflow-y-auto space-y-4">
            @foreach ($permissions as $module => $modulePermissions)
              <div>
                <h3 class="text-sm font-semibold text-gray-800 mb-2">{{ $module }}</h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                  @foreach ($modulePermissions as $permission)
                    <label class="flex items-center text-sm text-gray-600 font-normal">
                      <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 mr-2">
                      {{ $permission->name }}
                    </label>
                  @endforeach
                </div>
              </div>
            @endforeach
          </div>
        </div>

        <!-- Modal Footer -->
        <div class="flex justify-end items-center pt-4">
            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
              Create User
            </button>
        </div>
      </form>
    </div>
  </div>
</div>
@push('scripts')
  <script>
      document.addEventListener('DOMContentLoaded', function () {
        const phoneInputField = document.querySelector("#phone");

    
        const phoneInput = window.intlTelInput(phoneInputField, {
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
        });


fetch('https://get.geojs.io/v1/ip/geo.json')
        .then(response => response.json())
        .then(data => {
            const country = data.country_code;  
            phoneInput.setCountry(country);  
        })
        .catch(error => console.error('Error fetching location:', error));
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

            form.submit();
        });
    });

    
    </script>
    <script>
      // Also close the modal if the user clicks on the backdrop
      document.getElementById('Model').addEventListener('click', function(event) {
        if (event.target === this) {
          document.getElementById('closeModel').click();
        }
      });
    </script>
@endpush