<x-layout>

  <main class="sm:container mx-auto  max-w-fit mt-5 mb-20 sm:max-w-lg sm:mt-10">
    <div class="flex">
      <div class="w-full">
        <section class="flex flex-col break-words bg-white sm:border-1 sm:rounded-md  sm:shadow-lg">

          <header class=" font-bold text-center bg-gray-200 text-gray-700 py-5 px-6 sm:py-6 sm:px-8 sm:rounded-t-md">
            Login
          </header>

          <form id="recaptcha" class="border-2 w-full px-6 space-y-6 sm:px-10 sm:space-y-8" method="POST"
            action="{{ route('login.post') }}">
            @csrf
            @method('POST')
            <div class="flex flex-wrap">
              <label for="email" class="block text-gray-700 text-sm font-bold mb-2 sm:mb-4">
                E-Mail Address:
              </label>

              <input id="email" type="email"
                class="rounded-sm p-2 border-2 form-input w-full @error('email') border-red-500 @enderror" name="email"
                value="{{ old('email') }}" required autocomplete="email">

              @error('email')
                <p class="text-red-500 text-xs italic mt-4">
                  {{ $message }}
                </p>
              @enderror
            </div>

            <div class="flex flex-wrap">
              <label for="password" class="block text-gray-700 text-sm font-bold mb-2 sm:mb-4">
                Password:
              </label>

              <input id="password" type="password"
                class="rounded-sm p-2 border-2 form-input w-full @error('password') border-red-500 @enderror"
                name="password" required autocomplete="new-password">

              @error('password')
                <p class="text-red-500 text-xs italic mt-4">
                  {{ $message }}
                </p>
              @enderror
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
                class=" w-full select-none font-bold whitespace-no-wrap p-3 rounded-lg text-base leading-normal no-underline text-gray-100 bg-gray-700 hover:bg-gray-500 sm:py-4">
                Login
              </button>

              <a class="text-gray-500 mt-2 hover:text-blue-700 no-underline hover:underline"
                href="{{ route('forgot.password') }}">
                forgot your password ?
              </a>

              <p class="w-full text-xs text-center text-gray-700 my-6 sm:text-sm sm:my-8">
                {{ __('Dont have an account?') }}
                <a class="text-gray-500 hover:text-blue-700 no-underline hover:underline"
                  href="{{ route('register') }}">
                  Register
                </a>
              </p>
            </div>
          </form>

        </section>
      </div>
    </div>
    @if(env('IS_DEMO', false))
      <div class="mt-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4" role="alert">
        <p class="font-bold">Demo Credentials</p>
        <p>Email: <span class="font-mono">{{ env('ADMIN_EMAIL') }}</span></p>
        <p>Pass: <span class="font-mono">{{ env('ADMIN_PASS') }}</span></p>
      </div>
    @endif
  </main>
  @push('scripts')
    <script>
      function onSubmit(token) {
        document.getElementById("recaptcha").submit();
      }
    </script>
  @endpush
</x-layout>