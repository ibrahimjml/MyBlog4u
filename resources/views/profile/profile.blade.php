<x-layout>

    <x-profile.header
        :user="$user"
        :section="$section"
        :postcount="$postcount"
        :likescount="$likescount"
        :followerscount="$user->followers_count"
        :followingscount="$user->followings_count"
        :status="$authFollowings[$user->id] ?? null"
    />

    <div class="mx-auto w-full max-w-6xl px-4 pb-16 sm:px-6">
        <x-private-profile :user="$user" :status="$authFollowings[$user->id] ?? null">
            @switch($section)
                @case('home')
                    @include('profile.home', 
                        [
                            'articles' => $articles,
                            'tags' => $tags,
                            'pinned' => $pinned,
                            'pinnedTag' => $pinnedTag,
                            'userSocials' => $userSocials
                        ])
                @break

                @case('activity')
                    @include('profile.activity', [ 'activities' => $activities, 'userSocials' => $userSocials, 'publishedCount' => $postcount])
                @break

                @case('about')
                    @include('profile.aboutme', [ 'userSocials' => $userSocials])
                @break
            @endswitch
        </x-private-profile>
    </div>

    @include('profile.partials.who-viewd-profile-model', ['profileviews' => $profileviews])

    @push('scripts')
        <script>
            async function follow(button) {
                const userId = button.dataset.id;

                try {
                    const response = await fetch(`/user/${userId}/togglefollow`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                        },
                    });

                    const data = await response.json();

                    button.textContent = data.status === 1
                        ? 'Following'
                        : (data.status === 0 ? 'Requested' : 'Follow');

                    const followersCount = document.querySelector('#followers-count');
                    if (followersCount) {
                        let count = parseInt(followersCount.textContent.replace(/,/g, ''), 10) || 0;

                        if (data.status === 1) {
                            count += 1;
                        } else if (data.status === null) {
                            count = Math.max(0, count - 1);
                        }

                        followersCount.textContent = count.toLocaleString();
                    }

                    button.classList.remove(
                        'bg-gray-200', 'bg-gray-600', 'bg-yellow-500', 'text-black', 'text-white'
                    );

                    if (data.status === 1) {
                        button.classList.add('bg-gray-200', 'text-black');
                    } else if (data.status === 0) {
                        button.classList.add('bg-yellow-500', 'text-white');
                    } else {
                        button.classList.add('bg-gray-600', 'text-white');
                    }
                } catch (error) {
                    console.error(error);
                }
            }

            document.addEventListener('DOMContentLoaded', () => {
                const openButton = document.getElementById('open-viewed');
                const modal = document.getElementById('view-profile');
                const closeButton = document.getElementById('close-modal');

                if (!openButton || !modal) {
                    return;
                }

                openButton.addEventListener('click', () => {
                    modal.classList.remove('hidden');
                    document.body.classList.add('no-scroll');
                });

                closeButton?.addEventListener('click', () => {
                    modal.classList.add('hidden');
                    document.body.classList.remove('no-scroll');
                });
            });
        </script>
    @endpush

</x-layout>
