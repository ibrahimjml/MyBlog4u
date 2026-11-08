<div class="relative">
  <form action="{{ url()->current() }}" method="GET">

    <div class="relative">
      <select id="sort" name="sort" onchange="this.form.submit()"
        class="w-full cursor-pointer appearance-none rounded-full border border-border bg-card py-2 pl-4 pr-10 font-mono text-xs text-foreground shadow-sm transition-colors hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-ring sm:w-auto">
        <option value="latest" {{ $sorts == 'latest' ? 'selected' : '' }}>Latest</option>
        <option value="oldest" {{ $sorts == 'oldest' ? 'selected' : '' }}>Oldest</option>
        <option value="mostliked" {{ $sorts == 'mostliked' ? 'selected' : '' }}>Most Liked</option>
        <option value="mostviewed" {{ $sorts == 'mostviewed' ? 'selected' : '' }}>Most Viewed</option>
        <option value="followings" {{ $sorts == 'followings' ? 'selected' : '' }}>Following</option>
        <option value="featured" {{ $sorts == 'featured' ? 'selected' : '' }}>Featured</option>
        <option value="hashtagtrend" {{ $sorts == 'hashtagtrend' ? 'selected' : '' }}>Hashtag Trend</option>
      </select>
      <!-- Custom white arrow -->
    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
      <svg class="w-4 h-4 text-blueGray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path d="M19 9l-7 7-7-7" />
      </svg>
    </div>
    </div>
  </form>
</div>
