<article class="container mx-auto  max-w-3xl border rounded-xl my-4 border-gray-200 bg-white grid grid-cols-1 sm:grid-cols-2 ">

  <!-- Left Section: User Info + Image + Buttons -->
  <section class="flex flex-col gap-3 p-4 lg:w-90">
    <!-- User Info -->
    <div class="flex items-center gap-4 w-full">
      <a href="{{ route('profile', $post->user->username) }}">
        <img src="{{ $post->user->avatar_url }}" class="w-8 h-8 rounded-full object-cover border" alt="avatar">
      </a>
      <div>
          <div class="flex items-center justify-center gap-3">
          <a href="{{ route('profile', $post->user->username) }}" class="text-base font-semibold hover:underline truncate">
            {{ $post->user->username }}
          </a>
          @if(auth()->user()->id !== $post->user_id)
          <strong>·</strong>
          <x-follow-button
               :status="$authFollowings[$post->user_id] ?? null"
               :user-id="$post->user_id"
               type="label"
               onclick="fetchfollow(this)"
               class="follow px-3 py-1 rounded-lg text-xs font-bold"
           /> 
         @endif
         </div>
        <p class="text-xs text-gray-500">{{ $post->created_at->diffForHumans() }}</p>
      </div>
    </div>

    <!-- Post Image -->
    <div class="relative lg:aspect-[4/2] aspect-[4/2] lg:w-full rounded-lg overflow-hidden  group">
      <a href="{{route('single.post',$post->slug)}}">
        <img loading="lazy" 
             src="{{ $post->image_url }}" 
             alt="{{ $post->title }}" 
             class=" w-full h-full object-fill blur-md bg-gray-200 shadow-md transition-all duration-700 ease-out group-hover:scale-110"
             onload="this.classList.remove('blur-md', 'bg-gray-200')">
      </a>
      @if($post->is_featured)
      <span class="absolute top-2 left-2 bg-amber-400 bg-opacity-80 text-white text-xs font-medium px-3 py-1 rounded-md">
        Featured
      </span>
      @endif
    </div>

    <!-- Interactions -->
    @if($post->categories->isNotEmpty())
    <div class="flex items-center gap-2 text-sm text-gray-600">
      <b class="text-sm">in:</b>
      @foreach($post->categories as $category)
      @php
      $isActive = isset($currentCategory) && $currentCategory->name == $category->name;
      @endphp
       <a href="{{route('viewcategory',$category->name)}}"
          @class([
            'px-2 py-1 text-xs rounded-md',
            'bg-[#f5b576] text-gray-700 font-semibold' => $category->is_featured,
            'bg-gray-600 text-white' => !$category->is_featured,
            'bg-yellow-500' => $isActive
          ])> 
       @if($category->is_featured) &#x1F525; @endif {{ $category->name }}
    </a>
    @endforeach
    </div>
    @endif
    <div class="flex justify-between items-center ">
        <div class="flex gap-2 text-xs text-gray-600">
          @if($post->likes_count)
          <span class="whitespace-nowrap">{{ $post->likes_count }} {{ Str::plural('like',$post->likes_count) }}</span>
          <b>·</b>
          @endif
          @if($post->totalcomments_count)
          <span class="whitespace-nowrap">{{$post->totalcomments_count}} {{ Str::plural('comment',$post->totalcomments_count) }}</span>
          <b>·</b>
          @endif
          <span class="whitespace-nowrap">{{$post->views}} {{ Str::plural('view',$post->views)}}</span>
        </div>
      <div class="flex items-center">
        @if (!empty($showSaveButton))
        <button onclick="unsavedposts({{ $post->id }})" class="border-2 border-red-600 text-red-600 font-semibold px-4 py-1 text-sm rounded-md hover:bg-red-50 transition">
          Remove
        </button>
        @else
          <div onclick="save(this,{{$post->id}})" class="relative inline-flex items-center justify-center text-md cursor-pointer">
      <i class="text-gray-600 {{ in_array($post->id, session('saved-to', [])) ? 'fas fa-bookmark' : 'far fa-bookmark' }}"></i>
      <span class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-[12px] pb-1 {{ in_array($post->id, session('saved-to', [])) ? 'text-white' : 'text-gray-600' }}">
        {{ in_array($post->id, session('saved-to', [])) ? '✓' : '+' }}
      </span>
      </div>
        @endif
      </div>
    </div>
  </section>
  <!-- Right Section: Title + Description + Hashtags -->
  <section class="flex flex-col gap-3 lg:mt-12 p-2">
    <a href="{{route('single.post',$post->slug)}}">
  <h2 class="text-xl font-bold text-gray-800 line-clamp-2">
    {{ $post->title }}
  </h2>
    <div class="text-gray-600 leading-relaxed text-sm line-clamp-3 mt-2">
      {{ $post->short_excerpt }}
    </div>
    </a>
    @if($post->hashtags->isNotEmpty())
    <div class="flex flex-wrap gap-2 mt-1">
      @foreach($post->hashtags as $hashtag)
        @php
        $isActive = isset($currentHashtag) && $currentHashtag->name == $hashtag->name;
        @endphp
      <a href="{{ route('viewhashtag', $hashtag->name) }}" 
         @class([
          'px-2 py-1 text-xs rounded-md font-semibold', 
          'bg-[#f5b576] text-gray-700' => $hashtag->is_featured,
          'bg-blueGray-200 text-blueGray-500' => !$hashtag->is_featured,
          'bg-yellow-400' => $isActive
           ])>
        @if($hashtag->is_featured) &#x1F525; @else &#x23; @endif {{ $hashtag->name }}
      </a>
      @endforeach
    </div>
    @endif
  </section>
</article>

@push('scripts')
<script src="{{asset('js/savepost.js')}}"></script>
@endpush