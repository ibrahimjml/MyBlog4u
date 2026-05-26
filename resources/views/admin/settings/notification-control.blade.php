@extends('admin.partials.layout')
@section('title', 'notification control | Dashboard')
@section('content')
  <!-- Header -->
  @include('admin.partials.header', ['linktext' => 'Notification Control', 'route' => 'admin.settings.notification.view', 'value' => request('search')])

  <div class="md:ml-64 w-full mx-auto transform -translate-y-48">
    <div class="flex flex-col">
      <div class="w-full lg:w-8/12 px-4">
        <div class="relative flex flex-col min-w-0 break-words w-full mb-6 shadow-lg rounded-lg bg-blueGray-100 border-0">
          <div class="rounded-t bg-white mb-0 px-6 py-6">
            <div class="text-center flex justify-between">
              <h6 class="text-blueGray-700 text-xl font-bold">
                Manage Notifications
              </h6>

            </div>
          </div>
          <div class="flex-auto px-4 lg:px-10 py-10 pt-0">
            <h6 class="text-blueGray-400 text-sm mt-3 mb-6 font-bold uppercase">
              Get whether Notified
            </h6>
            <form action="{{ route('admin.settings.notification.toggle') }}" method="POST">
              @csrf
              @method('PATCH')
              <div class="flex flex-col">
                <div class="w-full px-4">
                  <div class="relative w-full mb-3">
                    @foreach (\App\Enums\NotificationType::cases() as $type)
                    <x-toggle 
                       name="notifications[{{ $type->value }}]"
                       :disabled="! auth()->user()->can('notifications.update')"
                       :checked="$setting[$type->value] ?? false"
                       label="{{$type->label()}}" />
                       <p class="mt-1 text-xs text-blueGray-400 my-2">{{ $type->description() }}</p>
                    @endforeach
                  </div>
                </div>
              </div>
               <div class="flex justify-end mt-6">
                @can('notifications.update')
                    <button class="bg-green-500 text-white active:bg-blue-600 font-bold uppercase text-xs px-4 py-2 rounded shadow hover:shadow-md outline-none focus:outline-none mr-1 ease-linear transition-all duration-150" type="submit">
                        Save Changes
                    </button>
                    @endcan
                </div>
            </form>
          </div>

        </div>

      </div>
@endsection