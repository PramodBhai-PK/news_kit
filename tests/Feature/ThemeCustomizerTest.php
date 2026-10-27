<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ThemeCustomizerTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $reporter;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@theme.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $this->reporter = User::create([
            'name' => 'Reporter User',
            'email' => 'reporter@theme.com',
            'password' => bcrypt('password'),
            'role' => 'reporter',
        ]);
    }

    public function test_non_admin_cannot_access_theme_studio()
    {
        $response = $this->actingAs($this->reporter)->get(route('admin.theme.index'));

        $response->assertStatus(403);
    }

    public function test_super_admin_can_access_theme_studio_with_live_preview()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.theme.index'));

        $response->assertStatus(200);
        $response->assertSee('थीम कलर स्टूडियो', false);
        $response->assertSee('portal-preview-iframe', false);
        $response->assertSee('The LallanTop Crimson', false);
        $response->assertSee('AajTak Dynamic Red', false);
        $response->assertSee('BBC / Reuters Deep Blue', false);
    }

    public function test_super_admin_can_publish_new_theme_colors()
    {
        $response = $this->actingAs($this->admin)
            ->postJson(route('admin.theme.publish'), [
                'primary_color' => '#1D4ED8',
                'accent_color' => '#06B6D4',
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'primary_color' => '#1D4ED8',
            'accent_color' => '#06B6D4',
        ]);

        $this->assertDatabaseHas('settings', [
            'key' => 'primary_color',
            'value' => '#1D4ED8',
        ]);

        $this->assertDatabaseHas('settings', [
            'key' => 'accent_color',
            'value' => '#06B6D4',
        ]);

        // Verify the public news portal immediately renders with the new published colors
        $portalResponse = $this->get('/');
        $portalResponse->assertStatus(200);
        $portalResponse->assertSee('--brand-primary: #1D4ED8', false);
        $portalResponse->assertSee('--brand-accent: #06B6D4', false);
    }

    public function test_theme_publish_rejects_invalid_hex_colors()
    {
        $response = $this->actingAs($this->admin)
            ->postJson(route('admin.theme.publish'), [
                'primary_color' => 'not-a-color',
                'accent_color' => 'red',
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['primary_color', 'accent_color']);
    }
}
