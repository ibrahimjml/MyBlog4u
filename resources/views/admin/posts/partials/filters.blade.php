<div class="flex flex-col md:flex-row md:items-center flex-wrap gap-4 ml-3">

  <div class="flex flex-col sm:flex-row gap-2 items-stretch sm:items-center">
    <!-- Sort Form -->
    <x-forms.filter-form exclude="sort">
      <div class="relative w-fit">
        <select id="sort" name="sort"
          class="pl-3 pr-8 appearance-none font-bold cursor-pointer bg-blueGray-200 text-blueGray-500 border-0 text-sm rounded-lg p-2.5"
          onchange="this.form.submit()">
          <option value="">All</option>
          <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Latest</option>
          <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest</option>
          <hr>
          @foreach (\App\Enums\PostStatus::cases() as $status)
          <option value="{{$status->value}}" {{request('sort') === $status->value ? 'selected' : ''}}>{{$status->label()}}</option>
          @endforeach
        </select>
        <!-- Custom white arrow -->
        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
          <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M19 9l-7 7-7-7" />
          </svg>
        </div>
      </div>
    </x-forms.filter-form>

    <!-- Featured Checkbox -->
    <div class="bg-blueGray-200 rounded-md p-2 h-10 w-fit flex items-center">
    <x-forms.filter-form exclude="featured" class="flex items-center gap-1">
        <input type="checkbox" name="featured" value="1" {{ request('featured') ? 'checked' : '' }}
          onchange="this.form.submit()" class="rounded-full w-4 h-4">
        <label class="text-blueGray-500 font-semibold" for="featured">Featured</label>
    </x-forms.filter-form>
    </div>
    <!-- Reported Checkbox -->
    <div class="bg-blueGray-200 rounded-md p-2 h-10 w-fit flex items-center">
      <x-forms.filter-form exclude="reported" class="flex items-center gap-1">
        <input type="checkbox" name="reported" value="1" {{ request('reported') ? 'checked' : '' }}
          onchange="this.form.submit()" class="rounded-full w-4 h-4">
        <label class="text-blueGray-500 font-semibold" for="reported">Reported</label>
      </x-forms.filter-form>
    </div>
  </div>
    <!-- Clear Filters  -->
    @if(request()->anyFilled(['search', 'sort', 'featured', 'reported']))
      <a href="{{ url()->current() }}" 
         class="bg-red-500 hover:bg-red-600 text-white w-fit font-semibold py-2 px-4 rounded-lg text-sm transition duration-200 flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
        Clear Filters
      </a>
    @endif
</div>