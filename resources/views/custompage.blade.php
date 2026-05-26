<x-layout>
  <main class="max-w-4xl mx-auto px-4 py-10">
    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">{{ $page->title }}</h1>

    <article class="prose prose-lg published-content max-w-none">
      {!! $page->content !!}
    </article>
  </main>
</x-layout>
