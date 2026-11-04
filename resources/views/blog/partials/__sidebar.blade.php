<div id="sidebar-menu"
  class="fixed inset-0 z-50 hidden bg-foreground/50 opacity-0 backdrop-blur-sm transition-opacity duration-300 ease-in-out">

  <div id="content-menu"
    class="relative ml-auto h-full w-[85vw] max-w-sm translate-x-full overflow-y-auto bg-background shadow-2xl transition-transform duration-300 ease-in-out sm:w-96">

    <div class="sticky top-0 z-10 flex items-center justify-between border-b border-border bg-background px-5 py-4">
      <p class="font-display text-lg font-semibold text-foreground">Browse</p>
      <button id="close-menu" type="button" title="Close menu" aria-label="Close sidebar menu"
        class="flex h-9 w-9 items-center justify-center rounded-full border border-border bg-card text-muted-foreground transition-colors hover:bg-secondary hover:text-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
        <i class="fas fa-times" aria-hidden="true"></i>
      </button>
    </div>

    <div class="space-y-4 p-5">
      @include('blog.popular-tags')
      @include('blog.categories')
      @include('blog.latest-news')
      @include('blog.whotofollow')
    </div>
  </div>
</div>

@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const openSidebar = document.getElementById('open-sidebar');
      const closeSidebar = document.getElementById('close-menu');
      const contentMenu = document.getElementById('content-menu');
      const sidebarMenu = document.getElementById('sidebar-menu');
      const footer = document.querySelector('footer');

      if (!openSidebar || !closeSidebar || !contentMenu || !sidebarMenu) return;

      const showSidebar = () => {
        document.body.classList.add('no-scroll');

        sidebarMenu.classList.remove('hidden');
        requestAnimationFrame(() => {
          sidebarMenu.classList.add('opacity-100');
          sidebarMenu.classList.remove('opacity-0');
          contentMenu.classList.remove('translate-x-full');
          contentMenu.classList.add('translate-x-0');
        });
      };

      const hideSidebar = () => {
        document.body.classList.remove('no-scroll');

        sidebarMenu.classList.remove('opacity-100');
        sidebarMenu.classList.add('opacity-0');
        contentMenu.classList.remove('translate-x-0');
        contentMenu.classList.add('translate-x-full');

        setTimeout(() => {
          sidebarMenu.classList.add('hidden');
        }, 300);
      };

      openSidebar.addEventListener('click', showSidebar);
      closeSidebar.addEventListener('click', hideSidebar);

      sidebarMenu.addEventListener('click', (event) => {
        if (event.target === sidebarMenu) {
          hideSidebar();
        }
      });

      document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !sidebarMenu.classList.contains('hidden')) {
          hideSidebar();
        }
      });

      // Hide the floating menu button when the footer enters the viewport.
      if (footer && 'IntersectionObserver' in window) {
        const footerObserver = new IntersectionObserver((entries) => {
          entries.forEach((entry) => {
            openSidebar.classList.toggle('hidden', entry.isIntersecting);
          });
        }, { threshold: 0.01 });

        footerObserver.observe(footer);
      }
    });
  </script>
@endpush
