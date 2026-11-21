/**
 * Blog filter: tab switching, sort popover, date range, AJAX refresh.
 */

function initScrollState(filter) {
    if (!filter || filter.dataset.scrollBound === 'true') return;

    filter.dataset.scrollBound = 'true';
    filter.dataset.scrolled = 'false';

    // 1px marker above the filter — when it leaves the viewport, the filter
    const marker = document.createElement('div');
    marker.setAttribute('aria-hidden', 'true');
    marker.style.cssText =
        'position:absolute;top:0;height:1px;width:1px;pointer-events:none;';
    filter.parentNode.insertBefore(marker, filter);

    new IntersectionObserver(
        ([entry]) => {
            filter.dataset.scrolled = entry.isIntersecting ? 'false' : 'true';
        },
        { rootMargin: '0px', threshold: 0 }
    ).observe(marker);
}

function initFilterForm(root) {
    if (!root || root.dataset.bound === 'true') return;

    const form = root.querySelector('#blog-filter-form');
    const popover = root.querySelector('#blog-filter-popover');
    if (!form || !popover) return;

    root.dataset.bound = 'true';

    const tabInput    = form.querySelector('[data-filter-tab]');
    const sortInput   = form.querySelector('[data-filter-sort]');
    const fromInput   = form.querySelector('[name="from"]');
    const toInput     = form.querySelector('[name="to"]');
    const tabButtons  = form.querySelectorAll('[data-filter-tab-button]');
    const sortButtons = form.querySelectorAll('[data-filter-sort-option]');
    const activeTabButton = form.querySelector('[data-filter-tab-button][data-active="true"]');
    const chevron     = activeTabButton?.querySelector('[data-filter-chevron]');
    const resetButton = form.querySelector('[data-filter-reset]');

    const selectedTabButton = () =>
        form.querySelector(`[data-filter-tab-button="${tabInput.value}"]`);

    // ---------- errors ----------
    const clearToInputError = () => {
        const el = form.querySelector('[data-error-for]');
        if (!el) return;
        el.textContent = '';
        el.classList.add('hidden');
    };

    const showToInputErrors = (errors) => {
        Object.entries(errors ?? {}).forEach(([field, messages]) => {
            const el = form.querySelector(`[data-error-for="${field}"]`);
            if (!el) return;
            el.textContent = messages[0];
            el.classList.remove('hidden');
        });
    };

    // ---------- popover ----------
    const setOpen = (open) => {
        popover.classList.toggle('hidden', !open);
        activeTabButton?.setAttribute('aria-expanded', open ? 'true' : 'false');
        chevron?.classList.toggle('rotate-180', open);
        clearToInputError();
    };

    window.__closeBlogFilter = () => setOpen(false);

    activeTabButton?.addEventListener('click', () => {
        setOpen(popover.classList.contains('hidden'));
    });

    // ---------- query ----------
    const buildQuery = () => {
        const params = new URLSearchParams();
        params.set('tab', tabInput.value);
        params.set('sort', sortInput.value);
        if (fromInput.value) params.set('from', fromInput.value);
        if (toInput.value) params.set('to', toInput.value);
        return params;
    };

    // ---------- AJAX apply ----------
    const applyFilter = async () => {
        const params = buildQuery();
        params.set('refresh_filter', '1');

        document.dispatchEvent(new Event('infinite-scroll:pause'));

        try {
            const response = await fetch(`${window.location.pathname}?${params.toString()}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    Accept: 'application/json',
                },
            });

            const data = await response.json();

            if (response.status === 401 && data.redirect) {
                window.location.href = data.redirect;
                return;
            }

            if (!response.ok) {
                if (response.status === 422 && data.errors) {
                    showToInputErrors(data.errors);
                    return;
                }
                throw new Error(data.message || 'Filter request failed');
            }

            if (data.url) history.replaceState({}, '', data.url);

            const postsContainer = document.getElementById('posts-container');
            if (postsContainer) postsContainer.innerHTML = data.html;

            if (data.trendingTagHtml) {
                const header = document.getElementById('trending-tag-header');
                if (header) header.outerHTML = data.trendingTagHtml;
            }

            if (data.filterHtml) {
                // replace the whole filter, then rebind
                root.outerHTML = data.filterHtml;
                window.initBlogFilter();
            }

            document.dispatchEvent(new CustomEvent('infinite-scroll:reset', {
                detail: { currentPage: data.currentPage, hasMore: data.hasMore },
            }));

            requestAnimationFrame(() =>
                window.scrollTo({ top: 0, behavior: 'smooth' })
            );
        } catch (error) {
            console.error(error);
            document.dispatchEvent(new CustomEvent('infinite-scroll:reset', {
                detail: { currentPage: 1, hasMore: false },
            }));
        }
    };

    // ---------- bindings ----------
    tabButtons.forEach((button) => {
        if (button.dataset.active === 'true') return;

        button.addEventListener('click', () => {
            tabInput.value = button.dataset.filterTabButton;
            sortInput.value = button.dataset.defaultSort;
            applyFilter();
        });
    });

    sortButtons.forEach((button) => {
        button.addEventListener('click', () => {
            sortInput.value = button.dataset.filterSortOption;
            applyFilter();
        });
    });

    resetButton?.addEventListener('click', (event) => {
        event.preventDefault();
        sortInput.value = selectedTabButton()?.dataset.defaultSort ?? sortInput.value;
        fromInput.value = '';
        toInput.value = '';
        applyFilter();
    });

    form.addEventListener('submit', (event) => {
        event.preventDefault();
        applyFilter();
    });
}

export function bootBlogFilter() {
    const root = document.getElementById('blog-filter');
    if (!root) return;

    initScrollState(root);
    initFilterForm(root);
}

// document-level handlers — bind once
if (!window.__blogFilterDocumentBound) {
    window.__blogFilterDocumentBound = true;

    document.addEventListener('click', (event) => {
        const form = document.getElementById('blog-filter-form');
        const popover = document.getElementById('blog-filter-popover');

        if (!form || !popover) return;
        if (popover.classList.contains('hidden')) return;
        if (form.contains(event.target)) return;

        window.__closeBlogFilter?.();
    });

    document.addEventListener('keydown', (event) => {
        if (event.key !== 'Escape') return;

        const popover = document.getElementById('blog-filter-popover');
        if (popover && !popover.classList.contains('hidden')) {
            window.__closeBlogFilter?.();
        }
    });
}

// public init
window.initBlogFilter = bootBlogFilter;

// initial mount
document.addEventListener('DOMContentLoaded', bootBlogFilter);