<?php

namespace Database\Seeders;

use App\Models\Advertisement;
use App\Models\Article;
use Illuminate\Database\Seeder;

class AdvertisementSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Clean existing or insert multiple rich sidebar advertisements
        Advertisement::truncate();

        // Ad 1: Top Leaderboard Banner for Homepage Header
        Advertisement::create([
            'title' => 'अमेज़न व फ्लिपकार्ट मेगा इलेक्ट्रॉनिक्स कार्निवल 2026',
            'type' => 'image',
            'image_url' => 'https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?w=1200&auto=format&fit=crop&q=80',
            'destination_url' => 'https://amazon.in',
            'placement' => 'header',
            'order' => 1,
            'is_active' => true,
            'views_count' => 3120,
            'clicks_count' => 140,
        ]);

        // Ad 2: Google AdSense / In-Feed Responsive Code Ad Unit
        $adsenseMockHtml = <<<'HTML'
<div class="w-full p-4 rounded-2xl bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-900 dark:to-slate-950 border border-slate-200 dark:border-slate-800 text-center shadow-xs">
    <div class="flex items-center justify-between text-[10px] text-slate-400 font-bold mb-2">
        <span class="flex items-center gap-1">
            <svg class="w-3 h-3 fill-blue-500" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
            Ads by Google
        </span>
        <span class="border border-slate-300 dark:border-slate-700 px-1 rounded text-[9px]">AdSense</span>
    </div>
    <div class="py-2">
        <h5 class="font-extrabold text-sm text-slate-900 dark:text-white mb-1">Google Cloud & AI Platform</h5>
        <p class="text-xs text-slate-600 dark:text-slate-400 mb-3">Build and scale intelligent web apps with $300 free cloud credits and Google Gemini API access.</p>
        <a href="https://cloud.google.com" target="_blank" rel="noopener sponsored" class="inline-block px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow transition-all">
            Start Free Trial →
        </a>
    </div>
</div>
HTML;

        Advertisement::create([
            'title' => 'Google AdSense (क्लाउड व AI रिस्पॉन्सिव बैनर)',
            'type' => 'code',
            'html_code' => $adsenseMockHtml,
            'placement' => 'in_feed',
            'order' => 1,
            'is_active' => true,
            'views_count' => 2340,
            'clicks_count' => 112,
        ]);

        // Ad 3: Smartphone sale banner (Sidebar)
        Advertisement::create([
            'title' => 'सैमसंग गैलेक्सी AI अल्ट्रा - भारी छूट व नो-कॉस्ट EMI',
            'type' => 'image',
            'image_url' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=600&auto=format&fit=crop&q=80',
            'destination_url' => 'https://samsung.com',
            'placement' => 'sidebar',
            'order' => 1,
            'is_active' => true,
            'views_count' => 980,
            'clicks_count' => 65,
        ]);

        // Ad 4: Tech Gadgets Banner (Sidebar)
        Advertisement::create([
            'title' => 'सोनी ब्राविया 4K स्मार्ट टीवी - एक्सक्लूसिव एक्सचेंज बोनस',
            'type' => 'image',
            'image_url' => 'https://images.unsplash.com/photo-1550745165-9bc0b252726f?w=600&auto=format&fit=crop&q=80',
            'destination_url' => 'https://sony.co.in',
            'placement' => 'sidebar',
            'order' => 2,
            'is_active' => true,
            'views_count' => 840,
            'clicks_count' => 52,
        ]);

        // 2. Attach reporter custom ads to specific articles for demonstration
        $aiArticle = Article::where('slug', 'chatgpt-5-and-gemini-next-gen-launch-features')->first();
        if ($aiArticle) {
            $aiArticle->update([
                'custom_ad_title' => 'विशेष ऑफर: फुल-स्टैक AI इंजीनियर सर्टिफिकेशन (50% फ्लैट छूट)',
                'custom_ad_type' => 'image',
                'custom_ad_image' => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?w=600&auto=format&fit=crop&q=80',
                'custom_ad_link' => 'https://example.com/ai-masterclass',
            ]);
        }

        $vandeArticle = Article::where('slug', 'vande-bharat-sleeper-10-new-routes-announced')->first();
        if ($vandeArticle) {
            $irctcAdHtml = <<<'HTML'
<div class="w-full p-4 rounded-2xl bg-gradient-to-r from-amber-500/10 to-orange-500/10 border border-amber-500/30 text-center">
    <div class="flex items-center justify-between text-[10px] text-amber-600 font-bold mb-1.5">
        <span>Google AdSense Partner</span>
        <span class="bg-amber-500 text-white px-1.5 py-0.5 rounded text-[9px] font-black">AD</span>
    </div>
    <h5 class="font-black text-sm text-slate-900 dark:text-white mb-1">IRCTC देखो अपना देश: स्पेशल समर टूर पैकेजेस</h5>
    <p class="text-xs text-slate-600 dark:text-slate-300 mb-3">वंदे भारत स्लीपर और लग्जरी होटल्स बुकिंग पर 20% तक की भारी बचत।</p>
    <a href="https://irctctourism.com" target="_blank" rel="noopener sponsored" class="inline-block px-4 py-1.5 bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs rounded-xl shadow transition-colors">
        अभी सीट बुक करें →
    </a>
</div>
HTML;

            $vandeArticle->update([
                'custom_ad_title' => 'आईआरसीटीसी स्पेशल टूर पार्टनर (Google AdSense)',
                'custom_ad_type' => 'code',
                'custom_ad_code' => $irctcAdHtml,
            ]);
        }
    }
}
