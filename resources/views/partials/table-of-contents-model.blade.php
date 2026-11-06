<div id="view-toc" role="dialog" aria-modal="true" aria-label="Table of contents"
  class="fixed inset-0 z-[60] hidden items-end bg-foreground/60 p-0 backdrop-blur-sm sm:items-center sm:justify-center sm:p-4">
  <div
    class="relative mx-auto flex max-h-[85vh] w-full max-w-md flex-col overflow-hidden rounded-t-3xl border border-border bg-background shadow-2xl sm:rounded-2xl">
    <div class="flex items-center justify-between border-b border-border px-5 py-4">
      <div>
        <p class="font-mono text-[10px] uppercase tracking-[0.22em] text-muted-foreground">Navigate</p>
        <h2 class="mt-1 font-display text-lg font-semibold text-foreground">On this page</h2>
      </div>

      <button id="close-toc" type="button" title="Close table of contents"
        class="inline-flex h-9 w-9 items-center justify-center rounded-full text-muted-foreground transition-colors hover:bg-secondary hover:text-foreground">
        <i class="fas fa-times"></i>
      </button>
    </div>

    <div id="toc2-container" class="overflow-y-auto p-3">
      <ul id="toc-links" class="space-y-0.5"></ul>
    </div>
  </div>
</div>

@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const modal = document.getElementById('view-toc');
      const openBtn = document.querySelector('.open-tocmodel');
      const closeBtn = document.getElementById('close-toc');
      const divider = document.getElementById('divider');
      const aside = document.getElementById('toc-aside');
      const asideList = document.getElementById('toc-aside-links');
      const modalList = document.getElementById('toc-links');
      const content = document.querySelector('.published-content');

      if (!modal || !content || !modalList) return;

      const headings = Array.from(content.querySelectorAll('h2, h3, h4'))
        .filter(heading => heading.textContent.trim().length > 0);

      if (headings.length === 0) return;

      if (openBtn) openBtn.classList.remove('hidden');
      if (divider) divider.classList.remove('hidden');
      if (aside) aside.classList.remove('hidden');

      const usedIds = new Set();
      const items = headings.map((heading, index) => {
        let id = heading.id;

        if (!id) {
          const slug = heading.textContent
            .toLowerCase()
            .trim()
            .replace(/[^\w\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .slice(0, 56);

          id = slug || `section-${index + 1}`;

          let suffix = 2;
          while (usedIds.has(id) || document.getElementById(id)) {
            id = `${slug || 'section'}-${suffix++}`;
          }

          heading.id = id;
        }

        usedIds.add(id);

        return {
          heading,
          id,
          text: heading.textContent.trim(),
          level: Number(heading.tagName.substring(1)),
        };
      });

      const createLink = (item, variant) => {
        const link = document.createElement('a');
        link.href = `#${item.id}`;
        link.dataset.tocId = item.id;
        link.textContent = item.text;

        if (variant === 'aside') {
          link.className = 'toc-aside-link flex w-full items-start border-l-2 border-transparent py-1.5 pl-4 text-left text-sm leading-snug text-muted-foreground transition-colors hover:text-foreground';
          link.style.paddingLeft = `${16 + Math.max(item.level - 2, 0) * 12}px`;
        } else {
          link.className = 'toc-modal-link block rounded-lg px-3 py-2 text-sm leading-snug text-muted-foreground transition-colors hover:bg-secondary hover:text-foreground';
          link.style.paddingLeft = `${12 + Math.max(item.level - 2, 0) * 14}px`;
        }

        link.addEventListener('click', event => {
          event.preventDefault();
          item.heading.scrollIntoView({ behavior: 'smooth', block: 'start' });
          setActive(item.id);
          history.replaceState(null, '', `#${item.id}`);
          closeModal();
        });

        return link;
      };

      if (asideList) {
        items.forEach(item => {
          const li = document.createElement('li');
          li.appendChild(createLink(item, 'aside'));
          asideList.appendChild(li);
        });
      }

      items.forEach(item => {
        const li = document.createElement('li');
        li.appendChild(createLink(item, 'modal'));
        modalList.appendChild(li);
      });

      const allLinks = Array.from(document.querySelectorAll('[data-toc-id]'));

      const setActive = id => {
        allLinks.forEach(link => {
          const isActive = link.dataset.tocId === id;

          if (link.classList.contains('toc-aside-link')) {
            link.classList.toggle('border-primary', isActive);
            link.classList.toggle('border-transparent', !isActive);
            link.classList.toggle('text-foreground', isActive);
            link.classList.toggle('text-muted-foreground', !isActive);
          } else {
            link.classList.toggle('bg-secondary', isActive);
            link.classList.toggle('text-foreground', isActive);
            link.classList.toggle('text-muted-foreground', !isActive);
          }
        });
      };

      const updateActive = () => {
        let current = items[0];

        items.forEach(item => {
          if (item.heading.getBoundingClientRect().top <= 160) {
            current = item;
          }
        });

        setActive(current.id);
      };

      const openModal = () => {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.classList.add('no-scroll');
        updateActive();
      };

      const closeModal = () => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.classList.remove('no-scroll');
      };

      if (openBtn) {
        openBtn.addEventListener('click', openModal);
      }

      if (closeBtn) {
        closeBtn.addEventListener('click', closeModal);
      }

      modal.addEventListener('click', event => {
        if (event.target === modal) closeModal();
      });

      document.addEventListener('keydown', event => {
        if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
          closeModal();
        }
      });

      window.addEventListener('scroll', updateActive, { passive: true });
      updateActive();
    });
  </script>
@endpush
