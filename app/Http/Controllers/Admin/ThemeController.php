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
            abort(403, 'Only Super Admin is authorized to change theme settings.');
        }

        $currentPrimary = setting('primary_color', '#E11D48');
        $currentAccent = setting('accent_color', '#F59E0B');

        // Sample article & category for preview switching
        $sampleArticle = Article::published()->latest('published_at')->first();
        $sampleCategory = Category::where('is_active', true)->first();

        // Curated Pro News Palettes
        $presets = [
            [
                'id' => 'editorial-crimson',
                'name' => 'Editorial Crimson',
                'tagline' => 'Bold & authoritative news vibe (Default)',
                'primary' => '#E11D48',
                'accent' => '#F59E0B',
            ],
            [
                'id' => 'breaking-red',
                'name' => 'Dynamic Breaking Red',
                'tagline' => 'High-impact breaking news look',
                'primary' => '#DC2626',
                'accent' => '#FBBF24',
            ],
            [
                'id' => 'global-blue',
                'name' => 'Deep Corporate Blue',
                'tagline' => 'International & corporate journalism',
                'primary' => '#1D4ED8',
                'accent' => '#06B6D4',
            ],
            [
                'id' => 'classic-emerald',
                'name' => 'Classic Emerald Green',
                'tagline' => 'Trustworthy, balanced & classic journalism',
                'primary' => '#059669',
                'accent' => '#10B981',
            ],
            [
                'id' => 'sunset-orange',
                'name' => 'Sunset Vibrant Orange',
                'tagline' => 'Youthful, energetic & engaging appeal',
                'primary' => '#EA580C',
                'accent' => '#FBBF24',
            ],
            [
                'id' => 'royal-amethyst',
                'name' => 'Royal Amethyst & Rose',
                'tagline' => 'Premium magazine & opinion styling',
                'primary' => '#7C3AED',
                'accent' => '#F43F5E',
            ],
            [
                'id' => 'cyber-teal',
                'name' => 'Cyber Tech & Aqua',
                'tagline' => 'Science, technology & future news',
                'primary' => '#0D9488',
                'accent' => '#2DD4BF',
            ],
            [
                'id' => 'midnight-slate',
                'name' => 'Midnight Executive Slate',
                'tagline' => 'Business, finance & dark minimal',
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
            abort(403, 'Only Super Admin is authorized to publish themes.');
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
                'message' => 'New theme colors published successfully to the live portal!',
                'primary_color' => $validated['primary_color'],
                'accent_color' => $validated['accent_color'],
            ]);
        }

        return redirect()->route('admin.theme.index')
            ->with('success', 'New theme colors published successfully to the live portal!');
    }

    public function reset(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized.');
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
