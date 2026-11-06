<div id="{{ $id }}"
  class="fixed inset-0 z-[80] hidden items-center justify-center bg-foreground/60 p-4 backdrop-blur-sm">
  <div class="relative w-full max-w-md rounded-2xl border border-border bg-background p-5 shadow-2xl">
    <p class="font-mono text-[10px] uppercase tracking-[0.22em] text-muted-foreground">Report</p>
    <h2 class="mt-1 font-display text-lg font-semibold text-foreground">What is wrong with this comment?</h2>

    <form action="{{ route('comment.report', $comment->id) }}" method="POST" class="report-form mt-4">
      @csrf
      @method('POST')

      <div class="space-y-1">
        @foreach (\App\Enums\ReportReason::commentReasons() as $reason)
          <label class="flex cursor-pointer items-center gap-3 rounded-lg px-2 py-2 text-sm text-foreground transition-colors hover:bg-secondary">
            <input type="radio" name="report_reason" value="{{ $reason->value }}" class="reason h-4 w-4 accent-primary">
            <span>{{ $reason->label() }}</span>
          </label>
        @endforeach
      </div>

      <textarea disabled name="other"
        class="other mt-3 w-full resize-y rounded-xl border border-border bg-secondary px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground disabled:cursor-not-allowed disabled:text-muted-foreground"
        rows="4" placeholder="Describe your reason if other"></textarea>

      <button disabled type="submit"
        class="submit mt-4 inline-flex items-center justify-center rounded-full bg-primary px-4 py-2 font-mono text-[10px] font-semibold uppercase tracking-[0.12em] text-primary-foreground transition-opacity hover:opacity-90 disabled:cursor-not-allowed disabled:opacity-50">
        Submit report
      </button>
    </form>

    <button type="button" title="Close"
      class="close-reports-modal absolute right-3 top-3 inline-flex h-8 w-8 items-center justify-center rounded-full text-muted-foreground transition-colors hover:bg-secondary hover:text-foreground">
      <i class="fas fa-times"></i>
    </button>
  </div>
</div>

@push('scripts')
  <script>
    function openReport(id) {
      const modal = document.getElementById(id);
      if (!modal) return;

      modal.classList.remove('hidden');
      modal.classList.add('flex');

      const closeBtn = modal.querySelector('.close-reports-modal');
      const reasons = Array.from(modal.querySelectorAll('.reason'));
      const other = modal.querySelector('.other');
      const submitBtn = modal.querySelector('.submit');

      reasons.forEach(reason => {
        reason.addEventListener('change', () => {
          submitBtn.disabled = false;

          if (reason.value === 'other') {
            other.disabled = false;
            other.focus();
          } else {
            other.disabled = true;
            other.value = '';
          }
        });
      });

      const closeHandler = () => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        closeBtn.removeEventListener('click', closeHandler);
      };

      closeBtn.addEventListener('click', closeHandler);

      modal.addEventListener('click', event => {
        if (event.target === modal) closeHandler();
      }, { once: true });
    }
  </script>
@endpush
