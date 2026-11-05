@props([
    'buttonId',
    'container',
    'route',
    'pageName',
    'currentPage',
    'hasMore',
    'buttonText' => 'Load More',
])

<div id="{{ $buttonId }}-container" class="{{ $hasMore ? '' : 'hidden' }} mt-8 text-center">
    <button id="{{ $buttonId }}" data-next-page="{{ $hasMore ? $currentPage + 1 : '' }}"
        class="inline-flex cursor-pointer items-center gap-2 rounded-full bg-foreground px-6 py-2.5 font-mono text-xs text-background transition-opacity hover:opacity-80 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
        {{ $buttonText }}
        <i class="fas fa-arrow-down text-[10px]" aria-hidden="true"></i>
    </button>
</div>

<div id="{{ $buttonId }}-spinner" class="hidden mt-6 text-center">
    <div class="inline-flex items-center gap-2 font-mono text-xs text-muted-foreground">
        <i class="fas fa-spinner fa-spin text-primary" aria-hidden="true"></i>
        <span>Loading more&hellip;</span>
    </div>
</div>

<p id="reach-end" class="hidden mt-6 w-fit mx-auto font-mono text-xs text-muted-foreground">
    You've reached the end.
</p>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const button = document.getElementById(@js($buttonId));
            if (!button) return;

            const container = document.getElementById(@js($container));
            const wrapper = document.getElementById(@js($buttonId . '-container'));
            const spinner = document.getElementById(@js($buttonId . '-spinner'));
            const reachEnd = document.getElementById('reach-end');
            const route = @js($route);
            const pageName = @js($pageName);

            button.addEventListener('click', async () => {
                spinner.classList.remove('hidden');
                wrapper.classList.add('hidden');

                try {
                    const params = new URLSearchParams(window.location.search);
                    params.set(pageName, button.dataset.nextPage);

                    const response = await fetch(`${route}?${params.toString()}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        }
                    });

                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }

                    const data = await response.json();
                    container.insertAdjacentHTML('beforeend', data.html);

                    if (data.hasMore) {
                        button.dataset.nextPage = data.nextPage;
                        wrapper.classList.remove('hidden');
                        reachEnd.classList.add('hidden');
                    } else {
                        wrapper.classList.add('hidden');
                        reachEnd.classList.remove('hidden');
                    }
                } catch (error) {
                    console.error(error);
                    wrapper.classList.remove('hidden');
                } finally {
                    spinner.classList.add('hidden');
                }
            });
        });
    </script>
@endpush
