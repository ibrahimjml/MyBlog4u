<x-layout>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="bg-white shadow-xl rounded-lg overflow-hidden"><!--start page card-->
      <div class="bg-gray-50 py-6 px-6 border-b border-gray-200"><!--start header-->
        <p class="text-xl text-gray-800 text-center">
          Update <b>{{ $post->title }}</b>
        </p>
      </div><!--end header-->
      <div class="grid grid-cols-12 gap-6 p-6"><!--start grid layout-->

        <div class="col-span-12 lg:col-span-8">
          <form action="{{ route('update.post', $post->slug) }}" method="POST" class="space-y-8">
            @csrf
            @method('PUT')
            {{-- Section 1: Post Details --}}
            <fieldset class="border border-gray-300 rounded-md p-5 bg-gray-50/50">
              <legend class="text-lg font-semibold text-gray-700 px-2 bg-white rounded">
                Post Details
              </legend>

              <div class="space-y-6">
                {{-- Title --}}
                <div>
                  <label class="block text-sm font-bold text-gray-700 mb-2">
                    Title <span class="text-red-500">*</span>
                  </label>
                  <input type="text" name="title" value="{{$post->title}}" required
                    class="w-full rounded-md border-gray-300 focus:ring-blue-500 focus:border-blue-500 p-2">
                  @error('title')
                    <p class="text-red-500 text-xs italic mt-4">
                      {{ $message }}
                    </p>
                  @enderror
                </div>

                {{-- Description --}}
                <div>
                  <label class="block text-sm font-bold text-gray-700 mb-2">
                    Description
                  </label>
                  <textarea name="description" id="textarea" rows="5"
                    class="w-full rounded-md border-gray-300 focus:ring-blue-500 focus:border-blue-500 p-2">
                  {{ $post->description }}
                  </textarea>
                  @error('description')
                    <p class="text-red-500 text-xs italic mt-4">
                      {{ $message }}
                    </p>
                  @enderror
                </div>
              </div>
            </fieldset>
            {{-- Section 2: Organization --}}
            <fieldset class="border border-gray-300 rounded-md p-5 bg-gray-50/50">
              <legend
                class="text-lg font-semibold text-gray-700 px-2 bg-white rounded border border-gray-200 shadow-sm">
                Organization</legend>

              <div class="space-y-6">
                {{-- Hashtags --}}
                <div class="flex flex-col w-full">
                  <label for="hashtagInput" class="block text-gray-700 text-sm font-bold mb-2">Hashtags
                    (Optional)</label>

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
                      <label for="selectedHashtag" class="block text-xs font-bold text-gray-500 mb-1">Available
                        Hashtags</label>
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

                {{-- Categories --}}
                @if($categories->isNotEmpty())
                  <div class="flex flex-col">
                    <label for="categories" class="block text-gray-700 text-sm font-bold mb-2">
                      Categories (Optional)
                    </label>
                    <select name="categories[]" id="categories" multiple
                      class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 p-2 h-32">
                      @foreach($categories as $category)
                        <option value="{{ $category->id }}"  @if($post->categories->contains($category->id)) selected @endif>{{ $category->name }}</option>
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

        </div>

        {{-- SIDEBAR --}}
        <div class="col-span-12 lg:col-span-4 space-y-6 mt-8">

          {{-- Settings --}}
          <fieldset class="border border-gray-300 rounded-md p-5 bg-gray-50/50">
            <legend class="text-lg font-semibold text-gray-700 px-2 bg-white rounded">
              Settings
            </legend>

            <x-toggle name="enabled" :checked="old('enabled', $post->allow_comments)" value="1" label="Enable Comments" />
          </fieldset>
          {{-- Submit --}}
          <div class="pt-4">
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
  @endpush
</x-layout>