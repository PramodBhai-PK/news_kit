<?php

namespace Tests\Feature;

use App\Models\Advertisement;
use App\Models\Article;
use App\Models\Category;
use App\Models\City;
use App\Models\State;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ApiV1Test extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Category $category;
    protected State $state;
    protected City $city;
    protected Article $article;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'email' => 'reporter@newskit.org',
            'password' => Hash::make('password123'),
            'role' => 'reporter',
            'is_active' => true,
        ]);

        $this->category = Category::create([
            'name' => 'Politics',
            'slug' => 'politics',
            'color' => '#E11D48',
            'order' => 1,
            'is_active' => true,
        ]);

        $this->state = State::create([
            'name' => 'Uttar Pradesh',
            'name_en' => 'Uttar Pradesh',
            'slug' => 'uttar-pradesh',
            'code' => 'UP',
            'order' => 1,
            'is_active' => true,
        ]);

        $this->city = City::create([
            'state_id' => $this->state->id,
            'name' => 'Lucknow',
            'name_en' => 'Lucknow',
            'slug' => 'lucknow',
            'order' => 1,
            'is_active' => true,
        ]);

        $this->article = Article::create([
            'title' => 'Breaking News: Election Results Announced',
            'slug' => 'breaking-news-election-results',
            'excerpt' => 'Historic outcome in recent municipal elections.',
            'content' => '<p>Detailed story regarding the municipal vote counting process.</p>',
            'category_id' => $this->category->id,
            'state_id' => $this->state->id,
            'city_id' => $this->city->id,
            'user_id' => $this->user->id,
            'status' => 'published',
            'is_breaking' => true,
            'is_featured' => true,
            'is_trending' => true,
            'published_at' => now()->subHour(),
        ]);
    }

    public function test_api_config_endpoint_returns_site_branding_and_features(): void
    {
        $response = $this->getJson('/api/v1/config');

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonStructure([
                'status',
                'data' => [
                    'app_name',
                    'app_tagline',
                    'logo_url',
                    'theme',
                    'features',
                    'whatsapp_channel',
                    'localization',
                    'monetization',
                ],
            ]);
    }

    public function test_api_articles_list_returns_paginated_articles(): void
    {
        $response = $this->getJson('/api/v1/articles');

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.slug', 'breaking-news-election-results')
            ->assertJsonStructure([
                'status',
                'data' => [
                    '*' => [
                        'id', 'title', 'slug', 'excerpt', 'category', 'location', 'author', 'views_count',
                    ],
                ],
                'meta',
                'links',
            ]);
    }

    public function test_api_articles_filters_by_category_and_location(): void
    {
        $response = $this->getJson('/api/v1/articles?category=politics&city=lucknow');

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonCount(1, 'data');

        $emptyResponse = $this->getJson('/api/v1/articles?category=sports');
        $emptyResponse->assertStatus(200)
            ->assertJsonCount(0, 'data');
    }

    public function test_api_article_detail_increments_views_and_returns_related(): void
    {
        $initialViews = $this->article->views_count;

        $response = $this->getJson("/api/v1/articles/{$this->article->slug}");

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.article.slug', $this->article->slug)
            ->assertJsonStructure([
                'status',
                'data' => [
                    'article' => ['id', 'title', 'content', 'clean_text', 'tags'],
                    'related',
                ],
            ]);

        $this->assertEquals($initialViews + 1, $this->article->fresh()->views_count);
    }

    public function test_api_breaking_trending_and_videos_endpoints(): void
    {
        $this->getJson('/api/v1/articles/breaking')
            ->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonCount(1, 'data');

        $this->getJson('/api/v1/articles/trending')
            ->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonCount(1, 'data');

        $this->getJson('/api/v1/articles/featured')
            ->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonCount(1, 'data');
    }

    public function test_api_categories_list_and_detail(): void
    {
        $response = $this->getJson('/api/v1/categories');

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.0.slug', 'politics');

        $detail = $this->getJson("/api/v1/categories/{$this->category->slug}");
        $detail->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('category.slug', 'politics');
    }

    public function test_api_hyperlocal_locations(): void
    {
        $states = $this->getJson('/api/v1/locations/states');
        $states->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.0.slug', 'uttar-pradesh');

        $cities = $this->getJson('/api/v1/locations/cities');
        $cities->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.0.slug', 'lucknow');

        $cityDetail = $this->getJson("/api/v1/locations/cities/{$this->city->slug}");
        $cityDetail->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('city.slug', 'lucknow');
    }

    public function test_api_citizen_tip_submission(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('scene.jpg');

        $response = $this->postJson('/api/v1/citizen-tips', [
            'title' => 'Bridge repair ongoing in city center',
            'description' => 'Local municipal workers began resurfacing the old bridge this morning.',
            'location_name' => 'City Bridge',
            'state_id' => $this->state->id,
            'city_id' => $this->city->id,
            'witness_name' => 'Citizen John',
            'witness_email' => 'citizen@example.com',
            'is_anonymous' => false,
            'media' => $file,
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.status', 'pending');

        $this->assertDatabaseHas('citizen_tips', [
            'title' => 'Bridge repair ongoing in city center',
            'status' => 'pending',
        ]);
    }

    public function test_api_newsletter_subscription(): void
    {
        $response = $this->postJson('/api/v1/newsletter/subscribe', [
            'email' => 'subscriber@test.com',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success');

        $this->assertDatabaseHas('subscribers', [
            'email' => 'subscriber@test.com',
        ]);
    }

    public function test_api_comments_index_and_store(): void
    {
        $response = $this->postJson("/api/v1/articles/{$this->article->slug}/comments", [
            'user_name' => 'Jane Reader',
            'user_email' => 'jane@example.com',
            'comment' => 'Very insightful report, thank you!',
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.user_name', 'Jane Reader');

        $get = $this->getJson("/api/v1/articles/{$this->article->slug}/comments");
        $get->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonCount(1, 'data');
    }

    public function test_api_advertisements_list_and_click(): void
    {
        $ad = Advertisement::create([
            'title' => 'Banner Ad',
            'type' => 'image',
            'image_url' => 'https://example.com/banner.jpg',
            'destination_url' => 'https://example.com/shop',
            'placement' => 'header',
            'is_active' => true,
            'clicks_count' => 0,
        ]);

        $list = $this->getJson('/api/v1/ads?placement=header');
        $list->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonCount(1, 'data');

        $click = $this->postJson("/api/v1/ads/{$ad->id}/click");
        $click->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.clicks_count', 1);
    }

    public function test_api_auth_flow_register_login_me_logout(): void
    {
        // 1. Register
        $register = $this->postJson('/api/v1/auth/register', [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'securepass123',
            'device_name' => 'TestPhone',
        ]);

        $register->assertStatus(201)
            ->assertJsonPath('status', 'success')
            ->assertJsonStructure(['data' => ['token', 'user']]);

        $token = $register->json('data.token');

        // 2. Profile with Bearer Token
        $me = $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/v1/auth/me');

        $me->assertStatus(200)
            ->assertJsonPath('data.email', 'newuser@example.com');

        // 3. Logout
        $logout = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/v1/auth/logout');

        $logout->assertStatus(200)
            ->assertJsonPath('status', 'success');

        // 4. Login
        $login = $this->postJson('/api/v1/auth/login', [
            'email' => 'newuser@example.com',
            'password' => 'securepass123',
            'device_name' => 'TestPhone',
        ]);

        $login->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonStructure(['data' => ['token', 'user']]);
    }
}
