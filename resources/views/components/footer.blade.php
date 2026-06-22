<footer
  class="mt-auto w-full bg-zinc-50 text-slate-700 dark:bg-black/90 dark:text-slate-200 border-t border-zinc-200 dark:border-zinc-700">
  <div class="mx-auto flex w-full max-w-7xl flex-col gap-8 px-4 py-8 text-sm sm:px-6 md:px-8">
    <div class="flex flex-col gap-8 md:flex-row md:items-start md:justify-between">
      <div class="max-w-sm">
        <div class="flex items-center gap-3">
          <span
            class="inline-flex h-10 w-10 items-center justify-center border-2 border-blue-300 rounded-full bg-slate-900 text-sm font-semibold text-white">IJ</span>
          <div>
            <p class="text-base font-semibold uppercase tracking-[0.12em] text-slate-900 dark:text-slate-100">Ibrahim
              Jamal</p>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Full Stack Developer</p>
          </div>
        </div>
        <div class="mt-4 flex flex-wrap gap-3 text-slate-500">
          <a href="https://github.com/ibrahimjml" target="_blank" rel="noopener noreferrer"
            class="transition-colors hover:text-slate-900 dark:hover:text-white">
            <i class="fab fa-github"></i>
          </a>
          <a href="https://linkedin.com/in/ibrahimjamal222" target="_blank" rel="noopener noreferrer"
            class="transition-colors hover:text-slate-900 dark:hover:text-white">
            <i class="fab fa-linkedin"></i>
          </a>
        </div>
      </div>

      <div class="grid w-full gap-8 sm:grid-cols-3 md:w-auto">
        <div>
          <p class="font-semibold uppercase tracking-[0.2em] text-slate-800 dark:text-slate-200 mb-3">Quick Links</p>
          <ul class="space-y-2 text-slate-600 dark:text-slate-400">
            <li><a href="{{ route('home') }}"
                class="transition-colors hover:text-slate-900 dark:hover:text-white">Home</a></li>
            <li><a href="{{ route('blog') }}"
                class="transition-colors hover:text-slate-900 dark:hover:text-white">Blog</a></li>
            @auth
              <li><a href="{{ route('profile.home', auth()->user()->username) }}"
                  class="transition-colors hover:text-slate-900 dark:hover:text-white">Profile</a></li>
            @else
              <li><a href="{{ route('login') }}"
                  class="transition-colors hover:text-slate-900 dark:hover:text-white">Profile</a></li>
            @endauth
            <li><a href="{{ route('bookmarks') }}"
                class="transition-colors hover:text-slate-900 dark:hover:text-white">Bookmarks</a></li>
          </ul>
        </div>
        <div>
          <p class="font-semibold uppercase tracking-[0.2em] text-slate-800 dark:text-slate-200 mb-3">Categories</p>
          <ul class="space-y-2 text-slate-600 dark:text-slate-400">
            @foreach($categories as $cat)
              <li><a href="{{ route('viewcategory', $cat->name) }}"
                  class="transition-colors hover:text-slate-900 dark:hover:text-white">{{ $cat->name }}</a></li>
            @endforeach
          </ul>
        </div>
        <div>
          <p class="font-semibold uppercase tracking-[0.2em] text-slate-800 dark:text-slate-200 mb-3">Site</p>
          <ul class="space-y-2 text-slate-600 dark:text-slate-400">
            @foreach($footerPages as $page)
              <li>
                <a href="{{ route('custom.page', $page->slug) }}"
                  class="transition-colors hover:text-slate-900 dark:hover:text-white">
                  {{ $page->title }}
                </a>
              </li>
            @endforeach
          </ul>
        </div>
      </div>
    </div>

    <hr class="border-t border-zinc-200 dark:border-zinc-700" />

    <div
      class="flex flex-col gap-4 text-xs text-slate-500 dark:text-slate-400 sm:flex-row sm:items-center sm:justify-between">
      <p class="whitespace-nowrap">&copy; {{ date('Y') }} Ibrahim Jamal</p>
      <div class="flex flex-wrap items-center gap-4">
        <div class="flex items-center gap-2">
          <span class="font-medium">Built on</span>
          <svg version="1.1" id="Layer_1" width="16px" xmlns="http://www.w3.org/2000/svg"
            xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 122.88 122.88"
            style="enable-background:new 0 0 122.88 122.88" xml:space="preserve">
            <style type="text/css">
              <![CDATA[
              .st0 {
                fill: #0080FF;
              }

              .st1 {
                fill-rule: evenodd;
                clip-rule: evenodd;
                fill: #0080FF;
              }
              ]]>
            </style>
            <g>
              <path class="st0"
                d="M61.45,122.88V99.05c25.22,0,44.8-25.01,35.11-51.56c-3.55-9.75-11.43-17.63-21.25-21.18 c-26.54-9.61-51.56,9.89-51.56,35.11l0,0H0c0-40.2,38.88-71.55,81.03-58.38c18.39,5.78,33.09,20.41,38.81,38.81 C133,84,101.65,122.88,61.45,122.88L61.45,122.88z" />
              <polygon class="st1"
                points="61.52,99.19 37.76,99.19 37.76,75.43 37.76,75.43 61.52,75.43 61.52,75.43 61.52,99.19" />
              <polygon class="st1"
                points="37.76,117.38 19.58,117.38 19.58,117.38 19.58,99.19 37.76,99.19 37.76,117.38" />
              <polygon class="st1"
                points="19.58,99.19 4.32,99.19 4.32,99.19 4.32,83.93 4.32,83.93 19.58,83.93 19.58,83.93 19.58,99.19" />
            </g>
          </svg>
          <span>DigitalOcean</span>
        </div>
        <div class="flex items-center gap-2">
          <i class="fas fa-code"></i>
          <span>Developed by Ibrahim Jamal</span>
        </div>
      </div>
    </div>
  </div>
</footer>
<x-scripts />

{!! $footer_scripts ?? '' !!}

</body>

</html>