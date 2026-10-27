<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Category;
use App\Models\City;
use App\Models\State;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HyperlocalLocationNewsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
    }

    public function test_states_and_cities_exist_in_database()
    {
        $this->assertDatabaseHas('states', [
            'slug' => 'uttar-pradesh',
            'name' => 'उत्तर प्रदेश',
            'name_en' => 'Uttar Pradesh',
        ]);

        $this->assertDatabaseHas('cities', [
            'slug' => 'lucknow',
            'name' => 'लखनऊ',
            'name_en' => 'Lucknow',
        ]);

        $state = State::where('slug', 'uttar-pradesh')->first();
        $this->assertNotEmpty($state->cities);
        $this->assertTrue($state->cities->contains('slug', 'lucknow'));
    }

    public function test_state_landing_page_renders_successfully()
    {
        $response = $this->get('/state/uttar-pradesh');

        $response->assertStatus(200);
        $response->assertSee('Uttar Pradesh');
        $response->assertSee('उत्तर प्रदेश');
        $response->assertSee('लखनऊ');
        $response->assertSee('राज्य विशेष कवरेज');
    }

    public function test_city_landing_page_renders_successfully()
    {
        $response = $this->get('/city/lucknow');

        $response->assertStatus(200);
        $response->assertSee('Lucknow');
        $response->assertSee('लखनऊ');
        $response->assertSee('हाइपरलोकल जिला डेस्क');
    }

    public function test_city_switcher_sets_session_and_redirects()
    {
        $response = $this->get('/set-city/lucknow');

        $response->assertRedirect();
        $response->assertSessionHas('user_city_slug', 'lucknow');
        $response->assertSessionHas('user_city_name', 'लखनऊ');
    }

    public function test_city_switcher_clear_resets_session()
    {
        $response = $this->withSession([
            'user_city_slug' => 'lucknow',
            'user_city_name' => 'लखनऊ',
        ])->get('/set-city/clear');

        $response->assertRedirect();
        $response->assertSessionMissing('user_city_slug');
        $response->assertSessionMissing('user_city_name');
    }

    public function test_homepage_renders_hyperlocal_news_hub()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('राज्य व शहर न्यूज़ हब');
        $response->assertSee('हाइपरलोकल न्यूज़ नेटवर्क');
        $response->assertSee('उत्तर प्रदेश');
        $response->assertSee('बिहार');
    }

    public function test_admin_article_creation_with_location_data()
    {
        $admin = User::where('role', 'admin')->first();
        $category = Category::first();
        $state = State::where('slug', 'uttar-pradesh')->first();
        $city = City::where('slug', 'lucknow')->first();

        $response = $this->actingAs($admin)->post(route('admin.articles.store'), [
            'title' => 'लखनऊ में शुरू हुआ नया हेरिटेज कॉरिडोर प्रोजेक्ट',
            'excerpt' => 'राजधानी लखनऊ के पुराने शहर में नया विकास कार्य शुरू हुआ।',
            'content' => '<p>लखनऊ में मुख्यमंत्री ने नए विकास कार्यों का शिलान्यास किया।</p>',
            'category_id' => $category->id,
            'state_id' => $state->id,
            'city_id' => $city->id,
            'location_name' => 'हजरतगंज, लखनऊ',
            'status' => 'published',
            'is_breaking' => false,
            'is_featured' => false,
            'is_editors_pick' => false,
        ]);

        $response->assertRedirect(route('admin.articles.index'));

        $this->assertDatabaseHas('articles', [
            'title' => 'लखनऊ में शुरू हुआ नया हेरिटेज कॉरिडोर प्रोजेक्ट',
            'state_id' => $state->id,
            'city_id' => $city->id,
            'location_name' => 'हजरतगंज, लखनऊ',
        ]);

        $article = Article::where('title', 'लखनऊ में शुरू हुआ नया हेरिटेज कॉरिडोर प्रोजेक्ट')->first();
        $this->assertEquals('हजरतगंज, लखनऊ', $article->location_badge);
        $article->location_name = null;
        $this->assertEquals('लखनऊ', $article->location_badge);
        $this->assertTrue($article->hasLocation());
    }

    public function test_xml_sitemap_includes_states_and_cities()
    {
        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);
        $response->assertSee('/state/uttar-pradesh');
        $response->assertSee('/state/bihar');
        $response->assertSee('/city/lucknow');
        $response->assertSee('/city/patna');
    }
}
