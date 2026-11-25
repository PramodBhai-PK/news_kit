<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_mobile_app_download_page_loads_successfully(): void
    {
        $response = $this->get('/download-app');

        $response->assertStatus(200);
        $response->assertSee('NewsKit');
        $response->assertSee('Download APK');
    }

    public function test_apk_download_route_redirects_or_downloads(): void
    {
        $response = $this->get('/download-apk');

        // If local APK exists, it downloads directly (200), otherwise redirects to GitHub release (302)
        $this->assertTrue(in_array($response->getStatusCode(), [200, 302]));
    }
}
