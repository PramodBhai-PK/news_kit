<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsPosterMakerTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $reporter;
    protected Category $category;
    protected Article $article;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin Editor',
            'email' => 'editor@newskit.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $this->reporter = User::create([
            'name' => 'Senior Field Reporter',
            'email' => 'reporter@newskit.com',
            'password' => bcrypt('password'),
            'role' => 'reporter',
        ]);

        $this->category = Category::create([
            'name' => 'Politics',
            'hindi_name' => 'राजनीति',
            'slug' => 'politics',
            'color' => '#E11D48',
        ]);

        $this->article = Article::create([
            'title' => 'विधानसभा चुनाव 2026: लखनऊ में मतदान केंद्रों पर भारी सुरक्षा, मतदाताओं में दिखा भारी उत्साह',
            'slug' => 'assembly-election-2026-lucknow-polling',
            'excerpt' => 'राजधानी लखनऊ में सुबह 7 बजे से ही मतदान केंद्रों पर लंबी कतारें देखी जा रही हैं।',
            'content' => '<p>लखनऊ में शांतिपूर्ण मतदान जारी है। सुरक्षा बलों ने कड़े इंतजाम किए हैं।</p>',
            'category_id' => $this->category->id,
            'user_id' => $this->reporter->id,
            'status' => 'published',
            'is_breaking' => true,
            'views_count' => 5400,
            'location_name' => 'हजरतगंज चौराहा, लखनऊ',
            'published_at' => now(),
        ]);
    }

    public function test_public_article_page_includes_graphic_card_maker_button_and_modal()
    {
        $response = $this->withSession(['locale' => 'hi'])->get(route('portal.article', $this->article->slug));

        $response->assertStatus(200);
        
        // Assert Graphic Card Maker button in the article share bar
        $response->assertSee('open-news-poster-btn', false);
        $response->assertSee('📸 न्यूज़ कार्ड बनाएं', false);
        $response->assertSee('data-title="विधानसभा चुनाव 2026', false);
        $response->assertSee('data-category="राजनीति"', false);
        
        // Assert news poster studio modal and canvas is included
        $response->assertSee('id="news-poster-studio-modal"', false);
        $response->assertSee('id="newsPosterCanvas"', false);
        $response->assertSee('ऑटोमैटिक न्यूज़ पोस्टर व ग्राफिक कार्ड मेकर', false);
        
        // Assert aspect ratios and themes exist
        $response->assertSee('data-ratio="1:1"', false);
        $response->assertSee('data-ratio="9:16"', false);
        $response->assertSee('data-ratio="16:9"', false);
        $response->assertSee('data-theme="breaking_crimson"', false);
        $response->assertSee('data-theme="editorial_navy"', false);
        $response->assertSee('data-theme="golden_impact"', false);
        $response->assertSee('data-theme="quote_statement"', false);

        // Assert Export Buttons
        $response->assertSee('id="poster-download-png-btn"', false);
        $response->assertSee('id="poster-copy-btn"', false);
        $response->assertSee('id="poster-share-whatsapp-btn"', false);
    }

    public function test_admin_articles_index_includes_poster_action_button()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.articles.index'));

        $response->assertStatus(200);
        $response->assertSee('open-news-poster-btn', false);
        $response->assertSee('पोस्टर', false);
        $response->assertSee('title="ऑटोमैटिक सोशल मीडिया पोस्टर बनाएं"', false);
        $response->assertSee('id="news-poster-studio-modal"', false);
    }

    public function test_admin_article_edit_includes_poster_studio_button()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.articles.edit', $this->article->id));

        $response->assertStatus(200);
        $response->assertSee('open-news-poster-btn', false);
        $response->assertSee('📸 पोस्टर बनाएं (Graphic Card)', false);
        $response->assertSee('id="news-poster-studio-modal"', false);
    }

    public function test_admin_sidebar_includes_quick_launcher_for_news_poster()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('ऑटो न्यूज़ पोस्टर', false);
        $response->assertSee('Graphic Card Maker', false);
        $response->assertSee('onclick="window.openNewsPosterStudio()"', false);
    }
}
