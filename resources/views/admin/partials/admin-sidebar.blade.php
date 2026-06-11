<!-- Divider -->
<hr class="my-4 md:min-w-full" />
<h6 class="md:min-w-full text-blueGray-500 text-xs capitalize font-bold block pt-1 pb-4 no-underline">
  Overview
</h6>
<ul class="md:flex-col md:min-w-full flex flex-col list-none">
  <li class="items-center">
    <a href="{{route('admin.panel')}}"
      class="text-sm capitalize py-3 font-bold block text-blueGray-700 hover:text-blueGray-500 {{ Route::is('admin.panel') ? 'text-blue-500 hover:text-blue-600' : ''}}">
      <i class="fas fa-home mr-2 text-sm opacity-75"></i>
      Dashboard
    </a>
  </li>
    <li class="items-center">
    <a href="{{route('admin.notifications.index')}}"
      class="text-sm capitalize py-3 font-bold block text-blueGray-700 hover:text-blueGray-500 {{ Route::is('admin.notifications') ? 'text-blue-500 hover:text-blue-600' : ''}}">
      <span class="text-sm relative">
        <i class="fas fa-bell"></i>
        <small
          class="absolute top-[-6px] left-2 h-4 w-4 bg-blue-500 text-white flex justify-center items-center rounded-full p-1 text-xs">
          {{ auth()->user()->unreadNotifications->count() }}
        </small>
        <p class="ml-3 inline-block">Notifications</p>
      </span>

    </a>
  </li>
  </ul>
<!-- Divider -->
<hr class="my-4 md:min-w-full" />
<h6 class="md:min-w-full text-blueGray-500 text-xs capitalize font-bold block pt-1 pb-4 no-underline">
  Content Management
</h6>
<ul class="md:flex-col md:min-w-full flex flex-col list-none">
<li class="items-center">
    <a href="{{route('admin.posts.page')}}"
      class="text-sm capitalize py-3 font-bold block text-blueGray-700 hover:text-blueGray-500 {{ Route::is(patterns: 'admin.posts.page') ? 'text-blue-500 hover:text-blue-600' : ''}}">
      <i class="fas fa-image mr-2 text-sm opacity-75"></i>
      Posts
    </a>
  </li>
<li class="items-center">
    <a href="{{ route('admin.posts.moderation.index') }}"
      class="text-sm capitalize py-3 font-bold block text-blueGray-700 hover:text-blueGray-500 {{ Route::is(patterns: 'admin.posts.moderation.index') ? 'text-blue-500 hover:text-blue-600' : ''}}">
      <i class="fas fa-shield-alt mr-2 text-sm opacity-75"></i>
      Post Moderation
    </a>
  </li>

  <li class="items-center">
    <a href="{{route('admin.tags.index')}}"
      class="text-sm capitalize py-3 font-bold block text-blueGray-700 hover:text-blueGray-500 {{ Route::is('admin.tags.index') ? 'text-blue-500 hover:text-blue-600' : ''}}">
      <i class="fas fa-tag mr-2 text-sm opacity-75"></i>
      Tags
    </a>
  </li>
  <li class="items-center">
    <a href="{{route('admin.categories.index')}}"
      class="text-sm capitalize py-3 font-bold block text-blueGray-700 hover:text-blueGray-500 {{ Route::is('admin.categories.index') ? 'text-blue-500 hover:text-blue-600' : ''}}">
      <i class="fas fa-list mr-2 text-sm opacity-75"></i>
      Categories
    </a>
  </li>
    <li class="items-center">
    <button id="dropdownDefaultButton" data-dropdown-toggle="dropdown"
      class="text-blueGray-700 py-3 hover:text-blueGray-500 text-sm capitalize font-bold rounded-lg  inline-flex gap-1 items-center ">
      <i class="fas fa-file-alt mr-3 text-sm opacity-75"></i>
      Reports
      <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
        viewBox="0 0 10 6">
        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4" />
      </svg>
    </button>

    <!-- Dropdown reports -->
    <div id="dropdown"
      class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 border-2 border-gray-500 ">
      <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDefaultButton">
        <li>
          <a href="{{route('admin.reports.posts.index')}}" class="block px-4 py-2 hover:bg-gray-100 text-gray-700">Post
            Reports</a>
        </li>
        <li>
          <a href="{{route('admin.reports.profiles.index')}}"
            class="block px-4 py-2 hover:bg-gray-100 text-gray-700">Profile Reports</a>
        </li>
        <li>
          <a href="{{route('admin.reports.comments.index')}}"
            class="block px-4 py-2 hover:bg-gray-100 text-gray-700">Comments Reports</a>
        </li>
      </ul>
    </div>
  </li>
  <li class="items-center">
    <a href="{{route('admin.posts.featured.page')}}"
      class="text-sm capitalize py-3 font-bold block text-blueGray-700 hover:text-blueGray-500 {{ Route::is('admin.posts.featured.page') ? 'text-blue-500 hover:text-blue-600' : ''}}">
      <i class="fas fa-star mr-2 text-sm opacity-75"></i>
      Featured
    </a>
  </li>
  <li class="items-center">
    <a href="{{route('admin.slides.index')}}"
      class="text-sm capitalize py-3 font-bold block text-blueGray-700 hover:text-blueGray-500 {{ Route::is('admin.slides.index') ? 'text-blue-500 hover:text-blue-600' : ''}}">
      <i class="fas fa-image mr-2 text-sm opacity-75"></i>
      Slides
    </a>
  </li>
  <li class="items-center">
    <a href="{{ route('admin.custom-pages.index') }}"
      class="text-sm capitalize py-3 font-bold block text-blueGray-700 hover:text-blueGray-500 {{ Route::is('admin.custom-pages.index') ? 'text-blue-500 hover:text-blue-600' : ''}}">
      <i class="fas fa-file-alt mr-2 text-sm opacity-75"></i>
      Custom pages
    </a>
  </li>
