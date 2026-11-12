<div id="2faModel" class="fixed inset-0 z-30 flex hidden items-center justify-center bg-foreground/40 p-4 backdrop-blur-sm">
  <div class="flex max-h-[90vh] w-full max-w-2xl flex-col overflow-hidden rounded-2xl border border-border bg-card shadow-2xl">
    <div class="flex items-center justify-between gap-4 border-b border-border px-5 py-4">
      <div class="flex items-center gap-3">
        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-accent text-accent-foreground">
          <i class="fas fa-qrcode"></i>
        </span>
        <h2 class="font-display text-base font-semibold text-foreground">Enable two-factor authentication</h2>
      </div>
      <button id="closeModel" type="button" aria-label="Close"
        class="flex h-9 w-9 items-center justify-center rounded-xl text-muted-foreground transition-colors hover:bg-secondary hover:text-foreground">
        <i class="fas fa-times"></i>
      </button>
    </div>

    <div class="overflow-y-auto p-5 sm:p-6">
      <p class="text-sm leading-6 text-muted-foreground">To finish enabling two-factor authentication, scan the QR code or copy the setup key into your authenticator app.</p>

      <div class="mt-4 flex flex-col items-center">
        <div class="rounded-2xl border border-border bg-white p-4">
          <img src="data:image/png;base64,{{ base64_encode($qr) }}" alt="Two-factor setup QR code" class="mx-auto">
        </div>

        <button id="continue2fa" type="button"
          class="mt-5 inline-flex items-center gap-2 rounded-xl bg-primary px-4 py-2.5 text-xs font-bold uppercase tracking-wide text-primary-foreground shadow-sm transition-opacity hover:opacity-90">
          <i class="fas fa-arrow-right"></i>
          <small>Continue</small>
        </button>

        <div class="relative my-5 flex w-full items-center">
          <div class="flex-grow border-t border-border"></div>
          <span class="mx-4 flex-shrink font-mono text-[10px] uppercase tracking-wide text-muted-foreground">or enter the code manually</span>
          <div class="flex-grow border-t border-border"></div>
        </div>

        <div class="flex w-full max-w-sm items-center gap-2 rounded-xl border border-border bg-background p-2">
          <div id="secret" class="flex-grow select-all px-2 text-center font-mono text-sm text-foreground sm:text-left">
            {{ $secret }}
          </div>
          <div class="flex h-9 w-9 shrink-0 cursor-pointer items-center justify-center rounded-lg border border-border text-muted-foreground transition-colors hover:border-primary hover:text-primary" role="button" tabindex="0" aria-label="Copy setup key">
            <i class="fas fa-copy"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
