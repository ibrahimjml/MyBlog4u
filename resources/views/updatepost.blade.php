{{-- resources/views/updatepost.blade.php --}}
<x-layout>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="bg-white shadow-xl rounded-lg overflow-hidden"><!--start page card-->
      <div class="bg-gray-50 py-6 px-6 border-b border-gray-200"><!--start header-->
        <h1 class="text-3xl font-extrabold text-gray-800 text-center">
          Update Article
        </h1>
      </div><!--end header-->
      <div class="grid grid-cols-12 gap-6 p-6"><!--start grid layout-->

        <div class="col-span-12 lg:col-span-8">
          <form action="{{ route('update.post', $post->slug) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')

            {{-- Section 1: Post Details --}}
            <fieldset id="post-details" class="border border-gray-300 rounded-md p-5 bg-gray-50/50">
              <legend class="text-lg font-semibold text-gray-700 px-2 bg-white rounded">
                Post Details
              </legend>

              <div class="space-y-6">
                <div>
                  <label class="block text-sm font-bold text-gray-700 mb-2">
                    Title <span class="text-red-500">*</span>
                  </label>
                  <input type="text" name="title" value="{{ old('title', $post->title) }}" required
                    class="w-full rounded-md border-gray-300 focus:ring-blue-500 focus:border-blue-500 p-2">
                  @error('title')
                    <p class="text-red-500 text-xs italic mt-4">{{ $message }}</p>
                  @enderror
                </div>
                {{-- short excerpt --}}
                <div>
                  <label class="block text-sm font-bold text-gray-700 mb-2">
                    Short Excerpt <span class="text-red-500">*</span>
                  </label>
                  <textarea name="short_excerpt" rows="5" required
                    class="w-full rounded-md border-gray-300 focus:ring-blue-500 focus:border-blue-500 p-2">{{ old('short_excerpt', $post->short_excerpt) }}</textarea>
                  @error('short_excerpt')
                    <p class="text-red-500 text-xs italic mt-4">{{ $message }}</p>
                  @enderror
                </div>
              </div>
            </fieldset>

            {{-- Section 2: post content --}}
            <fieldset id="post-content" class="border border-gray-300 rounded-md p-5 bg-gray-50/50">
              <legend
                class="text-lg font-semibold text-gray-700 px-2 bg-white rounded border border-gray-200 shadow-sm">
                Post Content
              </legend>
              <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">
                  Description
                </label>
                <textarea name="description" id="textarea" rows="5"
                  class="w-full rounded-md border-gray-300 focus:ring-blue-500 focus:border-blue-500 p-2">{{ old('description', $post->description) }}</textarea>
                @error('description')
                  <p class="text-red-500 text-xs italic mt-4">
                    {{ $message }}
                  </p>
                @enderror
              </div>
            </fieldset>

            {{-- Section 3: Media --}}
            <fieldset id="media" class="border border-gray-300 rounded-md p-5 bg-gray-50/50">
              <legend
                class="text-lg font-semibold text-gray-700 px-2 bg-white rounded border border-gray-200 shadow-sm">
                Media
              </legend>
              <div class="flex flex-col">
                <label for="imageSelected" class="block text-gray-700 text-sm font-bold mb-2">
                  Upload Image
                </label>
                <input type="file" id="imageSelected" name="image"
                  class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded-md cursor-pointer @error('image') border-red-500 @enderror">
                {{-- Image Preview --}}
                <div class="mt-4 relative w-fit {{ $post->image_url ? '' : 'hidden' }}" id="imageContainer">
                  <img id="imagePreview" src="{{ $post->image_url ?? '#' }}" alt="Image Preview"
                    class="max-w-[300px] rounded-md shadow-lg border-2 border-gray-200">
                  <button type="button" id="cancelPreview"
                    class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600 shadow-md focus:outline-none"
                    title="Remove image"> &times;
                  </button>
                  <span id="fileSize"
                    class="absolute bottom-2 left-2 bg-black bg-opacity-75 text-white text-xs px-2 py-1 rounded">
                  </span>
                </div> @error('image') <p class="text-red-500 text-xs italic mt-1"> {{ $message }} </p> @enderror
              </div>
            </fieldset>

            {{-- Section 4: Tags & Categories --}}
            <fieldset id="tags-categories" class="border border-gray-300 rounded-md p-5 bg-gray-50/50">
              <legend
                class="text-lg font-semibold text-gray-700 px-2 bg-white rounded border border-gray-200 shadow-sm">
                Tags & Categories</legend>

              <div class="space-y-6">
                {{-- Hashtags --}}
                <div class="flex flex-col w-full">
                  <label for="hashtagInput" class="block text-gray-700 text-sm font-bold mb-2">Hashtags (Optional)</label>

                  <input type="hidden" name="hashtag" id="hashtagsHidden">
                  <div id="tagContainer" class="flex flex-wrap gap-2 mb-3 min-h-[2rem]"></div><!-- Tags container -->

                  <!-- Add new tags -->
                  <input type="text" id="hashtagInput" placeholder="Type a hashtag and press Enter"
                    class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 w-full p-2 border" />

                  @error('hashtag')<p class="text-red-500 text-xs italic mt-1"> {{ $message }}</p>@enderror
                  <!-- or select existing tags -->
                  @if($allhashtags->isNotEmpty())
                    <div class="relative flex py-5 items-center">
                      <div class="flex-grow border-t border-gray-300"></div>
                      <span class="flex-shrink-0 mx-4 text-gray-400 text-xs uppercase font-bold">Or select existing</span>
                      <div class="flex-grow border-t border-gray-300"></div>
                    </div>

                    <div class="bg-white p-3 rounded border border-gray-200">
                      <label for="selectedHashtag" class="block text-xs font-bold text-gray-500 mb-1">Available Hashtags</label>
                      <select id="selectedHashtag" multiple
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 p-2 h-32">
                        @foreach($allhashtags as $all)
                          <option value="{{ $all }}">{{ $all }}</option>
                        @endforeach
                      </select>
                      <div class="flex justify-between items-center mt-2">
                        <p class="text-gray-500 text-xs">
                          <i class="fas fa-info-circle mr-1"></i> Select one or more (Max: 5)
                        </p>
                        <button type="button" onclick="addSelectedHashtags()"
                          class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold py-1 px-3 rounded transition duration-150 ease-in-out">
                          Add Selected
                        </button>
                      </div>
                    </div>
                  @endif
                </div>

                @if($categories->isNotEmpty())
                  <div class="flex flex-col">
                    <label for="categories" class="block text-gray-700 text-sm font-bold mb-2">Categories (Optional)</label>
                    <select name="categories[]" id="categories" multiple
                      class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 p-2 h-32">
                      @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                          @if(old('categories') ? in_array($category->id, old('categories', [])) : $post->categories->contains($category->id)) selected @endif>
                          {{ $category->name }}
                        </option>
                      @endforeach
                    </select>
                    <p class="text-gray-500 text-xs mt-2">
                      <i class="fas fa-info-circle mr-1"></i> Hold Ctrl/Cmd to select multiple (Max: 4)
                    </p>
                    @error('categories')
                      <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                  </div>
                @endif
              </div>
            </fieldset>
            {{-- publish settings --}}
            <fieldset id="publish-settings" class="border border-gray-300 rounded-md p-5 bg-gray-50/50">
              <legend
                class="text-lg font-semibold text-gray-700 px-2 bg-white rounded border border-gray-200 shadow-sm">
                Publish Settings
              </legend>
              <label for="status" class="block text-gray-700 text-sm font-bold mb-2">post status</label>
              <select name="status" id="status"
                class="pl-3 pr-8 appearance-none font-bold cursor-pointer bg-blueGray-200 text-blueGray-500 border-0 text-sm rounded-lg p-2.5">
                @foreach(\App\Enums\PostStatus::forUserCreation() as $status => $label)
                  <option value="{{ $status }}" {{ old('status', $post->status->value) === $status ? 'selected' : '' }}>
                    {{ $label }}
                  </option>
                @endforeach
              </select>
              <label for="enabled" class="block text-sm font-bold text-gray-700 mb-2 mt-5">
                Allow comments
              </label>
              <x-toggle name="enabled" :checked="old('enabled', $post->allow_comments)" value="1" label="let users reply to this post" />
            </fieldset>
        </div>

        {{-- Quick Navigation --}}
        <div class=" col-span-12 lg:col-span-4 space-y-6 mt-8 lg:fixed lg:top-48 lg:right-64 self-end z-10">

          {{-- Quick Navigation --}}
          <fieldset class="hidden lg:block border border-gray-300 rounded-md p-5 bg-gray-50/50">
            <legend class="text-lg font-semibold text-gray-700 px-2 bg-white rounded">
              Quick Navigation
            </legend>
            <div class="flex flex-col gap-2 rounded-2xl bg-white p-4 shadow-sm border border-gray-100 w-full">
              <a href="#post-details" class="quick-nav-link flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 transition" data-target="post-details">
                <i class="fas fa-file-alt w-4 text-blue-500"></i>
                <span>Post Details</span>
              </a>

              <a href="#post-content" class="quick-nav-link flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 transition" data-target="post-content">
                <i class="fas fa-align-left w-4 text-indigo-500"></i>
                <span>Post Content</span>
              </a>

              <a href="#media" class="quick-nav-link flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 transition" data-target="media">
                <i class="fas fa-image w-4 text-pink-500"></i>
                <span>Media</span>
              </a>

              <a href="#tags-categories" class="quick-nav-link flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 transition" data-target="tags-categories">
                <i class="fas fa-tags w-4 text-emerald-500"></i>
                <span>Tags & Categories</span>
              </a>

              <a href="#publish-settings" class="quick-nav-link flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 transition" data-target="publish-settings">
                <i class="fas fa-globe w-4 text-orange-500"></i>
                <span>Publish Settings</span>
              </a>
            </div>

          </fieldset>
          {{-- Submit --}}
          <div class="">
            <button type="submit"
              class="w-full bg-gray-800 hover:bg-gray-700 text-white font-bold py-3 rounded-lg shadow">
              Update Post
            </button>
          </div>
        </div>

        </form>
      </div><!--end grid layout -->
    </div><!--end page card -->
  </div>

  @push('scripts')
    <script>
      window.initialTags = @json(explode(',', $hashtags ?? ''));
    </script>

    <script>
      const imageSelected = document.getElementById('imageSelected');
      const imagePreview = document.getElementById('imagePreview');
      const imageContainer = document.getElementById('imageContainer');
      const cancelPreview = document.getElementById('cancelPreview');
      const fileSize = document.getElementById('fileSize');

      imageSelected.addEventListener('change', event => {
        const file = event.target.files[0];
        if (file) {
          const reader = new FileReader();

          reader.onload = eo => {
            imagePreview.src = eo.target.result;
            imageContainer.classList.remove('hidden');
            fileSize.textContent = `${(file.size / (1024 * 1024)).toFixed(2)} MB / 5MB`;
          }
          reader.readAsDataURL(file);
        } else {
          imagePreview.src = '#';
          imageContainer.classList.add('hidden');
        }
      });

      cancelPreview.addEventListener('click', function () {
        imageSelected.value = '';
        imagePreview.src = '#';
        imageContainer.classList.add('hidden');
      });
    </script>

    <script>
      (function () {
        const OFFSET = 80; // scroll offset for fixed headers
        const links = document.querySelectorAll('a.quick-nav-link');
        const sections = Array.from(links).map(l => document.getElementById(l.dataset.target));

        links.forEach(link => {
          link.addEventListener('click', function (e) {
            e.preventDefault();
            const targetId = this.dataset.target;
            const el = document.getElementById(targetId);
            if (!el) return;
            const top = el.getBoundingClientRect().top + window.scrollY - OFFSET;
            window.scrollTo({ top, behavior: 'smooth' });
          });
        });
      })();
    </script>
  @endpush
</x-layout>