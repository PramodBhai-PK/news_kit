{!! '<' . '?xml version="1.0" encoding="UTF-8"?' . '>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:news="http://www.google.com/schemas/sitemap-news/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
    @foreach($articles as $article)
    <url>
        <loc>{{ route('portal.article', $article->slug) }}</loc>
        <news:news>
            <news:publication>
                <news:name>{{ htmlspecialchars(setting('site_name', 'The LallanTop News'), ENT_XML1, 'UTF-8') }}</news:name>
                <news:language>{{ app()->getLocale() ?: 'hi' }}</news:language>
            </news:publication>
            <news:publication_date>{{ ($article->published_at ?? $article->created_at)->toAtomString() }}</news:publication_date>
            <news:title>{{ htmlspecialchars($article->title, ENT_XML1, 'UTF-8') }}</news:title>
        </news:news>
        @if($article->featured_image_url)
        <image:image>
            <image:loc>{{ $article->featured_image_url }}</image:loc>
            <image:title>{{ htmlspecialchars($article->title, ENT_XML1, 'UTF-8') }}</image:title>
        </image:image>
        @endif
    </url>
    @endforeach
</urlset>
