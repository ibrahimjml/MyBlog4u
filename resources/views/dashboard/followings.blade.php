<div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
  @forelse($people as $person)
    <div class="flex items-center gap-3 rounded-lg border border-gray-200 p-4 hover:bg-gray-50">
      <a href="{{ route('profile.home', $person->username) }}">
        <img src="{{ $person->avatar_url }}" alt="{{ $person->name }}" class="h-12 w-12 rounded-full object-cover">
      </a>
      <div class="min-w-0">
        <p class="truncate font-semibold text-gray-900">{{ $person->name }}</p>
        <p class="truncate text-sm text-gray-500">{{ '@' . $person->username }}</p>
      </div>

      <form action="{{ route('following.unfollow', $person->id) }}" method="POST" class="ml-auto"
        onsubmit="return confirm('Are you sure you want to unfollow this user?');">
        @csrf
        <button type="submit" class="px-2 py-1 bg-gray-500 rounded-md text-white">Following</button>
      </form>
    </div>
  @empty
    <p class="rounded-lg border border-gray-200 p-6 text-center text-gray-500 sm:col-span-2 xl:col-span-3">
      No following users yet.
    </p>
  @endforelse
</div>

<div class="mt-6">
  {{ $people->links() }}
</div>