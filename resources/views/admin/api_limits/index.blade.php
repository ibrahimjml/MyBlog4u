@extends('admin.partials.layout')
@section('title', 'API Limits | Dashboard')
@section('content')

  <div class="md:ml-64 ">
    @include('admin.partials.header', [
      'linktext' => 'Manage API Limits',
      'route' => 'admin.api-limits.index',
      'value' => request('search'),
      'searchColor' => 'bg-blueGray-200',
      'borderColor' => 'border-blueGray-200',
      'backgroundColor' => 'bg-gray-400'
    ])
  <div class="transform -translate-y-40 px-4">
    @can('limit.create')
    <div id="createLimit" class="flex justify-end mb-6">
    <a class="inline-flex items-center justify-center bg-gray-600 text-white py-2 px-5 rounded-lg font-bold capitalize cursor-pointer">
      create API Limit
    </a>
</div>
@endcan 
   <div class="bg-white shadow rounded-xl overflow-hidden max-w-7xl mx-4">
    <x-tables.table id="tableApiLimits" :headers="['#','Route Target','Method','Limits','Description','Status','Actions']" title="API Limits Table" >
    @forelse($limits as $limit)
           <tr>       
      <td class="p-2">{{ ($limits->currentPage() - 1) * $limits->perPage() + $loop->iteration }}</td>
      <td class="p-2">
        <span class=" py-1 px-3 text-blueGray-500  text-sm rounded-md bg-blueGray-200 bg-opacity-70 font-semibold w-fit">
       {{ $limit->route_name }}
        </span>
      </td>
      <td class="p-2">
        <span class=" px-2 py-1 text-xs rounded-full bg-green-100 text-green-800 font-semibold w-fit">
       {{ $limit->method->label() }}
        </span>
      </td>
      <td class="p-2">
        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
          {{ $limit->max_attempts }} attempts / {{ $limit->time_window }} minutes
        </span>
      </td>
      <td class="p-2">{{ $limit->description }}</td>
      <td>
        <i
        @class([
            "fas fa-circle  mr-2 text-xs",
            'text-green-600' => $limit->status === \App\Enums\ApiLimits\ApiLimitStatus::ACTIVE,
            'text-red-600' => $limit->status === \App\Enums\ApiLimits\ApiLimitStatus::DISABLED,
      ])></i>
        {{ $limit->status?->label() }}
      </td>
          <td class="text-white p-2">
        <div class="flex gap-2 justify-start items-center">
            @can('limit.update')
            <form action='{{ route('admin.api-limits.toggle.status', $limit->id) }}' method="POST" >
            @csrf
            @method('PATCH')
            <button type="submit" class="{{ $limit->status === \App\Enums\ApiLimits\ApiLimitStatus::ACTIVE ? 'text-green-500' : 'text-gray-300' }} rounded-lg p-2 cursor-pointer">
              <i class="fas fa-power-off"></i>
            </button>
          </form>
        @endcan 
          @can('limit.update')
          <button type="button" class="limitEdit text-gray-500 rounded-lg p-2 cursor-pointer hover:text-gray-300"
             data-update-url="{{ route('admin.api-limits.update', $limit) }}"
             data-api-limits='@json($limitPayloads[$limit->id] ?? [])'>
            <i class="fas fa-edit"></i>
        </button>
           @endcan   
      @can('limit.delete')
          <form action='{{ route('admin.api-limits.destroy', $limit->id) }}' method="POST" onsubmit="return confirm('Are you sure you want to delete this API limit?');">
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
      <td colspan="7" class="p-4 text-center font-bold text-blueGray-500">No ads found.</td>
      </tr>
    @endforelse
    </x-tables.table>
   </div>
  </div>
  </div>
  <!-- create new limit model -->
  @include('admin.api_limits.partials.create')
  <!-- edit limit model -->
  @include('admin.api_limits.partials.edit')
  
  @push('scripts')
  <script>
    const createLimitButton = document.getElementById('createLimit');
    const closeModelButton = document.getElementById('closeModel');
    const menu = document.getElementById("LimitModel");

      if (createLimitButton && closeModelButton && menu) {
        createLimitButton.addEventListener('click', () => {
          if (menu.classList.contains('hidden')) {
            menu.classList.remove('hidden');
          }
        });

        closeModelButton.addEventListener('click', () => {
          if (menu.classList.contains('fixed')) {
            menu.classList.add('hidden');
          }
        });
      }
  </script>
  @endpush
@endsection    