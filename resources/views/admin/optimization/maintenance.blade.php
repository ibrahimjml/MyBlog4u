@extends('admin.partials.layout')
@section('title', 'Maintenance | Dashboard')

@section('content')
  @include('admin.partials.header', ['linktext' => 'Maintenance', 'route' => 'admin.optimize.maintenance', 'value' => request('search')])

      

  <div class="md:ml-64 w-full mx-auto transform -translate-y-48 px-4">
    <div class="w-full lg:w-10/12 xl:w-9/12">
      <div class="relative flex flex-col min-w-0 break-words w-full mb-6 shadow-lg rounded-lg bg-white border-0">
        <div class="rounded-t bg-white px-6 py-6 border-b border-blueGray-100">
          <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
            <div>
              <h6 class="text-blueGray-700 text-xl font-bold">Maintenance</h6>
              <p class="text-sm text-blueGray-400 mt-1">
                Manage cache, compiled files, routes, and logs from one place.
              </p>
            </div>
            <span class="inline-flex items-center w-fit rounded-full bg-blueGray-50 px-3 py-1 text-xs font-semibold text-blueGray-500">
              <i class="fas fa-tools mr-2"></i>
              Optimization tools
            </span>
          </div>
        </div>

        <div class="flex-auto p-4 lg:p-8">
          <div class="mb-5 rounded-md border border-blueGray-100 bg-blueGray-50 px-4 py-3">
            <p class="text-sm font-semibold text-blueGray-700">Clear cache to make your site up to date.</p>
            <p class="text-xs text-blueGray-400 mt-1">
              Use these actions after deployment, configuration changes, or content updates that are not visible yet.
            </p>
          </div>
          <!-- Desktop view -->
          <div class="hidden overflow-hidden rounded-md border border-blueGray-100 md:block">
            <table class="w-full border-collapse bg-white text-left">
              <thead>
                <tr class="bg-blueGray-50 text-xs uppercase tracking-wide text-blueGray-500">
                  <th class="px-5 py-4 font-bold">Type</th>
                  <th class="px-5 py-4 font-bold">Description</th>
                  <th class="px-5 py-4 font-bold text-right">Action</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-blueGray-100">
                @foreach($maintenanceActions as $action)
                  <tr class="hover:bg-blueGray-50/70 transition-colors">
                    <td class="px-5 py-5 align-top">
                      <div class="flex items-start gap-3">
                        <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-md bg-blueGray-100 text-blueGray-600">
                          <i class="{{ $action['icon'] }}"></i>
                        </span>
                        <div>
                          <p class="text-sm font-bold text-blueGray-700">{{ $action['type'] }}</p>
                          @if($action['meta'])
                            <p class="mt-1 text-xs font-semibold text-blueGray-400">{{ $action['meta'] }}</p>
                          @endif
                        </div>
                      </div>
                    </td>
                    <td class="px-5 py-5 align-top">
                      <p class="max-w-xl text-sm leading-6 text-blueGray-500">{{ $action['description'] }}</p>
                    </td>
                    <td class="px-5 py-5 align-top text-right">
                      @can('maintenance.update')
                      <button type="button"
                        data-action="{{ $action['key'] }}"
                        class="maintenance-action-btn {{ $action['color'] }} inline-flex items-center justify-center rounded-md px-4 py-2 text-xs font-bold uppercase text-white shadow-sm transition focus:outline-none focus:ring-4">
                        <span class="btn-label">{{ $action['button'] }}</span>
                      </button>
                      @endcan
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <!-- Mobile view -->
          <div class="space-y-3 md:hidden">
            @foreach($maintenanceActions as $action)
              <div class="rounded-md border border-blueGray-100 bg-white p-4 shadow-sm">
                <div class="flex items-start gap-3">
                  <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-md bg-blueGray-100 text-blueGray-600">
                    <i class="{{ $action['icon'] }}"></i>
                  </span>
                  <div class="min-w-0 flex-1">
                    <p class="text-sm font-bold text-blueGray-700">{{ $action['type'] }}</p>
                    <p class="mt-2 text-sm leading-6 text-blueGray-500">{{ $action['description'] }}</p>
                    @if($action['meta'])
                      <p class="mt-2 text-xs font-semibold text-blueGray-400">{{ $action['meta'] }}</p>
                    @endif
                  </div>
                </div>
                @can('maintenance.update')
                <button type="button"
                  data-action="{{ $action['key'] }}"
                  class="maintenance-action-btn {{ $action['color'] }} mt-4 inline-flex w-full items-center justify-center rounded-md px-4 py-2 text-xs font-bold uppercase text-white shadow-sm transition focus:outline-none focus:ring-4">
                  <span class="btn-label">{{ $action['button'] }}</span>
                </button>
                @endcan
              </div>
            @endforeach
          </div>
          
      </div>
    </div>
  </div>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
      const buttons = document.querySelectorAll('.maintenance-action-btn');
      const url = "{{ route('admin.optimize.run') }}";
      const tokenMeta = document.querySelector('meta[name="csrf-token"]');
      const csrf = tokenMeta ? tokenMeta.getAttribute('content') : '';

      buttons.forEach(btn => {
        btn.addEventListener('click', async function () {
          const action = this.getAttribute('data-action');
          if (! action) return;
          this.setAttribute('disabled','disabled');
          const label = this.querySelector('.btn-label');
          const prev = label ? label.textContent : this.textContent;
          if (label) label.textContent = 'Processing...';

          try {
            const res = await fetch(url, {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json'
              },
              body: JSON.stringify({ action })
            });
            const data = await res.json();
            if (res.ok && data.status === 'ok') {
              alert(data.message || 'Done');
            } else {
              alert(data.message || 'Action failed');
            }
          } catch (e) {
            alert('Request failed: ' + e.message);
          } finally {
            this.removeAttribute('disabled');
            if (label) label.textContent = prev;
          }
        });
      });
    });
    </script>
    @endsection
