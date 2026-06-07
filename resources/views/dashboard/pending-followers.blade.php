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
      <div class="ml-auto flex items-center gap-2">
        <form action="{{ route('follow.accept', $person->id) }}" method="POST">
          @csrf
          <button type="submit" class="px-2 py-1 bg-gray-500 rounded-md text-white">confirm</button>
        </form>
        <form action="{{ route('follow.reject', $person->id) }}" method="POST">
          @csrf
          @method('DELETE')
          <button type="submit" class="follow px-3 p-[4px] rounded-lg text-xs font-bold">unfollow</button>
        </form>
      </div>
    </div>
  @empty
    <p class="rounded-lg border border-gray-200 p-6 text-center text-gray-500 sm:col-span-2 xl:col-span-3">
      No pending followers yet.
    </p>
  @endforelse
</div>

<div class="mt-6">
  {{ $people->links() }}
</div>