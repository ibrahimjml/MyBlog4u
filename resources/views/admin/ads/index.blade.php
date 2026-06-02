@extends('admin.partials.layout')
@section('title', 'Ads Page | Dashboard')
@section('content')

  <div class="md:ml-64 ">
    @include('admin.partials.header', [
      'linktext' => 'Manage Ads',
      'route' => 'admin.ads.index',
      'value' => request('search'),
      'searchColor' => 'bg-blueGray-200',
      'borderColor' => 'border-blueGray-200',
      'backgroundColor' => 'bg-gray-400'
    ])
  <div class="transform -translate-y-40 px-4">
      @can('ad.create')
      <div id="createAd" class="flex justify-end mb-6">
          <a class="inline-flex items-center justify-center bg-gray-600 text-white py-2 px-5 rounded-lg font-bold capitalize">
            create Ad
          </a>
      </div>
      @endcan
 <div class="bg-white shadow rounded-xl overflow-hidden max-w-7xl mx-4">
    <x-tables.table id="tableads" :headers="['#','Ad name','Type','Position','Dimension','Status','Actions']" title="Ads Table" >
    @forelse($ads as $ad)
    @php
      $adType = \App\Enums\Adplacements\AdType::tryFrom($ad->getRawOriginal('ad_type') ?? '');
      $adPosition = \App\Enums\Adplacements\AdPosition::tryFrom($ad->getRawOriginal('ad_position') ?? '');
      $adStatus = \App\Enums\Adplacements\AdStatus::tryFrom($ad->getRawOriginal('status') ?? '');
    @endphp
    <tr>
      <td class="p-2">{{ ($ads->currentPage() - 1) * $ads->perPage() + $loop->iteration }}</td>
      <td class="p-2">
        <span class=" py-1 px-3 text-blueGray-500  text-sm rounded-md bg-blueGray-200 bg-opacity-70 font-semibold w-fit">
          {{ $ad->ad_name }}
        </span>
      </td>
      <td class="p-2">
        <span class=" py-1 px-3 text-blueGray-500  text-sm rounded-md bg-blueGray-200 bg-opacity-70 font-semibold w-fit">
          @if ($adType?->value === \App\Enums\Adplacements\AdType::CUSTOM_BANNER->value)
          <i class="fas fa-image text-orange-400 mr-2"></i>
          @else   
          <i class="fas fa-code text-blue-400 mr-2"></i>
          @endif
          {{ $adType?->label() }}
        </span>
      </td>
      <td class="p-2">
        <span class=" py-1 px-3 text-blueGray-500  text-sm rounded-md bg-blueGray-200 bg-opacity-70 font-semibold w-fit">
          {{ $adPosition?->label() }}
        </span>
      </td>
      <td class="p-2">
        <span class="py-1 px-3 text-blueGray-500  text-sm rounded-md bg-blueGray-200 bg-opacity-70 font-semibold w-fit">
          {{ $ad->ad_dimension ?? 'N/A' }}
        </span>
      </td>
      <td>
        <i
        @class([
            "fas fa-circle  mr-2 text-xs",
            'text-green-600' => $adStatus?->value === \App\Enums\Adplacements\AdStatus::ACTIVE->value,
            'text-red-600' => $adStatus?->value === \App\Enums\Adplacements\AdStatus::DISABLED->value,
      ])></i>
        {{ $adStatus?->label() ?? 'Invalid status' }}
      </td>
      <td class="text-white p-2">
        <div class="flex gap-2 justify-start items-center">
            @can('ad.update')
            <form action='{{ route('admin.ads.toggle.status', $ad->id) }}' method="POST" >
            @csrf
            @method('PATCH')
            <button type="submit" class="{{ $ad->status === \App\Enums\Adplacements\AdStatus::ACTIVE ? 'text-green-500' : 'text-gray-300' }} rounded-lg p-2 cursor-pointer">
              <i class="fas fa-power-off"></i>
            </button>
          </form>
          @endcan
          @can('ad.update')
          <button type="button" class="adEdit text-gray-500 rounded-lg p-2 cursor-pointer hover:text-gray-300"
             data-update-url="{{ route('admin.ads.update', $ad) }}"
             data-ads='@json($adPayloads[$ad->id] ?? [])'>
            <i class="fas fa-edit"></i>
        </button>
        @endcan
        @can('ad.delete')
          <form action='{{ route('admin.ads.destroy', $ad->id) }}' method="POST" onsubmit="return confirm('Are you sure you want to delete this ad?');">
            @csrf
            @method('delete')
            <button type="submit" class="text-red-500 rounded-lg p-2 cursor-pointer hover:text-red-300">
              <i class="fas fa-trash"></i>
            </button>
          </form>
          @endcan
        </div>
      </td>
    </tr>
      @empty
      <tr>
    colspan="7" class="p-4 text-center font-bold text-blueGray-500">No ads found.</td>
      </tr>
    @endforelse
    </x-tables.table>
    <div class="p-4">
      {{ $ads->links() }}
    </div>
 </div>
    </div>  
  {{-- create ad model --}}
  @include('admin.ads.partials.create')
  {{-- edit ad model --}}
  @include('admin.ads.partials.edit')
    @push('scripts')
    <script>
      const showMenuAd = document.getElementById('createAd');
      const closeMenuAd = document.getElementById('closeModel');
      const menu = document.getElementById("AdModel");

      if (showMenuAd && closeMenuAd && menu) {
        showMenuAd.addEventListener('click', () => {
          if (menu.classList.contains('hidden')) {
            menu.classList.remove('hidden');
          }
        });

        closeMenuAd.addEventListener('click', () => {
          if (menu.classList.contains('fixed')) {
            menu.classList.add('hidden');
          }
        });
      }
      </script>
     @endpush
@endsection
