@extends('admin.partials.layout')
@section('title', 'notification control | Dashboard')
@section('content')
  <!-- Header -->
  @include('admin.partials.header', ['linktext' => 'Notification Control', 'route' => 'admin.settings.backup.view', 'value' => request('search')])
  <div class="md:ml-64 w-full mx-auto transform -translate-y-48">
    <div class="flex flex-col">
      <div class="w-full lg:w-8/12 px-4">
        <div class="relative flex flex-col min-w-0 break-words w-full mb-6 shadow-lg rounded-lg bg-blueGray-100 border-0">
          <div class="rounded-t bg-white mb-0 px-6 py-6">
            <div class="text-center flex justify-between">
              <h6 class="text-blueGray-700 text-xl font-bold">
                Daily backups database
              </h6>

            </div>
          </div>
          <div class="flex-auto px-4 lg:px-10 py-10 pt-0">
            <div class="bg-white shadow rounded-xl overflow-hidden max-w-7xl mx-4 mt-4">
            <x-tables.table id="tablebackups" :headers="['#','File','Actions']" title="backups table">
               <tbody>
                @foreach ($files as $file)        
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{basename($file)}}</td>
                  <td>
                <div class="flex gap-2 justify-start">
                  @can('backup.download')
                    <a href="{{ route('admin.settings.backup.download',['file' => $file]) }}" class="text-gray-500 rounded-lg p-2 cursor-pointer hover:text-blue-300">
                      <i class="fas fa-download"></i>
                    </a>
                    @endcan
                    @can('backup.delete')
                    <form action="{{ route('admin.settings.backup.destroy',['file' => $file]) }}" method="post">
                      @csrf
                      @method('delete')
                      <button class="text-red-500 rounded-lg p-2 cursor-pointer hover:text-gray-300" type="submit">
                        <i class="fas fa-trash"></i>
                      </button>
                    </form>
                    @endcan
                  </td>
                </tr>
                @endforeach
               </tbody>
            </x-tables.table>
          </div>
          </div>

        </div>

      </div>

  @endsection