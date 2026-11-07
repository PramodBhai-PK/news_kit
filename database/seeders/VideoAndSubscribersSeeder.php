<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\Subscriber;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class VideoAndSubscribersSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first() ?: User::first();
        $reporter = User::where('role', 'reporter')->first() ?: $admin;
        $categorySports = Category::where('slug', 'sports')->first() ?: Category::first();
        $categoryTech = Category::where('slug', 'technology')->first() ?: Category::first();
        $categoryNational = Category::where('slug', 'national')->first() ?: Category::first();

        // 1. YouTube Video Article 1 (Special Investigation)
        Article::updateOrCreate(
            ['slug' => 'ground-report-special-video-bulletin'],
            [
                'title' => 'Ground Report: Exclusive On-Site Video Coverage of Metro Rail Phase 4 Transit Expansion',
                'excerpt' => 'Exclusive on-site video tour highlighting underground tunneling and new interchange stations across the metropolitan region.',
                'content' => '<p>The Metropolitan Rapid Transit Corporation has accelerated underground infrastructure development at record speeds. In this special investigative report, explore tunnel boring machine (TBM) operations and upcoming passenger safety systems.</p><p>Transportation authorities estimate the new high-capacity corridor will serve over 500,000 daily commuters, trimming commute times by up to 40 minutes. Watch the complete video bulletin in the player above.</p>',
                'featured_image' => 'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?w=1200&auto=format&fit=crop&q=80',
                'image_caption' => 'Metro transit expansion corridor construction - special on-site video coverage',
                'video_type' => 'youtube',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'category_id' => $categoryNational?->id ?? 1,
                'user_id' => $reporter?->id ?? 1,
                'status' => 'published',
                'is_breaking' => false,
                'is_featured' => true,
                'is_trending' => true,
                'views_count' => 14200,
                'reading_time' => 3,
                'published_at' => now()->subHours(2),
            ]
        );

        // 2. Direct MP4 Video Article (Tech & Space Launch)
        Article::updateOrCreate(
            ['slug' => 'isro-next-gen-rocket-video-test'],
            [
                'title' => 'Exclusive Video: High-Thrust Hot Fire Test of Next-Gen Semi-Cryogenic Rocket Engine Completed',
                'excerpt' => 'Space agency releases footage of successful 500-second long-duration propulsion trials conducted at the cryogenic test facility.',
                'content' => '<p>Aerospace engineers successfully completed a comprehensive 500-second qualification test of the new high-thrust propulsion stage. This milestone marks a critical turning point for heavy-payload orbital launch vehicle programs.</p><p>The official high-definition test stream has been made available directly for viewers in the embedded video module above.</p>',
                'featured_image' => 'https://images.unsplash.com/photo-1541185933-ef5d8ed016c2?w=1200&auto=format&fit=crop&q=80',
                'image_caption' => 'Propulsion test facility during the live engine qualification trial',
                'video_type' => 'direct',
                'video_url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4',
                'category_id' => $categoryTech?->id ?? 1,
                'user_id' => $reporter?->id ?? 1,
                'status' => 'published',
                'is_breaking' => true,
                'is_featured' => false,
                'is_trending' => true,
                'views_count' => 28900,
                'reading_time' => 2,
                'published_at' => now()->subHours(4),
            ]
        );

        // 3. YouTube Video Article 2 (Cricket / Sports)
        Article::updateOrCreate(
            ['slug' => 'cricket-world-cup-highlights-video'],
            [
                'title' => 'Video Bulletin: Inside the Training Camp - Batting Practice and Precision Field Drills',
                'excerpt' => 'Behind-the-scenes video footage showcases star batsmen sharpening pull shots and boundary defense ahead of the tournament.',
                'content' => '<p>The national cricket squad convened for an intensive training session this morning. Under the guidance of coaching staff, top-order batsmen faced express seamers and spin specialists under simulated match conditions.</p><p>Analysts observe high team morale and tactical cohesion ahead of the opening fixture.</p>',
                'featured_image' => 'https://images.unsplash.com/photo-1540747913346-19e32dc3e97e?w=1200&auto=format&fit=crop&q=80',
                'image_caption' => 'Players during rigorous batting drills in the practice nets',
                'video_type' => 'youtube',
                'video_url' => 'https://www.youtube.com/watch?v=aqz-KE-bpKQ',
                'category_id' => $categorySports?->id ?? 1,
                'user_id' => $admin?->id ?? 1,
                'status' => 'published',
                'is_breaking' => false,
                'is_featured' => false,
                'is_trending' => true,
                'views_count' => 35600,
                'reading_time' => 3,
                'published_at' => now()->subHours(6),
            ]
        );

        $categoryBusiness = Category::where('slug', 'business')->first() ?: Category::first();

        // 4. YouTube Video Article 3 (Business & Markets)
        Article::updateOrCreate(
            ['slug' => 'stock-market-bull-run-video-analysis'],
            [
                'title' => 'Market Bulletin: Benchmark Indices Reach Record Highs - Chief Financial Analysts Share Video Insights',
                'excerpt' => 'Institutional and retail inflows drive surging equity valuations. Top equity analysts review sector charts and forward guidance.',
                'content' => '<p>Financial markets achieved historic highs today, propelled by extensive rally across IT, financial services, and manufacturing shares. Watch our full panel discussion to understand strategic sectoral opportunities for upcoming quarters.</p>',
                'featured_image' => 'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=1200&auto=format&fit=crop&q=80',
                'image_caption' => 'Record market rally - special video analysis report',
                'video_type' => 'youtube',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'category_id' => $categoryBusiness?->id ?? 1,
                'user_id' => $admin?->id ?? 1,
                'status' => 'published',
                'is_breaking' => false,
                'is_featured' => false,
                'is_trending' => true,
                'views_count' => 19800,
                'reading_time' => 2,
                'published_at' => now()->subHours(8),
            ]
        );

        // 5. Sample Newsletter Subscribers
        $subscribers = [
            ['email' => 'alex.turner@example.com', 'is_active' => true, 'ip_address' => '127.0.0.1'],
            ['email' => 'sarah.connor@example.com', 'is_active' => true, 'ip_address' => '127.0.0.1'],
            ['email' => 'michael.chang@example.com', 'is_active' => true, 'ip_address' => '127.0.0.1'],
            ['email' => 'emma.watson@example.com', 'is_active' => true, 'ip_address' => '127.0.0.1'],
            ['email' => 'david.beckham@example.com', 'is_active' => false, 'ip_address' => '127.0.0.1'],
        ];

        foreach ($subscribers as $s) {
            Subscriber::updateOrCreate(['email' => $s['email']], $s);
        }
    }
}
