@extends('admin.partials.layout')
@section('title', 'Media Settings | Dashboard')
@section('content')
  <!-- Header -->
  @include('admin.partials.header', ['linktext' => 'Media Settings', 'route' => 'admin.settings.auth.index', 'value' => request('search')])

  <div class="md:ml-64 w-full mx-auto transform -translate-y-48">
    <div class="flex flex-wrap">
      <div class="w-full lg:w-8/12 px-4">
        <div class="relative flex flex-col min-w-0 break-words w-full mb-6 shadow-lg rounded-lg bg-blueGray-100 border-0">
          <div class="rounded-t bg-white mb-0 px-6 py-6">
            <div class="text-center flex justify-between">
              <h6 class="text-blueGray-700 text-xl font-bold">
                Manage Media Settings
              </h6>

            </div>
          </div>
          <form id="mediaForm" action="{{ route('admin.settings.media.update') }}" method="POST"
            class="flex-auto px-4  lg:px-10 py-10 pt-0 ">
            @csrf
            @method('PUT')

             <x-media-driver :selected="$settings['media_driver'] ?? null" :values="$settings"/>
             @can('media.update')
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

{{-- @push('scripts')
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
@endpush --}}