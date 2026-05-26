<x-layout>
<main class="sm:container mx-auto  max-w-fit mt-5 mb-20 sm:max-w-lg sm:mt-10">

  <div class="flex">
      <div class="w-full">
      

          <section class="flex flex-col break-words bg-white sm:border-1 sm:rounded-md sm:shadow-sm sm:shadow-lg">
              <header class="font-semibold bg-gray-200 text-gray-700 py-5 px-6 sm:py-6 sm:px-8 sm:rounded-t-md">
                  {{ __('Reset Password') }}
              </header>

              <form class="border-2 w-full px-6 space-y-6 sm:px-10 sm:space-y-8" method="POST" action="{{route('forgot.password.post')}}">
                  @csrf
                  @method('POST')
                  <div class="flex flex-wrap">
                    <label for="email" class="block text-gray-700 text-sm font-bold mb-2 sm:mb-4">
                      E-Mail Address:
                    </label>

                    <input id="email" type="email"
                        class="rounded-sm p-2 border-2 form-input w-full @error('email') border-red-500 @enderror" name="email"
                        value="{{ old('email') }}" required autocomplete="email">
                    <!-- Google reCAPTCHA v2 checkbox -->
                    @recaptcha_enabled
                    <div class="g-recaptcha mt-2" data-sitekey="{{config('services.captcha.sitekey')}}">
                    </div>
                      @error('g-recaptcha-response')
                        <p class="text-red-500 text-xs italic mt-4">
                            {{ $message }}
                        </p>
                        @enderror
                    @endrecaptcha_enabled    
                    @error('email')
                    <p class="text-red-500 text-xs italic mt-4">
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                  <div class="flex flex-wrap justify-center items-center space-y-6 pb-6 sm:pb-10 sm:space-y-0 sm:justify-between">
                      <button type="submit"
                      class="w-full select-none font-bold whitespace-no-wrap p-3 rounded-lg text-base leading-normal no-underline text-gray-100 bg-gray-700 hover:bg-gray-500 sm:w-auto sm:px-4 sm:order-1">
                          {{ __('Send Password Reset Link') }}
                      </button>

                      <p class="mt-4 text-xs text-blue-500 hover:text-blue-700 whitespace-no-wrap no-underline hover:underline sm:text-sm sm:order-0 sm:m-0">
                          <a class="text-blue-500 hover:text-blue-700 no-underline" href="{{ route('login') }}">
                              {{ __('Back to login') }}
                          </a>
                      </p>
                  </div>
              </form>
          </section>
      </div>
  </div>
</main>
</x-layout>