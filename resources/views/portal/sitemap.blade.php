{!! '<' . '?xml version="1.0" encoding="UTF-8"?' . '>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
    <!-- Homepage -->
    <url>
        <loc>{{ url('/') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>always</changefreq>
        <priority>1.0</priority>
    </url>

    <!-- Categories -->
    @foreach($categories as $category)
    <url>
        <loc>{{ route('portal.category', $category->slug) }}</loc>
        <lastmod>{{ $category->updated_at->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach

    <!-- Tags -->
    @foreach($tags as $tag)
    <url>
        <loc>{{ route('portal.tag', $tag->slug) }}</loc>
        <lastmod>{{ $tag->updated_at->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.6</priority>
    </url>
    @endforeach

    <!-- States / Hyperlocal Hubs -->
    @if(isset($states))
    @foreach($states as $st)
    <url>
        <loc>{{ route('portal.state', $st->slug) }}</loc>
        <lastmod>{{ $st->updated_at->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.85</priority>
    </url>
    @endforeach
    @endif

    <!-- Cities & Districts -->
    @if(isset($cities))
    @foreach($cities as $ct)
    <url>
        <loc>{{ route('portal.city', $ct->slug) }}</loc>
        <lastmod>{{ $ct->updated_at->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach
    @endif

    <!-- Published News Articles -->
    @foreach($articles as $article)
    <url>
        <loc>{{ route('portal.article', $article->slug) }}</loc>
        <lastmod>{{ ($article->published_at ?? $article->updated_at)->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>{{ $article->is_breaking || $article->is_featured ? '0.9' : '0.7' }}</priority>
        @if($article->featured_image_url)
        <image:image>
            <image:loc>{{ $article->featured_image_url }}</image:loc>
            <image:title>{{ htmlspecialchars($article->title, ENT_XML1, 'UTF-8') }}</image:title>
            @if($article->image_caption)
            <image:caption>{{ htmlspecialchars($article->image_caption, ENT_XML1, 'UTF-8') }}</image:caption>
            @endif
        </image:image>
        @endif
    </url>
    @endforeach
</urlset>
