<div class="grid gap-4 sm:grid-cols-3">
  <div class="rounded-lg border border-gray-200 p-5">
    <div class="flex items-center gap-2">
      <i class="fas fa-check-circle text-green-500"></i>
      <p class="text-sm font-medium text-gray-500">Published</p>
    </div>
    <p class="mt-2 text-3xl font-bold text-gray-900">{{ $total_published_posts ?? 0 }}</p>
  </div>
  <div class="rounded-lg border border-gray-200 p-5">
    <div class="flex items-center gap-2">
      <i class="fas fa-clock text-yellow-500"></i>
      <p class="text-sm font-medium text-gray-500">Pending Review</p>
    </div>
    <p class="mt-2 text-3xl font-bold text-gray-900">{{ $total_pending_posts ?? 0 }}</p>
  </div>
  <div class="rounded-lg border border-gray-200 p-5">
    <div class="flex items-center gap-2">
      <i class="fas fa-file text-blue-500"></i>
      <p class="text-sm font-medium text-gray-500">Draft</p>
    </div>
    <p class="mt-2 text-3xl font-bold text-gray-900">{{ $total_draft_posts ?? 0 }}</p>
  </div>
  {{-- <div class="rounded-lg border border-gray-200 p-5">
    <div class="flex items-center gap-2">
      <i class="fas fa-clock text-purple-500"></i>
      <p class="text-sm font-medium text-gray-500">Scheduled</p>
    </div>
    <p class="mt-2 text-3xl font-bold text-gray-900">{{ $total_scheduled_posts ?? 0 }}</p>
  </div> --}}
  <div class="rounded-lg border border-gray-200 p-5">
    <div class="flex items-center gap-2">
      <i class="fas fa-heart text-red-500"></i>
      <p class="text-sm font-medium text-gray-500">Total Likes</p>
    </div>
    <p class="mt-2 text-3xl font-bold text-gray-900">{{ $total_likes ?? 0 }}</p>
  </div>
  <div class="rounded-lg border border-gray-200 p-5">
    <div class="flex items-center gap-2">
      <i class="fas fa-eye text-indigo-500"></i>
      <p class="text-sm font-medium text-gray-500">Profile Views</p>
    </div>
    <p class="mt-2 text-3xl font-bold text-gray-900">{{ $user->profile_views_count ?? 0 }}</p>
  </div>
  <div class="rounded-lg border border-gray-200 p-5">
    <div class="flex items-center gap-2">
      <i class="fas fa-signal text-indigo-500"></i>
      <p class="text-sm font-medium text-gray-500">Post Views</p>
    </div>
    <p class="mt-2 text-3xl font-bold text-gray-900">{{ $total_post_views ?? 0 }}</p>
  </div>
  <div class="rounded-lg border border-gray-200 p-5">
    <div class="flex items-center gap-2">
      <i class="fas fa-users text-green-500"></i>
      <p class="text-sm font-medium text-gray-500">Followers</p>
    </div>
    <p class="mt-2 text-3xl font-bold text-gray-900">{{ $user->followers_count ?? 0 }}</p>
  </div>
  <div class="rounded-lg border border-gray-200 p-5">
    <div class="flex items-center gap-2">
      <i class="fas fa-users text-yellow-500"></i>
      <p class="text-sm font-medium text-gray-500">Pending Followers</p>
    </div>
    <p class="mt-2 text-3xl font-bold text-gray-900">{{ $user->pending_followers_count ?? 0 }}</p>
  </div>
  <div class="rounded-lg border border-gray-200 p-5">
    <div class="flex items-center gap-2">
      <i class="fas fa-user-friends text-blue-500"></i>
      <p class="text-sm font-medium text-gray-500">Following</p>
    </div>
    <p class="mt-2 text-3xl font-bold text-gray-900">{{ $user->followings_count ?? 0 }}</p>
  </div>
</div>