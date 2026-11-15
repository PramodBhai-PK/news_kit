<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Middleware\SetLocale;
use App\Models\Advertisement;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ConfigController extends ApiController
{
    public function index(Request $request): JsonResponse
    {
        $branding = config('branding', []);
        
        $logoUrl = setting('logo_url', $branding['logo_url'] ?? '/images/logo.svg');
        if (!str_starts_with($logoUrl, 'http')) {
            $logoUrl = asset($logoUrl);
        }

        $faviconUrl = setting('favicon_url', $branding['favicon_url'] ?? '/images/favicon.svg');
        if (!str_starts_with($faviconUrl, 'http')) {
            $faviconUrl = asset($faviconUrl);
        }

        $whatsappQr = setting('whatsapp_qr_url');
        if ($whatsappQr && !str_starts_with($whatsappQr, 'http')) {
            $whatsappQr = asset($whatsappQr);
        }

        $data = [
            'app_name' => setting('site_name', $branding['site_name'] ?? 'NewsKit Portal'),
            'app_tagline' => setting('site_tagline', $branding['site_tagline'] ?? 'Independent, Accurate & Fast Breaking News'),
            'short_name' => setting('site_short_name', $branding['site_short_name'] ?? 'NK'),
            'logo_url' => $logoUrl,
            'favicon_url' => $faviconUrl,
            'theme' => [
                'primary_color' => setting('primary_color', $branding['primary_color'] ?? '#E11D48'),
                'accent_color' => setting('accent_color', $branding['accent_color'] ?? '#F59E0B'),
                'dark_bg' => $branding['dark_bg'] ?? '#0F172A',
            ],
            'features' => [
                'breaking_ticker' => (bool) setting('breaking_ticker_enabled', $branding['breaking_ticker_enabled'] ?? true),
                'audio_reader' => (bool) setting('audio_reader_enabled', $branding['audio_reader_enabled'] ?? true),
                'comments' => (bool) setting('comments_enabled', $branding['comments_enabled'] ?? true),
                'newsletter' => (bool) setting('newsletter_enabled', $branding['newsletter_enabled'] ?? true),
                'hyperlocal' => true,
                'citizen_journalism' => true,
            ],
            'whatsapp_channel' => [
                'enabled' => true,
                'channel_url' => setting('whatsapp_channel_url', $branding['social']['whatsapp'] ?? 'https://whatsapp.com/channel/newskit'),
                'followers_count' => setting('whatsapp_followers_count', '250,000+'),
                'qr_code_url' => $whatsappQr,
            ],
            'social_links' => [
                'twitter' => setting('social_twitter', $branding['social']['twitter'] ?? ''),
                'facebook' => setting('social_facebook', $branding['social']['facebook'] ?? ''),
                'youtube' => setting('social_youtube', $branding['social']['youtube'] ?? ''),
                'instagram' => setting('social_instagram', $branding['social']['instagram'] ?? ''),
            ],
            'localization' => [
                'default_locale' => config('app.locale', 'en'),
                'current_locale' => app()->getLocale(),
                'rtl_locales' => ['ar', 'ur'],
                'supported_locales' => SetLocale::SUPPORTED_LOCALES,
            ],
            'contact' => [
                'email' => setting('contact_email', $branding['contact_email'] ?? 'editor@newskit.org'),
                'phone' => setting('contact_phone', $branding['contact_phone'] ?? '+1 (555) 019-2834'),
                'address' => setting('office_address', $branding['office_address'] ?? 'New York, United States'),
                'copyright' => setting('copyright_text', $branding['copyright_text'] ?? ('© ' . date('Y') . ' NewsKit Media Network.')),
            ],
            'monetization' => [
                'admob_enabled' => (bool) env('ADMOB_ENABLED', false),
                'admob_banner_android' => env('ADMOB_BANNER_ANDROID', ''),
                'admob_banner_ios' => env('ADMOB_BANNER_IOS', ''),
                'admob_interstitial_android' => env('ADMOB_INTERSTITIAL_ANDROID', ''),
                'admob_interstitial_ios' => env('ADMOB_INTERSTITIAL_IOS', ''),
            ],
        ];

        return $this->success($data);
    }
}

