<div id="confirmationModel"
  class="fixed inset-0 z-30 flex hidden items-center justify-center bg-foreground/40 p-4 backdrop-blur-sm">
  <div class="flex max-h-[90vh] w-full max-w-2xl flex-col overflow-hidden rounded-2xl border border-border bg-card shadow-2xl">
    <div class="flex items-center justify-between gap-4 border-b border-border px-5 py-4">
      <div class="flex items-center gap-3">
        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-accent text-accent-foreground">
          <i class="fas fa-qrcode"></i>
        </span>
        <h2 class="font-display text-base font-semibold text-foreground">Confirm two-factor authentication</h2>
      </div>
      <button id="closeConfirmation" type="button" aria-label="Close"
        class="flex h-9 w-9 items-center justify-center rounded-xl text-muted-foreground transition-colors hover:bg-secondary hover:text-foreground">
        <i class="fas fa-times"></i>
      </button>
    </div>

    <div class="overflow-y-auto p-5 sm:p-6">
      <p class="font-display text-base font-semibold text-foreground">Verify authentication code</p>
      <p class="mt-1 text-sm text-muted-foreground">Enter the 6-digit code from your authenticator app.</p>
      <form id="confirm2faBtn">
        @csrf
        <input type="hidden" name="code">
        <div class="mt-5 flex justify-center gap-2">
          @for ($i = 0; $i < 6; $i++)
            <input type="text" inputmode="numeric" maxlength="1" autocomplete="off"
              class="twofa-input h-12 w-12 rounded-xl border border-border bg-background text-center font-mono text-xl font-bold text-foreground outline-none transition-colors focus:border-primary focus:ring-2 focus:ring-ring/30">
          @endfor
        </div>
      </form>
      <div class="mt-5 flex flex-wrap gap-3">
        <button form="confirm2faBtn" type="submit"
          class="inline-flex items-center gap-2 rounded-xl bg-primary px-4 py-2.5 text-xs font-bold uppercase tracking-wide text-primary-foreground shadow-sm transition-opacity hover:opacity-90 disabled:cursor-not-allowed disabled:opacity-60">
          <i class="fas fa-check"></i>
          Confirm
        </button>
        <button id="cancel" type="button"
          class="inline-flex items-center gap-2 rounded-xl border border-destructive/30 bg-destructive/10 px-4 py-2.5 text-xs font-bold uppercase tracking-wide text-destructive transition-colors hover:bg-destructive/15">
          <i class="fas fa-times"></i>
          Cancel
        </button>
      </div>
    </div>
  </div>
</div>
