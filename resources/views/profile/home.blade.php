
<div class="mt-8 grid grid-cols-1 gap-10 lg:grid-cols-[minmax(0,1fr)_16rem]">
    <div class="min-w-0">  
        @if($pinned->isNotEmpty())        
            <p class="flex items-center gap-2 font-mono text-[10px] uppercase tracking-[0.18em] text-muted-foreground">
                <i class="fas fa-thumbtack text-primary" aria-hidden="true"></i>
                {{ $pinned->count() . ' pinned ' . Str::plural('article',$pinned->count())}}
            </p>
           <div class="grid grid-cols-1 lg:grid-cols-2 gap-2">
             @foreach($pinned as $pin)
              <div class="group relative mt-3">
                 <a href="{{ route('single.post', $pin->slug) }}"
                    class="block overflow-hidden rounded-2xl border border-border bg-card">
                    <div class="relative aspect-[16/7] overflow-hidden bg-secondary">
                        <img src="{{ $pin->image_url }}" alt="{{ $pin->title }}" loading="lazy"
                            class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-[1.02]">
                          @if($pin->is_featured)
                             <span
                               class="absolute left-3 top-3 rounded-full bg-primary px-2.5 py-1 font-mono text-[10px] font-semibold uppercase tracking-widest text-primary-foreground">
                               Featured
                              </span>
                           @endif
                    </div>

                    <div class="flex items-start justify-between gap-4 p-4">
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2 font-mono text-[11px]">
                                @if($tagName = $pinnedTag[$pin->id] ?? null)
                                    <span class="text-primary">#{{ $tagName }}</span>
                                    <span class="text-border" aria-hidden="true">&middot;</span>
                                @endif
                                <span class="text-muted-foreground">{{ $pin->created_at->format('M d, Y') }}</span>
                            </div>
                            <h2 class="mt-1 font-display text-lg font-semibold leading-snug text-foreground transition-colors group-hover:text-primary">
                                {{ $pin->title }}
                            </h2>
                             @if($pin->short_excerpt)
                                <p class="mt-2 line-clamp-2 text-sm leading-relaxed text-muted-foreground">
                                    {{ $pin->short_excerpt }}
                                </p>
                            @endif
                        </div>
                        <i class="fas fa-arrow-up-right-from-square mt-1 text-xs text-muted-foreground" aria-hidden="true"></i>
                    </div>
                </a> 
                @if(auth()->check() && auth()->id() === $pin->user_id)
                    <button type="button" data-pin-id="{{ $pin->id }}" data-pinned="1" title="Unpin"
                        class="profile-pin-btn absolute right-3 top-3 flex h-7 w-7 items-center justify-center rounded-full bg-foreground/70 text-background opacity-0 transition-opacity hover:bg-foreground group-hover:opacity-100 focus-visible:opacity-100">
                        <i class="fas fa-thumbtack text-[11px] text-primary" aria-hidden="true"></i>
                    </button>
                @endif
              </div>
              @endforeach
            </div>
        @endif
        

        <div class="mt-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <h2 class="font-display text-xl font-bold text-foreground">
                Articles <span class="text-muted-foreground"> {{ '/ ' . $feedCount }}</span>
            </h2>
        </div>

        
        <div id="articles-grid" class="mt-6 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-2">
            @include('profile.partials.home-ajax')
        </div>

      <x-load-more button-id="load-more-articles"
                     container="articles-grid" 
                    :route="route('profile.home',$user->username)"
                    page-name="home_page" 
                    :current-page="$articles->currentPage()" 
                    :has-more="$articles->hasMorePages()"
                    button-text="Load More Articles" 
                    />
    </div>

    <x-profile.sidebar :user="$user" :socials="$userSocials"/>
</div>

@push('scripts')
    <script>
       document.addEventListener('click', async (e) => {
           const button = e.target.closest('.profile-pin-btn');
           if (!button) return;
       
           e.preventDefault();
       
           button.disabled = true;
       
           try {
               const response = await fetch(`/toggle/${button.dataset.pinId}/pin`, {
                   method: 'POST',
                   headers: {
                       'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                       'Accept': 'application/json',
                       'Content-Type': 'application/json',
                   },
               });
       
               const data = await response.json();
       
               if (data.status === 'limit_reached') {
                   toastr.error(data.message);
                   return;
               }
       
               toastr.success(data.status);
                  window.location.reload();
              } catch (error) {
                  console.error(error);
                  toastr.error('Something went wrong.');
              } finally {
                  button.disabled = false;
              }
          });
    </script>
@endpush
