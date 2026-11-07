<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;

class ExtraArticlesSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::first();
        $cats = Category::all()->keyBy('slug');

        $moreArticles = [
            [
                'title' => 'Next-Gen Multimodal AI Unveiled: Real-Time Voice and Vision Breakthroughs Announced',
                'slug' => 'chatgpt-5-and-gemini-next-gen-launch-features',
                'category_id' => $cats['technology']->id ?? 3,
                'user_id' => $admin->id,
                'excerpt' => 'Frontier artificial intelligence research unlocks instantaneous multimodal conversation with unprecedented latency reductions.',
                'content' => '<p><strong>San Francisco:</strong> A new milestone in artificial intelligence was inaugurated today as tech leaders demonstrated next-generation multimodal architectures capable of instantaneous audio and visual perception without processing delays.</p><p>Industry experts emphasize transformative ramifications across automated software development, education, and healthcare diagnostics.</p>',
                'featured_image' => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?w=1000&auto=format&fit=crop&q=80',
                'status' => 'published',
                'is_breaking' => true,
                'is_featured' => true,
                'is_trending' => true,
                'views_count' => 31200,
                'reading_time' => 3,
                'published_at' => now()->subMinutes(45),
            ],
            [
                'title' => 'High-Speed Sleeper Train Network Expands With 10 New Transcontinental Routes',
                'slug' => 'vande-bharat-sleeper-10-new-routes-announced',
                'category_id' => $cats['national']->id ?? 2,
                'user_id' => $admin->id,
                'excerpt' => 'National transportation authorities inaugurate cutting-edge sleeper express corridors equipped with world-class aerodynamic coaches.',
                'content' => '<p><strong>Capital Hub:</strong> Railway administrators unveiled comprehensive scheduling for ten high-capacity express sleeper corridors connecting major metropolitan regions.</p><p>The state-of-the-art trainsets integrate modern collision avoidance systems, regenerative braking, and ergonomic soundproof passenger compartments.</p>',
                'featured_image' => 'https://images.unsplash.com/photo-1532105956626-9569c03602f6?w=1000&auto=format&fit=crop&q=80',
                'status' => 'published',
                'is_breaking' => false,
                'is_featured' => true,
                'is_trending' => true,
                'views_count' => 24500,
                'reading_time' => 4,
                'published_at' => now()->subHours(1),
            ],
            [
                'title' => 'Central Bank Announces Benchmark Interest Rate Decision: Impacts on Mortgage and Commercial Loans',
                'slug' => 'rbi-monetary-policy-repo-rate-home-loan-emi-impact',
                'category_id' => $cats['business']->id ?? 6,
                'user_id' => $admin->id,
                'excerpt' => 'Monetary Policy Committee concludes quarterly deliberations with forward guidance on liquidity metrics, inflation targets, and consumer credit.',
                'content' => '<p><strong>Financial Center:</strong> The central bank monetary authority announced revised key interest rate thresholds aimed at sustaining economic growth while maintaining disciplined inflationary control.</p><p>Financial analysts project significant stabilizing effects on the housing sector and corporate capital expenditures.</p>',
                'featured_image' => 'https://images.unsplash.com/photo-1526304640581-d334cdbbf45e?w=1000&auto=format&fit=crop&q=80',
                'status' => 'published',
                'is_breaking' => false,
                'is_featured' => true,
                'is_trending' => true,
                'views_count' => 18900,
                'reading_time' => 3,
                'published_at' => now()->subHours(3),
            ],
            [
                'title' => 'International Film Academy Entry Selected: Acclaimed Indie Drama Chosen For Global Awards Race',
                'slug' => 'oscars-2027-india-official-entry-announcement',
                'category_id' => $cats['entertainment']->id ?? 4,
                'user_id' => $admin->id,
                'excerpt' => 'National cinema selection committee unanimously votes to endorse compelling feature following triumphs at prestigious film festivals.',
                'content' => '<p><strong>Film City:</strong> The official national jury convened today to formally designate the country\'s representative entry for the upcoming Academy Awards international feature category.</p><p>The director extended deep gratitude to the cast and crew, noting that authentic human storytelling transcends regional and cultural boundaries.</p>',
                'featured_image' => 'https://images.unsplash.com/photo-1517604931442-7e0c8ed2963c?w=1000&auto=format&fit=crop&q=80',
                'status' => 'published',
                'is_breaking' => false,
                'is_featured' => true,
                'is_trending' => true,
                'views_count' => 22400,
                'reading_time' => 2,
                'published_at' => now()->subHours(5),
            ],
            [
                'title' => 'National Cricket Strategy Camp: Head Coach Addresses Veteran Form and Upcoming Championship Series',
                'slug' => 'rohit-sharma-virat-kohli-test-cricket-head-coach-statement',
                'category_id' => $cats['sports']->id ?? 5,
                'user_id' => $admin->id,
                'excerpt' => 'Crucial tactical decisions finalized ahead of the five-match international test series, focusing on top-order stability and spin versatility.',
                'content' => '<p><strong>Sydney:</strong> Addressing journalists during the pre-series training session, the national team head coach outlined strategic preparations for the much-anticipated test championship series.</p><p>Coaching staff commended veteran composure and emphasized the mentorship provided to emerging fast bowlers in match-pressure simulations.</p>',
                'featured_image' => 'https://images.unsplash.com/photo-1531415074868-036b107e7742?w=1000&auto=format&fit=crop&q=80',
                'status' => 'published',
                'is_breaking' => true,
                'is_featured' => false,
                'is_trending' => true,
                'views_count' => 38700,
                'reading_time' => 3,
                'published_at' => now()->subHours(7),
            ]
        ];

        foreach ($moreArticles as $data) {
            Article::firstOrCreate(['slug' => $data['slug']], $data);
        }
    }
}
