<x-layout>


  <main class="mx-auto max-w-md sm:max-w-lg px-4 mt-5 mb-20 sm:mt-10">
    <div class="flex">
      <div class="w-full">
        <section class="flex flex-col break-words bg-white border border-gray-200 rounded-2xl shadow-sm">

          <header class="font-bold text-center bg-gray-100 text-gray-700 py-5 px-6 sm:py-6 sm:px-8 rounded-t-2xl">
            Register
          </header>

          <form id="phone-form" class="w-full px-4 py-6 space-y-6 sm:px-10 sm:space-y-5 h-fit" method="POST"
            action="{{ route('register.post') }}">
            @csrf

            <div class="flex flex-wrap">
              <label for="name" class="block text-gray-700 text-sm font-bold mb-2 sm:mb-4">
                Name:
              </label>

              <input id="name" type="text"
                class="w-full rounded-2xl border border-gray-300 bg-gray-50 p-3 text-gray-900 transition focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none @error('name') border-red-500 bg-white @enderror"
                name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

              @error('name')
                <p class="text-red-500 text-xs italic mt-4">
                  {{ $message }}
                </p>
              @enderror
            </div>
            <div class="flex flex-wrap">
              <label for="name" class="block text-gray-700 text-sm font-bold mb-2 sm:mb-4">
                Username:
              </label>

              <input id="username" type="text"
                class="w-full rounded-2xl border border-gray-300 bg-gray-50 p-3 text-gray-900 transition focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none @error('username') border-red-500 bg-white @enderror"
                name="username" value="{{ old('username') }}" required autocomplete="username" autofocus>

              @error('username')
                <p class="text-red-500 text-xs italic mt-4">
                  {{ $message }}
                </p>
              @enderror
            </div>
            <div class="flex flex-wrap">
              <label for="email" class="block text-gray-700 text-sm font-bold mb-2 sm:mb-4">
                E-Mail Address:
              </label>

              <input id="email" type="email" placeholder="mail.ru, gmail.com, outlook.com, hotmail.com"
                class="w-full rounded-2xl border border-gray-300 bg-gray-50 p-3 text-gray-900 transition focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none @error('email') border-red-500 bg-white @enderror"
                name="email" value="{{ old('email') }}" required autocomplete="email">

              @error('email')
                <p class="text-red-500 text-xs italic mt-4">
                  {{ $message }}
                </p>
              @enderror
            </div>

            <div class="flex flex-wrap mb-4">
              <label for="phone" class="block w-full text-gray-700 text-sm font-bold mb-2">
                Phone:
              </label>

              <input id="phone" type="tel"
                class="w-full rounded-2xl border border-gray-300 bg-gray-50 p-3 text-gray-900 transition focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none @error('phone') border-red-500 bg-white @enderror"
                name="phone" value="{{ old('phone') }}" required autocomplete="tel">

              @error('phone')
                <p class="text-red-500 text-xs italic mt-2 w-full">
                  {{ $message }}
                </p>
              @enderror
            </div>

            <div class="flex flex-wrap">
              <label for="password" class="block text-gray-700 text-sm font-bold mb-2 sm:mb-4">
                Password:
              </label>

              <input id="password" type="password"
                class="w-full rounded-2xl border border-gray-300 bg-gray-50 p-3 text-gray-900 transition focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none @error('password') border-red-500 bg-white @enderror"
                name="password" required autocomplete="new-password">

              @error('password')
                <p class="text-red-500 text-xs italic mt-4">
                  {{ $message }}
                </p>
              @enderror
            </div>

            <div class="flex flex-wrap">
              <label for="password-confirm" class="block text-gray-700 text-sm font-bold mb-2 sm:mb-4">
                Confirm Password:
              </label>

              <input id="password-confirm" type="password"
                class="w-full rounded-2xl border border-gray-300 bg-gray-50 p-3 text-gray-900 transition focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none"
                name="password_confirmation" required autocomplete="new-password">
            </div>
            <!-- accept terms checkbox -->
            <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:gap-3">
              <div class="flex items-center gap-2">
                <input id="accept" type="checkbox" name="accept"
                  class="h-4 w-4 rounded text-blue-600 border-gray-300 focus:ring-blue-500">
                <label for="accept" class="text-sm text-gray-700">I agree to <a class="text-blue-500 underline"
                    href="{{ route('custom.page', $terms) }}">terms</a> & <a class="text-blue-500 underline"
                    href="{{ route('custom.page', $privacy) }}">privacy policy</a>.</label>
              </div>
              @error('accept')<p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>@enderror
            </div>
            <!-- Google reCAPTCHA v2 checkbox -->
            @recaptcha_enabled
            <div class="g-recaptcha" data-sitekey="{{config('services.captcha.sitekey')}}"></div>
            @error('g-recaptcha-response')
              <p class="text-red-500 text-xs italic mt-4">
                {{ $message }}
              </p>
            @enderror
            @endrecaptcha_enabled
            <div class="flex flex-wrap">
              <button type="submit"
                class="w-full font-bold p-3 rounded-2xl text-base leading-normal text-white bg-gray-700 hover:bg-gray-500 transition sm:py-4">
                Register
              </button>

              <p class="w-full text-xs text-center text-gray-700 my-3 sm:text-sm sm:my-8">
                Already have an account?
                <a class="text-gray-500 hover:text-blue-700 no-underline hover:underline" href="{{ route('login') }}">
                  Login
                </a>
              </p>
            </div>
          </form>

        </section>
      </div>
    </div>
  </main>
  @push('scripts')
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        const phoneInputField = document.querySelector("#phone");

        // Initialize intl-tel-input
        const phoneInput = window.intlTelInput(phoneInputField, {
          utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
        });


        fetch('https://get.geojs.io/v1/ip/country.json')
          .then(response => response.json())
          .then(data => {
            const country = data.country_code;  // Country code like "US", "IN", etc.
            phoneInput.setCountry(country);  // Set the country
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
  @endpush
</x-layout>