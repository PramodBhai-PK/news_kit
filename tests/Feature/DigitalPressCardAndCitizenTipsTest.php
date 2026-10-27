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
            'name' => 'प्रधान संपादक (Chief Editor)',
            'email' => 'admin@press.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);
        $this->admin->ensurePressCardInitialized();

        $this->reporter = User::create([
            'name' => 'आलोक शर्मा (Senior Reporter)',
            'email' => 'alok@press.com',
            'password' => bcrypt('password'),
            'role' => 'reporter',
        ]);
        $this->reporter->ensurePressCardInitialized();

        $this->state = State::create([
            'name' => 'उत्तर प्रदेश',
            'name_en' => 'Uttar Pradesh',
            'slug' => 'uttar-pradesh',
            'code' => 'UP',
        ]);

        $this->city = City::create([
            'state_id' => $this->state->id,
            'name' => 'लखनऊ',
            'name_en' => 'Lucknow',
            'slug' => 'lucknow',
        ]);

        Category::create([
            'name' => 'Politics',
            'hindi_name' => 'राजनीति',
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
        $response->assertSee('डिजिटल प्रेस पहचान पत्र', false);
        $response->assertSee($this->reporter->name, false);
        $response->assertSee($this->reporter->press_id_code, false);
        $response->assertSee('MEDIA ACCREDITATION CARD', false);
        $response->assertSee('अनुच्छेद 19(1)(a) भारतीय संविधान', false);
        $response->assertSee('🖨️ प्रेस कार्ड प्रिंट करें', false);
    }

    public function test_public_can_verify_valid_press_card_via_live_qr()
    {
        $verificationUrl = route('press.verify', ['code' => $this->reporter->press_id_code]);
        $response = $this->get($verificationUrl);

        $response->assertStatus(200);
        $response->assertSee('सत्यापित • VERIFIED', false);
        $response->assertSee('मान्य एवं सक्रिय प्रेस पहचान पत्र', false);
        $response->assertSee($this->reporter->name, false);
        $response->assertSee($this->reporter->press_id_code, false);
        $response->assertSee('लाइव सर्वर सत्यापन टाइमस्टैम्प', false);
    }

    public function test_suspended_press_card_shows_warning_on_verification()
    {
        $this->reporter->update([
            'id_card_status' => 'suspended',
        ]);

        $response = $this->get(route('press.verify', ['code' => $this->reporter->press_id_code]));

        $response->assertStatus(200);
        $response->assertSee('चेतावनी • ALERT', false);
        $response->assertSee('अमान्य', false);
    }

    public function test_admin_can_update_reporter_press_card()
    {
        $response = $this->actingAs($this->admin)->post(route('admin.users.press-card.update', $this->reporter->id), [
            'press_id_code' => 'PRESS-UP-2026-9999',
            'designation' => 'विशेष खोजी संवाददाता (Investigative Bureau Chief)',
            'blood_group' => 'O+',
            'bureau_location' => 'लखनऊ विधानसभा ब्यूरो',
            'emergency_contact' => '+91 98765 00000',
            'id_card_issued_at' => '2026-01-01',
            'id_card_expires_at' => '2027-12-31',
            'id_card_status' => 'active',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $this->reporter->id,
            'press_id_code' => 'PRESS-UP-2026-9999',
            'designation' => 'विशेष खोजी संवाददाता (Investigative Bureau Chief)',
            'blood_group' => 'O+',
            'bureau_location' => 'लखनऊ विधानसभा ब्यूरो',
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
        $response->assertSee('सिटीज़न जर्नलिज्म बॉक्स (Citizen News Tip)', false);
        $response->assertSee('क्या घटना हुई? / मुख्य शीर्षक', false);
        $response->assertSee('मेरी पहचान पूरी तरह गोपनीय रखें (Keep Anonymous)', false);
        $response->assertSee('उत्तर प्रदेश', false);
    }

    public function test_citizen_can_submit_news_tip_with_evidence()
    {
        Storage::fake('public');

        $fakeImage = UploadedFile::fake()->image('road_accident_evidence.jpg');

        $response = $this->post(route('portal.citizen-tip.store'), [
            'title' => 'हजरतगंज चौराहे पर भीषण ट्रैफिक जाम, एम्बुलेंस फंसी',
            'description' => 'लखनऊ के हजरतगंज चौराहे पर पिछले 2 घंटे से भारी ट्रैफिक जाम लगा हुआ है। एक आपातकालीन एम्बुलेंस फंसी हुई है और कोई ट्रैफिक पुलिसकर्मी मौजूद नहीं है।',
            'location_name' => 'हजरतगंज चौराहा, निकट मेट्रो स्टेशन',
            'state_id' => $this->state->id,
            'city_id' => $this->city->id,
            'witness_name' => 'राजेश कुमार',
            'witness_phone' => '+91 98765 11111',
            'is_anonymous' => '0',
            'media_file' => $fakeImage,
        ]);

        $response->assertRedirect(route('portal.citizen-tip'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('citizen_tips', [
            'title' => 'हजरतगंज चौराहे पर भीषण ट्रैफिक जाम, एम्बुलेंस फंसी',
            'witness_name' => 'राजेश कुमार',
            'status' => 'pending',
            'media_type' => 'image',
        ]);
    }

    public function test_admin_can_view_citizen_tips_list_and_details()
    {
        $tip = CitizenTip::create([
            'title' => 'सरकारी अस्पताल में दवाओं की भारी किल्लत',
            'description' => 'मरीजों को बाहर से महंगी दवाएं खरीदने पर मजबूर किया जा रहा है।',
            'location_name' => 'जिला अस्पताल',
            'state_id' => $this->state->id,
            'city_id' => $this->city->id,
            'witness_name' => 'संजय वर्मा',
            'witness_phone' => '+91 99999 88888',
            'is_anonymous' => false,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.citizen-tips.index'));
        $response->assertStatus(200);
        $response->assertSee('सरकारी अस्पताल में दवाओं की भारी किल्लत', false);
        $response->assertSee('संजय वर्मा', false);

        $detailResponse = $this->actingAs($this->admin)->get(route('admin.citizen-tips.show', $tip->id));
        $detailResponse->assertStatus(200);
        $detailResponse->assertSee('⚡ खबर में बदलें (Convert to News Article)', false);
    }

    public function test_admin_can_convert_citizen_tip_to_draft_news_article()
    {
        $tip = CitizenTip::create([
            'title' => 'गांधी पार्क में अवैध निर्माण पर नागरिकों का कड़ा विरोध',
            'description' => 'स्थानीय निवासियों ने पार्क की जमीन पर हो रहे अवैध निर्माण का विरोध किया और नगर निगम को ज्ञापन सौंपा।',
            'location_name' => 'गांधी पार्क, लखनऊ',
            'state_id' => $this->state->id,
            'city_id' => $this->city->id,
            'witness_name' => 'सुनील गुप्ता',
            'witness_phone' => '+91 99887 76655',
            'is_anonymous' => false,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.citizen-tips.convert', $tip->id));

        $tip->refresh();
        $this->assertEquals('converted_to_article', $tip->status);
        $this->assertNotNull($tip->converted_article_id);

        $this->assertDatabaseHas('articles', [
            'id' => $tip->converted_article_id,
            'title' => 'गांधी पार्क में अवैध निर्माण पर नागरिकों का कड़ा विरोध',
            'status' => 'draft',
            'location_name' => 'गांधी पार्क, लखनऊ',
        ]);

        $response->assertRedirect(route('admin.articles.edit', $tip->converted_article_id));
        $response->assertSessionHas('success');
    }
}
