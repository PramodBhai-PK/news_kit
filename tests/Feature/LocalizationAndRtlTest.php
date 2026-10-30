<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocalizationAndRtlTest extends TestCase
{
    use RefreshDatabase;

    public function test_default_locale_is_english_and_ltr(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('lang="en"', false);
        $response->assertSee('dir="ltr"', false);
        $response->assertSee('Home');
    }

    public function test_can_switch_to_hindi_ltr(): void
    {
        $response = $this->get('/locale/hi');

        $response->assertRedirect();
        $response->assertSessionHas('locale', 'hi');

        $portalResponse = $this->withSession(['locale' => 'hi'])->get('/');
        $portalResponse->assertStatus(200);
        $portalResponse->assertSee('lang="hi"', false);
        $portalResponse->assertSee('dir="ltr"', false);
        $portalResponse->assertSee('होम');
    }

    public function test_can_switch_to_urdu_rtl(): void
    {
        $response = $this->get('/locale/ur');

        $response->assertRedirect();
        $response->assertSessionHas('locale', 'ur');

        // Next request in same session should render with dir="rtl" and lang="ur"
        $portalResponse = $this->withSession(['locale' => 'ur'])->get('/');
        $portalResponse->assertStatus(200);
        $portalResponse->assertSee('lang="ur"', false);
        $portalResponse->assertSee('dir="rtl"', false);
        $portalResponse->assertSee('صفحۂ اول'); // Urdu "Home"
    }

    public function test_can_switch_to_arabic_rtl(): void
    {
        $response = $this->get('/locale/ar');

        $response->assertRedirect();
        $response->assertSessionHas('locale', 'ar');

        $portalResponse = $this->withSession(['locale' => 'ar'])->get('/');
        $portalResponse->assertStatus(200);
        $portalResponse->assertSee('lang="ar"', false);
        $portalResponse->assertSee('dir="rtl"', false);
        $portalResponse->assertSee('الرئيسية'); // Arabic "Home"
    }

    public function test_can_switch_to_english_ltr(): void
    {
        $response = $this->get('/locale/en');

        $response->assertRedirect();
        $response->assertSessionHas('locale', 'en');

        $portalResponse = $this->withSession(['locale' => 'en'])->get('/');
        $portalResponse->assertStatus(200);
        $portalResponse->assertSee('lang="en"', false);
        $portalResponse->assertSee('dir="ltr"', false);
        $portalResponse->assertSee('Home');
    }

    public function test_invalid_locale_fallback_to_english(): void
    {
        $response = $this->get('/locale/french_invalid');

        $response->assertRedirect();
        $this->assertEquals('en', session('locale', 'en'));
    }

    public function test_category_display_name_localizes_correctly(): void
    {
        $category = Category::create([
            'name' => 'Politics',
            'hindi_name' => 'राजनीति',
            'slug' => 'politics',
            'color' => '#E11D48',
            'order' => 1,
            'is_active' => true,
        ]);

        // Hindi
        app()->setLocale('hi');
        $this->assertEquals('राजनीति', $category->display_name);

        // English
        app()->setLocale('en');
        $this->assertEquals('Politics', $category->display_name);

        // Urdu
        app()->setLocale('ur');
        $this->assertEquals('سیاست', $category->display_name);

        // Arabic
        app()->setLocale('ar');
        $this->assertEquals('السياسة', $category->display_name);
    }
}
