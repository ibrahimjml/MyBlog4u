@props([
    'checked' => false,
    'label' => null,
    'name' => null,
    'value' => null,
    'disabled' => false,
    'onchange' => null,
    'wrapperClass' => 'inline-flex items-center cursor-pointer',
    'toggleClass' => 'relative w-9 h-5 rounded-full transition-colors',
    'isFeed' => false
])

<label {{ $attributes->merge(['class' => $wrapperClass]) }}>
    <input
        type="checkbox"
        class="sr-only peer"
        @if($name) name="{{ $name }}" @endif
        @if($value) value="{{ $value }}" @endif
        @if($disabled) disabled @endif
        @checked($checked)
        @if($onchange) onchange="{{ $onchange }}" @endif
        {{ $attributes }}
    >

    <div  class="{{ $toggleClass }} bg-secondary peer-checked:bg-primary peer-focus:outline-none peer-focus:ring-2
            peer-focus:ring-ring/40 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-background
            after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-full  rtl:peer-checked:after:-translate-x-full">
  </div>

    @if($label)
        <span class="ml-3 text-sm font-medium text-foreground select-none">
            {{ $label }}
        </span>
    @endif
</label>
