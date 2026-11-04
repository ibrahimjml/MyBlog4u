<div class="space-y-6 mt-2">
  <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
    @forelse($news as $item)
      <article class="group overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-md">
        <a href="{{ $item->link }}" target="_blank" rel="noopener noreferrer" class="block">
          <div class="relative aspect-[16/9] bg-slate-100 overflow-hidden">
            <img src="{{ $item->image_url }}" alt="{{ $item->title }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-105" />
      @if ($item->category)
    <div class="absolute top-2 left-2 flex flex-wrap gap-1">
        @foreach (explode(',', $item->category) as $cat)
            <span class="rounded-lg px-2 py-1 bg-gray-300 text-sm tracking-[.2rem]">
                {{ trim($cat) }}
            </span>
        @endforeach
    </div>
@endif
          </div>
          <div class="p-5">
            <div class="flex flex-wrap items-center justify-between gap-2 text-xs uppercase tracking-[0.2em] text-slate-600">
              <span class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1 font-semibold text-slate-700">
                {{ optional($item->source)->name ?? 'Unknown source' }}
              </span>
              <p class="text-slate-500">{{ optional($item->created_at)->diffForHumans() }}</p>
            </div>
            <h2 class="mt-4 text-lg font-semibold text-slate-900">{{ $item->title }}</h2>
            <p class="mt-3 text-sm leading-6 text-slate-600" style="max-height: 4.5rem; overflow: hidden;">
              {!! $item->description ?? 'Read the latest story from this source.' !!}
            </p>
          </div>
        </a>
      </article>
    @empty
      <div class="col-span-full rounded-3xl border border-dashed border-slate-300 bg-slate-50 p-12 text-center text-slate-500">
        No news found for this source.
      </div>
    @endforelse
  </div>

</div>
