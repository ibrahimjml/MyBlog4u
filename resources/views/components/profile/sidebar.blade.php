@props([
    'user',
    'socials'
])

<aside class="space-y-6 lg:border-l lg:border-border lg:pl-7 lg:sticky lg:top-6 lg:self-start">
@unless(request()->routeIs('profile.aboutme', $user->username))
    <section>
        <h2 class="mt-2 font-display text-lg font-semibold leading-snug text-foreground border-b border-primary w-fit">About Me<b class="text-primary">.</b></h2>
        <p class="mt-2 text-sm leading-relaxed text-muted-foreground">
            {{ filled($user->aboutme) ? Str::limit(strip_tags($user->aboutme), 150) : 'No aboutme yet.' }}
        </p>
        <a href="{{ route('profile.aboutme', ['user' => $user->username]) }}"
            class="mt-3 inline-flex items-center gap-1.5 text-xs font-medium text-primary transition-opacity hover:opacity-80">
            More about {{ Str::lower($user->name) }}
            <i class="fas fa-arrow-up-right-from-square text-[10px]" aria-hidden="true"></i>
        </a>
    </section>
    <hr class="border-border">
@endunless
    
    @if(!empty($socials['builtin']) || !empty($socials['others']))
    <section>
        <p class="mt-2 font-display text-lg font-semibold leading-snug text-foreground border-b border-primary w-fit">Links<b class="text-primary">.</b></p>
        <div class="mt-3 flex flex-col gap-y-4 text-foreground text-sm font-mono w-48">
           <p class="font-mono text-[10px] uppercase tracking-[0.18em] text-muted-foreground mb-2">Social links</p>
           @foreach($socials['builtin'] as $platform => $url)
               <a href="{{ $url }}" target="_blank" rel="noopener noreferrer"
                  class="text-muted-foreground rounded-lg px-2 transition-colors hover:text-primary hover:bg-secondary"
                  aria-label="{{ ucfirst($platform) }}">
                   @switch($platform)
                       @case('github')
                           <span class="flex justify-between items-center mx-2">
                            <i class="fab fa-github"></i>
                           <p class="">Github</p>
                           </span>

                           @break
                       @case('linkedin')
                           <span class="flex justify-between items-center mx-2">
                            <i class="fab fa-linkedin"></i>
                           <p class="">Linkedin</p>
                           </span>
                           @break
                       @case('twitter')
                            <span class="flex justify-between items-center mx-2">
                            <svg fill="none" viewBox="0 0 20 20" width="20" height="20"><path stroke="currentColor" d="M8.87 11.122 3.556 4.057a.69.69 0 0 1 .551-1.103h1.907c.215 0 .418.1.548.271l4.192 5.498M8.87 11.122 4.328 17.09m4.541-5.97 4.3 5.61c.13.17.331.269.545.27l1.946.006a.69.69 0 0 0 .551-1.107l-5.456-7.177m0 0 4.427-5.799" stroke-linecap="round" stroke-width="1.25"></path></svg>
                           <p class="">X</p>
                           </span>
                           @break
                   @endswitch
               </a>
             @endforeach
             <div class="mt-4 pt-4">
                <p class="font-mono text-[10px] uppercase tracking-[0.18em] text-muted-foreground mb-2">Other links</p>

                <div class="flex flex-col gap-y-4 text-foreground text-sm font-mono w-48">
                    @foreach($socials['others'] as $other)
                        <a href="{{ $other['url'] }}" target="_blank" rel="noopener noreferrer"
                           class="text-muted-foreground rounded-lg px-2 transition-colors hover:text-primary hover:bg-secondary"
                           aria-label="{{ ucfirst($other['platform']) }}">
                            <span class="flex justify-between items-center mx-2">
                                <i class="fas fa-globe"></i>
                                <p>{{ $other['platform'] }}</p>
                            </span>
                        </a>
                    @endforeach
                </div>
            </div> 
        </div>
    </section>
    @endif
</aside>
