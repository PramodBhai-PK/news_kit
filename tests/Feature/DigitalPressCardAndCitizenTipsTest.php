<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\CitizenTip;
use App\Models\City;
use App\Models\State;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DigitalPressCardAndCitizenTipsTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $reporter;
    protected State $state;
    protected City $city;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Chief Editor',
            'email' => 'admin@press.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);
        $this->admin->ensurePressCardInitialized();

        $this->reporter = User::create([
            'name' => 'John Reporter',
            'email' => 'reporter@press.com',
            'password' => bcrypt('password'),
            'role' => 'reporter',
        ]);
        $this->reporter->ensurePressCardInitialized();

        $this->state = State::create([
            'name' => 'California',
            'name_en' => 'California',
            'slug' => 'california',
            'code' => 'CA',
        ]);

        $this->city = City::create([
            'state_id' => $this->state->id,
            'name' => 'Los Angeles',
            'name_en' => 'Los Angeles',
            'slug' => 'los-angeles',
        ]);

        Category::create([
            'name' => 'Politics',
            'hindi_name' => 'Politics',
            'slug' => 'politics',
            'color' => '#E11D48',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | FEATURE 1: Digital Press ID Card with Live QR Verification
    |--------------------------------------------------------------------------
    */

    public function test_reporter_can_view_own_press_id_card()
    {
        $response = $this->actingAs($this->reporter)->get(route('admin.profile.press-card'));

        $response->assertStatus(200);
        $response->assertSee('Official Digital Press ID Card');
        $response->assertSee($this->reporter->name);
        $response->assertSee($this->reporter->press_id_code);
        $response->assertSee('MEDIA ACCREDITATION CARD');
        $response->assertSee('Freedom of Speech');
        $response->assertSee('Print Press Card');
    }

    public function test_public_can_verify_valid_press_card_via_live_qr()
    {
        $verificationUrl = route('press.verify', ['code' => $this->reporter->press_id_code]);
        $response = $this->get($verificationUrl);

        $response->assertStatus(200);
        $response->assertSee('VERIFIED');
        $response->assertSee('Active & Valid Press Credential');
        $response->assertSee($this->reporter->name);
        $response->assertSee($this->reporter->press_id_code);
    }

    public function test_suspended_press_card_shows_warning_on_verification()
    {
        $this->reporter->update([
            'id_card_status' => 'suspended',
        ]);

        $response = $this->get(route('press.verify', ['code' => $this->reporter->press_id_code]));

        $response->assertStatus(200);
        $response->assertSee('ALERT');
        $response->assertSee('inactive or expired');
    }

    public function test_admin_can_update_reporter_press_card()
    {
        $response = $this->actingAs($this->admin)->post(route('admin.users.press-card.update', $this->reporter->id), [
            'press_id_code' => 'PRESS-CA-2026-9999',
            'designation' => 'Investigative Bureau Chief',
            'blood_group' => 'O+',
            'bureau_location' => 'Central News Bureau',
            'emergency_contact' => '+1 555 123 4567',
            'id_card_issued_at' => '2026-01-01',
            'id_card_expires_at' => '2027-12-31',
            'id_card_status' => 'active',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $this->reporter->id,
            'press_id_code' => 'PRESS-CA-2026-9999',
            'designation' => 'Investigative Bureau Chief',
            'blood_group' => 'O+',
            'bureau_location' => 'Central News Bureau',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | FEATURE 2: Citizen Journalism & Whistleblower News Tip Box
    |--------------------------------------------------------------------------
    */

    public function test_citizen_can_view_news_tip_form()
    {
        $response = $this->get(route('portal.citizen-tip'));

        $response->assertStatus(200);
        $response->assertSee('Citizen Journalism Box');
        $response->assertSee('1. What happened? / Main Headline');
        $response->assertSee('Keep my identity completely confidential');
        $response->assertSee('California');
    }

    public function test_citizen_can_submit_news_tip_with_evidence()
    {
        Storage::fake('public');

        $fakeImage = UploadedFile::fake()->image('road_accident_evidence.jpg');

        $response = $this->post(route('portal.citizen-tip.store'), [
            'title' => 'Major traffic congestion and road blockage near downtown',
            'description' => 'Severe traffic jam ongoing for the past 2 hours. Ambulance held up and immediate traffic management needed.',
            'location_name' => 'Downtown Central Avenue',
            'state_id' => $this->state->id,
            'city_id' => $this->city->id,
            'witness_name' => 'Alex Morgan',
            'witness_phone' => '+1 555 987 6543',
            'is_anonymous' => '0',
            'media_file' => $fakeImage,
        ]);

        $response->assertRedirect(route('portal.citizen-tip'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('citizen_tips', [
            'title' => 'Major traffic congestion and road blockage near downtown',
            'witness_name' => 'Alex Morgan',
            'status' => 'pending',
            'media_type' => 'image',
        ]);
    }

    public function test_admin_can_view_citizen_tips_list_and_details()
    {
        $tip = CitizenTip::create([
            'title' => 'Critical shortage of emergency supplies at local clinic',
            'description' => 'Patients are being forced to source medications from distant private suppliers.',
            'location_name' => 'County Hospital',
            'state_id' => $this->state->id,
            'city_id' => $this->city->id,
            'witness_name' => 'Sarah Connor',
            'witness_phone' => '+1 555 444 3333',
            'is_anonymous' => false,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.citizen-tips.index'));
        $response->assertStatus(200);
        $response->assertSee('Critical shortage of emergency supplies at local clinic');
        $response->assertSee('Sarah Connor');

        $detailResponse = $this->actingAs($this->admin)->get(route('admin.citizen-tips.show', $tip->id));
        $detailResponse->assertStatus(200);
        $detailResponse->assertSee('Convert to News Article');
    }

    public function test_admin_can_convert_citizen_tip_to_draft_news_article()
    {
        $tip = CitizenTip::create([
            'title' => 'Citizen rally protests unauthorized construction at Central Park',
            'description' => 'Local residents protested unauthorized development on park land and submitted petition.',
            'location_name' => 'Central Park, Los Angeles',
            'state_id' => $this->state->id,
            'city_id' => $this->city->id,
            'witness_name' => 'David Miller',
            'witness_phone' => '+1 555 222 1111',
            'is_anonymous' => false,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.citizen-tips.convert', $tip->id));

        $tip->refresh();
        $this->assertEquals('converted_to_article', $tip->status);
        $this->assertNotNull($tip->converted_article_id);

        $this->assertDatabaseHas('articles', [
            'id' => $tip->converted_article_id,
            'title' => 'Citizen rally protests unauthorized construction at Central Park',
            'status' => 'draft',
            'location_name' => 'Central Park, Los Angeles',
        ]);

        $response->assertRedirect(route('admin.articles.edit', $tip->converted_article_id));
        $response->assertSessionHas('success');
    }
}
