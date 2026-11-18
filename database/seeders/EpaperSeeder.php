<?php

namespace Database\Seeders;

use App\Models\EpaperEdition;
use App\Models\EpaperPage;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class EpaperSeeder extends Seeder
{
    public function run(): void
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        // 1. Generate Realistic Broadsheet Newspaper Pages & Multi-page PDF for Today
        try {
            $generator = new \App\Services\EpaperGeneratorService();
            $generator->generateAll();
        } catch (\Throwable $e) {
            // Fallback if GD is unavailable
            $edition1 = EpaperEdition::firstOrCreate(
                ['slug' => 'national-daily-' . $today->format('Y-m-d')],
                [
                    'title' => 'NewsKit National Daily Edition',
                    'edition_date' => $today,
                    'cover_image' => 'https://images.unsplash.com/photo-1585829365295-ab7cd400c167?w=1200&auto=format&fit=crop&q=80',
                    'pdf_file' => 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf',
                    'total_pages' => 6,
                    'views_count' => 1420,
                    'downloads_count' => 380,
                    'is_published' => true,
                ]
            );

            $pages1 = [
                [
                    'page_number' => 1,
                    'page_title' => 'Page 1 - Front Page / National Headlines',
                    'page_image' => 'https://images.unsplash.com/photo-1585829365295-ab7cd400c167?w=1400&auto=format&fit=crop&q=80',
                    'pdf_url' => 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf',
                ],
                [
                    'page_number' => 2,
                    'page_title' => 'Page 2 - National Politics & Parliament',
                    'page_image' => 'https://images.unsplash.com/photo-1504711434969-e33886168f5c?w=1400&auto=format&fit=crop&q=80',
                    'pdf_url' => null,
                ],
                [
                    'page_number' => 3,
                    'page_title' => 'Page 3 - State & City Metro News',
                    'page_image' => 'https://images.unsplash.com/photo-1586339949916-3e9457bef6d3?w=1400&auto=format&fit=crop&q=80',
                    'pdf_url' => null,
                ],
                [
                    'page_number' => 4,
                    'page_title' => 'Page 4 - Business, Economy & Markets',
                    'page_image' => 'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=1400&auto=format&fit=crop&q=80',
                    'pdf_url' => null,
                ],
                [
                    'page_number' => 5,
                    'page_title' => 'Page 5 - Editorial, Opinions & Op-Ed',
                    'page_image' => 'https://images.unsplash.com/photo-1457369804613-52c61a468e7d?w=1400&auto=format&fit=crop&q=80',
                    'pdf_url' => null,
                ],
                [
                    'page_number' => 6,
                    'page_title' => 'Page 6 - Sports Arena & Global Highlights',
                    'page_image' => 'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?w=1400&auto=format&fit=crop&q=80',
                    'pdf_url' => null,
                ],
            ];

            foreach ($pages1 as $page) {
                EpaperPage::updateOrCreate(
                    [
                        'epaper_edition_id' => $edition1->id,
                        'page_number' => $page['page_number'],
                    ],
                    $page
                );
            }
        }

        // 2. Yesterday's Edition
        $edition2 = EpaperEdition::firstOrCreate(
            ['slug' => 'national-daily-' . $yesterday->format('Y-m-d')],
            [
                'title' => 'NewsKit Sunday Magazine & Analysis',
                'edition_date' => $yesterday,
                'cover_image' => 'https://images.unsplash.com/photo-1504711434969-e33886168f5c?w=1200&auto=format&fit=crop&q=80',
                'pdf_file' => 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf',
                'total_pages' => 4,
                'views_count' => 2890,
                'downloads_count' => 740,
                'is_published' => true,
            ]
        );

        $pages2 = [
            [
                'page_number' => 1,
                'page_title' => 'Page 1 - Cover Story & Special Investigation',
                'page_image' => 'https://images.unsplash.com/photo-1504711434969-e33886168f5c?w=1400&auto=format&fit=crop&q=80',
                'pdf_url' => null,
            ],
            [
                'page_number' => 2,
                'page_title' => 'Page 2 - In-Depth Weekend Features',
                'page_image' => 'https://images.unsplash.com/photo-1585829365295-ab7cd400c167?w=1400&auto=format&fit=crop&q=80',
                'pdf_url' => null,
            ],
            [
                'page_number' => 3,
                'page_title' => 'Page 3 - Technology & Innovation Digest',
                'page_image' => 'https://images.unsplash.com/photo-1518770660439-4636190af475?w=1400&auto=format&fit=crop&q=80',
                'pdf_url' => null,
            ],
            [
                'page_number' => 4,
                'page_title' => 'Page 4 - Lifestyle, Books & Culture',
                'page_image' => 'https://images.unsplash.com/photo-1499750310107-5fef28a66643?w=1400&auto=format&fit=crop&q=80',
                'pdf_url' => null,
            ],
        ];

        foreach ($pages2 as $page) {
            EpaperPage::updateOrCreate(
                [
                    'epaper_edition_id' => $edition2->id,
                    'page_number' => $page['page_number'],
                ],
                $page
            );
        }
    }
}
