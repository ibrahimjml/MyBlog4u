@php
    $positionAds = collect($ads)
        ->filter(fn ($ad) => $ad->ad_position === $position);
@endphp
@foreach ($positionAds as $ad)
    <div class="max-w-7xl mx-auto my-6 p-4 bg-gray-100 rounded-lg shadow-md">
        @if ($ad->ad_type === \App\Enums\Adplacements\AdType::CUSTOM_BANNER)
            <a href="{{ $ad->link_url ?? '#' }}" target="_blank" rel="noopener noreferrer">
                <img
                    src="{{ $ad->image_path ? \Illuminate\Support\Facades\Storage::url($ad->image_path) : '' }}"
                    alt="{{ $ad->ad_name }}"
                    class="w-full h-auto object-cover rounded-md"
                >
            </a>
        @elseif ($ad->ad_type === \App\Enums\Adplacements\AdType::GOOGLE_ADSENSE)
            <div class="w-full h-auto">
                {!! $ad->ad_code !!}
            </div>
        @endif
    </div>
@endforeach
