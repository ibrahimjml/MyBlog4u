<x-layout>

  <main class="mx-auto max-w-md sm:max-w-lg px-4 mt-5 mb-20 sm:mt-10">
    <div class="flex">
      <div class="w-full">
        <section class="flex flex-col break-words bg-white border border-gray-200 rounded-2xl shadow-sm">

          <header class="font-bold text-center bg-gray-100 text-gray-700 py-5 px-6 sm:py-6 sm:px-8 rounded-t-2xl">
            Login
          </header>

          <form id="recaptcha" class="w-full border border-transparent px-4 py-6 space-y-6 sm:px-10 sm:space-y-5" method="POST"
            action="{{ route('login.post') }}">
            @csrf
            @method('POST')
            <div class="flex flex-wrap">
              <label for="login" class="block text-gray-700 text-sm font-bold mb-2 sm:mb-4">
                Username or Email
              </label>

              <input id="login" type="text"
                class="w-full rounded-2xl border border-gray-300 bg-gray-50 p-3 text-gray-900 transition focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none @error('login') border-red-500 bg-white @enderror" name="login"
                value="{{ old('login') }}" required >

              @error('login')
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
                class="w-full rounded-2xl border border-gray-300 bg-gray-50 p-3 text-gray-900 transition focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none @error('password') border-red-500 bg-white @enderror"
                name="password"  required >

              @error('password')
                <p class="text-red-500 text-xs italic mt-4">
                  {{ $message }}
                </p>
              @enderror
            </div>

            <div class="flex flex-col gap-4">
              <button type="submit"
                class="w-full font-bold p-3 rounded-2xl text-base leading-normal text-white bg-gray-700 hover:bg-gray-500 transition sm:py-4">
                Login
              </button>

              <a class="text-gray-500 hover:text-blue-700 no-underline hover:underline"
                href="{{ route('forgot.password') }}">
                Forgot your password?
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
    @if(config('demo.enabled'))
      <div class="mt-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4" role="alert">
        <p class="font-bold">Admin Credentials</p>
        <p>Username: <span class="font-mono">{{ config('demo.admin.username') }}</span></p>
        <p>Pass: <span class="font-mono">{{ config('demo.admin.password') }}</span></p>
      </div>
      <div class="mt-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4" role="alert">
        <p class="font-bold">User Credentials</p>
        <p>Username: <span class="font-mono">{{ config('demo.user.username') }}</span></p>
        <p>Pass: <span class="font-mono">{{ config('demo.user.password') }}</span></p>
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
