@props([
    'transparent' => false,
])

<ul class="relative hidden items-center space-x-6 md:flex">

    {{-- User dropdown --}}
    <li
        id="dropdown"
        @class([
            'relative pt-2 text-lg',
            'text-white' => $transparent,
            'text-gray-700' => ! $transparent,
        ])
    >
        @include('partials.hover-menu')

        <span class="flex cursor-pointer items-center gap-2 font-mono text-xs text-foreground">
            <img
              src="{{ auth()->user()->avatar_url }}"
              class="h-[26px] w-[26px] rounded-full object-cover"
            >
            {{ auth()->user()->name }}
            <i class="fas fa-angle-down ml-1"></i>
        </span>
    </li>

    {{-- Notifications --}}
    @unless(request()->is('admin.*'))
        <li
            id="hover-notification"
            data-notification-trigger
            @class([
                'relative cursor-pointer pt-2 text-lg',
                'text-white' => $transparent,
                'text-gray-700' => ! $transparent,
            ])
        >
            <span
                data-notification-count
                class="notification-count absolute top-2 left-3 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 p-1 text-xs font-semibold text-white"
            >
                {{ auth()->user()->unreadNotifications->count() }}
            </span>

            <i class="fas fa-bell"></i>
        </li>

        @include('partials.notifications-menu')
    @endunless

</ul>