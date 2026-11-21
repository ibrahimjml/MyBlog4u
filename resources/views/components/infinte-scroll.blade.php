@props([
    'container',
    'route',
    'pageName',
    'currentPage',
    'hasMore',
])

<!-- spinner  -->
<div id="loading-spinner" class="hidden mt-6 text-center">
    <div class="inline-flex items-center gap-2 font-mono text-xs text-muted-foreground">
        <i class="fas fa-spinner fa-spin text-primary" aria-hidden="true"></i>
        <span>Loading more&hellip;</span>
    </div>
</div>

<p id="reach-end" class="hidden mt-6 w-fit mx-auto font-mono text-xs text-muted-foreground">
    You've reached the end.
</p>
<!-- observer trigger -->
<div id="scroll-loading" class="h-10"></div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

    const container = document.getElementById(@js($container));
    const loadingSpinner = document.getElementById('loading-spinner');
    const reachEnd = document.getElementById('reach-end');
    const action = document.getElementById('scroll-loading');

    if (!container || !action) return;

    const route = @js($route);
    const pageName = @js($pageName);

    let currentPage = @js($currentPage);
    let hasMore = @js($hasMore);
    let isLoading = false;
    let isPaused = false;
    let requestController = null;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !isLoading && !isPaused && hasMore) {
                loadMorePosts();
            }
        });
    }, {
        rootMargin: '100px',
        threshold: 0.1
    });

    observer.observe(action);

    const applyState = (page, more) => {
        currentPage = page;
        hasMore = more;

        if (hasMore) {
            reachEnd.classList.add('hidden');
            observer.observe(action);
        } else {
            observer.disconnect();
            reachEnd.classList.remove('hidden');
        }
    };

    async function loadMorePosts() {

        if (isLoading || isPaused || !hasMore) return;

        isLoading = true;
        requestController = new AbortController();

        loadingSpinner.classList.remove('hidden');
        reachEnd.classList.add('hidden');

        try {

            const params = new URLSearchParams(window.location.search);
            params.set(pageName, currentPage + 1);

            const response = await fetch(`${route}?${params.toString()}`, {
                signal: requestController.signal,
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

            applyState(data.currentPage, data.hasMore);

        } catch (error) {
            if (error.name !== 'AbortError') {
                console.error(error);
            }
        } finally {
            isLoading = false;
            loadingSpinner.classList.add('hidden');
        }
    }

    document.addEventListener('infinite-scroll:pause', () => {
        isPaused = true;
        requestController?.abort();
        loadingSpinner.classList.add('hidden');
    });

    document.addEventListener('infinite-scroll:reset', (event) => {
        isPaused = false;
        isLoading = false;
        loadingSpinner.classList.add('hidden');
        applyState(event.detail?.currentPage ?? 1, event.detail?.hasMore ?? false);
    });

});
</script>
@endpush