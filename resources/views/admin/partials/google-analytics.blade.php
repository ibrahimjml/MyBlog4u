<div class="mt-8 w-[95%] mx-auto">
  <div class="bg-white border border-gray-200 rounded-xl p-6">
    {{-- header --}}
    <div class="flex items-start md:items-center justify-between mb-6 flex-col md:flex-row gap-2">
      <div>
        <p class="text-xl font-medium text-gray-900 m-0">Site analytics</p>
        <p class="text-sm text-gray-500 mt-1">
          Last 30 days · All traffic sources
        </p>
      </div>
      <span class="text-xs px-3 py-1 rounded-md bg-blue-50 text-blue-700">
        Google Analytics
      </span>
    </div>
    {{-- map --}}
    <div id="visitors-map" class="h-[480px]"></div>

    <div class="mt-6 border-t border-gray-100 pt-4">

      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2">
        {{-- Visitors --}}
        <div class="bg-gray-50 rounded-lg p-5 border border-gray-200">
          <div class="flex items-center gap-2 text-gray-500 text-sm mb-2">
            <span class="p-2 w-10 text-center rounded-md text-white" style="background-color: #3cf461;">
              <i class="fas fa-users"></i>
            </span>
            <span class="font-bold tracking-[0.2em]">Visitors</span>
          </div>

          <p class="text-3xl font-semibold text-gray-900">
            {{ number_format($site_analytics['visitors']) }}
          </p>

          <p class="text-xs text-gray-400 mt-1">unique users</p>
        </div>
        {{-- Page views --}}
        <div class="bg-gray-50 rounded-lg p-5 border border-gray-200">
          <div class="flex items-center gap-2 text-gray-500 text-sm mb-2">
            <span class="p-2 w-10 text-center rounded-md text-white" style="background-color: #9743e6;">
              <i class="fas fa-signal"></i>
            </span>
            <span class="font-bold tracking-[0.2em]">Page views</span>
          </div>

          <p class="text-3xl font-semibold text-gray-900">
            {{ number_format($site_analytics['page_views']) }}
          </p>

          <p class="text-xs text-gray-400 mt-1">total impressions</p>
        </div>
        {{-- Bounce rate --}}
        <div class="bg-gray-50 rounded-lg p-5 border border-gray-200">
          <div class="flex items-center gap-2 text-gray-500 text-sm mb-2">
            <span class="p-2 w-10 text-center rounded-md text-white" style="background-color: #f1e830;">
              <i class="fas fa-bolt"></i>
            </span>
            <span class="font-bold tracking-[0.2em]">Bounce rate</span>
          </div>

          <p class="text-3xl font-semibold text-gray-900">
            {{ $site_analytics['bounce_rate'] }}
            <span class="text-lg font-normal">%</span>
          </p>

          <p class="text-xs text-gray-400 mt-1">single-page sessions</p>
        </div>
      </div>

    </div>
  </div>
