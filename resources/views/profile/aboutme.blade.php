

<div class="mt-8 grid grid-cols-1 gap-10 lg:grid-cols-[minmax(0,1fr)_16rem]">
    <div class="min-w-0">
        <p class="font-mono text-[11px] text-muted-foreground">~/about me</p>

        @if(filled($user->aboutme))
        <h1 class="mt-1 font-display text-3xl font-bold text-foreground">A little about me.</h1>
        @endif

        <div class="mt-6 space-y-4 text-base leading-relaxed text-muted-foreground">
            @if(filled($user->aboutme))
                <div id="about-me" class="prose prose-base max-w-none">{!! $user->aboutme !!}</div>
             @else
             <div class="mt-1 text-center font-display text-lg font-bold text-muted-foreground">~/No About me yet.</div>   
            @endif
        </div>

    </div>

    <x-profile.sidebar :user="$user" :socials="$userSocials"/>
</div>
