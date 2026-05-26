@extends('admin.partials.layout')
@section('title', 'Update Custom Page | Dashboard')
@section('content')

<div class="md:ml-64">
  @include('admin.partials.header', [
    'linktext' => 'Update Custom Page',
    'route' => 'admin.custom-pages.index',
    'value' => request('search'),
    'searchColor' => 'bg-blueGray-200',
    'borderColor' => 'border-blueGray-200',
    'backgroundColor' => 'bg-gray-400'
  ])

  <div class="transform -translate-y-40 px-4">
    <div class="w-full lg:w-8/12">
      <div class="relative flex flex-col min-w-0 break-words w-full mb-6 shadow rounded-lg bg-blueGray-100 border-0">
        <div class="rounded-t bg-white mb-0 px-6 py-6">
          <div class="flex items-center justify-between gap-4">
            <h6 class="text-blueGray-700 text-xl font-bold">Update Custom Page</h6>
            <a href="{{ route('admin.custom-pages.index') }}" class="bg-blueGray-200 text-blueGray-500 font-bold uppercase text-xs px-4 py-2 rounded shadow hover:shadow-md">
              Back
            </a>
          </div>
        </div>

        <div class="flex-auto px-4 lg:px-10 py-10 pt-0">
          <form action="{{ route('admin.custom-pages.update', $page->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="flex flex-wrap">
              <div class="w-full lg:w-8/12 px-4">
                <div class="relative w-full mb-3">
                  <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2" for="title">Title</label>
                  <input type="text" id="title" name="title" value="{{ old('title', $page->title) }}" maxlength="30"
                    class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150 @error('title') border-red-500 @enderror">
                  @error('title')
                    <p class="text-red-500 text-xs italic mt-4">{{ $message }}</p>
                  @enderror
                </div>
              </div>

              <div class="w-full lg:w-4/12 px-4">
                <div class="relative w-full mb-3">
                  <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2" for="order">Order</label>
                  <input type="number" id="order" name="order" value="{{ old('order', $page->order) }}"
                    class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150 @error('order') border-red-500 @enderror">
                  @error('order')
                    <p class="text-red-500 text-xs italic mt-4">{{ $message }}</p>
                  @enderror
                </div>
              </div>

              <div class="w-full px-4">
                <div class="relative w-full mb-3">
                  <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2" for="textarea">Content</label>
                  <textarea id="textarea" name="content" rows="10"
                    class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150 @error('content') border-red-500 @enderror">{{ old('content', $page->content) }}</textarea>
                  @error('content')
                    <p class="text-red-500 text-xs italic mt-4">{{ $message }}</p>
                  @enderror
                </div>
              </div>

              <div class="w-full lg:w-6/12 px-4">
                <label class="inline-flex items-center cursor-pointer mb-4">
                  <input type="checkbox" name="is_active" value="1" class="form-checkbox rounded text-gray-600" @checked(old('is_active', $page->is_active->value))>
                  <span class="ml-2 text-sm font-semibold text-blueGray-600">Active</span>
                </label>
              </div>

              <div class="w-full lg:w-6/12 px-4">
                <label class="inline-flex items-center cursor-pointer mb-4">
                  <input type="checkbox" name="show_in_footer" value="1" class="form-checkbox rounded text-gray-600" @checked(old('show_in_footer', $page->show_in_footer))>
                  <span class="ml-2 text-sm font-semibold text-blueGray-600">Show in footer</span>
                </label>
              </div>
            </div>

            <button class="bg-gray-600 ml-4 text-white font-bold uppercase text-xs px-4 py-2 rounded shadow hover:shadow-md outline-none focus:outline-none ease-linear transition-all duration-150" type="submit">
              Update Page
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
