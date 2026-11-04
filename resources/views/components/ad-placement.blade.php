@php
    $positionAds = collect($ads)
        ->filter(fn ($ad) => $ad->ad_position === $position);
    [$width, $height] = explode('x', $positionAds->first()->ad_dimension ?? '0x0');
@endphp

@foreach ($positionAds as $ad)
    <div class="mx-auto my-6 overflow-hidden rounded-xl border border-primary bg-secondary p-3" style="max-width: {{ $width }}px; height: {{ $height }}px;">
        @if ($ad->ad_type === \App\Enums\Adplacements\AdType::CUSTOM_BANNER)
            <a href="{{ $ad->link_url ?? '#' }}" target="_blank" rel="noopener noreferrer">
                <img
                    src="{{ $ad->image_url }}"
                    alt="{{ $ad->ad_name }}"
                    class="w-full h-full object-fill rounded-md"
                >
            </a>
        @elseif ($ad->ad_type === \App\Enums\Adplacements\AdType::GOOGLE_ADSENSE)
            <div class="w-full h-full">
                {!! $ad->ad_code !!}
            </div>
        @endif
    </div>
@endforeach
