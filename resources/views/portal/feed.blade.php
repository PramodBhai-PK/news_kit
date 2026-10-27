{!! '<' . '?xml version="1.0" encoding="UTF-8"?' . '>' !!}
<rss version="2.0"
     xmlns:content="http://purl.org/rss/1.0/modules/content/"
     xmlns:wfw="http://wellformedweb.org/CommentAPI/"
     xmlns:dc="http://purl.org/dc/elements/1.1/"
     xmlns:atom="http://www.w3.org/2005/Atom"
     xmlns:media="http://search.yahoo.com/mrss/">
    <channel>
        <title>{{ setting('site_name', 'The LallanTop News') }}</title>
        <atom:link href="{{ route('portal.feed') }}" rel="self" type="application/rss+xml" />
        <link>{{ url('/') }}</link>
        <description>{{ setting('site_tagline', 'बेबाक, सटीक और सबसे तेज़ ख़बरें') }}</description>
        <lastBuildDate>{{ now()->toRssString() }}</lastBuildDate>
        <language>{{ app()->getLocale() ?: 'hi' }}</language>
        <generator>{{ setting('site_name', 'News Kit CMS') }}</generator>

        @foreach($articles as $article)
        <item>
            <title><![CDATA[{{ $article->title }}]]></title>
            <link>{{ route('portal.article', $article->slug) }}</link>
            <guid isPermaLink="true">{{ route('portal.article', $article->slug) }}</guid>
            <pubDate>{{ ($article->published_at ?? $article->created_at)->toRssString() }}</pubDate>
            <dc:creator><![CDATA[{{ $article->author->name ?? setting('site_name') }}]]></dc:creator>
            @if($article->category)
            <category><![CDATA[{{ $article->category->name }}]]></category>
            @endif
            <description><![CDATA[{{ $article->excerpt ?: Str::limit(strip_tags($article->content), 200) }}]]></description>
            <content:encoded><![CDATA[{!! $article->content !!}]]></content:encoded>
            @if($article->featured_image_url)
            <enclosure url="{{ $article->featured_image_url }}" length="102400" type="image/jpeg" />
            <media:content url="{{ $article->featured_image_url }}" medium="image" />
            @endif
        </item>
        @endforeach
    </channel>
</rss>
