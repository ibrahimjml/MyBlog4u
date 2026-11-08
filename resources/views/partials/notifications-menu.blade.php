@php
  use App\Enums\NotificationType;
@endphp

<div style="display: none;"
  class="notification-menu flex h-[400px] w-[calc(100vw-1rem)] max-w-[500px] flex-col overflow-y-auto bg-card font-mono text-xs text-card-foreground shadow-xl sm:w-[500px]">
  <!-- delete all / mark all as read -->
  <div class="flex items-center justify-between gap-3 border-b border-border p-3">
    <p class="font-display text-base font-semibold text-foreground">Notifications</p>
    <div class="flex items-center gap-1">
      <form id="marksall" action="{{ route('notifications.readall') }}" method="GET"></form>
      <button form="marksall" type="submit" title="mark all read"
        class="rounded-full p-2 text-muted-foreground transition-colors hover:bg-secondary hover:text-foreground">
        <i class="fas fa-check"></i>
      </button>

      @if($unreadNotificationsCount > 0)
        <form action="{{ route('notifications.deleteAll') }}" method="POST">
          @csrf
          @method('DELETE')
          <button title="delete all" type="submit"
            class="rounded-full p-2 text-muted-foreground transition-colors hover:bg-secondary hover:text-destructive">
            <i class="fas fa-trash"></i>
          </button>
        </form>
      @endif
    </div>
  </div>

  <div class="border-b border-border p-2">
    <div id="notification-filters" class="flex flex-wrap gap-2">
      <button
        class="filter-btn active rounded-full bg-primary px-3 py-1 text-xs text-primary-foreground"
        data-type="all">All</button>
      <button
        class="filter-btn rounded-full bg-secondary px-3 py-1 text-xs text-muted-foreground transition-colors hover:bg-accent hover:text-accent-foreground"
        data-type="{{ NotificationType::LIKE->value }}">Like</button>
      <button
        class="filter-btn rounded-full bg-secondary px-3 py-1 text-xs text-muted-foreground transition-colors hover:bg-accent hover:text-accent-foreground"
        data-type="{{ NotificationType::COMMENTS->value }}">Comment</button>
      <button
        class="filter-btn rounded-full bg-secondary px-3 py-1 text-xs text-muted-foreground transition-colors hover:bg-accent hover:text-accent-foreground"
        data-type="{{ NotificationType::FOLLOW->value }}">Follow</button>
      <button
        class="filter-btn rounded-full bg-secondary px-3 py-1 text-xs text-muted-foreground transition-colors hover:bg-accent hover:text-accent-foreground"
        data-type="{{ NotificationType::REPORT->value }}">Report</button>
      <button
        class="filter-btn rounded-full bg-secondary px-3 py-1 text-xs text-muted-foreground transition-colors hover:bg-accent hover:text-accent-foreground"
        data-type="{{ NotificationType::VIEWEDPROFILE->value }}">Viewed</button>
    </div>
  </div>

  <!-- notification section -->
  <ul data-notification-list class="notification-list min-h-12 flex-1 space-y-2 overflow-y-auto p-2">
    @forelse($notifications as $notification)
      @include('components.notification', ['notification' => $notification])
    @empty
      <li class="py-6 text-center text-muted-foreground">No new notifications.</li>
    @endforelse
  </ul>
</div>

@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const buttons = document.querySelectorAll('.filter-btn');
      const items = document.querySelectorAll('.notification-item');

      buttons.forEach(btn => {
        btn.addEventListener('click', () => {
          buttons.forEach(b => {
            b.classList.remove('bg-primary', 'text-primary-foreground', 'active');
            b.classList.add('bg-secondary', 'text-muted-foreground');
          });

          btn.classList.remove('bg-secondary', 'text-muted-foreground');
          btn.classList.add('bg-primary', 'text-primary-foreground', 'active');

          const filter = btn.dataset.type;
          items.forEach(item => {
            if (filter === 'all' || item.dataset.type === filter) {
              item.style.display = 'flex';
            } else {
              item.style.display = 'none';
            }
          });
        });
      });
    });
  </script>
@endpush
