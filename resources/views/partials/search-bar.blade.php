<form action="{{route('blog.search')}}" id="search-form"  method="GET" class="w-full">
   @if(request()->has('sort'))
    <input type="hidden" name="sort" value="{{ request('sort') }}">
  @endif
  <div class="relative">
    <input type="text" name="search" 
    id="search-input"
    placeholder="Search posts & tags..."
    value="{{$searchquery ?? ''}}"
    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-black focus:border-transparent placeholder:text-gray-400 text-sm transition-all duration-200"
    />
    <i class="fas fa-search absolute right-3 top-3 text-gray-400 text-sm pointer-events-none"></i>
  </div>
</form>

@push('scripts')
    
<script>
  document.addEventListener('DOMContentLoaded', function () {

  const searchInput = document.getElementById('search-input');
  const searchForm = document.getElementById('search-form');
  const appliedFilter = document.getElementById('applied-filter');
  const resetFilterButton = document.getElementById('reset-filter');


  searchInput.addEventListener('input', function () {
  
    searchForm.submit();  
  });
});

</script>
@endpush
