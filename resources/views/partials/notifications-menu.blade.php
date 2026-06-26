@php
  use App\Enums\NotificationType;
@endphp

<div style="display: none;" class="notification-menu flex flex-col w-[calc(100vw-1rem)] h-[400px] overflow-y-auto max-w-[500px] bg-white shadow-[0_10px_15px_rgba(0,0,0,0.4)] rounded-xl px-2 py-3 sm:w-[500px]">  <!-- delete all / mark all as read -->
    <div class="flex gap-3 p-3 justify-between items-center">
      <p class="text-xl font-bold text-left">Notifications</p>
      <div class="flex justify-end items-center gap-2">
  <form id="marksall" action="{{ route('notifications.readall') }}" method="GET" class="text-right mb-2">
      </form> 
      <span title="mark all read" class="text-blue-500 p-2 text-center">
        
      <button form="marksall" type="submit" ><i class="fas fa-check mr-1"></i></button>
      </span>
      @if(auth()->user()->unreadNotifications->count() > 0)
     <form action="{{ route('notifications.deleteAll') }}" method="POST" class="text-center  sm:text-right sm:w-fit sm:ml-auto">
          @csrf
          @method('DELETE')
          <button title="delete all" type="submit" class="text-sm p-2 rounded-lg text-red-500 ">
            <i class="fas fa-trash mr-1"></i>
       </button>
      </form> 
      @endif
      </div>
    
  </div>
<div class="p-2 border-b-2 border-b-gray-200">
   <div id="notification-filters" class="flex flex-wrap gap-2 mt-4">
      <button class="filter-btn active px-3 py-1 rounded-full bg-blue-500 text-white text-sm" data-type="all">All</button>
      <button class="filter-btn px-3 py-1 rounded-full bg-gray-200 hover:bg-blue-100 text-sm" data-type="{{NotificationType::LIKE->value}}">Like</button>
      <button class="filter-btn px-3 py-1 rounded-full bg-gray-200 hover:bg-blue-100 text-sm" data-type="{{NotificationType::COMMENTS->value}}">Comment</button>
      <button class="filter-btn px-3 py-1 rounded-full bg-gray-200 hover:bg-blue-100 text-sm" data-type="{{NotificationType::FOLLOW->value}}">Follow</button>
      <button class="filter-btn px-3 py-1 rounded-full bg-gray-200 hover:bg-blue-100 text-sm" data-type="{{NotificationType::REPORT->value}}">Report</button>
      <button class="filter-btn px-3 py-1 rounded-full bg-gray-200 hover:bg-blue-100 text-sm" data-type="{{NotificationType::VIEWEDPROFILE->value}}">Viewed</button>
    </div>
</div>
  <!-- notification section -->
<ul data-notification-list class="notification-list flex-1 overflow-y-auto space-y-3 min-h-12">      
      @forelse($notifications as $notification)
       @include('components.notification',['notification' => $notification])
        @empty
        <li class="text-center text-gray-500 py-6">No new notifications.</li>
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

      buttons.forEach(b => b.classList.remove('bg-blue-500', 'text-white', 'active'));
      btn.classList.add('bg-blue-500', 'text-white', 'active');

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
