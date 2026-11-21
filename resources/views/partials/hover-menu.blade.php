@php
    $authLinks =  [
        [
            'label'   => 'Profile',
            'url'     => route('profile.home', auth()->user()->username),
            'avatar'  => auth()->user()->avatar_url,
            'alt'     => auth()->user()->name,
            'visible' => true,
        ],
        [
            'label'   => 'My Dashboard',
            'url'     => route('dashboard.index'),
            'icon'    => 'fas fa-tachometer-alt',
            'visible' => true,
        ],
        [
            'label'   => 'Create Post',
            'url'     => route('createpage'),
            'icon'    => 'fas fa-image',
            'visible' => true,
        ],
        [
            'label'   => 'My Bookmarks',
            'url'     => route('bookmarks'),
            'icon'    => 'fas fa-bookmark',
            'visible' => true,
        ],
        [
            'label'   => 'Profile Info',
            'url'     => route('info'),
            'icon'    => 'fas fa-edit',
            'visible' => true,
        ],
        [
            'label'   => 'Account settings',
            'url'     => route('account'),
            'icon'    => 'fas fa-cog',
            'visible' => true,
        ],
        [
            'label'   => 'Admin Access',
            'url'     => route('admin.panel'),
            'icon'    => 'fas fa-cog',
            'visible' => auth()->user()->hasAnyRole(['Admin', 'Moderator']) || auth()->user()->hasPermission('Access'),
            'class'   => 'bg-primary/20 hover:bg-primary/40',
        ],
    ];
@endphp

@auth
    <form id="logoutForm" action="{{ route('logout') }}" method="POST">@csrf</form>

    <ul id="hiddenul2" style="display: none;"
        class="absolute right-0 top-full z-[70] mt-3 w-60 rounded-xl border border-border bg-card p-2 font-mono text-xs text-card-foreground shadow-xl">

        @foreach($authLinks as $link)
            @if($link['visible'] ?? false)
                <li>
                    <a href="{{ $link['url'] }}"
                        class="flex items-center gap-3 rounded-lg px-2.5 py-2 text-muted-foreground transition-colors hover:text-foreground {{ $link['class'] ?? 'hover:bg-secondary' }}">
                        @if(!empty($link['avatar']))
                            <img src="{{ $link['avatar'] }}" alt="{{ $link['alt'] }}"
                                class="h-6 w-6 shrink-0 rounded-full object-cover">
                        @else
                            <i class="{{ $link['icon'] }} w-4 text-center text-[11px]"></i>
                        @endif
                        <span>{{ $link['label'] }}</span>
                    </a>
                </li>
            @endif
        @endforeach

        <li class="mt-1 border-t border-border pt-1">
            <button form="logoutForm" type="submit"
                class="flex w-full items-center gap-3 rounded-lg px-2.5 py-2 text-left text-muted-foreground transition-colors hover:bg-secondary hover:text-foreground">
                <i class="fas fa-sign-out-alt w-4 text-center text-[11px]"></i>
                <span>Logout</span>
            </button>
        </li>
    </ul>
@endauth
