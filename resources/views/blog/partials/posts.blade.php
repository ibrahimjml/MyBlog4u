{{-- Applied Filter --}}
@if(($showAppliedFilter ?? true) && $searchquery)
  <div id="applied-filter" class="mt-2 text-center block">
    <span class="text-xl text-gray-600 font-medium"> You Searched For : <strong class="text-gray-800">{{ $searchquery }}</strong></span>
    <button id="reset-filter" type="button" class=" ml-2 text-xs font-bold text-white my-5 bg-red-500 hover:bg-red-600 p-2 rounded-lg"> Clear</button>
  </div>
@endif   

@foreach ($posts as $post) 
<x-postcard :post="$post" />
<x-ad-placement :ads="$ads" :position="\App\Enums\Adplacements\AdPosition::INNER_FEED" /><!-- Inner feed ads replace with google adsense-->
@endforeach

@if(($showAppliedFilter ?? true) && $searchquery)
@push('scripts')
    
<script>
  document.addEventListener('DOMContentLoaded', function () {

  const appliedFilter = document.getElementById('applied-filter');
  const resetFilterButton = document.getElementById('reset-filter');

  resetFilterButton.addEventListener('click', function (e) {
    e.preventDefault();

  window.location.href = '/blog';
});

});


</script>
@endpush
@endif