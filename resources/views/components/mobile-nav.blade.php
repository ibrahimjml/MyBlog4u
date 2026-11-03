@props([
    'transparent' => false,
])

<div class="relative flex items-center ml-auto gap-6 lg:hidden">
       {{-- Mobile Search --}}
    <div class="lg:hidden">
        <x-search-button />
    </div>
    @unless(request()->is('admin.*'))
        <div
            data-notification-trigger
            @class([
                'relative cursor-pointer text-lg',
                'text-white' => $transparent,
                'text-gray-700' => ! $transparent,
            ])
        >
            <span
                data-notification-count
                class="notification-count absolute left-3 top-0 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 p-1 text-xs font-semibold text-white"
            >
                {{ auth()->user()->unreadNotifications->count() }}
            </span>

            <i class="fas fa-bell"></i>
        </div>

        @include('partials.notifications-menu')
    @endunless

    <img
        id="mobile-btn"
        src="{{ auth()->user()->avatar_url }}"
        class="h-[26px] w-[26px] cursor-pointer rounded-full object-cover"
        role="button"
        tabindex="0"
        aria-label="Open user menu"
    >
</div>

<div class="md:hidden">
    @include('partials.burger-menu')
</div>