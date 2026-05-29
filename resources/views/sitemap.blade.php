@php
echo '<?xml version="1.0" encoding="UTF-8"?>';
@endphp

<urlset
    xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
    xmlns:xhtml="http://www.w3.org/1999/xhtml"
    xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"
    xmlns:video="http://www.google.com/schemas/sitemap-video/1.1"
    xmlns:news="http://www.google.com/schemas/sitemap-news/0.9"
>

    <url>
        <loc>{{ url('/') }}</loc>
    </url>

    <url>
        <loc>{{ url('/blog') }}</loc>
    </url>

    @foreach($posts as $post)
        <url>
            <loc>{{ route('single.post', ['post' => $post->slug]) }}</loc>

            @if($post->updated_at)
                <lastmod>{{ $post->updated_at->toAtomString() }}</lastmod>
            @endif

            <changefreq>weekly</changefreq>
            <priority>0.8</priority>
        </url>
    @endforeach
    @foreach($categories as $category)
      <url>
        <loc>{{ route('viewcategory', ['category' => $category->name]) }}</loc>
        </url>
    @endforeach
    @foreach($hashtags as $hashtag)
      <url>
        <loc>{{ route('viewhashtag', ['hashtag' => $hashtag->name]) }}</loc>
        </url>
    @endforeach
</urlset>