@extends('admin.partials.layout')
@section('title', 'Custom Pages | Dashboard')
@section('content')

<div class="md:ml-64 ">
  @include('admin.partials.header', [
    'linktext' => 'Manage Custom Pages',
    'route' => 'admin.custom-pages.index',
    'value' => request('search'),
    'searchColor' => 'bg-blueGray-200',
    'borderColor' => 'border-blueGray-200',
    'backgroundColor' => 'bg-gray-400'
  ])

  <div class="transform -translate-y-40 px-4">
    <div class="flex justify-end mb-6">
      <a href="{{ route('admin.custom-pages.create') }}" class="inline-flex items-center justify-center bg-gray-600 text-white py-2 px-5 rounded-lg font-bold capitalize">
        create custom page
      </a>
    </div>

    <div class="bg-white shadow rounded-xl overflow-hidden max-w-7xl">
      <x-tables.table id="tableCustomPages" :headers="['#', 'Title', 'Slug', 'Status', 'Footer', 'Updated', 'Actions']" title="Custom Pages Table" >
      @forelse($pages as $page)
      <tr>
        <td class="p-2">{{ ($pages->currentPage() - 1) * $pages->perPage() + $loop->iteration }}</td>
        <td class="p-2">{{ $page->title }}</td>
        <td class="p-2">{{ $page->slug }}</td>
        <td class="p-2">
          <i @class([
              "fas fa-circle mr-2 text-xs",
              'text-green-600' => $page->is_active === \App\Enums\CustomPageStatus::ACTIVE,
              'text-red-600' => $page->is_active === \App\Enums\CustomPageStatus::INACTIVE,
          ])></i>
          {{ $page->is_active->label() }}
        </td>
        <td class="p-2">{{ $page->show_in_footer ? 'Yes' : 'No' }}</td>
        <td class="p-2">{{ $page->updated_at->diffForHumans() }}</td>
        <td class="text-white p-2">
          <div class="flex gap-2 justify-start">
            <form action='{{ route('admin.custom-pages.delete', $page->id) }}' method="POST" onsubmit="return confirm('Are you sure you want to delete this page?');">
              @csrf
              @method('delete')
              <button type="submit" class="text-red-500 rounded-lg p-2 cursor-pointer hover:text-red-300">
                <i class="fas fa-trash"></i>
              </button>
            </form>

            <a href="{{ route('admin.custom-pages.edit', $page->id) }}" class="text-gray-500 rounded-lg p-2 cursor-pointer hover:text-gray-300">
              <i class="fas fa-edit"></i>
            </a>
          </div>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="7" class="p-4 text-center font-bold text-blueGray-500">No custom pages found.</td>
      </tr>
      @endforelse
      </x-tables.table>
      <div class="px-4 py-3">
        {!! $pages->links() !!}
      </div>
    </div>
  </div>
</div> 
@endsection
