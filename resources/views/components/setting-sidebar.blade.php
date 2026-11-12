@php
    $settingsNav = [
      [
        'label' => 'Profile details',
        'icon' => 'fas fa-user',
        'url' => route('info'),
        'active' => request()->routeIs('info'),
      ],
      [
        'label' => 'Account management',
        'icon' => 'fas fa-cog',
        'url' => route('account'),
        'active' => request()->routeIs('account'),
      ],
      [
        'label' => 'Account privacy',
        'icon' => 'fas fa-lock',
        'url' => route('account.privacy'),
        'active' => request()->routeIs('account.privacy'),
      ],
      [
        'label' => 'Two-factor auth',
        'icon' => 'fas fa-shield-alt',
        'url' => route('two.factor.view'),
        'active' => request()->routeIs('two.factor.view'),
      ],
      [
        'label' => 'Active sessions',
        'icon' => 'fas fa-desktop',
        'url' => route('active.sessions'),
        'active' => request()->routeIs('active.sessions*'),
      ],
    ];
  @endphp
<aside id="mobileSidebar"
        class="fixed inset-y-0 left-0 z-40 w-72 -translate-x-full overflow-y-auto border-r border-border bg-card p-4 shadow-xl transition-transform duration-300 lg:sticky lg:top-24 lg:z-auto lg:h-fit lg:translate-x-0 lg:rounded-2xl lg:border lg:p-3 lg:shadow-none">
        <div class="mb-4 flex items-center gap-3 rounded-xl border border-border bg-secondary/40 p-3">
          <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
            class="h-10 w-10 shrink-0 rounded-full border border-border object-cover">
          <div class="min-w-0">
            <p class="truncate text-sm font-semibold text-foreground">{{ $user->name }}</p>
            <p class="truncate font-mono text-[11px] text-muted-foreground">{{ '@' . $user->username }}</p>
          </div>
        </div>

        <p class="px-3 font-mono text-[10px] uppercase tracking-[0.18em] text-muted-foreground">User settings</p>

        <nav class="mt-2 flex flex-col gap-1" aria-label="Settings sections">
          @foreach($settingsNav as $item)
            <a href="{{ $item['url'] }}" @if($item['active']) aria-current="page" @endif
              @class([
                'flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-colors',
                'bg-primary text-primary-foreground shadow-sm' => $item['active'],
                'text-muted-foreground hover:bg-secondary hover:text-foreground' => ! $item['active'],
              ])>
              <i class="{{ $item['icon'] }} w-4 text-center text-xs" aria-hidden="true"></i>
              <span class="truncate">{{ $item['label'] }}</span>
            </a>
          @endforeach
        </nav>

        <div class="mt-6 border-t border-border pt-3">
          <button type="button"
            onclick="if (history.length > 1) { history.back(); } else { window.location.href='{{ route('profile.home', $user->username) }}'; }"
            class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-muted-foreground transition-colors hover:bg-secondary hover:text-foreground">
            <i class="fas fa-arrow-left w-4 text-center text-xs" aria-hidden="true"></i>
            Back
          </button>
        </div>
      </aside>