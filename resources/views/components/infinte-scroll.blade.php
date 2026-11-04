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

    let currentPage = @js($currentPage);
    let hasMore = @js($hasMore);
    let isLoading = false;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !isLoading && hasMore) {
                loadMorePosts();
            }
        });
    }, {
        rootMargin: '100px',
        threshold: 0.1
    });

    observer.observe(action);

    async function loadMorePosts() {

        if (isLoading || !hasMore) return;

        isLoading = true;

        loadingSpinner.classList.remove('hidden');
        reachEnd.classList.add('hidden');

        try {

            const params = new URLSearchParams(window.location.search);
            params.set(@js($pageName), currentPage + 1);

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

            currentPage = data.currentPage;
            hasMore = data.hasMore;

            if (!hasMore) {
                observer.disconnect();
                reachEnd.classList.remove('hidden');
            }

        } catch (error) {
            console.error(error);
        } finally {
            isLoading = false;
            loadingSpinner.classList.add('hidden');
        }
    }

});
</script>
@endpush