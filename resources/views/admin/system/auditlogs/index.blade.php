@extends('admin.partials.layout')
@section('title', 'Audit Logs | Dashboard')
@section('content')

  @include('admin.partials.header', [
    'linktext' => 'Audit Logs',
    'route' => 'admin.system.audit-logs.index',
    'value' => request('search'),
    'searchColor' => 'bg-blueGray-200',
    'borderColor' => 'border-blueGray-200',
    'backgroundColor' => 'bg-gray-400'
  ])
  <div class="md:ml-64 transform -translate-y-48">
    <div class="flex flex-col">
      <div class="w-full lg:w-8/12 px-4">
        <div class="relative flex flex-col min-w-0 break-words w-full mb-6 shadow-lg rounded-lg bg-blueGray-100 border-0">
          <div class="rounded-t bg-white mb-0 px-6 py-6">
            <div class="text-center flex justify-between">
              <h6 class="text-blueGray-700 text-xl font-bold">
                Audit Logs <small>{{ '(' . $auditLogs->count() . ')' }}</small>
              </h6>

            </div>
          </div>
          <div class="flex-auto px-4 lg:px-10 py-10 pt-0">
            <div class="bg-white shadow rounded-xl overflow-hidden max-w-7xl mx-4 mt-4">
              <x-tables.table id="tableauditlogs" :headers="['#', 'Activity', 'Actions']" title="audit logs table">
                <tbody>
                  @foreach ($auditLogs as $auditLog)
                    <tr class="border-b border-blueGray-300 last:border-0">
                      <td>{{ $loop->iteration }}</td>
                      <td>
                        <div class="flex flex-col gap-2 justify-center mt-1">
                          <span>
                            <b><a
                                href="{{ route('profile.home', ['user' => $auditLog->user->username]) }}">{{ $auditLog->reference_name }}</a></b>
                            {{ $auditLog->action . '-' . $auditLog->module }}
                          </span>
                          <span>
                            <b class="text-blueGray-400">{{ $auditLog->created_at->diffForHumans() }}</b> -
                            {{'(' . $auditLog->ip_address . ')' }}
                          </span>
                        </div>
                      </td>
                      <td>
                        <div class="flex gap-2 justify-start">
                          @can('audit-logs.delete')
                            <form action="{{ route('admin.system.audit-logs.destroy', ['auditLog' => $auditLog->id]) }}"
                              method="post">
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
              <div class="m-4">
                {!! $auditLogs->links() !!}
              </div>
            </div>
          </div>

        </div>

      </div>
    </div>

  </div>
@endsection