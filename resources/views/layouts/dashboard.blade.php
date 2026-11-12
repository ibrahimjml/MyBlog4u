<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Dashboard | {{ $user->name }}</title>
  <link rel="stylesheet" href="{{asset('assets/vendor/@fortawesome/fontawesome-free/css/all.min.css')}}" />
  @stack('header')
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @stack('vite')
  <style>
    .notification-menu {
      display: none;
      position: absolute;
      top: 2.75rem;
      right: 0;
      line-height: 1.5;
      text-align: left;
      z-index: 50;
    }

    .notification-menu.is-open {
      display: block !important;
    }

    @media (max-width: 640px) {
      .notification-menu {
        position: fixed;
        top: 5rem;
        left: 0.5rem;
        right: 0.5rem;
        max-height: calc(100vh - 5.5rem);
        overflow: hidden;
      }
    }
  </style>
</head>
<body class="min-h-screen bg-background font-sans text-foreground antialiased overflow-hidden">
  <main class="min-h-dvh w-full lg:flex lg:gap-6 lg:p-6">
    <aside id="mobileSidebar"
      class="fixed left-0 top-0 z-30 h-dvh w-80 -translate-x-full overflow-y-auto border-r border-border bg-card p-4 shadow-xl transition-transform duration-300 lg:static lg:h-[calc(100vh-3rem)] lg:translate-x-0 lg:flex-shrink-0 lg:rounded-2xl lg:border lg:shadow-none">
      @include('components.dashboard-sidebar')
    </aside>

    <section
      class="h-dvh w-full min-w-0 overflow-y-auto bg-card lg:h-[calc(100vh-3rem)] lg:flex-1 lg:rounded-2xl lg:border lg:border-border">
      <div
        class="sticky top-0 z-20 flex h-20 items-center justify-between gap-4 border-b border-border bg-card/95 px-4 backdrop-blur sm:px-6 lg:px-8">
        <div class="flex min-w-0 items-center gap-4">
          <button id="toggleSidebar" type="button" aria-label="Open sidebar"
            class="flex h-10 w-10 items-center justify-center rounded-xl border border-border text-muted-foreground transition-colors hover:border-primary hover:text-primary lg:hidden">
            <i class="fas fa-bars" aria-hidden="true"></i>
          </button>
          <div class="min-w-0">
            <p class="font-mono text-[10px] uppercase tracking-[0.18em] text-muted-foreground">Creator dashboard</p>
            <p class="truncate font-display text-xl font-bold text-foreground">
              {{ auth()->user()->name }}'s Studio<span class="text-primary">.</span>
            </p>
          </div>
        </div>

        <div class="flex items-center gap-3">
          <div class="relative">
            <div data-notification-trigger role="button" tabindex="0" aria-label="Notifications"
              class="relative flex h-10 w-10 cursor-pointer items-center justify-center rounded-xl border border-border text-muted-foreground transition-colors hover:border-primary hover:text-primary">
              <span
                class="absolute -right-1.5 -top-1.5 flex h-5 min-w-5 items-center justify-center rounded-full bg-primary px-1 font-mono text-[10px] font-bold text-primary-foreground">
                {{ auth()->user()->unreadNotifications->count() }}
              </span>
              <i class="fas fa-bell" aria-hidden="true"></i>
            </div>
            @include('partials.notifications-menu')
          </div>

          <a href="{{ route('profile.home', $user->username) }}"
            class="block h-10 w-10 shrink-0 overflow-hidden rounded-full border-2 border-border transition-colors hover:border-primary"
            aria-label="View profile">
            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="h-full w-full object-cover">
          </a>
        </div>
      </div>

      <div class="px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
        @yield('content')
      </div>
    </section>
  </main>
  <script>
  const toggleBtn = document.getElementById('toggleSidebar');
  const sidebar = document.getElementById('mobileSidebar');

  if (toggleBtn && sidebar) {
    toggleBtn.addEventListener('click', () => {
      sidebar.classList.toggle('-translate-x-full');
    });

    document.addEventListener('click', (e) => {
      if (!sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
        if (!sidebar.classList.contains('-translate-x-full')) {
          sidebar.classList.add('-translate-x-full');
          sidebar.classList.remove('show-sidebar');
        }
      }
    });
  }

  document.querySelectorAll('[data-notification-trigger]').forEach((trigger) => {
    const notifications = trigger.nextElementSibling;

    if (!notifications || !notifications.classList.contains('notification-menu')) {
      return;
    }

    trigger.addEventListener('click', (event) => {
      event.stopPropagation();
      notifications.classList.toggle('is-open');
    });

    document.addEventListener('click', (event) => {
      if (!trigger.contains(event.target) && !notifications.contains(event.target)) {
        notifications.classList.remove('is-open');
      }
    });
  });

  document.querySelectorAll('.filter-btn').forEach((btn) => {
    btn.addEventListener('click', () => {
      const menu = btn.closest('.notification-menu');
      const filter = btn.dataset.type;

      if (!menu) {
        return;
      }

      menu.querySelectorAll('.filter-btn').forEach((button) => {
        button.classList.remove('active', 'bg-primary', 'text-primary-foreground');
        button.classList.add('bg-secondary', 'text-muted-foreground');
      });

      btn.classList.add('active', 'bg-primary', 'text-primary-foreground');
      btn.classList.remove('bg-secondary', 'text-muted-foreground');

      menu.querySelectorAll('.notification-item').forEach((item) => {
        item.style.display = filter === 'all' || item.dataset.type === filter ? 'flex' : 'none';
      });
    });
  });
  </script>
  @stack('scripts')
</body>
</html>
