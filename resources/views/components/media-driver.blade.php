@props([
    'selected' => null,
    'values' => [],
])

<div class="rounded-lg p-5 border-2 mt-2">
  <p class="text-lg font-bold mb-3">
    Driver
  </p>

  <div class="relative w-fit">
  <select id="media-driver" name="media_driver"
        class="pl-3 pr-8 appearance-none font-bold cursor-pointer bg-blueGray-200 text-blueGray-500 border-0 text-sm rounded-lg p-2.5"
>
    @foreach($options as $option)
      <option value="{{ $option->value }}" @selected(old('media_driver', $selected) == $option->value)>
        {{ $option->label() }}
      </option>
    @endforeach
  </select>
  <!-- Custom white arrow -->
    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
      <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path d="M19 9l-7 7-7-7" />
      </svg>
    </div>
  </div>

  <div class="mt-5">
    @foreach($fields as $driver => $driverFields)
      <div class="driver hidden" data-driver="{{ $driver }}">
      @foreach($driverFields as $field)
    <div class="mb-4">

        <label class="block mb-1">
            {{ $field['label'] }}
        </label>

        @if(($field['type'] ?? 'text')  === 'select')

            <div class="relative w-fit">
                <select
                    name="{{ $field['name'] }}"
                    class="pl-3 pr-8 appearance-none font-bold cursor-pointer bg-blueGray-200 text-blueGray-500 border-0 text-sm rounded-lg p-2.5"
                >
                    @foreach($field['options'] as $value => $label)
                        <option
                            value="{{ $value }}"
                            @selected(old($field['name'], $values[$field['name']] ?? '') == $value)
                        >
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </div>
        @else
            <input
                type="text"
                name="{{ $field['name'] }}"
                value="{{ old($field['name'], $values[$field['name']] ?? '') }}"
                placeholder="{{ $field['placeholder'] ?? '' }}"
                class="border rounded px-3 py-2 w-full max-w-xs"
            >
        @endif
    </div>
@endforeach
      </div>
    @endforeach

  </div>
</div>

<script>

  (() => {

    const select = document.getElementById('media-driver');
    const drivers = document.querySelectorAll('[data-driver]');

    function update() {
      drivers.forEach(driver => {
        driver.classList.toggle(
          'hidden',
          driver.dataset.driver !== select.value
        );
      });
    }

    select.addEventListener('change', update);
    update();
  })();

</script>