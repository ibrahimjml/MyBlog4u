@props([
    'user',
    'section' => 'home',
    'postcount' => 0,
    'likescount' => 0,
    'followerscount' => 0,
    'followingscount' => 0,
    'status' => null,
])

@php
    $isOwner = auth()->check() && auth()->user()->is($user);

    $tabs = [
        ['section' => 'home', 'label' => 'Home', 'icon' => 'fas fa-home', 'url' => route('profile.home', ['user' => $user->username])],
        ['section' => 'activity', 'label' => 'Activity', 'icon' => 'fas fa-terminal', 'url' => route('profile.activity', ['user' => $user->username])],
        ['section' => 'about', 'label' => 'About me', 'icon' => 'far fa-user', 'url' => route('profile.aboutme', ['user' => $user->username])],
    ];

    $stats = [
        [  
           'label' => Str::plural('Post', $postcount),
           'title' => Number::format($postcount). ' ' . Str::plural('Post', $postcount),
           'value' => $postcount
       ],
       [ 
           'label' => Str::plural('Like', $postcount), 
           'title' => Number::format($likescount). ' ' . Str::plural('Like', $likescount),
           'value' => $likescount
        ],
        [ 
            'label' => Str::plural('Follower', $postcount), 
            'title' => Number::format($followerscount). ' ' . Str::plural('Follower', $followerscount),
            'value' => $followerscount, 
            'id' => 'followers-count'
        ],
        [ 
            'label' => Str::plural('Following', $postcount), 
            'title' => Number::format($followingscount). ' ' . Str::plural('Following', $followingscount),
            'value' => $followingscount
        ],
    ];

    $iconButton = 'inline-flex h-9 w-9 items-center justify-center rounded-lg border border-border bg-card text-muted-foreground transition-colors hover:border-primary hover:text-primary';
@endphp

<div class="mx-auto w-full max-w-6xl px-4 sm:px-6">
    <nav aria-label="Breadcrumb" class="flex flex-wrap items-center gap-2 py-4 font-mono text-[11px] text-muted-foreground">
        <a href="{{ route('home') }}" class="transition-colors hover:text-foreground">~/home</a>
        <span aria-hidden="true">/</span>
        <span class="text-foreground">{{ '@' . $user->username }}</span>
    </nav>

    <div class="relative">
        <div class="relative h-40 overflow-hidden rounded-2xl border border-border bg-secondary sm:h-48">
            <img src="{{ $user->cover }}" alt="{{ $user->name }} cover"
                class="h-full w-full object-cover blur-md transition-all duration-700 ease-out"
                onload="this.classList.remove('blur-md')">
        </div>

        <div class="flex items-end justify-between gap-4 my-2">
            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }} avatar"
                class="relative -mt-12 ml-4 h-20 w-20 rounded-full border-4 border-background object-cover shadow-sm sm:-mt-14 sm:ml-6 sm:h-24 sm:w-24">

            <div class="relative flex items-center gap-2 pb-1">
                <button type="button" onclick="toggleShareMenu()" title="Share"
                    class="{{ $iconButton }}" aria-label="Share profile">
                    <i class="fas fa-share text-xs" aria-hidden="true"></i>
                </button>
                @include('partials.share-menu', ['top' => 'top-11', 'right' => '-right-2'])

                @if($isOwner)
                    <button type="button" onclick="openQrModal()" title="QR code" class="{{ $iconButton }}"
                        aria-label="Show QR code">
                        <i class="fas fa-qrcode text-xs" aria-hidden="true"></i>
                    </button>
                    <button type="button" id="open-viewed" title="Who viewed your profile" class="{{ $iconButton }}"
                        aria-label="Who viewed your profile">
                        <i class="fas fa-eye text-xs" aria-hidden="true"></i>
                    </button>
                    @include('profile.partials.qrcode-model')
                @elseif(auth()->check())
                        <button type="button" onclick="openReort()" title="Report" class="{{ $iconButton }}"
                            aria-label="Report profile">
                            <i class="fas fa-flag text-xs" aria-hidden="true"></i>
                        </button>
                        @include('profile.partials.report-profile')

                    <x-follow-button :status="$status" :user-id="$user->id" type="label" onclick="follow(this)"
                        class="rounded-full px-3 py-1.5 font-mono text-xs font-medium" />
                @else
                    <a href="{{ route('login') }}"
                        class="rounded-full border border-border bg-card px-3 py-1.5 font-mono text-xs font-medium text-foreground transition-colors hover:border-primary hover:text-primary">
                        Follow
                    </a>
                @endif
            </div>
        </div>
    </div>

    <div class="mt-3 flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
        <div class="min-w-0">
            <h1 class="mt-1 font-display text-4xl font-bold tracking-tight text-foreground sm:text-5xl">
                {{ $user->name }}<span class="text-primary">.</span>
            </h1>
            <p class="mt-1 font-mono text-sm text-muted-foreground">{{ '@' . $user->username }}</p>
            <p class="mt-2 max-w-2xl text-sm leading-relaxed text-primary font-mono">
                {{ $user->bio ?: 'Tell people about yourself.' }}
            </p>
        </div>

         <!-- User Stats -->
        <dl class="flex shrink-0 items-end gap-7 sm:gap-9">
            @foreach($stats as $stat)
                <div class="text-center" title="{{ $stat['title'] }}">
                    <dt class="font-mono text-[10px] uppercase tracking-[0.16em] text-muted-foreground">
                        {{ $stat['label'] }}
                    </dt>
                    <dd @if(! empty($stat['id'])) id="{{ $stat['id'] }}" @endif
                        class="mt-1 font-display text-2xl font-bold text-foreground">
                        {{ number_format((int) $stat['value']) }}
                    </dd>
                </div>
            @endforeach
        </dl>
    </div>

   <!-- tab sections -->
    <nav class="mt-6 flex items-center gap-6  border-y border-border" aria-label="Profile sections">
        @foreach($tabs as $tab)
            @php $active = $section === $tab['section']; @endphp
            <a href="{{ $tab['url'] }}" @if($active) aria-current="page" @endif
                @class([
                    '-mb-px inline-flex shrink-0 items-center gap-2 border-b-2 py-3 font-mono text-xs transition-colors',
                    'border-primary font-medium text-primary' => $active,
                    'border-transparent text-muted-foreground hover:text-foreground' => ! $active,
                ])>
                <i class="{{ $tab['icon'] }} text-[10px]" aria-hidden="true"></i>
                <span>{{ $tab['label'] }}</span>
            </a>
        @endforeach
    </nav>
</div>
