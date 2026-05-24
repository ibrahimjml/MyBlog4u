@props([
    'checked' => false,
    'label' => null,
    'name' => null,
    'value' => null,
    'disabled' => false,
    'onchange' => null,
    'wrapperClass' => 'inline-flex items-center cursor-pointer',
    'toggleClass' => 'relative w-9 h-5 rounded-full transition-colors',
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

    <div  class="{{ $toggleClass }} bg-blueGray-200 peer-checked:bg-black peer-focus:outline-none peer-focus:ring-4
            peer-focus:ring-brand-soft after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white
            after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-full  rtl:peer-checked:after:-translate-x-full">
  </div>

    @if($label)
        <span class="ml-3 text-sm font-medium text-gray-900 select-none">
            {{ $label }}
        </span>
    @endif
</label>
