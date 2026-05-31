{{-- start trigger observer Interactions menu --}}
<div id="action-bar-trigger" class="h-[1px] w-full"></div>
<div id="action-bar"
  class=" container mx-auto mb-5 w-full sm:w-fit h-14 space-x-2 flex justify-center items-center gap-2 border-2 border-blueGray-200 rounded-full px-2 sm:px-6 py-3 text-md lg:text-2xl bg-white transition-all duration-300 z-50">
  @if($post->status === \App\Enums\PostStatus::PUBLISHED)
    @auth
      <div class="flex items-center justify-center">
        <span onclick="fetchLike(this)"
          class="cursor-pointer w-8 h-8 rounded-full flex justify-center items-center  hover:bg-gray-200 transition-bg duration-150 ">
          <i class="fa-heart like-icon {{ $post->is_liked() ? 'fas text-red-500' : 'far' }}" data-id="{{ $post->id }}"></i>
        </span>
    @else
        <div class="flex items-center justify-center">
          <span onclick="window.location.href='{{ route('login') }}'"
            class="cursor-pointer w-8 h-8 rounded-full flex justify-center items-center  hover:bg-gray-200 transition-bg duration-150 ">
            <i class="far fa-heart like-icon"></i>
          </span>
      @endauth
        <span title="view who liked" id="likes-count" class="open-view-model text-sm cursor-pointer">
          {{ $post->likes_count}}
        </span>

      </div>
  @else
      <span class="text-sm text-gray-500">--</span>
    @endif
    @if($post->allow_comments || $post->status === \App\Enums\PostStatus::PUBLISHED)
      <div class="h-4 w-px bg-gray-400"></div>
      <div class="flex items-center justify-center">
        @auth
        <span title="write a comment" id="openModel"
          class="cursor-pointer flex items-center justify-center  w-8 h-8 rounded-full   hover:bg-gray-200 transition-bg duration-150">
          <i class="far fa-comment"></i>
        </span>
        @else
        <span 
          onclick="window.location.href='{{ route('login') }}'"
          class="cursor-pointer flex items-center justify-center  w-8 h-8 rounded-full   hover:bg-gray-200 transition-bg duration-150">
          <i class="far fa-comment"></i>
        </span>
        @endauth
        <span class="text-sm">{{ $post->totalcomments_count }}</span>
      </div>
    @else
      <span class="text-sm text-gray-500">--</span>
    @endif
    @if(auth()->check() && auth()->user()->is($post->user))
      <div class="h-4 w-px bg-gray-400"></div>
      <span id="openviewsmodel"
        class="cursor-pointer w-8 h-8 rounded-full flex justify-center items-center  hover:bg-gray-200 transition-bg duration-150 ">
        <i class="far fa-eye "></i>
        <span class="text-sm ml-1">{{$post->views}}</span>
      </span>
    @endif
    <div id="divider" class="h-4 w-px bg-gray-400 hidden"></div>
    <span title="table of contents" class="open-tocmodel cursor-pointer hidden">
      <i class="fas fa-list"></i>
    </span>

    <div class="h-4 w-px bg-gray-400"></div>
    @auth
    <span title="save" onclick="savedTo(this,{{$post->id}})"
      class="cursor-pointer flex items-center justify-center w-8 h-8 rounded-full   hover:bg-gray-200 transition-bg duration-150">
      <i class="fa-bookmark bookmark-icon {{in_array($post->id, session('saved-to', [])) ? 'fas' : 'far'}}"></i>
    </span>
    @else
    <span title="save" onclick="window.location.href='{{ route('login') }}'"
      class="cursor-pointer flex items-center justify-center w-8 h-8 rounded-full   hover:bg-gray-200 transition-bg duration-150">
      <i class="far fa-bookmark bookmark-icon"></i>
    </span>
    @endauth
    <div class="h-4 w-px bg-gray-400"></div>
    <div class="relative">
      <span onclick="toggleShareMenu()"
        class="cursor-pointer flex justify-center items-center  w-8 h-8 rounded-full   hover:bg-gray-200 transition-bg duration-150">
        <i class="far fa-share-square"></i>
      </span>
      @include('partials.share-menu', ['top' => '-top-40', 'right' => '-right-8'])
    </div>
    @auth
      <div @class([
        'relative flex  items-center',
        'hidden' => auth()->user()->cannot('report', $post) && auth()->user()->is($post->user)
      ])>
        <div class="h-4 w-px bg-gray-400"></div>
        <span id="openmoremodel" title="more options"
          class=" cursor-pointer flex justify-center items-center  w-8 h-8 rounded-full  hover:bg-gray-200 transition-bg duration-150"><i
            class="fas fa-ellipsis-v"></i></span>
        {{-- more menu --}}
        @include('partials.more-menu')
      </div>
    @endauth
  </div>
  <!-- end trigger observer -->
  <div id="action-bar-end"></div>

  @push('scripts')
    {{-- observer for model inertactions --}}
    <script>
      document.addEventListener('DOMContentLoaded', () => {
        const actionBar = document.getElementById('action-bar');
        const trigger = document.getElementById('action-bar-trigger');
        const endtrigger = document.getElementById('action-bar-end');

        const positionObserver = new IntersectionObserver(entries => {
          entries.forEach(entry => {
            if (entry.isIntersecting) {
              actionBar.classList.remove('fixed-bottom', 'hidden-bottom');
            } else {
              actionBar.classList.add('fixed-bottom');
              actionBar.classList.remove('hidden-bottom');
            }
          });
        }, { threshold: 0 });

        // Observe  hide when scrolled below menu
        const endObserver = new IntersectionObserver(entries => {
          entries.forEach(entry => {
            if (entry.isIntersecting) {
              actionBar.classList.remove('hidden-bottom');
            } else {
              actionBar.classList.add('hidden-bottom');
            }
          });
        }, { threshold: 0 });

        positionObserver.observe(trigger);
        endObserver.observe(endtrigger);
      });
    </script>
  @endpush