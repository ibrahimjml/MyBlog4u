@foreach ($posts as $post)
  <x-postcard :post="$post" />
  <x-ad-placement :ads="$ads" :position="\App\Enums\Adplacements\AdPosition::INNER_FEED" /><!-- Inner feed ads replace with google adsense-->
@endforeach
