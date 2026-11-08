<div id="qrModal" class="fixed inset-0 z-[80] hidden items-center justify-center bg-foreground/60 p-4 backdrop-blur-sm">
  <div class="relative w-full max-w-sm rounded-2xl border border-border bg-background p-6 text-center shadow-2xl">
    <p class="font-mono text-[10px] uppercase tracking-[0.22em] text-muted-foreground">Share profile</p>
    <h2 class="mt-1 font-display text-lg font-semibold text-foreground">Scan my QR code</h2>
    <p class="mt-1 text-xs leading-relaxed text-muted-foreground">Point a camera at the code to open this profile.</p>

    <div id="qrContainer" class="mx-auto mt-5 flex w-fit items-center justify-center rounded-xl border border-border bg-card p-3">
      <img id="qrImage" src="{{ route('qr-code.image') }}" alt="QR code for {{ '@' . $user->username }}"
        class="h-48 w-48 object-contain">
    </div>

    <div class="mt-6 flex items-center justify-center gap-3">
      <a id="downloadQrBtn" href="{{ route('qr-code.image') }}" download="qr-code.png"
        class="inline-flex items-center justify-center gap-2 rounded-full bg-primary px-4 py-2 font-mono text-[10px] font-semibold uppercase tracking-[0.12em] text-primary-foreground transition-opacity hover:opacity-90">
        <i class="fas fa-download" aria-hidden="true"></i>
        Download
      </a>
      <button type="button" onclick="closeQrModal()"
        class="inline-flex items-center justify-center rounded-full border border-border bg-card px-4 py-2 font-mono text-[10px] font-semibold uppercase tracking-[0.12em] text-foreground transition-colors hover:border-primary hover:text-primary">
        Close
      </button>
    </div>

    <button type="button" onclick="closeQrModal()" title="Close"
      class="absolute right-3 top-3 inline-flex h-8 w-8 items-center justify-center rounded-full text-muted-foreground transition-colors hover:bg-secondary hover:text-foreground">
      <i class="fas fa-times"></i>
    </button>
  </div>
</div>

@push('scripts')
<script>
  function openQrModal() {
    const modal = document.getElementById('qrModal');
    if (!modal) return;

    const qrImage = modal.querySelector('#qrImage');
    const downloadBtn = modal.querySelector('#downloadQrBtn');

    if (qrImage && downloadBtn) {
      downloadBtn.href = qrImage.src;
    }

    modal.classList.remove('hidden');
    modal.classList.add('flex');
  }

  function closeQrModal() {
    const modal = document.getElementById('qrModal');
    if (!modal) return;

    modal.classList.add('hidden');
    modal.classList.remove('flex');
  }

  document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('qrModal');
    if (!modal) return;

    modal.addEventListener('click', function (event) {
      if (event.target === modal) closeQrModal();
    });

    document.addEventListener('keydown', function (event) {
      if (event.key === 'Escape') closeQrModal();
    });
  });
</script>
@endpush
