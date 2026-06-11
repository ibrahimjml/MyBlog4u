<footer class="mt-auto w-screen flex flex-col items-center bg-zinc-50 text-center text-surface dark:bg-opacity-0 dark:text-black border-t-2">
  <div class="w-full h-full bg-black/5 p-4 text-center"><!-- dynamic custom pages -->
    @if($footerPages->isNotEmpty())
      <nav class="mb-3 flex flex-wrap justify-center gap-4 text-sm font-semibold text-gray-600">
        @foreach($footerPages as $page)
          <a href="{{ route('custom.page', $page->slug) }}" class="hover:text-gray-900">
            {{ $page->title }}
          </a>
        @endforeach
      </nav>
    @endif

  &copy; {{ date('Y') }} Copyright:
    <p class="text-lg font-bold">Ibrahim jamal</p>
    <div class="flex justify-center px-2">
      <a href="https://github.com/ibrahimjml" class="text-gray-400 hover:text-gray-900">
        <i class="fab fa-github"></i>
    </a>
    </div>
  </div>
</footer>
<x-scripts/>

{!! $footer_scripts ?? '' !!}

</body>
</html>