</ul>
<!-- Divider -->
<hr class="my-4 md:min-w-full" />
<h6 class="md:min-w-full text-blueGray-500 text-xs capitalize font-bold block pt-1 pb-4 no-underline">
  Users and Privileges
</h6>
<!-- Navigation -->
<ul class="md:flex-col md:min-w-full flex flex-col list-none">
  <li class="items-center">
    <a href="{{route('admin.users.page')}}"
      class="text-sm capitalize py-3 font-bold block {{ Route::is('admin.users.page') ? 'text-blue-500 hover:text-blue-600' : ''}}">
      <i class="fas fa-user mr-2 text-sm opacity-75"></i>
      Users
    </a>
  </li>

  
  <li class="items-center">
    <a href="{{route('admin.roles.index')}}"
      class="text-sm capitalize py-3 font-bold block text-blueGray-700 hover:text-blueGray-500 {{ Route::is('admin.roles.index') ? 'text-blue-500 hover:text-blue-600' : ''}}">
      <i class="fas fa-user-tag mr-2 text-sm opacity-75"></i>
      Roles
    </a>
  </li>
  <li class="items-center">
    <a href="{{route('admin.permissions.index')}}"
      class="text-sm capitalize py-3 font-bold block text-blueGray-700 hover:text-blueGray-500 {{ Route::is('admin.permissions.index') ? 'text-blue-500 hover:text-blue-600' : ''}}">
      <i class="fas fa-key mr-2 text-sm opacity-75"></i>
      Permissions
    </a>
  </li>



</ul>
<!-- Monetization -->
<hr class="my-4 md:min-w-full" />
<h6 class="md:min-w-full text-blueGray-500 text-sm capitalize font-bold block pt-1 pb-4 no-underline">
  Monetization
</h6>
<ul class="md:flex-col md:min-w-full flex flex-col list-none">
  <li class="items-center">
    <a href="{{ route('admin.ads.index') }}"
      class="text-sm capitalize py-3 font-bold block {{ Route::is('admin.ads.index') ? 'text-blue-500 hover:text-blue-600' : ''}}">
      <i class="fas fa-ad mr-2 text-sm opacity-75"></i>
      Ad Placements
    </a>
  </li>
</ul>
<!-- System Settings -->
<hr class="my-4 md:min-w-full" />
<h6 class="md:min-w-full text-blueGray-500 text-sm capitalize font-bold block pt-1 pb-4 no-underline">
  System Settings
