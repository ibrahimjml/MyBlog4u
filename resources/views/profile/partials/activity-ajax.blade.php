@use('App\Enums\UserActivityType')
@if ($activities->isEmpty())
    <p class="py-12 text-center font-display text-lg font-semibold text-muted-foreground">
        No recent activity to show.
    </p>
@endif

@foreach ($activities as $date => $entries)
    <div class="activity-group relative mb-8">
        <span class="absolute -left-[1.6rem] top-1.5 h-2 w-2 rounded-full bg-primary ring-4 ring-background"
            aria-hidden="true"></span>

        <p class="font-mono text-xs text-muted-foreground">{{ $date }}</p>

        <div class="mt-3 space-y-3">
            @foreach ($entries as $entry)
                @php
                    /** @var \App\Enums\UserActivityType $type */
                    $type = $entry['type'];
                    $anchor = $type->anchor();
                    $url = route('single.post', $entry['slug']) . $type->anchor($entry['comment_id'] ?? null);
                    $title = $type === UserActivityType::Posted ? $entry['title'] : $entry['post_title'];
                @endphp

                <a href="{{ $url }}"
                    class="group flex items-center gap-4 rounded-xl border border-border bg-card p-3 transition-colors hover:border-primary/40">
                    <span
                        class="flex h-12 w-16 shrink-0 items-center justify-center overflow-hidden rounded-lg border border-border bg-secondary">
                        @if (!empty($entry['image_url']))
                            <img src="{{ $entry['image_url'] }}" alt="" loading="lazy"
                                class="h-full w-full object-cover">
                        @else
                            <i class="far fa-file text-muted-foreground" aria-hidden="true"></i>
                        @endif
                    </span>

                    <span class="min-w-0 flex-1">
                        <span class="flex items-center gap-2 font-mono text-[11px] text-muted-foreground">
                            <i class="{{ $type->icon() }} text-primary" aria-hidden="true"></i>
                            <span>{{ $type->label() }}</span>
                        </span>
                        <span
                            class="mt-0.5 block truncate font-display text-sm font-semibold text-foreground transition-colors group-hover:text-primary">
                            {{ $title }}
                        </span>
                    </span>

                    <i class="fas fa-arrow-up-right-from-square text-[10px] text-muted-foreground"
                        aria-hidden="true"></i>
                </a>
            @endforeach
        </div>
    </div>
@endforeach