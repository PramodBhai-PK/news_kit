<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoArchitectureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed');
    }

    public function test_xml_sitemap_renders_successfully()
    {
        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/xml; charset=utf-8');
        $response->assertSee('<urlset', false);
        $response->assertSee('<loc>', false);
        $response->assertSee(url('/'), false);
    }

    public function test_google_news_sitemap_renders_successfully()
    {
        $response = $this->get('/news-sitemap.xml');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/xml; charset=utf-8');
        $response->assertSee('xmlns:news="http://www.google.com/schemas/sitemap-news/0.9"', false);
        $response->assertSee('<news:publication>', false);
        $response->assertSee('<news:title>', false);
    }

    public function test_rss_feed_renders_successfully()
    {
        $response = $this->get('/feed');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/rss+xml; charset=utf-8');
        $response->assertSee('<rss version="2.0"', false);
        $response->assertSee('<channel>', false);
        $response->assertSee('<item>', false);
    }

    public function test_rss_xml_alias_redirects_to_feed()
    {
        $response = $this->get('/rss.xml');

        $response->assertRedirect('/feed');
    }

    public function test_homepage_contains_enterprise_seo_tags_and_website_schema()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('max-image-preview:large', false);
        $response->assertSee('googlebot-news', false);
        $response->assertSee('hreflang="hi"', false);
        $response->assertSee('hreflang="en"', false);
        $response->assertSee('hreflang="ur"', false);
        $response->assertSee('hreflang="ar"', false);
        $response->assertSee('"@type": "WebSite"', false);
        $response->assertSee('"@type": "SearchAction"', false);
        $response->assertSee('"@type": "NewsMediaOrganization"', false);
    }

    public function test_article_page_contains_news_article_schema_and_opengraph_image()
    {
        $article = Article::published()->first();

        $this->assertNotNull($article);

        $response = $this->get(route('portal.article', $article->slug));

        $response->assertStatus(200);
        $response->assertSee('"@type": "NewsArticle"', false);
        $response->assertSee('"@type": "BreadcrumbList"', false);
        $response->assertSee('property="og:type" content="article"', false);
        $response->assertSee('name="twitter:card" content="summary_large_image"', false);
    }
}
