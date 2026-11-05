<ul id="mobile-menu"
  class="burger-menu hidden w-[250px] rounded-xl border border-border bg-card p-2 text-left font-mono text-xs leading-normal text-card-foreground shadow-xl">

  @guest
    <li>
      <a href="{{ route('login') }}"
        class="flex items-center gap-3 rounded-lg px-2.5 py-2 text-muted-foreground transition-colors hover:bg-secondary hover:text-foreground">
        <i class="fas fa-sign-in-alt w-4 text-center text-[11px]"></i>
        <span>Login</span>
      </a>
    </li>
    <li>
      <a href="{{ route('register') }}"
        class="flex items-center gap-3 rounded-lg px-2.5 py-2 text-muted-foreground transition-colors hover:bg-secondary hover:text-foreground">
        <i class="fas fa-user-plus w-4 text-center text-[11px]"></i>
        <span>Register</span>
      </a>
    </li>
  @else
    <li>
      <a href="{{ route('profile.home', auth()->user()->username) }}"
        class="flex items-center gap-3 rounded-lg px-2.5 py-2 text-muted-foreground transition-colors hover:bg-secondary hover:text-foreground">
        <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}"
          class="h-5 w-5 shrink-0 rounded-full object-cover">
        <span>Profile</span>
      </a>
    </li>

    @if(auth()->user()->hasAnyRole(['Admin', 'Moderator']) || auth()->user()->hasPermission('Access'))
      <li>
        <a href="{{ route('admin.panel') }}"
          class="flex items-center gap-3 rounded-lg px-2.5 py-2 bg-primary/20 text-muted-foreground transition-colors hover:bg-primary/40 hover:text-foreground">
          <i class="fas fa-lock w-4 text-center text-[11px]"></i>
          <span>Admin Panel</span>
        </a>
      </li>
    @endif

    <li>
      <a href="{{ route('dashboard.index') }}"
        class="flex items-center gap-3 rounded-lg px-2.5 py-2 text-muted-foreground transition-colors hover:bg-secondary hover:text-foreground">
        <i class="fas fa-tachometer-alt w-4 text-center text-[11px]"></i>
        <span>My Dashboard</span>
      </a>
    </li>

    <li>
      <a href="{{ route('account') }}"
        class="flex items-center gap-3 rounded-lg px-2.5 py-2 text-muted-foreground transition-colors hover:bg-secondary hover:text-foreground">
        <i class="fas fa-cog w-4 text-center text-[11px]"></i>
        <span>Account settings</span>
      </a>
    </li>

    <li>
      <a href="/blog"
        class="flex items-center gap-3 rounded-lg px-2.5 py-2 text-muted-foreground transition-colors hover:bg-secondary hover:text-foreground">
        <i class="fas fa-image w-4 text-center text-[11px]"></i>
        <span>Blog</span>
      </a>
    </li>

    <li>
      <a href="{{ route('createpage') }}"
        class="flex items-center gap-3 rounded-lg px-2.5 py-2 text-muted-foreground transition-colors hover:bg-secondary hover:text-foreground">
        <i class="fas fa-plus w-4 text-center text-[11px]"></i>
        <span>Create post</span>
      </a>
    </li>

    <li>
      <a href="/getsavedposts"
        class="flex items-center gap-3 rounded-lg px-2.5 py-2 text-muted-foreground transition-colors hover:bg-secondary hover:text-foreground">
        <i class="fas fa-bookmark w-4 text-center text-[11px]"></i>
        <span>Saved</span>
      </a>
    </li>
  @endguest

  @unless(request()->routeIs('home'))
    <li>
      <a href="/"
        class="flex items-center gap-3 rounded-lg px-2.5 py-2 text-muted-foreground transition-colors hover:bg-secondary hover:text-foreground">
        <i class="fas fa-home w-4 text-center text-[11px]"></i>
        <span>Home</span>
      </a>
    </li>
  @endunless

  <li class="mt-1 border-t border-border pt-1">
    <form id="logoutFRM" action="{{ route('logout') }}" method="POST">@csrf</form>
    <button form="logoutFRM" type="submit"
      class="flex w-full items-center gap-3 rounded-lg px-2.5 py-2 text-left text-muted-foreground transition-colors hover:bg-secondary hover:text-foreground">
      <i class="fas fa-sign-out-alt w-4 text-center text-[11px]"></i>
      <span>Logout</span>
    </button>
  </li>
</ul>
