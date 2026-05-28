<div class="flex lg:flex-row flex-col lg:items-center items-start gap-2 my-5">
  <button class="block bg-blue-500 text-white w-fit font-semibold p-2 rounded-md"
    onclick="window.location.href='{{ route('createpage') }}'">
    <i class="fas fa-plus"></i> write an article
  </button>
  <x-forms.filter-form exclude="sort">
    <div class="relative w-fit">
      <select id="sort" name="sort"
        class="pl-3 pr-8 appearance-none font-bold cursor-pointer bg-blueGray-200 text-blueGray-500 border-0 text-sm rounded-lg p-2.5"
        onchange="this.form.submit()">
        <option value="">All</option>
        <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Latest</option>
        <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest</option>
        <hr class="my-2">
        @foreach (\App\Enums\PostStatus::forMyDashboard() as $status)
          <option value="{{$status->value}}" {{request('sort') === $status->value ? 'selected' : ''}}>
            {{ $status->label() }}
          </option>
        @endforeach
      </select>
      <!-- Custom white arrow -->
      <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path d="M19 9l-7 7-7-7" />
        </svg>
      </div>
    </div>
  </x-forms.filter-form>
  <!-- Clear Filters  -->
  @if(request()->Filled('sort'))
    <a href="{{ url()->current() }}"
      class="bg-red-500 hover:bg-red-600 text-white w-fit font-semibold py-2 px-4 rounded-lg text-sm transition duration-200 flex items-center gap-2">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
      </svg>
      Clear Filters
    </a>
  @endif
</div>
<div class="space-y-4">
  @forelse($posts as $post)
    <div
      class="group-data-[size=sm]/card:px-3 rounded-xl p-4 border border-gray-200 sm:p-5 flex flex-col sm:flex-row items-start sm:items-center gap-4 sm:gap-6">
      <div
        class="w-full sm:w-32 h-40 sm:h-24 rounded-xl border border-border/50 bg-muted overflow-hidden flex items-center justify-center shrink-0 shadow-inner relative">
        <img src="{{ $post->image_url }}" alt="{{ $post->title }} " class="object-cover w-full h-full">
      </div>

      <div class="flex-1 min-w-0 flex flex-col justify-center h-full space-y-2">
        <div class="flex items-center gap-2">
          <span @class([
            'w-2 h-2 rounded-full shadow-[0_0_8px_rgba(34,197,94,0.6)]',
            'bg-green-500' => $post->status === \App\Enums\PostStatus::PUBLISHED,
            'bg-yellow-500' => $post->status === \App\Enums\PostStatus::PENDING,
            'bg-blue-500' => $post->status === \App\Enums\PostStatus::DRAFT,
          ])></span>
          @if($post->categories->isNotEmpty())
            @foreach($post->categories as $category)

              <span data-slot="badge" data-variant="secondary"
                class="group/badge inline-flex h-5 w-fit shrink-0 items-center justify-center gap-1 overflow-hidden rounded-4xl border border-transparent px-2 py-0.5 whitespace-nowrap transition-all text-[10px] uppercase tracking-widest font-bold bg-primary/5 text-primary border-none">
                {{ $category->name }}
              </span>
            @endforeach
          @endif
        </div>

        <a class="font-extrabold text-lg sm:text-xl leading-tight hover:text-primary transition-colors line-clamp-2"
          href="{{ route('single.post', $post->slug) }}">
          {{ $post->title }}
        </a>

        <div class="flex items-center gap-3 text-xs font-medium text-muted-foreground">
          <span class="flex items-center gap-1.5">
            <i class="fas fa-clock w-3.5 h-3.5"></i>
            {{ $post->created_at->diffForHumans() }}
          </span>
        </div>
      </div>

      <div
        class="flex flex-row sm:flex-col items-center sm:items-end justify-between w-full sm:w-auto h-full gap-4 pt-4 sm:pt-0 border-t sm:border-none border-border/50">

        <div
          class="flex items-center gap-4 text-muted-foreground bg-muted/30 px-3 py-1.5 rounded-full border border-border/50">

          <span class="flex items-center gap-1.5 text-xs font-bold" title="Views">
            <i class="fas fa-eye w-3.5 h-3.5 text-primary"></i>
            {{ $post->views }}
          </span>

          <span class="flex items-center gap-1.5 text-xs font-bold" title="Likes">
            <i class="fas fa-heart w-3.5 h-3.5 text-red-500"></i>
            {{ $post->likes_count }}
          </span>

          <span class="flex items-center gap-1.5 text-xs font-bold" title="Comments">
            <i class="fas fa-comment w-3.5 h-3.5 text-blue-500"></i>
            {{ $post->comments_count }}
          </span>
        </div>

        <div class="flex items-center gap-1.5">

          <a data-slot="button" data-variant="ghost" data-size="icon"
            class="group/button inline-flex shrink-0 items-center justify-center bg-clip-padding text-sm font-medium whitespace-nowrap transition-all outline-none select-none size-8 h-9 w-9 rounded-full bg-background border border-border/50 hover:border-primary hover:text-primary shadow-sm"
            href={{ route('single.post', $post->slug) }}>
            <i class="fas fa-eye w-4 h-4"></i>
          </a>

          <a data-slot="button" data-variant="ghost" data-size="icon"
            class="group/button inline-flex shrink-0 items-center justify-center bg-clip-padding text-sm font-medium whitespace-nowrap transition-all outline-none select-none size-8 h-9 w-9 rounded-full bg-background border border-border/50 hover:border-blue-500 hover:text-blue-500 shadow-sm"
            href={{ route('edit.post', $post->slug) }}>
            <i class="fas fa-pen-square w-4 h-4"></i>
          </a>

          <button id="dropdownMenu-{{ $post->id }}" data-dropdown-toggle="dropdown-{{ $post->id }}" type="button"
            class="h-9 w-9 flex items-center justify-center rounded-full bg-background border border-border/50 hover:bg-muted shadow-sm outline-none transition-colors">
            <i class="fas fa-ellipsis-h w-4 h-4 text-muted-foreground"></i>
          </button>

          <div id="dropdown-{{ $post->id }}"
            class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 border-2 border-gray-500 ">
            <ul class=" text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownMenu-{{ $post->id }}">
              <li class="hover:bg-red-500 ">
                <form method="POST" action="{{ route('delete.post', $post->slug) }}"
                  onsubmit="return confirm('Are you sure you want to delete this post?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="w-full text-left px-4 py-2 text-gray-700"> <i
                      class="fas fa-trash-alt w-4 h-4 mr-4"></i> Delete Post</button>
                </form>
              </li>
              {{-- <li class="hover:bg-yellow-500">
                <a href="{{route('admin.reports.profiles.index')}}" class="block px-4 py-2  text-gray-700"><i
                    class="fas fa-thumbtack w-4 h-4 mr-4"></i> Pin Post</a>
              </li> --}}
            </ul>
          </div>
        </div>
      </div>
    </div>
  @empty
    <p class="rounded-lg border border-gray-200 p-6 text-center text-gray-500 sm:col-span-2 xl:col-span-3">No posts yet.
    </p>
  @endforelse
</div>
<div class="mt-6">
  {{ $posts->links() }}
</div>