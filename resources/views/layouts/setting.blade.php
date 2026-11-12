<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Settings | {{ $user->name }}</title>
  <link rel="stylesheet" href="{{ asset('assets/vendor/@fortawesome/fontawesome-free/css/all.min.css') }}" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
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
        top: 4.5rem;
        left: 0.5rem;
        right: 0.5rem;
        max-height: calc(100vh - 5rem);
        overflow: hidden;
      }
    }
  </style>
</head>
<body class="min-h-screen bg-background font-sans text-foreground antialiased">
  <header id="header" class="sticky top-0 z-40 border-b border-border bg-background/95 backdrop-blur">
    <div class="mx-auto flex h-16 w-full max-w-6xl items-center justify-between gap-4 px-4 sm:px-6">
      <div class="flex min-w-0 items-center gap-3">
        <button id="toggleSidebar" type="button" aria-label="Open settings navigation"
            class="lg:hidden flex h-10 w-10 items-center justify-center rounded-xl border border-border text-muted-foreground transition-colors hover:border-primary hover:text-primary">
            <i class="fas fa-bars" aria-hidden="true"></i>
          </button>
        <div class="min-w-0">
          @yield('header')
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
  </header>

  <main class="mx-auto w-full max-w-6xl px-4 py-6 sm:px-6 lg:py-10">
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-[17rem_minmax(0,1fr)]">
      <div id="sidebarOverlay" class="fixed inset-0 z-30 hidden bg-foreground/30 backdrop-blur-sm lg:hidden"></div>

      <x-setting-sidebar :user="$user" /> <!-- setting-sidebar -->

      <section class="min-w-0 overflow-hidden rounded-2xl border border-border bg-card">
        <div class="p-5 sm:p-7 lg:p-8">
          @yield('content')
        </div>
      </section>
    </div>
  </main>

  <script>
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

    document.addEventListener('DOMContentLoaded', () => {
      const toggleBtn = document.getElementById('toggleSidebar');
      const header = document.getElementById('header');
      const sidebar = document.getElementById('mobileSidebar');
      const overlay = document.getElementById('sidebarOverlay');

      if (!toggleBtn || !sidebar || !overlay) return;

      const openSidebar = () => {
        header.classList.remove('sticky','top-0');
        sidebar.classList.remove('-translate-x-full');
        overlay.classList.remove('hidden');
      };

      const closeSidebar = () => {
        header.classList.add('sticky','top-0');
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
      };

      toggleBtn.addEventListener('click', (event) => {
        event.stopPropagation();
        sidebar.classList.contains('-translate-x-full') ? openSidebar() : closeSidebar();
      });

      overlay.addEventListener('click', closeSidebar);
    });
  </script>
  <!-- toastr -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  @stack('scripts')
</body>
</html>