</div>
<div class="w-[95%] mx-auto grid grid-cols-1 md:grid-cols-2 gap-2">
  {{-- most visited pages --}}
  <div class="mt-8">
    <div class="bg-white border border-gray-200 rounded-xl p-8">

      <div class="flex items-center justify-between mb-6">
        <div>
          <p class="text-xl font-medium m-0">Most visited pages</p>
          <p class="text-sm text-gray-500 mt-1 mb-0">Last 30 days</p>
        </div>
        <span class="text-xs px-3 py-1 rounded-md bg-blue-50 text-blue-700">
          Google Analytics
        </span>
      </div>

      <table class="w-full text-sm">
        <thead>
          <tr class="border-b border-gray-100">
            <th class="text-left px-3 py-2 text-gray-500 font-medium w-8">#</th>
            <th class="text-left px-3 py-2 text-gray-500 font-medium">Page</th>
            <th class="text-right px-3 py-2 text-gray-500 font-medium">Views</th>
          </tr>
        </thead>
        <tbody>
          @foreach($most_visited_pages as $i => $page)
            <tr class="border-b border-gray-50 hover:bg-gray-50 transition-colors">
              <td class="px-3 py-3 text-gray-400">{{ $i + 1 }}</td>
              <td class="px-3 py-3">
                <p class="m-0 font-medium text-gray-900">{{ $page['title'] }}</p>
                <a href="https://{{ $page['url'] }}" target="_blank"
                  class="text-xs text-blue-400 hover:text-blue-600 transition-colors mt-0.5 block truncate max-w-xs">
                  {{ $page['url'] }}
                </a>
              </td>
              <td class="px-3 py-3 text-right font-medium text-gray-900">
                {{ number_format($page['page_views']) }}
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>

    </div>
  </div>
  {{-- Top browsers --}}
  <div class="mt-8">
    <div class="bg-white border border-gray-200 rounded-xl p-8">

      <div class="flex items-center justify-between mb-6">
        <div>
          <p class="text-xl font-medium m-0">Top Browsers</p>
          <p class="text-sm text-gray-500 mt-1 mb-0">Last 30 days</p>
        </div>
        <span class="text-xs px-3 py-1 rounded-md bg-blue-50 text-blue-700">
          Google Analytics
        </span>
      </div>

      <table class="w-full text-sm">
        <thead>
          <tr class="border-b border-gray-100">
            <th class="text-left px-3 py-2 text-gray-500 font-medium w-8">#</th>
            <th class="text-left px-3 py-2 text-gray-500 font-medium">Browsers</th>
            <th class="text-right px-3 py-2 text-gray-500 font-medium">Views</th>
          </tr>
        </thead>
        <tbody>
          @foreach($top_browsers as $browser)
            <tr class="border-b border-gray-50 hover:bg-gray-50 transition-colors">
              <td class="px-3 py-3 text-gray-400">{{ $loop->iteration }}</td>
              <td class="px-3 py-3">
                <p class="m-0 font-medium text-gray-900">{{ $browser['browser'] }}</p>
              </td>
              <td class="px-3 py-3 text-right font-medium text-gray-900">
                {{ number_format($browser['page_views']) }}
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>

    </div>
  </div>
</div>
{{-- Top referrers --}}
<div class="mt-8 w-[95%] mx-auto">
  <div class="bg-white border border-gray-200 rounded-xl p-8">

    <div class="flex items-center justify-between mb-6">
      <div>
        <p class="text-xl font-medium m-0">Top traffic sources</p>
        <p class="text-sm text-gray-500 mt-1 mb-0">Last 30 days</p>
      </div>
      <span class="text-xs px-3 py-1 rounded-md bg-blue-50 text-blue-700">
        Google Analytics
      </span>
    </div>

    <table class="w-full text-sm">
      <thead>
        <tr class="border-b border-gray-100">
          <th class="text-left px-3 py-2 text-gray-500 font-medium w-8">#</th>
          <th class="text-left px-3 py-2 text-gray-500 font-medium">Source</th>
          <th class="text-right px-3 py-2 text-gray-500 font-medium">Views</th>
        </tr>
      </thead>
      <tbody>
        @forelse($top_referrers as $i => $ref)
          <tr class="border-b border-gray-50 hover:bg-gray-50 transition-colors">
            <td class="px-3 py-3 text-gray-400">{{ $i + 1 }}</td>
            <td class="px-3 py-3 font-medium text-gray-900 capitalize">
              {{ $ref['source'] === '(direct)' ? 'Direct' : $ref['source'] }}
            </td>

            <td class="px-3 py-3 text-right font-medium text-gray-900">
              {{ number_format($ref['page_views']) }}
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="4" class="px-3 py-6 text-center text-gray-400">No referrer data yet</td>
          </tr>
        @endforelse
      </tbody>
    </table>

  </div>
</div>