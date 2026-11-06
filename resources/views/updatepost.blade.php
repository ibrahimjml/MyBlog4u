<x-layout>
  @push('vite')
    @vite(['resources/js/tinymce.js'])
  @endpush

  @php
    $labelClass = 'mb-2 block font-mono text-[11px] uppercase tracking-widest text-muted-foreground';
    $inputClass = 'w-full rounded-lg border border-border bg-background px-3 py-2.5 text-sm text-foreground placeholder:text-muted-foreground/60 focus:border-primary focus:outline-none focus:ring-2 focus:ring-ring/30';
    $errorClass = 'mt-1.5 font-mono text-[11px] text-destructive';
    $sectionClass = 'rounded-2xl border border-border bg-card p-5 md:p-6';
  @endphp

  <div class="mx-auto w-full max-w-6xl px-6 py-10 md:py-14">

    <header class="mb-8 flex flex-wrap items-end justify-between gap-4">
      <div>
        <p class="font-mono text-xs text-primary">~/edit</p>
        <h1 class="mt-2 font-display text-3xl font-bold tracking-tight md:text-4xl">Update Article</h1>
        <p class="mt-2 max-w-xl text-sm leading-relaxed text-muted-foreground">
          Edit the post, refresh its cover image, or adjust publishing settings.
        </p>
      </div>

      <a href="{{ route('dashboard.posts') }}"
        class="inline-flex items-center gap-2 rounded-full border border-border bg-card px-4 py-2 font-mono text-xs text-foreground transition-colors hover:bg-secondary">
        <i class="fas fa-arrow-left text-primary" aria-hidden="true"></i>
        back to my dashboard
      </a>
    </header>

    <div class="grid gap-6 lg:grid-cols-3">
      <div class="lg:col-span-2">
        <form id="postForm" action="{{ route('update.post', $post->slug) }}" method="POST"
          enctype="multipart/form-data" class="space-y-6">
          @csrf
          @method('PUT')

          {{-- Post details --}}
          <section id="post-details" class="{{ $sectionClass }}">
            <div class="mb-5 flex items-center gap-3">
              <span
                class="flex h-8 w-8 items-center justify-center rounded-full bg-accent font-mono text-xs text-accent-foreground">01</span>
              <div>
                <h2 class="font-display text-lg font-semibold text-foreground">Post details</h2>
                <p class="font-mono text-[11px] text-muted-foreground">Title and short summary</p>
              </div>
            </div>

            <div class="space-y-5">
              <div>
                <label for="title" class="{{ $labelClass }}">Title <span class="text-primary">*</span></label>
                <input id="title" type="text" name="title" value="{{ old('title', $post->title) }}" required
                  class="{{ $inputClass }}" placeholder="A clear, specific headline">
                @error('title')
                  <p class="{{ $errorClass }}">{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label for="short_excerpt" class="{{ $labelClass }}">Short excerpt <span class="text-primary">*</span></label>
                <textarea id="short_excerpt" name="short_excerpt" rows="4" required class="{{ $inputClass }}"
                  placeholder="One or two sentences that appear on cards and previews">{{ old('short_excerpt', $post->short_excerpt) }}</textarea>
                @error('short_excerpt')
                  <p class="{{ $errorClass }}">{{ $message }}</p>
                @enderror
              </div>
            </div>
          </section>

          {{-- Content --}}
          <section id="post-content" class="{{ $sectionClass }}">
            <div class="mb-5 flex items-center gap-3">
              <span
                class="flex h-8 w-8 items-center justify-center rounded-full bg-accent font-mono text-xs text-accent-foreground">02</span>
              <div>
                <h2 class="font-display text-lg font-semibold text-foreground">Post content</h2>
                <p class="font-mono text-[11px] text-muted-foreground">The main article body</p>
              </div>
            </div>

            <label for="textarea" class="{{ $labelClass }}">Description</label>
            <textarea name="description" id="textarea" rows="12" class="{{ $inputClass }}">{{ old('description', $post->description) }}</textarea>
            @error('description')
              <p class="{{ $errorClass }}">{{ $message }}</p>
            @enderror
          </section>

          {{-- Media --}}
          <section id="media" class="{{ $sectionClass }}">
            <div class="mb-5 flex items-center gap-3">
              <span
                class="flex h-8 w-8 items-center justify-center rounded-full bg-accent font-mono text-xs text-accent-foreground">03</span>
              <div>
                <h2 class="font-display text-lg font-semibold text-foreground">Cover image</h2>
                <p class="font-mono text-[11px] text-muted-foreground">Used on cards and search results</p>
              </div>
            </div>

            <label for="imageSelected" class="{{ $labelClass }}">Replace image</label>
            <input type="file" id="imageSelected" name="image" accept="image/*"
              class="block w-full cursor-pointer rounded-lg border border-border bg-background text-sm text-muted-foreground file:mr-4 file:border-0 file:bg-secondary file:px-4 file:py-2.5 file:font-mono file:text-xs file:text-foreground hover:file:bg-accent hover:file:text-accent-foreground @error('image') border-destructive @enderror">
            <input type="hidden" name="remove_image" id="removeImage" value="0">

            <div class="relative mt-4 w-fit {{ $post->image_path ? '' : 'hidden' }}" id="imageContainer">
              <img id="imagePreview" src="{{ $post->image_url ?? '#' }}" alt="Image preview"
                class="max-w-[280px] rounded-xl border border-border object-cover shadow-lg">
              <button type="button" id="cancelPreview"
                class="absolute -right-2 -top-2 flex h-6 w-6 items-center justify-center rounded-full bg-destructive text-destructive-foreground shadow-md transition-opacity hover:opacity-80"
                title="Remove image">&times;</button>
              <span id="fileSize"
                class="absolute bottom-2 left-2 rounded bg-foreground/80 px-2 py-1 font-mono text-[10px] text-background backdrop-blur-sm"></span>
            </div>

            @error('image')
              <p class="{{ $errorClass }}">{{ $message }}</p>
            @enderror
          </section>

          {{-- Tags & categories --}}
          <section id="tags-categories" class="{{ $sectionClass }}">
            <div class="mb-5 flex items-center gap-3">
              <span
                class="flex h-8 w-8 items-center justify-center rounded-full bg-accent font-mono text-xs text-accent-foreground">04</span>
              <div>
                <h2 class="font-display text-lg font-semibold text-foreground">Tags &amp; categories</h2>
                <p class="font-mono text-[11px] text-muted-foreground">Help readers discover this post</p>
              </div>
            </div>

            <div class="space-y-6">
              <div>
                <label for="hashtagInput" class="{{ $labelClass }}">Hashtags (optional)</label>
                <input type="hidden" name="hashtag" id="hashtagsHidden">
                <div id="tagContainer" class="mb-3 flex min-h-[2rem] flex-wrap gap-2"></div>

                <input type="text" id="hashtagInput" placeholder="Type a hashtag and press Enter"
                  class="{{ $inputClass }}">
                @error('hashtag')
                  <p class="{{ $errorClass }}">{{ $message }}</p>
                @enderror

                @if($allhashtags->isNotEmpty())
                  <div class="relative flex items-center py-5">
                    <div class="flex-grow border-t border-border"></div>
                    <span class="mx-4 flex-shrink-0 font-mono text-[10px] uppercase tracking-widest text-muted-foreground">Or select existing</span>
                    <div class="flex-grow border-t border-border"></div>
                  </div>

                  <div class="rounded-xl border border-border bg-background p-3">
                    <label for="selectedHashtag" class="{{ $labelClass }}">Available hashtags</label>
                    <select id="selectedHashtag" multiple class="{{ $inputClass }} h-32">
                      @foreach($allhashtags as $all)
                        <option value="{{ $all }}">{{ $all }}</option>
                      @endforeach
                    </select>
                    <div class="mt-3 flex flex-wrap items-center justify-between gap-2">
                      <p class="font-mono text-[11px] text-muted-foreground">Select one or more (max 5)</p>
                      <button type="button" onclick="addSelectedHashtags()"
                        class="rounded-full bg-secondary px-3 py-1.5 font-mono text-[11px] text-foreground transition-colors hover:bg-accent hover:text-accent-foreground">
                        Add selected
                      </button>
                    </div>
                  </div>
                @endif
              </div>

              @if($categories->isNotEmpty())
                <div>
                  <label for="categories" class="{{ $labelClass }}">Categories (optional)</label>
                  <select name="categories[]" id="categories" multiple class="{{ $inputClass }} h-32">
                    @foreach($categories as $category)
                      <option value="{{ $category->id }}"
                        @if(old('categories') ? in_array($category->id, old('categories', [])) : $post->categories->contains($category->id)) selected @endif>
                        {{ $category->name }}
                      </option>
                    @endforeach
                  </select>
                  <p class="mt-2 font-mono text-[11px] text-muted-foreground">Hold Ctrl/Cmd to select multiple (max 4)</p>
                  @error('categories')
                    <p class="{{ $errorClass }}">{{ $message }}</p>
                  @enderror
                </div>
              @endif
            </div>
          </section>

          {{-- Publish settings --}}
          <section id="publish-settings" class="{{ $sectionClass }}">
            <div class="mb-5 flex items-center gap-3">
              <span
                class="flex h-8 w-8 items-center justify-center rounded-full bg-accent font-mono text-xs text-accent-foreground">05</span>
              <div>
                <h2 class="font-display text-lg font-semibold text-foreground">Publish settings</h2>
                <p class="font-mono text-[11px] text-muted-foreground">Visibility and comments</p>
              </div>
            </div>

            <div class="space-y-5">
              <div>
                <label for="status" class="{{ $labelClass }}">Post status</label>
                <select name="status" id="status" class="{{ $inputClass }} cursor-pointer">
                  @foreach(\App\Enums\PostStatus::forUserCreation() as $status => $label)
                    <option value="{{ $status }}"
                      {{ old('status', $post->status->value) === $status ? 'selected' : '' }}>
                      {{ $label }}
                    </option>
                  @endforeach
                </select>
              </div>

              <div>
                <p class="{{ $labelClass }}">Allow comments</p>
                <x-toggle name="enabled" :checked="old('enabled', $post->allow_comments)" value="1"
                  label="Let users reply to this post" />
              </div>
            </div>
          </section>
        </form>
      </div>

      {{-- Sidebar --}}
      <aside class="space-y-4 lg:sticky lg:top-6 lg:self-start">
        <div class="rounded-2xl border border-border bg-card p-5">
          <div class="mb-4">
            <h2 class="font-display text-lg font-semibold text-foreground">Quick navigation</h2>
            <p class="mt-1 font-mono text-[11px] text-muted-foreground">Jump to a section</p>
          </div>

          <div class="flex flex-col gap-1">
            <a href="#post-details" data-target="post-details"
              class="quick-nav-link flex items-center gap-3 rounded-lg px-3 py-2 font-mono text-xs text-muted-foreground transition-colors hover:bg-secondary hover:text-foreground">
              <i class="fas fa-file-alt w-4 text-primary" aria-hidden="true"></i>
              Post details
            </a>
            <a href="#post-content" data-target="post-content"
              class="quick-nav-link flex items-center gap-3 rounded-lg px-3 py-2 font-mono text-xs text-muted-foreground transition-colors hover:bg-secondary hover:text-foreground">
              <i class="fas fa-align-left w-4 text-primary" aria-hidden="true"></i>
              Post content
            </a>
            <a href="#media" data-target="media"
              class="quick-nav-link flex items-center gap-3 rounded-lg px-3 py-2 font-mono text-xs text-muted-foreground transition-colors hover:bg-secondary hover:text-foreground">
              <i class="fas fa-image w-4 text-primary" aria-hidden="true"></i>
              Cover image
            </a>
            <a href="#tags-categories" data-target="tags-categories"
              class="quick-nav-link flex items-center gap-3 rounded-lg px-3 py-2 font-mono text-xs text-muted-foreground transition-colors hover:bg-secondary hover:text-foreground">
              <i class="fas fa-tags w-4 text-primary" aria-hidden="true"></i>
              Tags &amp; categories
            </a>
            <a href="#publish-settings" data-target="publish-settings"
              class="quick-nav-link flex items-center gap-3 rounded-lg px-3 py-2 font-mono text-xs text-muted-foreground transition-colors hover:bg-secondary hover:text-foreground">
              <i class="fas fa-globe w-4 text-primary" aria-hidden="true"></i>
              Publish settings
            </a>
          </div>
        </div>
        <div class="mt-2 flex items-center gap-2">
           <a href="{{ route('dashboard.posts') }}"
              class="rounded-full border border-border bg-card px-4 py-2 font-mono text-xs text-foreground transition-colors hover:bg-secondary">
              Cancel
            </a>
        <button type="submit" form="postForm"
          class="w-full rounded-full bg-primary px-5 py-3 font-mono text-xs font-semibold text-primary-foreground transition-opacity hover:opacity-90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
          Update
        </button>
        </div>
      </aside>
    </div>
  </div>

  @push('scripts')
    <script>
      window.initialTags = @json(collect(explode(',', $hashtags ?? ''))->map(fn ($tag) => trim($tag))->filter()->values());
    </script>

    <script>
      const imageSelected = document.getElementById('imageSelected');
      const imagePreview = document.getElementById('imagePreview');
      const imageContainer = document.getElementById('imageContainer');
      const cancelPreview = document.getElementById('cancelPreview');
      const fileSize = document.getElementById('fileSize');

      imageSelected.addEventListener('change', (event) => {
        document.getElementById('removeImage').value = '0';

        const file = event.target.files[0];

        if (file) {
          const reader = new FileReader();

          reader.onload = (eo) => {
            imagePreview.src = eo.target.result;
            imageContainer.classList.remove('hidden');
            fileSize.textContent = `${(file.size / (1024 * 1024)).toFixed(2)} MB / {{ image_upload_size() }} MB`;
          };

          reader.readAsDataURL(file);
        } else {
          imagePreview.src = '#';
          imageContainer.classList.add('hidden');
        }
      });

      cancelPreview.addEventListener('click', () => {
        imageSelected.value = '';
        document.getElementById('removeImage').value = '1';
        imagePreview.src = '#';
        imageContainer.classList.add('hidden');
      });
    </script>

    <script>
      (function () {
        const OFFSET = 110;
        const links = document.querySelectorAll('a.quick-nav-link');

        links.forEach((link) => {
          link.addEventListener('click', (e) => {
            e.preventDefault();
            const targetId = link.dataset.target;
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


