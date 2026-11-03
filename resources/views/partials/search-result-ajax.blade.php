@php
    $sections = $type === \App\Enums\SearchType::ALL
        ? $results
        : [strtolower($type->value) => $results];

    $labels = [
        'posts' => \App\Enums\SearchType::POSTS->label(),
        'users' => \App\Enums\SearchType::USERS->label(),
        'tags' => \App\Enums\SearchType::TAGS->label(),
        'categories' => \App\Enums\SearchType::CATEGORIES->label(),
        'news' => \App\Enums\SearchType::NEWS->label(),
    ];
    $icons = [
        'tags' => 'fa-hashtag',
        'categories' => 'fa-tag',
    ];
    $hasResults = collect($sections)->contains(fn ($items) => $items->isNotEmpty());
@endphp

<div class="space-y-3" data-search-results>
  <div class="flex gap-2 overflow-x-auto border-b border-border px-2 py-3">

@foreach(\App\Enums\SearchType::cases() as $tab)

<button
    type="button"
    class="search-type-tab rounded-full px-3 py-1 font-mono text-xs transition-colors
    {{ $type === $tab ? 'bg-primary text-primary-foreground' : 'bg-secondary text-muted-foreground hover:bg-accent hover:text-accent-foreground' }}"
    data-type="{{ $tab->value }}"
>
    {{ $tab->label() }}
</button>

@endforeach

</div>
  @forelse($sections as $section => $items)
    @continue($items->isEmpty())

    <section class="overflow-hidden rounded-2xl border border-border bg-card p-1">
      <div class="px-2 py-1.5 font-mono text-[11px] uppercase tracking-widest text-muted-foreground">{{ $labels[$section] }}</div>
      <div role="group" aria-label="{{ $labels[$section] }}">
        @foreach($items as $item)
          @if($section === 'posts')
          @include('partials.search.posts',['item' => $item])
          @elseif($section === 'users')
            @include('partials.search.users',['item' => $item])
          @elseif($section === 'tags')
            @include('partials.search.tags',['item' => $item,'icon' => $icons[$section]])
          @elseif($section === 'news')
            @include('partials.search.news',['item' => $item])
          @else
            @include('partials.search.categories',['item' => $item, 'icon' => $icons[$section]])
          @endif
        @endforeach
      </div>
    </section>
  @empty
    <div></div>
  @endforelse

  @if(!$hasResults)
    <div class="rounded-2xl border border-dashed border-border px-4 py-10 text-center font-mono text-xs text-muted-foreground">
      No results found for <span class="font-semibold text-foreground">{{ $searchquery }}</span>.
    </div>
  @endif
</div>
