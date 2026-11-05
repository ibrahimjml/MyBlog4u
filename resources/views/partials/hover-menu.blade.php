<form id="logoutForm" action="{{ route('logout') }}" method="POST">@csrf</form>

<ul id="hiddenul2" style="display: none;"
  class="absolute right-0 top-full z-[70] mt-3 w-60 rounded-xl border border-border bg-card p-2 font-mono text-xs text-card-foreground shadow-xl">

  <li>
    <a href="{{ route('profile.home', auth()->user()->username) }}"
      class="flex items-center gap-3 rounded-lg px-2.5 py-2 text-muted-foreground transition-colors hover:bg-secondary hover:text-foreground">
      <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}"
        class="h-6 w-6 shrink-0 rounded-full object-cover">
      <span>Profile</span>
    </a>
  </li>

  <li>
    <a href="{{ route('dashboard.index') }}"
      class="flex items-center gap-3 rounded-lg px-2.5 py-2 text-muted-foreground transition-colors hover:bg-secondary hover:text-foreground">
      <i class="fas fa-tachometer-alt w-4 text-center text-[11px]"></i>
      <span>My Dashboard</span>
    </a>
  </li>

  <li>
    <a href="{{ route('createpage') }}"
      class="flex items-center gap-3 rounded-lg px-2.5 py-2 text-muted-foreground transition-colors hover:bg-secondary hover:text-foreground">
      <i class="fas fa-image w-4 text-center text-[11px]"></i>
      <span>Create Post</span>
    </a>
  </li>

  <li>
    <a href="{{ route('bookmarks') }}"
      class="flex items-center gap-3 rounded-lg px-2.5 py-2 text-muted-foreground transition-colors hover:bg-secondary hover:text-foreground">
      <i class="fas fa-bookmark w-4 text-center text-[11px]"></i>
      <span>My Bookmarks</span>
    </a>
  </li>

  <li>
    <a href="{{ route('info') }}"
      class="flex items-center gap-3 rounded-lg px-2.5 py-2 text-muted-foreground transition-colors hover:bg-secondary hover:text-foreground">
      <i class="fas fa-edit w-4 text-center text-[11px]"></i>
      <span>Profile Info</span>
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
    <a href="{{ route('admin.panel') }}"
      class="flex items-center gap-3 rounded-lg px-2.5 py-2 bg-primary/20 text-muted-foreground transition-colors hover:bg-primary/40 hover:text-foreground">
      <i class="fas fa-cog w-4 text-center text-[11px]"></i>
      <span>Admin Access</span>
    </a>
  </li>

  <li class="mt-1 border-t border-border pt-1">
    <button form="logoutForm" type="submit"
      class="flex w-full items-center gap-3 rounded-lg px-2.5 py-2 text-left text-muted-foreground transition-colors hover:bg-secondary hover:text-foreground">
      <i class="fas fa-sign-out-alt w-4 text-center text-[11px]"></i>
      <span>Logout</span>
    </button>
  </li>
</ul>
