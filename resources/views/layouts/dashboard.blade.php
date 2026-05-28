<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Dashboard | {{ $user->name }}</title>
  <link rel="stylesheet" href="{{asset('assets/vendor/@fortawesome/fontawesome-free/css/all.min.css')}}" />
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

html,body{
  font-family: 'Poppins',sans-serif !important;
  overflow-x: hidden;
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

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
<body class="min-h-screen bg-gray-100 overflow-hidden">
  @yield('content')
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
        button.classList.remove('active', 'bg-blue-500', 'text-white');
        button.classList.add('bg-gray-200');
      });

      btn.classList.add('active', 'bg-blue-500', 'text-white');
      btn.classList.remove('bg-gray-200');

      menu.querySelectorAll('.notification-item').forEach((item) => {
        item.style.display = filter === 'all' || item.dataset.type === filter ? 'flex' : 'none';
      });
    });
  });
  </script>
  @stack('scripts')
</body>
</html>