</h6>
<ul class="md:flex-col md:min-w-full flex flex-col list-none">
  <li class="items-center">
    <a href="{{ route('admin.api-limits.index') }}"
      class="text-sm capitalize py-3 font-bold block {{ Route::is('admin.api-limits.index') ? 'text-blue-500 hover:text-blue-600' : ''}}">
      <i class="fas fa-rocket mr-2 text-sm opacity-75"></i>
      API Rate Limits
    </a>
  </li>
  <li class="items-center">
    <a href="{{ route('admin.settings.auth.index') }}"
      class="text-sm capitalize py-3 font-bold block {{ Route::is('admin.settings.auth.index') ? 'text-blue-500 hover:text-blue-600' : ''}}">
      <i class="fas fa-shield-alt mr-2 text-sm opacity-75"></i>
      Auth & Security
    </a>
  </li>
  <li class="items-center">
    <a href="{{ route('admin.settings.smtp') }}"
      class="text-sm capitalize py-3 font-bold block {{ Route::is('admin.settings.smtp') ? 'text-blue-500 hover:text-blue-600' : ''}}">
      <i class="fas fa-envelope mr-2 text-sm opacity-75"></i>
      smtp mail
    </a>
  </li>
  <li class="items-center">
    <a href="{{ route('admin.settings.backup.view') }}"
      class="text-sm capitalize py-3 font-bold block {{ Route::is('admin.settings.backup.view') ? 'text-blue-500 hover:text-blue-600' : ''}}">
      <i class="fas fa-database mr-2 text-sm opacity-75"></i>
      Backups
    </a>
  </li>
  <li class="items-center">
    <a href="{{ route('admin.settings.notification.view') }}"
      class="text-sm capitalize py-3 font-bold block {{ Route::is('admin.settings.notification.view') ? 'text-blue-500 hover:text-blue-600' : ''}}">
      <i class="fas fa-bell mr-2 text-sm opacity-75"></i>
      Notification settings
    </a>
  </li>
</ul>
<!-- Maintenance -->
<hr class="my-4 md:min-w-full" />
<h6 class="md:min-w-full text-blueGray-500 text-sm capitalize font-bold block pt-1 pb-4 no-underline">
  Optimization
</h6>
<ul class="md:flex-col md:min-w-full flex flex-col list-none">
<li class="items-center">
    <a href="javascript:void(0)"
      class="text-sm capitalize py-3 font-bold block {{ Route::is('admin.settings.backup.view') ? 'text-blue-500 hover:text-blue-600' : ''}}">
      <i class="fas fa-image mr-2 text-sm opacity-75"></i>
      Image Optimization
    </a>
  </li>
<li class="items-center">
    <a href="{{ route('admin.seo.index') }}"
      class="text-sm capitalize py-3 font-bold block {{ Route::is('admin.seo.index') ? 'text-blue-500 hover:text-blue-600' : ''}}">
      <i class="fas fa-search mr-2 text-sm opacity-75"></i>
      SEO Tools
    </a>
  </li>
<li class="items-center">
    <a href="{{ route('admin.optimize.maintenance') }}"
      class="text-sm capitalize py-3 font-bold block {{ Route::is('admin.optimize.maintenance') ? 'text-blue-500 hover:text-blue-600' : ''}}">
      <i class="fas fa-cogs mr-2 text-sm opacity-75"></i>
      Maintenance
    </a>
  </li>
  </ul>
<!-- Divider -->
<hr class="my-4 md:min-w-full" />

<h6 class="md:min-w-full text-blueGray-500 text-sm capitalize font-bold block pt-1 pb-4 no-underline">
  Site
</h6>
<ul class="md:flex-col md:min-w-full flex flex-col list-none">
  <li class="items-center">
    <a href="{{route('home')}}"
      class="text-sm capitalize py-3 font-bold block text-blueGray-700 hover:text-blueGray-500 {{ Route::is('home') ? 'text-blue-500 hover:text-blue-600' : ''}}">
      <i class="fas fa-globe mr-2 text-sm opacity-75"></i>
      View Site
    </a>
  </li>

  <li class="items-center">
    <form id="logoutAdmin" action="{{ route('logout') }}" method="post">@csrf</form>
    <button form="logoutAdmin" type="submit"
      class="text-sm capitalize py-3 font-bold block text-blueGray-700 hover:text-blueGray-500 {{ Route::is('logout') ? 'text-blue-500 hover:text-blue-600' : ''}}">
      <i class="fas fa-sign-out-alt mr-2 text-sm opacity-75"></i>
      Log out
    </button>
  </li>
</ul>