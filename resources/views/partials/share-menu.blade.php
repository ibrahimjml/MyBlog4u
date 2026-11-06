@props([
  'top' => 'top-10',
  'right' => '-right-6',
])

<div id="sharemodel"
  class="absolute {{ $top }} {{ $right }} z-20 hidden h-fit w-44 space-y-0.5 rounded-xl border border-border bg-card p-1.5 shadow-xl">
  <button type="button" onclick="copyPermalink(window.location.href)"
    class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-left text-sm font-medium text-foreground transition-colors hover:bg-secondary">
    <i class="fas fa-link w-4 text-muted-foreground" aria-hidden="true"></i> Copy link
  </button>

  <a onclick="shareTo('linkedin')" href="#"
    class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-foreground transition-colors hover:bg-secondary">
    <i class="fab fa-linkedin w-4 text-muted-foreground" aria-hidden="true"></i> LinkedIn
  </a>

  <a onclick="shareTo('whatsapp')" href="#"
    class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-foreground transition-colors hover:bg-secondary">
    <i class="fab fa-whatsapp w-4 text-muted-foreground" aria-hidden="true"></i> WhatsApp
  </a>

  <a onclick="shareTo('twitter')" href="#"
    class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-foreground transition-colors hover:bg-secondary">
    <i class="fab fa-twitter w-4 text-muted-foreground" aria-hidden="true"></i> X
  </a>
</div>

@push('scripts')
  <script>
    function toggleShareMenu() {
      const menu = document.getElementById('sharemodel');
      if (menu) menu.classList.toggle('hidden');
    }
  </script>

  <script>
    function shareTo(platform) {
      const url = encodeURIComponent(window.location.href);
      let shareUrl = '#';

      switch (platform) {
        case 'linkedin':
          shareUrl = `https://www.linkedin.com/shareArticle?mini=true&url=${url}`;
          break;
        case 'whatsapp':
          shareUrl = `https://wa.me/?text=${url}`;
          break;
        case 'twitter':
          shareUrl = `https://twitter.com/intent/tweet?url=${url}`;
          break;
      }

      window.open(shareUrl, '_blank');
    }
  </script>

  <script>
    function copyPermalink(url) {
      navigator.clipboard.writeText(url).then(() => {
        toastr.options = {
          "closeButton": true,
          "progressBar": true,
          "positionClass": "toast-bottom-right",
          "timeOut": 2000
        };
        toastr.success("Copied to clipboard");
      });
    }
  </script>
@endpush
