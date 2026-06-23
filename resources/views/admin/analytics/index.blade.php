@extends('admin.partials.layout')
@section('title', 'Google Analytics | Dashboard')
@section('content')
  <!-- Header -->
  @include('admin.partials.header', ['linktext' => 'Google Analytics', 'route' => 'admin.analytics.index', 'value' => request('search')])

  <div class="md:ml-64 w-full mx-auto transform -translate-y-48">
    <div class="flex flex-wrap">
      <div class="w-full lg:w-8/12 px-4">
        <div class="relative flex flex-col min-w-0 break-words w-full mb-6 shadow-lg rounded-lg bg-blueGray-100 border-0">
          <div class="rounded-t bg-white mb-0 px-6 py-6">
            <div class="text-left">
              <h6 class="text-blueGray-700 text-xl font-bold">
                Google Analytics
              </h6>
              <p class="text-sm text-blueGray-400 mt-1">Config Credentials for Google Analytics</p>
            </div>
          </div>
          <form id="mediaForm" action="{{ route('admin.analytics.update') }}" method="POST" enctype="multipart/form-data"
            class="flex-auto px-4  lg:px-10 py-10 pt-0 ">
            @csrf
            @method('PUT')

            <div class="rounded-lg p-5 w-full mt-3 flex justify-between items-center border-2 border-gray-300">
              <label class="block font-bold my-2">Enable Dashboard Widgets</label>
              <input type="hidden" name="analytics_dashboard_widgets" value="0">
              <x-toggle name="analytics_dashboard_widgets" value="1" :checked="$settings['analytics_dashboard_widgets'] ?? false" onchange="openAnalyticsConfig(this)" />
            </div>
            <div id="config-section" class="{{ ($settings['analytics_dashboard_widgets']) ? '' : 'hidden' }} rounded-lg p-5 w-full mt-3  border-2 border-gray-300">
              <div class="mb-2">
                <label for="" class="block text-sm font-medium text-gray-700 mb-2">Property Id for GA4</label>
                <input type="text"
                  class=" border border-gray-300 rounded-lg px-3 py-2 w-full max-w-xs placeholder:text-sm"
                  name="analytics_property_id" placeholder="Google Property Id for GA4" value="{{ old('analytics_property_id',$settings['analytics_property_id']) }}">
              </div>
              <div class="mb-2">
                <label for="" class="block text-sm font-medium text-gray-700 mb-2">Service Account Credentials</label>
                <textarea rows="10" class=" border border-gray-300 rounded-lg px-3 py-2 w-full max-w-xs"
                  name="analytics_service_account_credentials">{{ old('analytics_service_account_credentials', $settings['analytics_service_account_credentials'] ?? '') }}</textarea>
              </div>
              @error('analytics_service_account_credentials')
                    <p class="text-red-500 text-xs italic mt-4">
                      {{ $message }}
                    </p>
                  @enderror
              @can('analytics.update')    
              <div class="mb-2">
                <label for="jsonUpload" class="block text-sm font-medium text-gray-700 mb-2">Upload JSON Service
                  Account</label>
                <div class="flex items-center space-x-3">
                  <input id="jsonUpload" type="file" name="json" class="hidden" onchange="handleJsonUpload(event)">
                  <button type="button" onclick="document.getElementById('jsonUpload').click()"
                    class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Upload JSON</button>
                  <span id="jsonFilename" class="text-sm text-gray-600">No file selected</span>
                </div>
              </div>
              @endcan
            </div>
            @can('analytics.update')
            <button
              class="block bg-green-500 ml-auto mt-2 w-fit text-white active:bg-gray-600 font-bold uppercase text-xs px-4 py-2 rounded shadow hover:shadow-md outline-none focus:outline-none mr-1 ease-linear transition-all duration-150"
              type="submit">
              <i class="fas fa-save mr-2"></i>
              Save preferences
            </button>
            @endcan
          </form>
          <script>
            async function handleJsonUpload(e) {
              const file = e.target.files[0];
              const filenameEl = document.getElementById('jsonFilename');
              const textarea = document.querySelector('textarea[name="analytics_service_account_credentials"]');
              if (!file) {
                filenameEl.textContent = 'No file selected';
                return;
              }
              filenameEl.textContent = file.name;

              const formData = new FormData();
              formData.append('json', file);

              try {
                const res = await fetch('{{ route('admin.analytics.json') }}', {
                  method: 'POST',
                  headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                  },
                  body: formData,
                });

                const data = await res.json();

                // support multiple response shapes
                const content = data.content || null;

                if (!res.ok || !content) {
                  const message = data.message || 'Failed to upload or parse JSON file.';
                  alert(message);
                  return;
                }

                textarea.value = content;
              } catch (err) {
                console.error(err);
                alert('An error occurred while uploading the JSON file.');
              }
            }

            function openAnalyticsConfig(eo)
            {
              const configSection = document.getElementById('config-section');
          if (!eo.checked) configSection.classList.add('hidden');
          else configSection.classList.remove('hidden');
            }
          </script>
        </div>
      </div>
    </div>
  </div>
@endsection