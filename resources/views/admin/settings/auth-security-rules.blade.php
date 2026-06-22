@extends('admin.partials.layout')
@section('title', 'auth security rules | Dashboard')
@section('content')
  <!-- Header -->
  @include('admin.partials.header', ['linktext' => 'Auth Security Rules', 'route' => 'admin.settings.auth.index', 'value' => request('search')])

  <div class="md:ml-64 w-full mx-auto transform -translate-y-48">
    <div class="flex flex-wrap">
      <div class="w-full lg:w-8/12 px-4">
        <div class="relative flex flex-col min-w-0 break-words w-full mb-6 shadow-lg rounded-lg bg-blueGray-100 border-0">
          <div class="rounded-t bg-white mb-0 px-6 py-6">
            <div class="text-center flex justify-between">
              <h6 class="text-blueGray-700 text-xl font-bold">
                Auth Security Rules
              </h6>

            </div>
          </div>
          {{-- user registration rules --}}
          <form id="actionForm" action="{{ route('admin.settings.auth.update') }}" method="POST"
            class="flex-auto px-4  lg:px-10 py-10 pt-0 ">
            @csrf
            @method('PUT')
            <h6 class="text-blueGray-400 text-sm mt-3 mb-2 font-bold uppercase">
              User Registration Rules
            </h6>
            <p class="text-sm text-blueGray-400">Manage how new users interact with your system.</p>
            <div class="rounded-lg p-5 w-full mt-3 flex justify-between items-center border-2 border-gray-300">
              <div class="">
                <p class="text-lg font-bold">Enable User Registration</p>
                <p class="text-xs text-blueGray-400 mt-1">If disabled, the registration page will be blocked, and no new
                  users can join.</p>
              </div>
              <div class="">
                <input type="hidden" name="enable_registration" value="0">
                <x-toggle name="enable_registration" value="1" :checked="$settings->allow_registration ?? true"
                  onchange="openAllowedEmails(this)" />
              </div>
            </div>
            {{-- allowed emails domain --}}
            <div id="allowed-emails-section"
              class="{{ ($settings?->allow_registration) ? '' : 'hidden' }} rounded-lg p-5 w-full mt-3 border-2 border-gray-300">
              <p class="text-lg font-bold mb-3">Allowed Email Domains</p>
              <input type="text" name="allowed_email_domains"
                value="{{ old('allowed_email_domains', $settings?->allowed_email_domains) }}"
                placeholder="e.g. gmail.com, mail.ru, outlook.com"
                class=" border border-gray-300 rounded-lg px-3 py-2 w-full max-w-xs">
              <div class="flex items-start gap-2 mt-2">
                <i class="fas fa-info-circle text-blueGray-400"></i>
                <p class="text-xs text-blueGray-400">Leave empty to allow all emails. Separate multiple domains with
                  commas. If set, only users with these email domains will be allowed to register.</p>
              </div>
            </div>
            <hr class="my-4">
            <h6 class="text-blueGray-400 text-sm mt-3 mb-2 font-bold uppercase">
              Enable Google reCAPTCHA
            </h6>
            <p class="text-sm text-blueGray-400">Protect your registration form from automated submissions.</p>

            <div class="rounded-lg p-5 w-full mt-3 flex justify-between items-center border-2 border-gray-300">
              <div class="">
                <p class="text-lg font-bold">Enable Google reCAPTCHA</p>
                <p class="text-xs text-blueGray-400 mt-1">If enabled, users will need to complete a reCAPTCHA challenge
                  during registration.</p>
              </div>
              <div class="">
                <input type="hidden" name="require_captcha" value="0">
                <x-toggle name="require_captcha" value="1" :checked="$settings->require_captcha ?? false"
                  onchange="openRecaptchaKeys(this)" />
              </div>
            </div>
            <div id="recaptcha-keys-section"
              class="{{ ($settings?->require_captcha ?? false) ? '' : 'hidden' }} rounded-lg p-5 w-full mt-3 border-2 border-gray-300">
              <!-- reCAPTCHA keys -->
              <p class="text-lg font-bold mb-3">reCAPTCHA Keys</p>
              <label for="recaptcha_site_key" class="block text-sm font-medium text-gray-700">Site Key</label>
              <input type="password" name="recaptcha_site_key"
                value="{{ old('recaptcha_site_key', $settings?->recaptcha_sitekey) }}"
                placeholder="enter recaptcha site key"
                class=" border border-gray-300 rounded-lg px-3 py-2 w-full max-w-xs">
              <label for="recaptcha_secret_key" class="block text-sm font-medium text-gray-700 mt-2">Secret Key</label>
              <input type="password" name="recaptcha_secret_key"
                value="{{ old('recaptcha_secret_key', $settings?->recaptcha_secretkey) }}"
                placeholder="enter recaptcha secret key"
                class="block mt-2 border border-gray-300 rounded-lg px-3 py-2 w-full max-w-xs">
            </div>
            @can('authsecurity.update')
            <button
              class="block bg-green-500 ml-auto mt-2 w-fit text-white active:bg-gray-600 font-bold uppercase text-xs px-4 py-2 rounded shadow hover:shadow-md outline-none focus:outline-none mr-1 ease-linear transition-all duration-150"
              type="submit">
              <i class="fas fa-save mr-2"></i>
              Save preferences
            </button>  
            @endcan
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

    @push('scripts')
      <script>
        function openAllowedEmails(toggle) {
          const allowedEmailsSection = document.getElementById('allowed-emails-section');
          if (!toggle.checked) allowedEmailsSection.classList.add('hidden');
          else allowedEmailsSection.classList.remove('hidden');
        }
        function openRecaptchaKeys(toggle) {
          const recaptchaKeysSection = document.getElementById('recaptcha-keys-section');
          if (!toggle.checked) recaptchaKeysSection.classList.add('hidden');
          else recaptchaKeysSection.classList.remove('hidden');
        }
      </script>
    @endpush