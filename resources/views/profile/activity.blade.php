@use('App\Enums\UserActivityType')
@php
    $tabs = UserActivityType::cases()
@endphp

<div class="mt-8 grid grid-cols-1 gap-10 lg:grid-cols-[minmax(0,1fr)_16rem]">
    <div class="min-w-0">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="font-mono text-[11px] text-muted-foreground">~/activity</p>
                <h1 class="mt-1 font-display text-3xl font-bold text-foreground">Recent activity</h1>
            </div>
            <p class="font-mono text-[11px] uppercase tracking-[0.14em] text-muted-foreground">
                {{ $publishedCount }} published {{ Str::plural('article', $publishedCount) }}
            </p>
        </div>
        <!-- filters tabs -->
        @if($activities->isNotEmpty())
        <div id="activity-filters" data-route="{{ route('profile.activity', $user->username) }}" class="my-4 flex flex-wrap gap-2">
             <button type="button" data-filter="all"
                    class="activity-filter rounded-full border border-primary bg-primary px-3 py-1 font-mono text-[11px] font-medium text-primary-foreground transition-colors">
                    All
                </button>
             @foreach($tabs as $tab)
                    <button type="button" data-filter="{{ $tab->value }}"
                        class="activity-filter rounded-full border border-border bg-card px-3 py-1 font-mono text-[11px] text-muted-foreground transition-colors hover:border-primary hover:text-primary">
                        <i class="{{ $tab->icon() }} text-primary" aria-hidden="true"></i>
                         {{ $tab->value }}
                    </button>
                @endforeach
          </div>  
          @endif
        @if($activities->isEmpty())
            <p class="py-12 text-center font-display text-lg font-semibold text-muted-foreground">
                No recent activity to show.
            </p>
        @else
            <div id="activities-load" class="relative mt-8 border-l border-border pl-6">
                <!-- activities ajax -->
                 @include('profile.partials.activity-ajax', ['activities' => $activities])

            </div>
        @endif
        <x-load-more button-id="load-more-activities"
                          container="activities-load" 
                          :route="route('profile.activity',$user->username)"
                          page-name="activity_page" 
                          :current-page="$activities->currentPage()" 
                          :has-more="$activities->hasMorePages()"
                          button-text="Load More Activities" 
                          />
    </div>

    <x-profile.sidebar :user="$user" :socials="$userSocials"/>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const filters = document.getElementById('activity-filters');
            const container = document.getElementById('activities-load');

            if (!filters || !container) return;

            const route = filters.dataset.route;
            const buttons = filters.querySelectorAll('.activity-filter');

            const loadMoreButton = document.getElementById('load-more-activities');
            const loadMoreWrapper = document.getElementById('load-more-activities-container');
            const reachEnd = document.getElementById('reach-end');

            let activeRequest = null;

            const setActive = (active) => {
                buttons.forEach((button) => {
                    const isActive = button === active;

                    button.classList.toggle('border-primary', isActive);
                    button.classList.toggle('bg-primary', isActive);
                    button.classList.toggle('text-primary-foreground', isActive);
                    button.classList.toggle('border-border', !isActive);
                    button.classList.toggle('bg-card', !isActive);
                    button.classList.toggle('text-muted-foreground', !isActive);
                    button.setAttribute('aria-pressed', isActive ? 'true' : 'false');
                });
            };

            const buttonFor = (type) => filters.querySelector(`.activity-filter[data-filter="${type}"]`);

            const updatePagination = (data) => {
                if (!loadMoreButton) return;

                const hasItems = container.querySelector('.activity-group') !== null;

                if (data.hasMore) {
                    loadMoreButton.dataset.nextPage = data.nextPage;
                    loadMoreWrapper?.classList.remove('hidden');
                    reachEnd?.classList.add('hidden');
                } else {
                    loadMoreButton.dataset.nextPage = '';
                    loadMoreWrapper?.classList.add('hidden');
                    reachEnd?.classList.toggle('hidden', !hasItems);
                }
            };

            const load = async (type, { push = true } = {}) => {
                const url = new URL(route, window.location.origin);

                if (type && type !== 'all') {
                    url.searchParams.set('type', type);
                }

                url.searchParams.set('activity_page', '1');

                activeRequest?.abort();
                const controller = new AbortController();
                activeRequest = controller;

                buttons.forEach((button) => { button.disabled = true; });
                container.classList.add('opacity-50');

                try {
                    const response = await fetch(url.toString(), {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        },
                        signal: controller.signal,
                    });

                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }

                    const data = await response.json();

                    container.innerHTML = data.html;
                    updatePagination(data);

                    const active = buttonFor(type);
                    if (active) setActive(active);

                    if (push) {
                        const browserUrl = new URL(window.location.href);

                        if (type && type !== 'all') {
                            browserUrl.searchParams.set('type', type);
                        } else {
                            browserUrl.searchParams.delete('type');
                        }

                        browserUrl.searchParams.delete('activity_page');
                        history.pushState({ type }, '', browserUrl.toString());
                    }
                } catch (error) {
                    if (error.name === 'AbortError') return;

                    console.error(error);
                } finally {
                    if (activeRequest === controller) {
                        buttons.forEach((button) => { button.disabled = false; });
                        container.classList.remove('opacity-50');
                    }
                }
            };

            buttons.forEach((button) => {
                button.addEventListener('click', () => {
                    if (button.classList.contains('bg-primary')) return;

                    load(button.dataset.filter);
                });
            });

            window.addEventListener('popstate', (event) => {
                const type = event.state?.type
                    ?? new URL(window.location.href).searchParams.get('type')
                    ?? 'all';

                load(type, { push: false });
            });

            const initialType = new URL(window.location.href).searchParams.get('type');

            if (initialType) {
                const active = buttonFor(initialType);
                if (active) setActive(active);
            }
        });
    </script>
@endpush
