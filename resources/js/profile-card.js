window.addEventListener('DOMContentLoaded', () => {
    let card = null;
    let activeTrigger = null;
    let hideTimer = null;

    const formatCount = (value) => Number(value ?? 0).toLocaleString();

    const removeCard = () => {
        clearTimeout(hideTimer);
        hideTimer = null;

        if (card) {
            card.remove();
            card = null;
        }

        activeTrigger = null;
    };

    const scheduleHide = () => {
        clearTimeout(hideTimer);
        hideTimer = setTimeout(removeCard, 120);
    };

    const positionCard = (trigger) => {
        const rect = trigger.getBoundingClientRect();
        const cardRect = card.getBoundingClientRect();

        let top = rect.bottom + 8;
        let left = rect.left + rect.width / 2 - cardRect.width / 2;

        left = Math.max(8, Math.min(left, window.innerWidth - cardRect.width - 8));
        if (top + cardRect.height > window.innerHeight - 8) {
            top = rect.top - cardRect.height - 8;
        }

        card.style.top = `${top}px`;
        card.style.left = `${left}px`;
    };

    const showCard = (trigger) => {
        clearTimeout(hideTimer);
        hideTimer = null;

        if (activeTrigger === trigger && card) return;

        removeCard();
        activeTrigger = trigger;

        let data;

        try {
            data = JSON.parse(trigger.dataset.avatar || '{}');
        } catch {
            return;
        }

        if (!data || !data.name) return;

        const name = data.name;
        const username = data.username;
        const avatarUrl = data.avatar_url;
        const bio = data.bio ? data.bio : '';
        const url = data.url;

        card = document.createElement('div');
        card.className = [
            'fixed z-[100] w-72 rounded-2xl border border-border bg-card p-4 text-card-foreground shadow-xl',
            'ring-1 ring-border/60 pointer-events-auto',
            'animate-pop',
        ].join(' ');

        card.innerHTML = `
            <div class="flex items-start gap-3">
                <img src="${avatarUrl}" alt="${name}"
                     class="h-12 w-12 rounded-full object-cover ring-2 ring-border">
                <div class="min-w-0">
                    <p class="truncate font-display text-sm font-semibold text-foreground">${name}</p>
                    <p class="truncate font-mono text-[11px] text-muted-foreground">@${username}</p>
                </div>
            </div>
             ${bio ? `<p class="mt-3 line-clamp-3 text-sm leading-6 text-muted-foreground">${bio}</p>` : ''}
            <div class="mt-3 grid grid-cols-4 divide-x divide-border border-t border-border pt-3 text-center">
                <div class="px-1">
                    <p class="font-display text-sm font-bold text-foreground">${formatCount(data.published_posts_count)}</p>
                    <p class="mt-0.5 font-mono text-[9px] uppercase tracking-wide text-muted-foreground">Posts</p>
                </div>
                <div class="px-1">
                    <p class="font-display text-sm font-bold text-foreground">${formatCount(data.followers_count)}</p>
                    <p class="mt-0.5 font-mono text-[9px] uppercase tracking-wide text-muted-foreground">Followers</p>
                </div>
                <div class="px-1">
                    <p class="font-display text-sm font-bold text-foreground">${formatCount(data.followings_count)}</p>
                    <p class="mt-0.5 font-mono text-[9px] uppercase tracking-wide text-muted-foreground">Following</p>
                </div>
                <div class="px-1">
                    <p class="font-display text-sm font-bold text-foreground">${formatCount(data.likes_count)}</p>
                    <p class="mt-0.5 font-mono text-[9px] uppercase tracking-wide text-muted-foreground">Likes</p>
                </div>
            </div>
            <div class="mt-3 border-t border-border pt-3">
                <a href="${url}"
                   class="flex w-full items-center justify-center gap-1 rounded-xl bg-primary px-3 py-2 font-mono text-[10px] font-bold uppercase tracking-wide text-primary-foreground transition-opacity hover:opacity-90">
                    View profile <span aria-hidden="true">&rarr;</span>
                </a>
            </div>
        `;

        card.addEventListener('mouseenter', () => {
            clearTimeout(hideTimer);
            hideTimer = null;
        });
        card.addEventListener('mouseleave', scheduleHide);

        document.body.appendChild(card);
        positionCard(trigger);
    };

    document.addEventListener('mouseover', (event) => {
        const trigger = event.target.closest('[data-slot="hover-card-trigger"]');

        if (trigger) {
            showCard(trigger);
        }
    });

    document.addEventListener('mouseout', (event) => {
        const trigger = event.target.closest('[data-slot="hover-card-trigger"]');

        if (!trigger) return;
        if (event.relatedTarget && trigger.contains(event.relatedTarget)) return;

        scheduleHide();
    });
});
