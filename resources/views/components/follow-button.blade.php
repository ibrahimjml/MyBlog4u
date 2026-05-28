<button {{ $attributes->merge([
        'class' => $stateClass([
            'accepted' => 'bg-gray-200 text-black',
            'pending'  => 'bg-yellow-500 text-white',
            'default'  => 'bg-gray-600 text-white',
        ]),
        'data-id' => $userId,
    ]) }}>
  @if($type === 'icon')
        <i class="fas fa-{{ $icon() }}"></i>
    @elseif($type === 'label')
        {{ $label() }}
    @elseif($type === 'followers')
        {{ $followersButtons() }}
    @endif
</button>