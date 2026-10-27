<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ThemeController extends Controller
{
    public function index()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'केवल प्रधान संपादक (Super Admin) को थीम व कलर सेटिंग्स बदलने की अनुमति है।');
        }

        $currentPrimary = setting('primary_color', '#E11D48');
        $currentAccent = setting('accent_color', '#F59E0B');

        // Sample article & category for preview switching
        $sampleArticle = Article::published()->latest('published_at')->first();
        $sampleCategory = Category::where('is_active', true)->first();

        // Curated Pro News Palettes
        $presets = [
            [
                'id' => 'lallantop-crimson',
                'name' => 'The LallanTop Crimson',
                'tagline' => 'बोल्ड व एग्रेसिव न्यूज़ वाइब (Original)',
                'primary' => '#E11D48',
                'accent' => '#F59E0B',
            ],
            [
                'id' => 'aajtak-red',
                'name' => 'AajTak Dynamic Red',
                'tagline' => 'सबसे तेज़ ब्रेकिंग न्यूज़ लुक',
                'primary' => '#DC2626',
                'accent' => '#FBBF24',
            ],
            [
                'id' => 'bbc-blue',
                'name' => 'BBC / Reuters Deep Blue',
                'tagline' => 'अंतरराष्ट्रीय व कॉर्पोरेट जर्नलिज्म',
                'primary' => '#1D4ED8',
                'accent' => '#06B6D4',
            ],
            [
                'id' => 'hindu-emerald',
                'name' => 'The Hindu Emerald Green',
                'tagline' => 'विश्वसनीय, शांत व क्लासिक पत्रकारिता',
                'primary' => '#059669',
                'accent' => '#10B981',
            ],
            [
                'id' => 'ndtv-orange',
                'name' => 'Sunset Vibrant Orange',
                'tagline' => 'युवा, ऊर्जावान और आकर्षक अपील',
                'primary' => '#EA580C',
                'accent' => '#FBBF24',
            ],
            [
                'id' => 'royal-amethyst',
                'name' => 'Royal Amethyst & Rose',
                'tagline' => 'प्रीमियम मैगज़ीन व ओपिनियन स्टाइल',
                'primary' => '#7C3AED',
                'accent' => '#F43F5E',
            ],
            [
                'id' => 'cyber-teal',
                'name' => 'Cyber Tech & Aqua',
                'tagline' => 'साइंस, टेक व फ्यूचरिस्टिक न्यूज़',
                'primary' => '#0D9488',
                'accent' => '#2DD4BF',
            ],
            [
                'id' => 'midnight-slate',
                'name' => 'Midnight Executive Slate',
                'tagline' => 'बिजनेस, फाइनेंस व डार्क मिनिमल',
                'primary' => '#0F172A',
                'accent' => '#64748B',
            ],
        ];

        return view('admin.theme.index', compact(
            'currentPrimary',
            'currentAccent',
            'sampleArticle',
            'sampleCategory',
            'presets'
        ));
    }

    public function publish(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'केवल प्रधान संपादक (Super Admin) को थीम पब्लिश करने की अनुमति है।');
        }

        $validated = $request->validate([
            'primary_color' => ['required', 'regex:/^#([a-f0-9]{6}|[a-f0-9]{3})$/i'],
            'accent_color' => ['required', 'regex:/^#([a-f0-9]{6}|[a-f0-9]{3})$/i'],
        ]);

        Setting::set('primary_color', $validated['primary_color'], 'branding', 'color');
        Setting::set('accent_color', $validated['accent_color'], 'branding', 'color');

        Cache::forget('app_setting_primary_color');
        Cache::forget('app_setting_accent_color');

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'नया थीम कलर सफलतापूर्वक लाइव न्यूज़ पोर्टल पर पब्लिश हो गया है!',
                'primary_color' => $validated['primary_color'],
                'accent_color' => $validated['accent_color'],
            ]);
        }

        return redirect()->route('admin.theme.index')
            ->with('success', 'नया थीम कलर सफलतापूर्वक लाइव न्यूज़ पोर्टल पर पब्लिश हो गया है!');
    }

    public function reset(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'अनुमति नहीं है।');
        }

        $primary = setting('primary_color', '#E11D48');
        $accent = setting('accent_color', '#F59E0B');

        return response()->json([
            'success' => true,
            'primary_color' => $primary,
            'accent_color' => $accent,
        ]);
    }
}
