@extends('admin.partials.layout')
@section('title', 'SEO Tools | Dashboard')

@section('content')
  @include('admin.partials.header', ['linktext' => 'SEO Tools', 'route' => 'admin.seo.index', 'value' => request('search')])

  <div class="md:ml-64 w-full mx-auto transform -translate-y-48">
    <div class="flex flex-wrap">
      <div class="w-full lg:w-8/12 px-4">
        <div class="relative flex flex-col min-w-0 break-words w-full mb-6 shadow-lg rounded-lg bg-blueGray-100 border-0">
          <div class="rounded-t bg-white mb-0 px-6 py-6">
            <div class="text-center flex justify-between">
              <h6 class="text-blueGray-700 text-xl font-bold">
                SEO Settings
              </h6>
            </div>
          </div>

          <form action="{{ route('admin.seo.update') }}" method="POST" enctype="multipart/form-data"
            class="flex-auto px-4 lg:px-10 py-10 pt-0">
            @csrf
            @method('PUT')

            <h6 class="text-blueGray-400 text-sm mt-3 mb-2 font-bold uppercase">
              Site Identity
            </h6>
            <p class="text-sm text-blueGray-400">Control the public identity used by browsers and search previews.</p>

            <div class="rounded-lg p-5 w-full mt-3 border-2 border-gray-300">
              <label for="app_name" class="block text-lg font-bold mb-1">Application Name</label>
              <input id="app_name" type="text" name="app_name" value="{{ old('app_name', $seoSettings?->app_name) }}"
                placeholder="Enter application name" class="border border-gray-300 rounded-lg px-3 py-2 w-full max-w-md">
              @error('app_name')
                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
              @enderror
            </div>

            <div class="rounded-lg p-5 w-full mt-3 border-2 border-gray-300">
              <label for="favicon_path" class="block text-lg font-bold mb-1">Favicon Path</label>
              <input id="favicon_path" type="file" name="favicon_path" accept="image/*"
                class="block border border-gray-300 rounded-lg px-3 py-2 w-full max-w-md bg-white">
              <input type="hidden" name="delete_favicon" id="delete_favicon" value="0">
              <div class="flex items-start gap-2 mt-2">
                <i class="fas fa-info-circle text-blueGray-400"></i>
                <p class="text-xs text-blueGray-400">Use 16 x 16 pixels for the favicon.</p>
              </div>
              <!-- current favicon image -->
              <div id="currentFaviconImage"
                class="w-[200px] bg-white border-2 border-gray-300 border-dashed relative mt-3 p-2 bg-blueGray-100 rounded-md">
                <p class="text-xs font-semibold text-blueGray-500 mb-2">Current {{ $seoSettings?->favicon_path ? 'Favicon' : 'Default Favicon' }}</p>
                <img src="{{ $seoSettings?->favicon_url ?? asset('img/icon.png')  }}" alt="Current favicon image" class="w-28 object-cover rounded">
                @if($seoSettings?->favicon_path)
                @can('seo.update')
                <button id="removeFaviconImageButton" type="button"
                  class="text-red-500 rounded-full p-2 absolute top-0 right-0 hover:text-red-300">
                  <i class="fas fa-trash"></i>
                </button>
                @endcan
                @endif
              </div>
              @error('favicon_path')
                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
              @enderror
            </div>

            <hr class="my-4">

            <h6 class="text-blueGray-400 text-sm mt-3 mb-2 font-bold uppercase">
              Default Meta Tags
            </h6>
            <p class="text-sm text-blueGray-400">Set fallback metadata for pages that do not define their own SEO values.
            </p>

            <div class="rounded-lg p-5 w-full mt-3 border-2 border-gray-300">
              <label for="meta_title" class="block text-lg font-bold mb-1">Meta Title</label>
              <input id="meta_title" type="text" name="meta_title"
                value="{{ old('meta_title', $seoSettings?->meta_title) }}" placeholder="Default page title"
                class="border border-gray-300 rounded-lg px-3 py-2 w-full">
              @error('meta_title')
                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
              @enderror
            </div>

            <div class="rounded-lg p-5 w-full mt-3 border-2 border-gray-300">
              <label for="meta_description" class="block text-lg font-bold mb-1">Meta Description</label>
              <textarea id="meta_description" name="meta_description" rows="4"
                placeholder="Default search engine description"
                class="border border-gray-300 rounded-lg px-3 py-2 w-full">{{ old('meta_description', $seoSettings?->meta_description) }}</textarea>
              @error('meta_description')
                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
              @enderror
            </div>

            <div class="rounded-lg p-5 w-full mt-3 border-2 border-gray-300">
              <label for="meta_keywords" class="block text-lg font-bold mb-1">Meta Keywords</label>
              <input id="meta_keywords" type="text" name="meta_keywords"
                value="{{ old('meta_keywords', $metaKeywords) }}" placeholder="blog, articles, news"
                class="border border-gray-300 rounded-lg px-3 py-2 w-full">
              <div class="flex items-start gap-2 mt-2">
                <i class="fas fa-info-circle text-blueGray-400"></i>
                <p class="text-xs text-blueGray-400">Separate multiple keywords with commas.</p>
              </div>
              @error('meta_keywords')
                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
              @enderror
            </div>

            <div class="rounded-lg p-5 w-full mt-3 border-2 border-gray-300">
              <label for="meta_author" class="block text-lg font-bold mb-1">Meta Author</label>
              <input id="meta_author" type="text" name="meta_author"
                value="{{ old('meta_author', $seoSettings?->meta_author) }}" placeholder="Author or site owner"
                class="border border-gray-300 rounded-lg px-3 py-2 w-full max-w-md">
              @error('meta_author')
                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
              @enderror
            </div>

            <hr class="my-4">

            <h6 class="text-blueGray-400 text-sm mt-3 mb-2 font-bold uppercase">
              Social Sharing
            </h6>
            <p class="text-sm text-blueGray-400">Set the default Open Graph image used when pages are shared.</p>

            <div class="rounded-lg p-5 w-full mt-3 border-2 border-gray-300">
              <label for="meta_og_image" class="block text-lg font-bold mb-1">Open Graph Image</label>
              <input id="meta_og_image" type="file" name="meta_og_image" accept="image/*"
                class="block border border-gray-300 rounded-lg px-3 py-2 w-full max-w-md bg-white">
              <input type="hidden" name="delete_meta_og_image" id="delete_meta_og_image" value="0">
              <div class="flex items-start gap-2 mt-2">
                <i class="fas fa-info-circle text-blueGray-400"></i>
                <p class="text-xs text-blueGray-400">Use 1200 x 630 pixels for the Open Graph image.</p>
              </div>
              <!-- current og image -->
              <div id="currentMetaOgImage"
                class="w-[200px] bg-white border-2 border-gray-300 border-dashed relative mt-3 p-2 bg-blueGray-100 rounded-md">
                <p class="text-xs font-semibold text-blueGray-500 mb-2">Current {{ $seoSettings?->meta_og_image_url ? 'Og Image' : 'Default Og Image' }}:</p>
                <img src="{{ $seoSettings?->meta_og_image_url ?? asset('img/logo2.png') }}" alt="Current Open Graph image" class="w-40 object-cover rounded">
                @if($seoSettings?->meta_og_image)
                @can('seo.update')
                <button id="removeMetaOgImage" type="button"
                  class="text-red-500 rounded-full p-2 absolute top-0 right-0 hover:text-red-300">
                  <i class="fas fa-trash"></i>
                </button>
                @endcan
                @endif
              </div>


              @error('meta_og_image')
                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
              @enderror
            </div>

            <hr class="my-4">

            <h6 class="text-blueGray-400 text-sm mt-3 mb-2 font-bold uppercase">
              Tracking Scripts
            </h6>
            <p class="text-sm text-blueGray-400">Add global scripts that should load in the page header or footer.</p>

            <div class="rounded-lg p-5 w-full mt-3 border-2 border-gray-300">
              <label for="header_scripts" class="block text-lg font-bold mb-1">Header Scripts</label>
              <textarea id="header_scripts" name="header_scripts" rows="5" placeholder="<script>...</script>"
                class="block w-full rounded-md border-gray-300 placeholder:text-green-500 bg-black/80 text-green-500 shadow-sm focus:border-green-500 focus:ring-green-500 p-3 sm:text-sm">{{ old('header_scripts', $seoSettings?->header_scripts) }}</textarea>
              @error('header_scripts')
                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
              @enderror
            </div>

            <div class="rounded-lg p-5 w-full mt-3 border-2 border-gray-300">
              <label for="footer_scripts" class="block text-lg font-bold mb-1">Footer Scripts</label>
              <textarea id="footer_scripts" name="footer_scripts" rows="5" placeholder="<script>...</script>"
                class="block w-full rounded-md border-gray-300 placeholder:text-green-500 bg-black/80 text-green-500 shadow-sm focus:border-green-500 focus:ring-green-500 p-3 sm:text-sm">{{ old('footer_scripts', $seoSettings?->footer_scripts) }}</textarea>
              @error('footer_scripts')
                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
              @enderror
            </div>
            @can('seo.update')
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

  @push('scripts')
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        const removeFaviconButton = document.getElementById('removeFaviconImageButton');
        const deleteFaviconInput = document.getElementById('delete_favicon');
        const currentFaviconImage = document.getElementById('currentFaviconImage');
        const faviconInput = document.getElementById('favicon_path');

        if (removeFaviconButton) {
          removeFaviconButton.addEventListener('click', function () {
            deleteFaviconInput.value = '1';
            if (currentFaviconImage) {
              currentFaviconImage.classList.add('hidden');
            }
          });
        }

        if (faviconInput) {
          faviconInput.addEventListener('change', function () {
            deleteFaviconInput.value = '0';
          });
        }

        const removeMetaOgImage = document.getElementById('removeMetaOgImage');
        const deleteMetaOgInput = document.getElementById('delete_meta_og_image');
        const currentMetaOgImage = document.getElementById('currentMetaOgImage');
        const metaOgInput = document.getElementById('meta_og_image');

        if (removeMetaOgImage) {
          removeMetaOgImage.addEventListener('click', function () {
            deleteMetaOgInput.value = '1';
            if (currentMetaOgImage) {
              currentMetaOgImage.classList.add('hidden');
            }
          });
        }

        if (metaOgInput) {
          metaOgInput.addEventListener('change', function () {
            deleteMetaOgInput.value = '0';
          });
        }
      });
    </script>
  @endpush
@endsection