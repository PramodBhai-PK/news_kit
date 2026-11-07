<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Setting;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Users
        $admin = User::firstOrCreate(
            ['email' => 'admin@newskit.com'],
            [
                'name' => 'John Anderson (Editor-in-Chief)',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'bio' => 'Senior journalist and geopolitical analyst with 15+ years of experience in investigative reporting and ground journalism.',
                'avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150&auto=format&fit=crop&q=80',
            ]
        );

        $editor = User::firstOrCreate(
            ['email' => 'editor@newskit.com'],
            [
                'name' => 'Sarah Mitchell (Senior Editor)',
                'password' => Hash::make('password123'),
                'role' => 'editor',
                'bio' => 'Lead editor specializing in technology, science, and international global affairs.',
                'avatar' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?w=150&auto=format&fit=crop&q=80',
            ]
        );

        // 2. Categories
        $categoriesData = [
            [
                'name' => 'Politics',
                'hindi_name' => 'Politics',
                'slug' => 'politics',
                'color' => '#E11D48',
                'order' => 1,
                'description' => 'Elections, parliament updates, political discourse, and in-depth policy analysis.'
            ],
            [
                'name' => 'National',
                'hindi_name' => 'National',
                'slug' => 'national',
                'color' => '#2563EB',
                'order' => 2,
                'description' => 'Major breaking events, nationwide policies, and community-centered stories.'
            ],
            [
                'name' => 'Technology',
                'hindi_name' => 'Technology',
                'slug' => 'technology',
                'color' => '#7C3AED',
                'order' => 3,
                'description' => 'Artificial intelligence, newest smartphones, gadgets, and global tech developments.'
            ],
            [
                'name' => 'Entertainment',
                'hindi_name' => 'Entertainment',
                'slug' => 'entertainment',
                'color' => '#DB2777',
                'order' => 4,
                'description' => 'Film reviews, OTT releases, box office collections, and celebrity interviews.'
            ],
            [
                'name' => 'Sports',
                'hindi_name' => 'Sports',
                'slug' => 'sports',
                'color' => '#059669',
                'order' => 5,
                'description' => 'Cricket, football, Olympics, and international sporting fixtures and results.'
            ],
            [
                'name' => 'Business',
                'hindi_name' => 'Business',
                'slug' => 'business',
                'color' => '#D97706',
                'order' => 6,
                'description' => 'Stock markets, economic budget, startups, inflation, and personal finance.'
            ],
            [
                'name' => 'Viral & Fact Check',
                'hindi_name' => 'Viral & Fact Check',
                'slug' => 'viral-fact-check',
                'color' => '#EA580C',
                'order' => 7,
                'description' => 'Debunking misinformation, fact-checking claims, and verifying digital media.'
            ],
        ];

        $categories = [];
        foreach ($categoriesData as $cat) {
            $categories[$cat['slug']] = Category::create($cat);
        }

        // 3. Tags
        $tagsData = ['BreakingNews', 'Budget2026', 'ElectionWatch', 'AI Revolution', 'Cricket2026', 'CinemaExclusive', 'SpaceMission', 'Startups'];
        $tags = [];
        foreach ($tagsData as $tagName) {
            $tags[$tagName] = Tag::create(['name' => $tagName]);
        }

        // 4. Articles
        $articles = [
            [
                'title' => 'Parliament Debates Groundbreaking Digital Personal Data Protection Bill Amid Intense Deliberation',
                'slug' => 'parliament-debate-digital-data-protection-bill',
                'category_id' => $categories['politics']->id,
                'user_id' => $admin->id,
                'excerpt' => 'Special legislative session witnessed intense discourse on citizen digital rights, data security, and artificial intelligence regulations.',
                'content' => "<p><strong>New Delhi:</strong> A landmark parliamentary session convened today to debate comprehensive digital privacy protections and data security standards. The IT Minister introduced the legislation emphasizing that safeguarding individual privacy remains a foundational national priority.</p><p>Lawmakers engaged in productive discussions regarding provisions governing global technology platforms, user consent frameworks, and cross-border data flows.</p><p>The debate further addressed social media accountability, AI synthetic media, and cyber defense protocols, reaffirming that user privacy and data sovereignty remain non-negotiable.</p>",
                'featured_image' => 'https://images.unsplash.com/photo-1541872703-74c5e44368f9?w=1000&auto=format&fit=crop&q=80',
                'image_caption' => 'Parliamentary delegates discussing legislation during the special legislative session',
                'status' => 'published',
                'is_breaking' => true,
                'is_featured' => true,
                'is_trending' => true,
                'views_count' => 14200,
                'reading_time' => 4,
                'published_at' => now()->subHours(2),
            ],
            [
                'title' => 'Space Agency Achieves Major Milestone: Shukrayaan Venus Mission Finalizes Design for 2028 Launch',
                'slug' => 'isro-shukrayaan-mission-preparations-venus-launch',
                'category_id' => $categories['technology']->id,
                'user_id' => $editor->id,
                'excerpt' => 'Following Lunar and Solar exploration milestones, scientists prepare advanced orbiters to unlock the atmospheric mysteries of Venus.',
                'content' => "<p><strong>Bengaluru:</strong> The National Space Research Agency announced the completion of primary design assessments and payload configurations for its flagship Venus orbiter mission (Shukrayaan-1).</p><p>Venus possesses an extremely dense and acidic atmosphere. The scientific objectives center on examining subsurface structures, atmospheric chemistry, and cloud layers.</p><p>Astrophysicists believe that studying Venusian climate evolution offers vital insights into planetary atmospheric changes on Earth.</p>",
                'featured_image' => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?w=1000&auto=format&fit=crop&q=80',
                'image_caption' => 'Deep space mission operation facility during payload testing',
                'status' => 'published',
                'is_breaking' => true,
                'is_featured' => true,
                'is_trending' => true,
                'views_count' => 28900,
                'reading_time' => 5,
                'published_at' => now()->subHours(4),
            ],
            [
                'title' => 'World T20 Championship Final: Thrilling Last-Over Finish Secures Dramatic 5-Run Victory',
                'slug' => 't20-championship-final-india-beats-australia-thriller',
                'category_id' => $categories['sports']->id,
                'user_id' => $admin->id,
                'excerpt' => 'Defending 12 runs in the tense final over, precision bowling and exceptional fielding secure a memorable championship triumph.',
                'content' => "<p><strong>Melbourne:</strong> In one of the most exhilarating finishes in modern tournament history, a dramatic final over sealed a five-run victory before a roaring crowd of ninety thousand spectators.</p><p>The captain anchored the first innings with a commanding half-century and demonstrated clutch fielding in the closing overs.</p><p>Post-match honors were bestowed on the key all-rounder whose decisive dual contributions turned the match in crucial moments.</p>",
                'featured_image' => 'https://images.unsplash.com/photo-1540747913346-19e32dc3e97e?w=1000&auto=format&fit=crop&q=80',
                'image_caption' => 'Team players celebrate after clinching the championship trophy',
                'status' => 'published',
                'is_breaking' => true,
                'is_featured' => false,
                'is_trending' => true,
                'views_count' => 45600,
                'reading_time' => 3,
                'published_at' => now()->subHours(6),
            ],
            [
                'title' => 'Blockbuster Action Thriller Teaser Breaks Worldwide Streaming Records Within 24 Hours',
                'slug' => 'bollywood-megastar-action-thriller-teaser-records',
                'category_id' => $categories['entertainment']->id,
                'user_id' => $editor->id,
                'excerpt' => 'Featuring high-octane stunts and cutting-edge visual effects, the official teaser surpasses 50 million digital impressions.',
                'content' => "<p><strong>Mumbai:</strong> Anticipation reached fever pitch as the official teaser for the upcoming cinematic tentpole was unveiled across digital streaming platforms.</p><p>Within twelve hours of launch, the preview accumulated over fifty million cumulative views. Producers highlighted international collaboration on state-of-the-art VFX and action choreography.</p><p>The director confirmed that the feature will debut across theaters worldwide in five major international languages.</p>",
                'featured_image' => 'https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?w=1000&auto=format&fit=crop&q=80',
                'image_caption' => 'World premiere screening and festival red carpet event',
                'status' => 'published',
                'is_breaking' => false,
                'is_featured' => false,
                'is_trending' => true,
                'views_count' => 19300,
                'reading_time' => 2,
                'published_at' => now()->subHours(8),
            ],
            [
                'title' => 'Global Stock Markets Rally: Sensex Climbs 850 Points Amid Robust Technology and Banking Gains',
                'slug' => 'stock-market-rally-sensex-surges-850-points',
                'category_id' => $categories['business']->id,
                'user_id' => $admin->id,
                'excerpt' => 'Strong institutional inflows and favorable international trade indicators power broad-based gains across key industrial sectors.',
                'content' => "<p><strong>Financial District:</strong> Equities recorded broad-based advances during today's trading session, driven by heavy buying in financial and technology benchmarks.</p><p>Leading indices surged over two percent, supported by strong quarterly earnings expectations and sustained investment confidence.</p><p>Market analysts note that resilient consumer demand and corporate balance sheet strength continue to support broader market momentum.</p>",
                'featured_image' => 'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=1000&auto=format&fit=crop&q=80',
                'image_caption' => 'Trading floor and market display boards reflecting rally',
                'status' => 'published',
                'is_breaking' => false,
                'is_featured' => false,
                'is_trending' => false,
                'views_count' => 11200,
                'reading_time' => 3,
                'published_at' => now()->subDay(),
            ],
            [
                'title' => 'Fact Check: Viral Messages Claiming Government Free Laptop Scheme Debunked as Phishing Fraud',
                'slug' => 'fact-check-free-laptop-scheme-viral-whatsapp-scam',
                'category_id' => $categories['viral-fact-check']->id,
                'user_id' => $editor->id,
                'excerpt' => 'Widely circulated messages promising free computing devices verified as phishing scam aimed at harvesting user credentials.',
                'content' => "<p><strong>New Delhi:</strong> Multiple chain messages circulated across messaging platforms falsely asserting that a nationwide free laptop distribution program has been initiated by the Ministry of Electronics.</p><p><strong>Official Investigation:</strong> Our editorial verification team reviewed official government portals and confirmed that no such initiative or authorization exists.</p><p>The links embedded in these viral messages redirect to fraudulent landing pages designed to harvest sensitive user personal data and financial credentials.</p><p>Internet users are strongly cautioned against sharing unverified links or submitting private information on unauthorized websites.</p>",
                'featured_image' => 'https://images.unsplash.com/photo-1563986768609-322da13575f3?w=1000&auto=format&fit=crop&q=80',
                'image_caption' => 'Fact-checking editorial team reviewing digital authenticity',
                'status' => 'published',
                'is_breaking' => false,
                'is_featured' => false,
                'is_trending' => true,
                'views_count' => 34100,
                'reading_time' => 3,
                'published_at' => now()->subDay(),
            ],
        ];

        foreach ($articles as $artData) {
            $created = Article::create($artData);
            // Attach 2 random tags
            $created->tags()->attach([$tags['BreakingNews']->id, $tags['ElectionWatch']->id]);

            // Add sample comments
            Comment::create([
                'article_id' => $created->id,
                'user_name' => 'Robert Sterling',
                'user_email' => 'robert.sterling@example.com',
                'comment' => 'Extremely insightful and balanced coverage. This is what reputable journalism looks like.',
                'is_approved' => true,
            ]);
            Comment::create([
                'article_id' => $created->id,
                'user_name' => 'Elena Rostova',
                'user_email' => 'elena.rostova@example.com',
                'comment' => 'Very important reporting, especially for young professionals. Great presentation.',
                'is_approved' => true,
            ]);
        }

        // 5. Default Settings in DB for Single-File / Single-Page Branding
        $defaultSettings = [
            'site_name' => ['value' => 'NewsKit Portal', 'group' => 'branding', 'type' => 'text', 'label' => 'Site Name'],
            'site_tagline' => ['value' => 'Truthful, Independent & Fast News', 'group' => 'branding', 'type' => 'text', 'label' => 'Site Tagline'],
            'site_short_name' => ['value' => 'NewsKit', 'group' => 'branding', 'type' => 'text', 'label' => 'Short Name (Badge)'],
            'logo_url' => ['value' => '/images/logo.svg', 'group' => 'branding', 'type' => 'image', 'label' => 'Primary Logo URL'],
            'favicon_url' => ['value' => '/images/favicon.svg', 'group' => 'branding', 'type' => 'image', 'label' => 'Favicon URL'],
            'primary_color' => ['value' => '#E11D48', 'group' => 'branding', 'type' => 'color', 'label' => 'Primary Theme Color'],
            'accent_color' => ['value' => '#F59E0B', 'group' => 'branding', 'type' => 'color', 'label' => 'Accent / Highlight Color'],
            'breaking_ticker_enabled' => ['value' => '1', 'group' => 'portal', 'type' => 'boolean', 'label' => 'Breaking News Ticker Status'],
            'breaking_ticker_title' => ['value' => 'Breaking News', 'group' => 'portal', 'type' => 'text', 'label' => 'Ticker Heading'],
            'contact_email' => ['value' => 'editor@newskit.com', 'group' => 'general', 'type' => 'text', 'label' => 'Contact Email'],
            'contact_phone' => ['value' => '+1 (555) 019-2834', 'group' => 'general', 'type' => 'text', 'label' => 'Contact Phone'],
            'footer_about' => ['value' => 'NewsKit is an advanced, independent digital news portal delivering unbiased breaking news, investigative journalism, politics, technology, sports, and culture.', 'group' => 'general', 'type' => 'textarea', 'label' => 'Footer About Text'],
            'copyright_text' => ['value' => '© 2026 NewsKit Portal Network. All rights reserved.', 'group' => 'general', 'type' => 'text', 'label' => 'Copyright Notice'],
            'social_twitter' => ['value' => 'https://twitter.com/newskit', 'group' => 'social', 'type' => 'text', 'label' => 'X (Twitter) Link'],
            'social_facebook' => ['value' => 'https://facebook.com/newskit', 'group' => 'social', 'type' => 'text', 'label' => 'Facebook Link'],
            'social_youtube' => ['value' => 'https://youtube.com/@newskit', 'group' => 'social', 'type' => 'text', 'label' => 'YouTube Link'],
            'social_instagram' => ['value' => 'https://instagram.com/newskit', 'group' => 'social', 'type' => 'text', 'label' => 'Instagram Link'],
            'social_whatsapp' => ['value' => 'https://whatsapp.com/channel/newskit', 'group' => 'social', 'type' => 'text', 'label' => 'WhatsApp Channel Link'],
        ];

        foreach ($defaultSettings as $key => $data) {
            Setting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $data['value'],
                    'group' => $data['group'],
                    'type' => $data['type'],
                    'label' => $data['label'],
                ]
            );
        }

        // 6. Comprehensive Sample Data Seed
        $this->call([
            StateAndCitySeeder::class,
            AdvertisementSeeder::class,
            TeamUsersSeeder::class,
            VideoAndSubscribersSeeder::class,
            ExtraArticlesSeeder::class,
        ]);
    }
}
