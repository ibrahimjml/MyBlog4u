@props([
    'id' => null,
])

@php
    $guestLinks = [
        [
            'label' => 'Login',
            'url'   => route('login'),
            'icon'  => 'fas fa-sign-in-alt',
        ],
        [
            'label' => 'Register',
            'url'   => route('register'),
            'icon'  => 'fas fa-user-plus',
        ],
    ];

    $authLinks =  [
        [
            'label' => 'Profile',
            'url'   => route('profile.home', auth()->user()->username),
            'avatar'=> auth()->user()->avatar_url,
            'alt'   => auth()->user()->name,
            'visible' => true,
        ],
        [
            'label'   => 'Admin Panel',
            'url'     => route('admin.panel'),
            'icon'    => 'fas fa-lock',
            'visible' => auth()->user()->hasAnyRole(['Admin', 'Moderator']) || auth()->user()->hasPermission('Access'),
            'class'   => 'bg-primary/20 hover:bg-primary/40',
        ],
        [
            'label' => 'My Dashboard',
            'url'   => route('dashboard.index'),
            'icon'  => 'fas fa-tachometer-alt',
            'visible' => true,
        ],
        [
            'label' => 'Account settings',
            'url'   => route('account'),
            'icon'  => 'fas fa-cog',
            'visible' => true,
        ],
        [
            'label' => 'Blog',
            'url'   => route('blog'),
            'icon'  => 'fas fa-image',
            'visible' => true,
        ],
        [
            'label' => 'Create post',
            'url'   => route('createpage'),
            'icon'  => 'fas fa-plus',
            'visible' => true,
        ],
        [
            'label' => 'My Bookmarks',
            'url'   => route('bookmarks'),
            'icon'  => 'fas fa-bookmark',
            'visible' => true,
        ],
    ];
@endphp

<ul id="{{ $id }}"
    class="burger-menu hidden w-[250px] rounded-xl border border-border bg-card p-2 text-left font-mono text-xs leading-normal text-card-foreground shadow-xl">

    @guest
        @foreach($guestLinks as $link)
            <li>
                <a href="{{ $link['url'] }}"
                    class="flex items-center gap-3 rounded-lg px-2.5 py-2 text-muted-foreground transition-colors hover:bg-secondary hover:text-foreground">
                    <i class="{{ $link['icon'] }} w-4 text-center text-[11px]"></i>
                    <span>{{ $link['label'] }}</span>
                </a>
            </li>
        @endforeach
    @else
        @foreach($authLinks as $link)
            @if($link['visible'] ?? false)
                <li>
                    <a href="{{ $link['url'] }}"
                        class="flex items-center gap-3 rounded-lg px-2.5 py-2 text-muted-foreground transition-colors hover:text-foreground {{ $link['class'] ?? 'hover:bg-secondary' }}">
                        @if(!empty($link['avatar']))
                            <img src="{{ $link['avatar'] }}" alt="{{ $link['alt'] }}"
                                class="h-5 w-5 shrink-0 rounded-full object-cover">
                        @else
                            <i class="{{ $link['icon'] }} w-4 text-center text-[11px]"></i>
                        @endif
                        <span>{{ $link['label'] }}</span>
                    </a>
                </li>
            @endif
        @endforeach

        <li class="mt-1 border-t border-border pt-1">
            <form id="logoutFRM" action="{{ route('logout') }}" method="POST">@csrf</form>
            <button form="logoutFRM" type="submit"
                class="flex w-full items-center gap-3 rounded-lg px-2.5 py-2 text-left text-muted-foreground transition-colors hover:bg-secondary hover:text-foreground">
                <i class="fas fa-sign-out-alt w-4 text-center text-[11px]"></i>
                <span>Logout</span>
            </button>
        </li>
    @endguest
</ul>
