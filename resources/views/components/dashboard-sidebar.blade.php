@php
  $links = [
    [
      'label' => 'Overview',
      'icon' => 'fas fa-tachometer-alt',
      'url' => route('dashboard.index'),
      'active' => request()->routeIs('dashboard.index'),
    ],
    [
      'label' => 'My posts',
      'icon' => 'fas fa-book',
      'url' => route('dashboard.posts'),
      'active' => request()->routeIs('dashboard.posts'),
      'count' => $total_posts,
    ],
    [
      'label' => 'Followers',
      'icon' => 'fas fa-users',
      'url' => route('dashboard.followers'),
      'active' => request()->routeIs('dashboard.followers'),
      'count' => $user->followers_count,
    ],
    [
      'label' => 'Pending followers',
      'icon' => 'fas fa-user-clock',
      'url' => route('dashboard.pending.followers'),
      'active' => request()->routeIs('dashboard.pending.followers'),
      'count' => $user->pending_followers_count,
    ],
    [
      'label' => 'Following',
      'icon' => 'fas fa-user-plus',
      'url' => route('dashboard.followings'),
      'active' => request()->routeIs('dashboard.followings'),
      'count' => $user->followings_count,
    ],
    [
      'label' => 'Bookmarks',
      'icon' => 'fas fa-bookmark',
      'url' => route('bookmarks'),
      'active' => request()->routeIs('bookmarks'),
    ],
    [
      'label' => 'Settings',
      'icon' => 'fas fa-cog',
      'url' => route('account'),
      'active' => request()->routeIs('info', 'account', 'account.privacy', 'two.factor.view', 'active.sessions*'),
    ],
  ];
@endphp

<div class="flex h-full flex-col">
  <a href="{{ route('profile.home', $user->username) }}"
    class="flex items-center gap-3 rounded-2xl border border-border bg-secondary/40 p-3 transition-colors hover:border-primary/40">
    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
      class="h-10 w-10 shrink-0 rounded-full border border-border object-cover">
    <div class="min-w-0">
      <p class="truncate text-sm font-semibold text-foreground">{{ $user->name }}</p>
      <p class="truncate font-mono text-[11px] text-muted-foreground">{{ '@' . $user->username }}</p>
    </div>
  </a>

  <p class="mt-6 px-3 font-mono text-[10px] uppercase tracking-[0.18em] text-muted-foreground">
    Creator studio
  </p>

  <nav class="mt-2 flex flex-col gap-1" aria-label="Dashboard sections">
    @foreach($links as $link)
      <a href="{{ $link['url'] }}" @if($link['active']) aria-current="page" @endif
        @class([
          'group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-colors',
          'bg-primary text-primary-foreground shadow-sm' => $link['active'],
          'text-muted-foreground hover:bg-secondary hover:text-foreground' => ! $link['active'],
        ])>
        <i class="{{ $link['icon'] }} w-4 text-center text-xs" aria-hidden="true"></i>
        <span class="truncate">{{ $link['label'] }}</span>
        @isset($link['count'])
          <span @class([
            'ml-auto rounded-full px-2 py-0.5 font-mono text-[10px] font-semibold',
            'bg-primary-foreground/20 text-primary-foreground' => $link['active'],
            'bg-secondary text-foreground' => ! $link['active'],
          ])>{{ number_format($link['count']) }}</span>
        @endisset
      </a>
    @endforeach
  </nav>

  <div class="mt-auto border-t border-border pt-4">
    <a href="{{ route('blog') }}"
      class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-muted-foreground transition-colors hover:bg-secondary hover:text-foreground">
      <i class="fas fa-arrow-left w-4 text-center text-xs" aria-hidden="true"></i>
      Back to site
    </a>
  </div>
</div>
