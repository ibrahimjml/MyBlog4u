<x-private-profile :user="$user" :status="$authFollowings[$user->id] ?? null">
@if(isset($user->aboutme))
  <div id="about-me" class="prose prose-lg mt-6 lg:mx-auto mx-0">{!! $user->aboutme !!}</div>
@else
<p class="text-center text-lg font-semibold mt-6">About me {{$user->username}} content goes here...</p>
@endif
</x-private-profile>