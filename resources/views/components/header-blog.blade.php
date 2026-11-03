@php
    $isTransparent = Route::is(['profile.*']);
    $isFixed = Route::is(['blog', 'news', 'viewhashtag', 'viewcategory']);
    $isHome = Route::is('home');

    $navItems = [
        ['label' => 'home', 'href' => route('home'), 'active' => Route::is('home')],
        ['label' => 'blog', 'href' => route('blog'), 'active' => Route::is('blog')],
        ['label' => 'news', 'href' => route('news'), 'active' => Route::is('news')]
    ];
@endphp

<header
    id="blog-navigation"
    @class([
        'w-full',
        'border-b border-border bg-background' => ! $isTransparent,
        'absolute z-50 border-transparent bg-transparent' => $isTransparent,
        'fixed top-0 z-50' => $isFixed,
    ])
>
    <div class="mx-auto flex w-full max-w-6xl items-center justify-between gap-6 px-6 py-4">

        <x-logo />

        <nav class="hidden items-center gap-6 font-mono text-xs lg:flex" aria-label="Primary">
            @foreach($navItems as $item)
                <a
                    href="{{ $item['href'] }}"
                    @class([
                        'transition-colors',
                        'font-medium text-foreground' => $item['active'],
                        'text-muted-foreground hover:text-foreground' => ! $item['active'],
                    ])
                    @if($item['active']) aria-current="page" @endif
                >
                    {{ $item['label'] }}
                </a>
            @endforeach
            <x-search-button isFeed=true/>
        </nav>

        <div class="flex items-center gap-4">
            @guest
                <x-guest-links />
            @else
                <x-desktop-nav :transparent="false" />
                <x-mobile-nav :transparent="false" />
            @endguest
        </div>

    </div>
</header>
