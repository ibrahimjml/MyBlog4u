@props([
    'model',
    'sliders',
    'type' => 'Category',
])

@php
    $slideClass = 'slider-' . strtolower($type);
@endphp

<div class="relative my-6 h-48 w-full overflow-hidden rounded-2xl border border-border bg-secondary lg:h-72">

    @if($model->posts_count > 0)

        @foreach($sliders as $index => $slide)
            <img src="{{ $slide->image_url }}" alt="{{ $slide->title }}"
                class="{{ $slideClass }} absolute inset-0 h-full w-full object-center transition-opacity duration-1000 {{ $index === 0 ? 'opacity-100' : 'opacity-0' }}">
        @endforeach

        <div class="absolute inset-0 bg-foreground/60"></div>

        <div class="absolute inset-0 flex flex-col justify-end p-6 text-background md:p-8">
            <p class="font-mono text-[11px] uppercase tracking-[.3em] text-background/70">
                {{ $type }}
            </p>
            <h1 class="mt-2 font-display text-3xl font-bold tracking-tight md:text-4xl">
                {{ $model->name }}
            </h1>
            <p class="mt-1 font-mono text-xs text-background/80">
                {{ $model->posts_count }} {{ Str::plural('article', $model->posts_count) }}
            </p>
        </div>

    @else

        <div class="absolute inset-0 flex items-center justify-center p-6 text-center">
            <div>
                <p class="font-display text-2xl font-semibold text-foreground">
                    {{ $model->name }}
                </p>
                <p class="mt-2 font-mono text-xs text-muted-foreground">
                    This {{ strtolower($type) }} has no articles yet.
                </p>
            </div>
        </div>

    @endif

</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const slides = document.querySelectorAll('.{{ $slideClass }}');

            if (slides.length <= 1) return;

            let current = 0;

            setInterval(() => {
                slides[current].classList.remove('opacity-100');
                slides[current].classList.add('opacity-0');

                current = (current + 1) % slides.length;

                slides[current].classList.remove('opacity-0');
                slides[current].classList.add('opacity-100');
            }, 4000);
        });
    </script>
@endpush
