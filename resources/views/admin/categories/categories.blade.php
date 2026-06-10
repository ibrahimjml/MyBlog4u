@extends('admin.partials.layout')
@section('title', 'Categories Page | Dashboard')
@section('content')

<div class="md:ml-64 ">
  @include('admin.partials.header', [
         'linktext'         => 'Categories Table',
         'route'            => 'admin.categories.index', 
         'value'            => request('search'),
         'searchColor'      => 'bg-blueGray-200',
         'borderColor'      => 'border-blueGray-200',
         'backgroundColor'  => 'bg-gray-400'
     ])

<div class="flex justify-between transform -translate-y-40 px-4">
  <div class="flex gap-3 w-fit">
    @include('admin.categories.partials.filter')
  </div>
@can('category.create')
  <button id="openCatModel"
        class="text-center ml-0 mr-2 sm:ml-auto w-30 h-10 text-sm lg:text-lg bg-gray-600  text-white py-2 px-5 rounded-lg font-bold capitalize mb-6">create
    category
  </button>
  @endcan
</div>
   <div class="bg-white shadow rounded-xl overflow-hidden max-w-7xl mx-4 transform -translate-y-40 ">
      <x-tables.table id="tablecategories" :headers="['#', 'Category', 'Related Posts', 'CreatedAt', 'UpdatedAt', 'Actions']"
        title="Categories Table">
        @forelse ($categories as $category)
          <tr >
            <td class="p-2">{{ ($categories->currentPage() - 1) * $categories->perPage() + $loop->iteration }}</td>
            <td class=" p-2">
              <span class=" py-1 px-3 text-blueGray-500  text-sm rounded-md bg-blueGray-200 bg-opacity-70 font-semibold w-fit">

                {{$category->name}}
            </td>
            </span>
            <td class="p-2"> {{$category->allPosts->count()}}</td>

            <td class="p-2">{{$category->created_at->diffForHumans()}}</td>
            <td class="p-2">{{$category->updated_at->diffForHumans()}}</td>
            <td class=" text-white p-2">
              <div class="flex gap-2 justify-start">
                @can('category.delete')
                  <form class="catsdelete" action='{{route('admin.categories.delete', $category->id)}}' method="POST">
                    @csrf
                    @method('delete')
                    <button type="submit" class="text-red-500 rounded-lg p-2 cursor-pointer hover:text-red-300">
                      <i class="fas fa-trash"></i>
                    </button>
                  </form>
                @endcan
                @can('category.update')
                  <button class="catsedit text-gray-500 rounded-lg p-2 cursor-pointer hover:text-gray-300"
                    data-name="{{ $category->name }}" data-id="{{ $category->id }}">
                    <i class="fas fa-edit"></i>
                  </button>
                @endcan
                @can('category.feature')
                  <form action="{{ route('admin.categories.feature', $category->id) }}" method="POST"
                    onsubmit="return confirm('Toggle featured status?');">
                    @csrf
                    @method('PUT')
                    <button type="submit"
                      class="rounded-lg text-yellow-600 p-2 hover:text-yellow-400 transition-colors duration-100">
                      <i class="{{ $category->is_featured ? 'fas' : 'far' }} fa-star"></i>
                    </button>
                  </form>
                @endcan
              </div>

            </td>
          </tr>
        @empty
          <h4 class="text-center font-bold">Sorry, column not found</h4>
        @endforelse
      </x-tables.table>
      <div class="relative md:ml-64 ">
        {!! $categories->links() !!}
      </div>
    </div>
</div>
  {{-- create category model --}}
  @include('admin.categories.partials.create-category-model')
  {{-- edit category model --}}
  @include('admin.categories.partials.edit-category-model')
  @push('scripts')
    <script>
      const showmenu = document.getElementById('openCatModel');
      const closemenu = document.getElementById('closeModel');
      const menu = document.getElementById("Model");

      if (showmenu && closemenu && menu) {
        showmenu.addEventListener('click', () => {
          if (menu.classList.contains('hidden')) {
            menu.classList.remove('hidden');
          }
        });

        closemenu.addEventListener('click', () => {
          if (menu.classList.contains('fixed')) {
            menu.classList.add('hidden');
          }
        });
      }
      // delete category
      const categories = document.querySelectorAll('.catsdelete');
      categories.forEach(cat => {
        cat.addEventListener('submit', (eo) => {
          eo.preventDefault();
          let options = {
            method: 'DELETE',
            headers: {
              'X-CSRF-TOKEN': cat.querySelector('input[name="_token"]').value,
              'Content-Type': 'application/json',
              'Accept': 'application/json',
            },
          };
          fetch(cat.action, options)
            .then(response => response.json())
            .then(data => {
              if (data.deleted === true) {
                toastr.success(data.message);
                cat.closest('tr').remove();
              }
            })
            .catch(error => {
              console.error('Error:', error);
            });
        });
      });
    </script>
  @endpush
@endsection