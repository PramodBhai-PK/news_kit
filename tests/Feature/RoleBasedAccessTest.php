<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleBasedAccessTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $reporter;
    protected User $editor;
    protected Category $category;
    protected Article $reporterArticle;
    protected Article $otherArticle;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $this->editor = User::create([
            'name' => 'Editor User',
            'email' => 'editor@test.com',
            'password' => bcrypt('password'),
            'role' => 'editor',
        ]);

        $this->reporter = User::create([
            'name' => 'Reporter User',
            'email' => 'reporter@test.com',
            'password' => bcrypt('password'),
            'role' => 'reporter',
        ]);

        $this->category = Category::create([
            'name' => 'National',
            'slug' => 'national',
            'color' => '#ef4444',
        ]);

        $this->reporterArticle = Article::create([
            'title' => 'Reporter Own Story',
            'slug' => 'reporter-own-story',
            'content' => 'Story content',
            'category_id' => $this->category->id,
            'user_id' => $this->reporter->id,
            'status' => 'published',
        ]);

        $this->otherArticle = Article::create([
            'title' => 'Other Author Story',
            'slug' => 'other-author-story',
            'content' => 'Other content',
            'category_id' => $this->category->id,
            'user_id' => $this->admin->id,
            'status' => 'published',
        ]);
    }

    public function test_admin_can_access_team_management_and_settings(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/users');
        $response->assertStatus(200);
        $response->assertSee('टीम व यूज़र्स प्रबंधन');

        $response = $this->actingAs($this->admin)->get('/admin/settings');
        $response->assertStatus(200);
    }

    public function test_reporter_dashboard_is_scoped(): void
    {
        $response = $this->actingAs($this->reporter)->get('/admin/dashboard');
        $response->assertStatus(200);
        $response->assertSee('रिपोर्टर डैशबोर्ड');
        $response->assertSee('मेरी कुल ख़बरें');
    }

    public function test_reporter_cannot_access_team_management_or_settings(): void
    {
        $response = $this->actingAs($this->reporter)->get('/admin/users');
        $response->assertStatus(403);

        $response = $this->actingAs($this->reporter)->get('/admin/settings');
        $response->assertStatus(403);
    }

    public function test_reporter_can_manage_own_profile(): void
    {
        $response = $this->actingAs($this->reporter)->get('/admin/profile');
        $response->assertStatus(200);
        $response->assertSee('व्यक्तिगत विवरण');
    }

    public function test_reporter_cannot_edit_other_author_article(): void
    {
        $response = $this->actingAs($this->reporter)->get("/admin/articles/{$this->otherArticle->id}/edit");
        $response->assertStatus(403);

        // But reporter CAN edit their own article
        $response = $this->actingAs($this->reporter)->get("/admin/articles/{$this->reporterArticle->id}/edit");
        $response->assertStatus(200);
    }
}
