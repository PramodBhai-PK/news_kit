<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'केवल प्रधान संपादक (Super Admin) को ब्रांडिंग सेटिंग्स बदलने की अनुमति है।');
        }

        $settings = Setting::all()->pluck('value', 'key')->toArray();

        // Merge with config defaults if not in DB
        $defaults = config('branding');
        foreach ($defaults as $k => $v) {
            if (!isset($settings[$k]) && !is_array($v)) {
                $settings[$k] = $v;
            }
        }

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'केवल प्रधान संपादक (Super Admin) को ब्रांडिंग सेटिंग्स बदलने की अनुमति है।');
        }

        $inputs = $request->except(['_token', 'logo_file', 'favicon_file', 'whatsapp_qr_file']);

        // Handle Logo Upload
        if ($request->hasFile('logo_file')) {
            $path = $request->file('logo_file')->store('branding', 'public');
            $inputs['logo_url'] = Storage::url($path);
        }

        // Handle Favicon Upload
        if ($request->hasFile('favicon_file')) {
            $path = $request->file('favicon_file')->store('branding', 'public');
            $inputs['favicon_url'] = Storage::url($path);
        }

        // Handle WhatsApp Channel QR Upload
        if ($request->hasFile('whatsapp_qr_file')) {
            $path = $request->file('whatsapp_qr_file')->store('branding', 'public');
            $inputs['whatsapp_qr_url'] = Storage::url($path);
        }

        foreach ($inputs as $key => $val) {
            // Determine type and group
            $group = 'general';
            $type = 'text';

            if (in_array($key, ['site_name', 'site_tagline', 'site_short_name', 'logo_url', 'favicon_url', 'primary_color', 'accent_color'])) {
                $group = 'branding';
            } elseif (str_starts_with($key, 'social_')) {
                $group = 'social';
            } elseif (in_array($key, ['breaking_ticker_enabled', 'breaking_ticker_title'])) {
                $group = 'portal';
            }

            if (in_array($key, ['primary_color', 'accent_color'])) {
                $type = 'color';
            } elseif (in_array($key, ['footer_about', 'meta_description'])) {
                $type = 'textarea';
            }

            Setting::set($key, $val, $group, $type);
            Cache::forget('app_setting_' . $key);
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'पूरी वेबसाइट और CMS की ब्रांडिंग, लोगो और सेटिंग्स सफलतापूर्वक अपडेट हो गई हैं!');
    }
}
