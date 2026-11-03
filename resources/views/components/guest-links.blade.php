
    {{-- Mobile Search --}}
    <div class="lg:hidden">
        <x-search-button />
    </div>

    @unless(Route::is('login'))
        <a
            href="{{ route('login') }}"
            class="inline-flex items-center rounded-full bg-foreground px-4 py-1.5 font-mono text-xs text-background transition-opacity hover:opacity-80 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring"
        >
            sign in
        </a>
    @endunless