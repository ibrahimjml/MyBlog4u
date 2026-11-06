<div id="reportsmodel"
  class="fixed inset-0 z-[80] hidden items-center justify-center bg-foreground/60 p-4 backdrop-blur-sm">
  <div class="relative w-full max-w-md rounded-2xl border border-border bg-background p-5 shadow-2xl">
    <p class="font-mono text-[10px] uppercase tracking-[0.22em] text-muted-foreground">Report</p>
    <h2 class="mt-1 font-display text-lg font-semibold text-foreground">What is wrong with this post?</h2>

    <form action="{{ route('post.report', $post->id) }}" method="POST" class="mt-4">
      @csrf
      @method('POST')

      <div class="space-y-1">
        @foreach ($reasons as $reason)
          <label class="flex cursor-pointer items-center gap-3 rounded-lg px-2 py-2 text-sm text-foreground transition-colors hover:bg-secondary">
            <input type="radio" name="report_reason" value="{{ $reason['value'] }}" class="reason h-4 w-4 accent-primary">
            <span>{{ $reason['value'] }}</span>
          </label>
        @endforeach
      </div>

      <textarea disabled id="other" name="other"
        class="mt-3 w-full resize-y rounded-xl border border-border bg-secondary px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground disabled:cursor-not-allowed disabled:text-muted-foreground"
        rows="4" placeholder="Describe your reason if other"></textarea>

      @error('other')
        <p class="mt-2 text-xs font-medium text-destructive">{{ $message }}</p>
      @enderror

      <button disabled id="submit" type="submit"
        class="mt-4 inline-flex items-center justify-center rounded-full bg-primary px-4 py-2 font-mono text-[10px] font-semibold uppercase tracking-[0.12em] text-primary-foreground transition-opacity hover:opacity-90 disabled:cursor-not-allowed disabled:opacity-50">
        Submit report
      </button>
    </form>

    <button id="close-reports-modal" type="button" title="Close"
      class="absolute right-3 top-3 inline-flex h-8 w-8 items-center justify-center rounded-full text-muted-foreground transition-colors hover:bg-secondary hover:text-foreground">
      <i class="fas fa-times"></i>
    </button>
  </div>
</div>

@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const modal = document.getElementById('reportsmodel');
      if (!modal) return;

      const radiobtns = modal.querySelectorAll('.reason');
      const textarea = modal.querySelector('#other');
      const submit = modal.querySelector('#submit');

      radiobtns.forEach(radio => {
        radio.addEventListener('change', function() {
          submit.disabled = false;

          if (this.value === 'Other' || this.value === 'other') {
            textarea.disabled = false;
            textarea.focus();
          } else {
            textarea.disabled = true;
            textarea.value = '';
          }
        });
      });
    });

    function openReort() {
      const more = document.getElementById('moremodel');
      const reportsmodel = document.getElementById('reportsmodel');
      const closereportsmodel = document.getElementById('close-reports-modal');

      if (!reportsmodel) return;

      if (more && !more.classList.contains('hidden')) more.classList.add('hidden');

      reportsmodel.classList.remove('hidden');
      reportsmodel.classList.add('flex');

      const closeHandler = () => {
        reportsmodel.classList.add('hidden');
        reportsmodel.classList.remove('flex');
        closereportsmodel.removeEventListener('click', closeHandler);
      };

      closereportsmodel.addEventListener('click', closeHandler);

      reportsmodel.addEventListener('click', event => {
        if (event.target === reportsmodel) closeHandler();
      }, { once: true });
    }
  </script>
@endpush
