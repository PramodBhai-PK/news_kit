<?php

namespace Tests\Feature;

use App\Models\Advertisement;
use App\Models\Article;
use App\Models\Category;
use App\Models\Subscriber;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsletterAndVideoTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $this->category = Category::create([
            'name' => 'National',
            'slug' => 'national',
            'color' => '#ef4444',
            'is_active' => true,
        ]);
    }

    public function test_user_can_subscribe_to_newsletter_via_json(): void
    {
        $response = $this->postJson('/newsletter/subscribe', [
            'email' => 'reader@example.com',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseHas('subscribers', [
            'email' => 'reader@example.com',
            'is_active' => true,
        ]);
    }

    public function test_user_can_subscribe_to_newsletter_via_web_form(): void
    {
        $response = $this->post('/newsletter/subscribe', [
            'email' => 'portaluser@example.com',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('subscribers', [
            'email' => 'portaluser@example.com',
            'is_active' => true,
        ]);
    }

    public function test_newsletter_subscription_requires_valid_email(): void
    {
        $response = $this->postJson('/newsletter/subscribe', [
            'email' => 'not-an-email',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_admin_can_view_and_manage_subscribers(): void
    {
        $sub = Subscriber::create([
            'email' => 'testsub@example.com',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/subscribers');
        $response->assertStatus(200);
        $response->assertSee('testsub@example.com');
        $response->assertSee('न्यूज़लेटर सब्सक्राइबर्स');

        // Toggle subscriber status
        $toggleResponse = $this->actingAs($this->admin)->post("/admin/subscribers/{$sub->id}/toggle");
        $toggleResponse->assertRedirect();
        $this->assertFalse($sub->fresh()->is_active);

        // Delete subscriber
        $delResponse = $this->actingAs($this->admin)->delete("/admin/subscribers/{$sub->id}");
        $delResponse->assertRedirect();
        $this->assertDatabaseMissing('subscribers', ['id' => $sub->id]);
    }

    public function test_article_with_youtube_video_renders_embed_player(): void
    {
        $article = Article::create([
            'title' => 'Breaking News YouTube Video Story',
            'slug' => 'youtube-video-story',
            'content' => 'Full story content with YouTube coverage',
            'category_id' => $this->category->id,
            'user_id' => $this->admin->id,
            'video_type' => 'youtube',
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $response = $this->get("/article/{$article->slug}");
        $response->assertStatus(200);
        $response->assertSee('youtube.com/embed/dQw4w9WgXcQ');
        $response->assertSee('YouTube Video Coverage');
    }

    public function test_article_with_direct_mp4_video_renders_video_tag(): void
    {
        $article = Article::create([
            'title' => 'Exclusive Direct Video Story',
            'slug' => 'direct-video-story',
            'content' => 'Full story content with MP4 video',
            'category_id' => $this->category->id,
            'user_id' => $this->admin->id,
            'video_type' => 'direct',
            'video_url' => 'https://example.com/stream.mp4',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $response = $this->get("/article/{$article->slug}");
        $response->assertStatus(200);
        $response->assertSee('<video', false);
        $response->assertSee('https://example.com/stream.mp4');
        $response->assertSee('Exclusive Digital Video Report');
    }

    public function test_homepage_renders_video_bulletin_gallery(): void
    {
        Article::create([
            'title' => 'Featured Video for Bulletin',
            'slug' => 'featured-video-bulletin',
            'content' => 'Video content',
            'category_id' => $this->category->id,
            'user_id' => $this->admin->id,
            'video_type' => 'youtube',
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Video Bulletin & Special Coverage');
        $response->assertSee('Featured Video for Bulletin');
    }

    public function test_global_ad_click_increments_clicks_count_and_redirects(): void
    {
        $ad = Advertisement::create([
            'title' => 'Test Sidebar Ad',
            'type' => 'image',
            'image_url' => 'https://example.com/ad.jpg',
            'destination_url' => 'https://example.com/shop',
            'placement' => 'sidebar',
            'is_active' => true,
            'clicks_count' => 0,
        ]);

        $response = $this->get("/ad/{$ad->id}/click");
        $response->assertRedirect('https://example.com/shop');
        $this->assertEquals(1, $ad->fresh()->clicks_count);
    }

    public function test_custom_article_ad_click_increments_clicks_count_and_redirects(): void
    {
        $article = Article::create([
            'title' => 'Article with Custom Ad',
            'slug' => 'article-with-custom-ad',
            'content' => 'Article content',
            'category_id' => $this->category->id,
            'user_id' => $this->admin->id,
            'status' => 'published',
            'custom_ad_type' => 'image',
            'custom_ad_image' => 'https://example.com/custom-ad.jpg',
            'custom_ad_link' => 'https://partner.com/deal',
            'custom_ad_clicks_count' => 0,
            'published_at' => now(),
        ]);

        $response = $this->get("/article/{$article->id}/ad-click");
        $response->assertRedirect('https://partner.com/deal');
        $this->assertEquals(1, $article->fresh()->custom_ad_clicks_count);
    }

    public function test_visiting_article_increments_custom_ad_views(): void
    {
        $article = Article::create([
            'title' => 'Article with Active Custom Ad',
            'slug' => 'article-with-active-custom-ad',
            'content' => 'Article content',
            'category_id' => $this->category->id,
            'user_id' => $this->admin->id,
            'status' => 'published',
            'custom_ad_type' => 'image',
            'custom_ad_image' => 'https://example.com/custom-ad.jpg',
            'custom_ad_link' => 'https://partner.com/deal',
            'custom_ad_views_count' => 0,
            'published_at' => now(),
        ]);

        $this->get("/article/{$article->slug}");
        $this->assertEquals(1, $article->fresh()->custom_ad_views_count);
    }
}
