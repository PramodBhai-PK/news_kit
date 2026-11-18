<?php

namespace App\Services;

use App\Models\EpaperEdition;
use App\Models\EpaperPage;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;

class EpaperGeneratorService
{
    protected string $outputDir;
    protected int $width = 1240;
    protected int $height = 1754;

    // Windows Font paths
    protected string $fontTimesBold = 'C:\\Windows\\Fonts\\timesbd.ttf';
    protected string $fontTimes = 'C:\\Windows\\Fonts\\times.ttf';
    protected string $fontArialBold = 'C:\\Windows\\Fonts\\arialbd.ttf';
    protected string $fontArial = 'C:\\Windows\\Fonts\\arial.ttf';
    protected string $fontGeorgia = 'C:\\Windows\\Fonts\\georgia.ttf';

    public function __construct()
    {
        $this->outputDir = public_path('demo/epaper');
        if (!File::isDirectory($this->outputDir)) {
            File::makeDirectory($this->outputDir, 0755, true);
        }
    }

    /**
     * Generate complete dummy edition (6 broadsheet pages + combined PDF + DB records).
     */
    public function generateAll(): array
    {
        $pagesData = [
            1 => [
                'section' => 'FRONT PAGE',
                'lead_title' => 'PARLIAMENT PASSES HISTORIC ₹12 LAKH CR GREEN ENERGY ACCORD',
                'lead_sub' => 'Nation pledges net-zero acceleration; massive solar & green hydrogen grid to power 100 million households by 2028.',
                'image_url' => 'https://images.unsplash.com/photo-1541872703-74c5e44368f9?w=800&auto=format&fit=crop&q=80',
                'image_caption' => 'Parliament House, New Delhi: Lawmakers across party lines unite to pass the historic energy bill.',
                'col1_title' => 'Unanimous Vote Clears Path for Clean Tech Revolution',
                'col1_text' => "NEW DELHI — In a rare and historic display of parliamentary unity on Wednesday, both houses of Parliament unanimously passed the landmark National Clean Energy & Sustainability Accord. The comprehensive legislation unlocks over twelve lakh crore rupees in public-private green investments across the nation.\n\nKey provisions include universal grid modernizations, state-backed green hydrogen subsidies, and decentralized solar infrastructure for over one hundred million households by the end of 2028.\n\nGlobal leaders and environmental delegates welcomed the accord as the single most consequential climate commitment by a developing economic powerhouse.",
                'col2_title' => 'ISRO Unveils 2027 Lunar Base Mission Module',
                'col2_text' => "BENGALURU — Space scientists on Wednesday unveiled the prototype habitat module for India's upcoming crewed lunar surface exploration program. Slated for launch in late 2027, the modular habitat features life-support systems designed for a 30-day continuous stay by four astronauts.\n\nOfficials confirmed that testing has begun at the Sriharikota launch complex, with international partners providing scientific observation payloads.",
                'col3_title' => 'Sensex Surges 850 Points on Industrial GDP Leap',
                'col3_text' => "MUMBAI — Domestic equity benchmarks rallied sharply on Wednesday following release of official manufacturing figures that revealed industrial growth hitting a five-year peak of 8.4%. The BSE Sensex closed above 84,200 while foreign institutional inflows touched a record monthly high.",
                'side_box_title' => 'BRIEF HEADLINES',
                'side_box_items' => [
                    '• Digital Public Grid crosses 250,000 connected panchayats.',
                    '• National Highway Authority completes 4,200 km expressway stretch.',
                    '• Supreme Court directs swift digital case clearances.',
                    '• Central Bank reports foreign exchange reserves at record $712 Billion.',
                ],
            ],
            2 => [
                'section' => 'NATION & POLITY',
                'lead_title' => 'HIGH-SPEED RAIL NETWORK TO CONNECT 12 CAPITAL CITIES BY 2028',
                'lead_sub' => 'Cabinet approves ₹4.5 lakh crore bullet train phase-two expansion spanning northern and southern industrial corridors.',
                'image_url' => 'https://images.unsplash.com/photo-1474487548417-781cb71495f3?w=800&auto=format&fit=crop&q=80',
                'image_caption' => 'The next-generation aerodynamic bullet train trainset during high-speed track calibration trials.',
                'col1_title' => 'Industrial Corridors to Halve Commute Times',
                'col1_text' => "NEW DELHI — Modern inter-city transport took a giant leap forward as the Union Cabinet officially approved phase-two of the National Bullet Train Expansion Program. Under the plan, twelve state capitals will be linked via state-of-the-art elevated high-speed tracks designed for operating speeds of 320 km/h.\n\nThe project will cut the New Delhi-Lucknow transit to under 2 hours and Mumbai-Ahmedabad to 1 hour 45 minutes, dramatically altering trade and regional labor mobility.",
                'col2_title' => 'Judicial e-Courts Portal Crosses 100M Hearings',
                'col2_text' => "NEW DELHI — India's digital judiciary initiative achieved a landmark milestone on Wednesday as virtual court hearings crossed the hundred million mark. Chief Justice praised digital accessibility for rural litigants, noting that paperless hearings cut average trial pendency by 42% over three years.",
                'col3_title' => 'Semiconductor Mission Phase II Gets Green Light',
                'col3_text' => "GANDHINAGAR — Commercial fabrication of 28nm and 14nm microchips will commence ahead of schedule in three integrated clusters following the approval of Phase II incentives. Top global chipmakers are co-investing with domestic industrial conglomerates.",
                'side_box_title' => 'PARLIAMENT WATCH',
                'side_box_items' => [
                    '• Cyber Data Privacy Rules tabled in Lok Sabha.',
                    '• Consumer Protection Amendment passed without dissent.',
                    '• Special committee formed on AI workforce integration.',
                    '• Railway safety allocation increased by 28%.',
                ],
            ],
            3 => [
                'section' => 'METRO & REGIONAL',
                'lead_title' => 'AI-POWERED TRAFFIC HUBS CUT COMMUTE TIMES BY 35% ACROSS METROS',
                'lead_sub' => 'Smart adaptive traffic signaling and real-time transit priority corridors deployed across Delhi, Mumbai, Bengaluru, and Hyderabad.',
                'image_url' => 'https://images.unsplash.com/photo-1519501025264-65ba15a82390?w=800&auto=format&fit=crop&q=80',
                'image_caption' => 'Central control room monitoring multi-modal smart transit flows in the national capital region.',
                'col1_title' => 'Adaptive Grid Relieves Chronic Peak Congestion',
                'col1_text' => "MUMBAI & NEW DELHI — The first comprehensive data review of the Smart Integrated Urban Traffic Grid revealed that automated algorithmic signal timings have successfully reduced average peak-hour delay by 35% across four major metropolitan areas.\n\nEmergency ambulance response times also dropped by 48% due to automated green-light preemption corridors synchronized with GPS dispatch centers.",
                'col2_title' => 'River Cleanliness Drive Restores Pristine Quality',
                'col2_text' => "VARANASI — Intensive municipal water reclamation plants and industrial zero-liquid discharge norms have resulted in historic water quality improvements across major river ghats, with dissolved oxygen reaching optimal biological levels for the first time in four decades.",
                'col3_title' => 'Metro Phase 4 Airport Line Completes Tunneling',
                'col3_text' => "BENGALURU — Tunnel boring operations for the underground express airport metro link concluded three weeks ahead of schedule. The 38-km line will connect downtown tech parks directly with international flight terminals in under 35 minutes.",
                'side_box_title' => 'CIVIC BULLETINS',
                'side_box_items' => [
                    '• 100% LED street lighting achieved in municipal wards.',
                    '• 500 new electric public buses added to city fleet.',
                    '• Rainwater harvesting mandatory for all new commercial builds.',
                    '• Citizen grievance portal resolves 96% complaints in 48 hours.',
                ],
            ],
            4 => [
                'section' => 'BUSINESS & MARKETS',
                'lead_title' => 'SERVICES & TECH EXPORTS CROSS RECORD $210 BILLION IN H1',
                'lead_sub' => 'Enterprise AI adoption, global capability centers, and electronics manufacturing drive unprecedented foreign trade expansion.',
                'image_url' => 'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=800&auto=format&fit=crop&q=80',
                'image_caption' => 'Stock exchange floor and financial analytics terminals on a historic trading session.',
                'col1_title' => 'Sovereign Wealth Funds Double Down on Tech Ecosystem',
                'col1_text' => "MUMBAI — Global institutional capital continued its bullish inflow into domestic tech, fintech, and renewable energy sectors, propelling first-half service exports to a record $210 billion.\n\nIndustry analysts attribute the momentum to the rapid scaling of Global Capability Centers (GCCs) in tier-1 and tier-2 hubs, which now employ over two million engineering and data science professionals.",
                'col2_title' => 'EV Sales Hit 2.5 Million Quarterly Mark',
                'col2_text' => "PUNE — Commercial and passenger electric vehicle registrations surged 62% year-on-year, driven by localized solid-state battery manufacturing and expanding fast-charging networks across national highways.",
                'col3_title' => 'Central Bank Holds Repo Rate at 6.5%',
                'col3_text' => "MUMBAI — In its bi-monthly monetary policy address, the Reserve Bank Monetary Policy Committee unanimously kept key lending rates unchanged, citing retail inflation moderating comfortably to 3.8% within the target band.",
                'side_box_title' => 'MARKET PULSE',
                'side_box_items' => [
                    '• BSE SENSEX: 84,210.45 (+852.10 | +1.02%)',
                    '• NSE NIFTY 50: 25,680.20 (+248.60 | +0.98%)',
                    '• GOLD (10g / 24K): ₹78,420 (Unchanged)',
                    '• BRENT CRUDE: $74.20 / bbl (-1.4%)',
                ],
            ],
            5 => [
                'section' => 'OPINION & EDITORIAL',
                'lead_title' => 'THE NEXT DECADE: BALANCING ARTIFICIAL INTELLIGENCE & CIVIC RIGHTS',
                'lead_sub' => 'As algorithmic systems enter public governance, constitutional safeguards, transparency, and human-centric design must lead.',
                'image_url' => 'https://images.unsplash.com/photo-1457369804613-52c61a468e7d?w=800&auto=format&fit=crop&q=80',
                'image_caption' => 'The evolving landscape of technology, law, and human civilization.',
                'col1_title' => 'Algorithmic Public Policy Demands Democratic Audits',
                'col1_text' => "EDITORIAL — The swift integration of generative neural networks into public administration, judicial case management, and healthcare dispatch represents an unprecedented evolutionary leap for democratic governance.\n\nYet technology is only as trustworthy as the ethical guardrails that guide its deployment. An automated algorithm that allocates civic welfare or filters welfare claims must remain subject to open civic audit, explainability, and accessible appeals processes.\n\nOur democracy has always derived strength from transparency, and its digital institutions must uphold that timeless principle.",
                'col2_title' => 'Agritech: The Engine of Rural Prosperity',
                'col2_text' => "BY DR. RAMESHWAR PATIL — Micro-precision irrigation, drone-assisted crop monitoring, and decentralized solar cold chains are transforming smallholder agricultural economics. Yields have expanded by 26% while ground-water draw has fallen significantly across progressive farm cooperatives.",
                'col3_title' => 'Letters to the Editor: Urban Green Spaces',
                'col3_text' => "SIR — The municipal council's move to convert disused railway yards into public urban forests is highly commendable. Every citizen deserves clean air, tree-lined walking tracks, and safe community spaces within a fifteen-minute radius of their neighborhood.",
                'side_box_title' => 'THOUGHT OF THE DAY',
                'side_box_items' => [
                    '\"Wisdom is not a product of schooling',
                    'but of the lifelong attempt to acquire it.\"',
                    '— Albert Einstein',
                    '',
                    'VOX POPULI: 88% citizens favor digital',
                    'governance for public service delivery.',
                ],
            ],
            6 => [
                'section' => 'SPORTS & ARENA',
                'lead_title' => 'INDIA CLINCHES CHAMPIONSHIP TROPHY IN DRAMATIC FINAL OVER',
                'lead_sub' => 'Thrilling final ball victory before 110,000 roaring fans caps an undefeated tournament run for the national squad.',
                'image_url' => 'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?w=800&auto=format&fit=crop&q=80',
                'image_caption' => 'The national team captain hoists the championship trophy amid celebratory fireworks.',
                'col1_title' => 'Clutch Death Bowling Seals Historic Win',
                'col1_text' => "AHMEDABAD — In one of the most pulsating cricket matches in modern history, the national team defended twelve runs off the final over to claim the World Champions Trophy before a rapturous capacity crowd of 110,000 spectators.\n\nThe strike bowler delivered three consecutive reverse-swinging yorkers under extreme pressure, triggering deafening cheers across the nation and widespread street celebrations.",
                'col2_title' => 'Olympic Excellence Academy Inaugurated',
                'col2_text' => "PATIALA — A cutting-edge Olympic training and sports science biomechanics center opened its doors on Wednesday. Over 200 elite athletes across track, boxing, shooting, and archery have been inducted under specialized international coaching squads.",
                'col3_title' => 'Grandmaster Advances to World Chess Final',
                'col3_text' => "GENEVA — The 18-year-old Indian chess sensation secured a historic spot in the World Championship Grand Finals following a masterclass positional victory with the black pieces in Round 14.",
                'side_box_title' => 'SPORTS SCOREBOARD',
                'side_box_items' => [
                    '• CRICKET: IND 288/6 def. AUS 282/9 (by 6 runs)',
                    '• FOOTBALL: Capital United 3 - 2 Rovers FC',
                    '• BADMINTON: National Open Semis at 4 PM',
                    '• TENNIS: Grand Slam qualifiers underway',
                ],
            ],
        ];

        $generatedPagePaths = [];

        foreach ($pagesData as $pageNum => $data) {
            $filePath = $this->renderPage($pageNum, $data);
            $generatedPagePaths[$pageNum] = $filePath;
        }

        // Generate combined multi-page PDF
        $pdfPath = $this->generatePdf($generatedPagePaths);

        // Update/Seed Database
        $edition = $this->updateDatabase($generatedPagePaths, $pdfPath);

        return [
            'pages' => $generatedPagePaths,
            'pdf' => $pdfPath,
            'edition' => $edition,
        ];
    }

    /**
     * Render a single high-resolution newspaper broadsheet page.
     */
    protected function renderPage(int $pageNum, array $data): string
    {
        $im = imagecreatetruecolor($this->width, $this->height);

        // Palette
        $bg = imagecolorallocate($im, 250, 248, 244); // warm newsprint paper
        $ink = imagecolorallocate($im, 22, 22, 22);    // deep black ink
        $slate = imagecolorallocate($im, 80, 85, 95);  // dark slate text
        $lightGray = imagecolorallocate($im, 215, 218, 224); // rules
        $darkRed = imagecolorallocate($im, 175, 20, 35);    // crimson brand
        $cardBg = imagecolorallocate($im, 242, 240, 234);  // box container

        imagefill($im, 0, 0, $bg);

        // Outer Page Border
        imagesetthickness($im, 2);
        imagerectangle($im, 30, 30, $this->width - 30, $this->height - 30, $ink);
        imagerectangle($im, 34, 34, $this->width - 34, $this->height - 34, $lightGray);

        $cursorY = 50;

        // Top Micro Info Bar
        $todayStr = Carbon::today()->format('l, F d, Y');
        $topInfo = strtoupper("National Edition • {$todayStr} • 24 Pages • Vol. XLII No. 312 • ₹5.00 • www.newskit.com");
        $this->drawText($im, 10, 45, $cursorY, $slate, $this->fontArialBold, $topInfo);
        $this->drawText($im, 10, $this->width - 220, $cursorY, $darkRed, $this->fontArialBold, "WEATHER: 28°C / 16°C SUNNY");

        $cursorY += 14;
        $this->drawHLine($im, 40, $this->width - 40, $cursorY, $lightGray, 1);

        // MASTHEAD (on Page 1 full; on other pages compact banner)
        $cursorY += 15;
        if ($pageNum === 1) {
            // Left Ear Box
            imagefilledrectangle($im, 45, $cursorY, 260, $cursorY + 70, $cardBg);
            imagerectangle($im, 45, $cursorY, 260, $cursorY + 70, $lightGray);
            $this->drawText($im, 9, 55, $cursorY + 20, $darkRed, $this->fontArialBold, "INSIDE TODAY");
            $this->drawText($im, 8.5, 55, $cursorY + 36, $ink, $this->fontArial, "• Tech Boom 2026: Pg 4");
            $this->drawText($im, 8.5, 55, $cursorY + 50, $ink, $this->fontArial, "• Metro AI Traffic: Pg 3");
            $this->drawText($im, 8.5, 55, $cursorY + 64, $ink, $this->fontArial, "• Op-Ed on AI & Law: Pg 5");

            // Right Ear Box
            imagefilledrectangle($im, $this->width - 260, $cursorY, $this->width - 45, $cursorY + 70, $cardBg);
            imagerectangle($im, $this->width - 260, $cursorY, $this->width - 45, $cursorY + 70, $lightGray);
            $this->drawText($im, 9, $this->width - 250, $cursorY + 20, $darkRed, $this->fontArialBold, "SPORTS SPECIAL");
            $this->drawText($im, 8.5, $this->width - 250, $cursorY + 36, $ink, $this->fontArial, "• Historic Championship: Pg 6");
            $this->drawText($im, 8.5, $this->width - 250, $cursorY + 50, $ink, $this->fontArial, "• Olympic Academy Opens");
            $this->drawText($im, 8.5, $this->width - 250, $cursorY + 64, $ink, $this->fontArial, "• Grandmaster to Final");

            // Center Big Masthead
            $masthead = "THE NEWSKIT DAILY";
            $this->drawCenteredText($im, 40, $cursorY + 45, $ink, $this->fontTimesBold, $masthead);
            $tagline = "THE NATION'S PREMIER JOURNAL OF TRUTH, INTEGRITY & INVESTIGATIVE JOURNALISM";
            $this->drawCenteredText($im, 9, $cursorY + 68, $slate, $this->fontArialBold, $tagline);

            $cursorY += 85;
        } else {
            // Compact Header for Inner Pages
            imagefilledrectangle($im, 40, $cursorY, $this->width - 40, $cursorY + 45, $cardBg);
            imagerectangle($im, 40, $cursorY, $this->width - 40, $cursorY + 45, $lightGray);

            $this->drawText($im, 12, 55, $cursorY + 28, $darkRed, $this->fontArialBold, "NEWSKIT " . $data['section']);
            $this->drawCenteredText($im, 11, $cursorY + 28, $ink, $this->fontTimesBold, "THE NEWSKIT DAILY • " . strtoupper($todayStr));
            $this->drawText($im, 11, $this->width - 140, $cursorY + 28, $slate, $this->fontArialBold, "PAGE {$pageNum}");

            $cursorY += 58;
        }

        // Section Accent Rule
        $this->drawHLine($im, 40, $this->width - 40, $cursorY, $ink, 3);
        $cursorY += 16;

        // SECTION HEADER BADGE
        $sectionBadge = "[ " . $data['section'] . " ]";
        $this->drawText($im, 10, 45, $cursorY, $darkRed, $this->fontArialBold, $sectionBadge);
        $cursorY += 24;

        // LEAD HEADLINE (Big Bold Typography)
        $cursorY = $this->drawWrappedText($im, 24, 45, $cursorY, $ink, $this->fontTimesBold, $data['lead_title'], 1140, 32);
        $cursorY += 6;

        // LEAD SUBHEAD (Italic serif / slate)
        $cursorY = $this->drawWrappedText($im, 12, 45, $cursorY, $slate, $this->fontGeorgia, $data['lead_sub'], 1140, 18);
        $cursorY += 12;

        $this->drawHLine($im, 40, $this->width - 40, $cursorY, $lightGray, 1);
        $cursorY += 16;

        // MAIN CONTENT AREA: Photo + Columns
        $contentStartY = $cursorY;
        $photoW = 680;
        $photoH = 360;

        // Draw Inset Photo Box
        $this->drawPhotoBox($im, 45, $cursorY, $photoW, $photoH, $data['image_url'], $data['image_caption']);

        // Side Column next to photo (Width: ~440px)
        $sideX = 750;
        $sideW = 440;
        $sideY = $contentStartY + 5;

        $this->drawHLine($im, $sideX, $sideX + $sideW, $sideY, $darkRed, 2);
        $sideY += 18;
        $sideY = $this->drawWrappedText($im, 14, $sideX, $sideY, $ink, $this->fontTimesBold, $data['col1_title'], $sideW, 20);
        $sideY += 10;
        $this->drawWrappedText($im, 9.5, $sideX, $sideY, $slate, $this->fontTimes, $data['col1_text'], $sideW, 14);

        $cursorY += $photoH + 50;
        $this->drawHLine($im, 40, $this->width - 40, $cursorY, $lightGray, 1);
        $cursorY += 18;

        // THREE-COLUMN LOWER BODY
        $colW = 350;
        $colGap = 35;
        $col1X = 45;
        $col2X = $col1X + $colW + $colGap;
        $col3X = $col2X + $colW + $colGap;

        // Column 1
        $c1Y = $cursorY;
        $this->drawHLine($im, $col1X, $col1X + $colW, $c1Y, $darkRed, 2);
        $c1Y += 18;
        $c1Y = $this->drawWrappedText($im, 13, $col1X, $c1Y, $ink, $this->fontTimesBold, $data['col2_title'], $colW, 18);
        $c1Y += 8;
        $this->drawWrappedText($im, 9.5, $col1X, $c1Y, $slate, $this->fontTimes, $data['col2_text'], $colW, 14);

        // Column Divider 1
        $this->drawVLine($im, $col2X - 18, $cursorY, $this->height - 80, $lightGray, 1);

        // Column 2
        $c2Y = $cursorY;
        $this->drawHLine($im, $col2X, $col2X + $colW, $c2Y, $darkRed, 2);
        $c2Y += 18;
        $c2Y = $this->drawWrappedText($im, 13, $col2X, $c2Y, $ink, $this->fontTimesBold, $data['col3_title'], $colW, 18);
        $c2Y += 8;
        $this->drawWrappedText($im, 9.5, $col2X, $c2Y, $slate, $this->fontTimes, $data['col3_text'], $colW, 14);

        // Column Divider 2
        $this->drawVLine($im, $col3X - 18, $cursorY, $this->height - 80, $lightGray, 1);

        // Column 3: Side Box / Data Box
        $c3Y = $cursorY;
        imagefilledrectangle($im, $col3X, $c3Y, $col3X + $colW, $this->height - 85, $cardBg);
        imagerectangle($im, $col3X, $c3Y, $col3X + $colW, $this->height - 85, $lightGray);
        $this->drawHLine($im, $col3X, $col3X + $colW, $c3Y, $darkRed, 3);

        $c3Y += 22;
        $this->drawText($im, 11, $col3X + 16, $c3Y, $darkRed, $this->fontArialBold, $data['side_box_title']);
        $c3Y += 14;
        $this->drawHLine($im, $col3X + 16, $col3X + $colW - 16, $c3Y, $lightGray, 1);
        $c3Y += 18;

        foreach ($data['side_box_items'] as $item) {
            $c3Y = $this->drawWrappedText($im, 9.5, $col3X + 16, $c3Y, $ink, $this->fontArial, $item, $colW - 32, 14);
            $c3Y += 8;
        }

        // FOOTER
        $footerY = $this->height - 55;
        $this->drawHLine($im, 40, $this->width - 40, $footerY, $ink, 2);
        $footerY += 16;
        $this->drawText($im, 9, 45, $footerY, $slate, $this->fontArialBold, "PAGE {$pageNum} • THE NEWSKIT DAILY");
        $this->drawCenteredText($im, 8.5, $footerY, $slate, $this->fontArial, "Edited, Printed & Published by NewsKit Media Network Ltd. • RNI Regn. No. DELENG/2026/89412");
        $this->drawText($im, 9, $this->width - 150, $footerY, $darkRed, $this->fontArialBold, "www.newskit.com");

        $outputPath = $this->outputDir . DIRECTORY_SEPARATOR . "page_{$pageNum}.jpg";
        imagejpeg($im, $outputPath, 88);
        imagedestroy($im);

        return $outputPath;
    }

    /**
     * Download or draw photo box with caption.
     */
    protected function drawPhotoBox($im, int $x, int $y, int $w, int $h, string $imageUrl, string $caption): void
    {
        $lightGray = imagecolorallocate($im, 215, 218, 224);
        $cardBg = imagecolorallocate($im, 230, 230, 230);
        $captionColor = imagecolorallocate($im, 60, 65, 75);

        $imgPlaced = false;

        // Try downloading image
        try {
            $ctx = stream_context_create([
                'http' => ['timeout' => 4, 'user_agent' => 'Mozilla/5.0'],
            ]);
            $raw = @file_get_contents($imageUrl, false, $ctx);
            if ($raw) {
                $src = @imagecreatefromstring($raw);
                if ($src) {
                    $srcW = imagesx($src);
                    $srcH = imagesy($src);
                    imagecopyresampled($im, $src, $x, $y, 0, 0, $w, $h - 35, $srcW, $srcH);
                    imagedestroy($src);
                    $imgPlaced = true;
                }
            }
        } catch (\Throwable $e) {
            $imgPlaced = false;
        }

        // Fallback drawing if image download failed
        if (!$imgPlaced) {
            imagefilledrectangle($im, $x, $y, $x + $w, $y + $h - 35, $cardBg);
            $this->drawCenteredText($im, 14, $y + ($h / 2) - 20, $captionColor, $this->fontArialBold, "NEWSKIT PRESS PHOTOGRAPH");
        }

        // Photo border
        imagesetthickness($im, 1);
        imagerectangle($im, $x, $y, $x + $w, $y + $h - 35, $lightGray);

        // Caption Box underneath
        $captionY = $y + $h - 18;
        $this->drawText($im, 8.5, $x + 4, $captionY, $captionColor, $this->fontArial, "PHOTO: " . $caption);
    }

    /**
     * Helper to draw text with TrueType font.
     */
    protected function drawText($im, float $size, int $x, int $y, int $color, string $font, string $text): void
    {
        if (file_exists($font)) {
            imagettftext($im, $size, 0, $x, $y, $color, $font, $text);
        } else {
            imagestring($im, 4, $x, $y - 12, $text, $color);
        }
    }

    /**
     * Helper to draw horizontally centered text.
     */
    protected function drawCenteredText($im, float $size, int $y, int $color, string $font, string $text): void
    {
        if (file_exists($font)) {
            $box = imagettfbbox($size, 0, $font, $text);
            $textWidth = abs($box[4] - $box[0]);
            $x = (int)(($this->width - $textWidth) / 2);
            imagettftext($im, $size, 0, $x, $y, $color, $font, $text);
        } else {
            $textWidth = strlen($text) * 8;
            $x = (int)(($this->width - $textWidth) / 2);
            imagestring($im, 5, $x, $y - 14, $text, $color);
        }
    }

    /**
     * Draw text wrapped to max width.
     */
    protected function drawWrappedText($im, float $size, int $x, int $y, int $color, string $font, string $text, int $maxWidth, int $lineHeight): int
    {
        $words = preg_split('/\s+/', $text);
        $currentLine = '';

        foreach ($words as $word) {
            $testLine = $currentLine . ($currentLine === '' ? '' : ' ') . $word;
            
            if (file_exists($font)) {
                $box = imagettfbbox($size, 0, $font, $testLine);
                $lineWidth = abs($box[4] - $box[0]);
            } else {
                $lineWidth = strlen($testLine) * ($size * 0.7);
            }

            if ($lineWidth > $maxWidth && $currentLine !== '') {
                $this->drawText($im, $size, $x, $y, $color, $font, $currentLine);
                $y += $lineHeight;
                $currentLine = $word;
            } else {
                $currentLine = $testLine;
            }
        }

        if ($currentLine !== '') {
            $this->drawText($im, $size, $x, $y, $color, $font, $currentLine);
            $y += $lineHeight;
        }

        return $y;
    }

    protected function drawHLine($im, int $x1, int $x2, int $y, int $color, int $thickness = 1): void
    {
        imagesetthickness($im, $thickness);
        imageline($im, $x1, $y, $x2, $y, $color);
    }

    protected function drawVLine($im, int $x, int $y1, int $y2, int $color, int $thickness = 1): void
    {
        imagesetthickness($im, $thickness);
        imageline($im, $x, $y1, $x, $y2, $color);
    }

    /**
     * Build standard PDF 1.4 from JPEG page images.
     */
    public function generatePdf(array $pagePaths): string
    {
        $outputPath = $this->outputDir . DIRECTORY_SEPARATOR . 'newskit_daily_edition.pdf';
        $count = count($pagePaths);
        $offsets = [];
        $out = "%PDF-1.4\n";

        // Object 1: Catalog
        $offsets[1] = strlen($out);
        $out .= "1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n";

        // Object 2: Pages
        $kids = [];
        for ($i = 0; $i < $count; $i++) {
            $pageObjId = 3 + ($i * 3);
            $kids[] = "$pageObjId 0 R";
        }
        $offsets[2] = strlen($out);
        $out .= "2 0 obj\n<< /Type /Pages /Kids [" . implode(' ', $kids) . "] /Count $count >>\nendobj\n";

        // Objects for each page
        for ($i = 0; $i < $count; $i++) {
            $pageObjId = 3 + ($i * 3);
            $imgObjId = 4 + ($i * 3);
            $contentObjId = 5 + ($i * 3);

            $jpegData = file_get_contents($pagePaths[$i + 1]);
            $imgLen = strlen($jpegData);
            $imgInfo = getimagesizefromstring($jpegData);
            $pxW = $imgInfo[0];
            $pxH = $imgInfo[1];

            $pdfW = 595;
            $pdfH = 842;

            // Page Obj
            $offsets[$pageObjId] = strlen($out);
            $out .= "$pageObjId 0 obj\n<< /Type /Page /Parent 2 0 R /MediaBox [0 0 $pdfW $pdfH] /Resources << /XObject << /Im1 $imgObjId 0 R >> >> /Contents $contentObjId 0 R >>\nendobj\n";

            // Image XObject
            $offsets[$imgObjId] = strlen($out);
            $out .= "$imgObjId 0 obj\n<< /Type /XObject /Subtype /Image /Width $pxW /Height $pxH /ColorSpace /DeviceRGB /BitsPerComponent 8 /Filter /DCTDecode /Length $imgLen >>\nstream\n" . $jpegData . "\nendstream\nendobj\n";

            // Content Stream (places image to fill page)
            $contentStream = "q $pdfW 0 0 $pdfH 0 0 cm /Im1 Do Q";
            $contentLen = strlen($contentStream);
            $offsets[$contentObjId] = strlen($out);
            $out .= "$contentObjId 0 obj\n<< /Length $contentLen >>\nstream\n$contentStream\nendstream\nendobj\n";
        }

        $totalObjs = 2 + ($count * 3);
        $xrefOffset = strlen($out);
        $out .= "xref\n0 " . ($totalObjs + 1) . "\n0000000000 65535 f \n";
        for ($i = 1; $i <= $totalObjs; $i++) {
            $out .= sprintf("%010d 00000 n \n", $offsets[$i]);
        }
        $out .= "trailer\n<< /Size " . ($totalObjs + 1) . " /Root 1 0 R >>\nstartxref\n$xrefOffset\n%%EOF\n";

        file_put_contents($outputPath, $out);
        return $outputPath;
    }

    /**
     * Update database records with the generated dummy epaper.
     */
    protected function updateDatabase(array $pagePaths, string $pdfPath): EpaperEdition
    {
        $today = Carbon::today();

        $coverUrl = url('demo/epaper/page_1.jpg');
        $pdfUrl = url('demo/epaper/newskit_daily_edition.pdf');

        $edition = EpaperEdition::updateOrCreate(
            ['edition_date' => $today],
            [
                'title' => 'NewsKit National Daily Edition',
                'slug' => 'national-daily-' . $today->format('Y-m-d'),
                'cover_image' => $coverUrl,
                'pdf_file' => $pdfUrl,
                'total_pages' => 6,
                'views_count' => 1840,
                'downloads_count' => 460,
                'is_published' => true,
            ]
        );

        $pageTitles = [
            1 => 'Page 1 - Front Page / National Headlines',
            2 => 'Page 2 - Nation, Parliament & Policies',
            3 => 'Page 3 - Metro, City & Regional Affairs',
            4 => 'Page 4 - Business, Economy & Financial Times',
            5 => 'Page 5 - Opinion, Editorial & Letters',
            6 => 'Page 6 - Sports Arena & Global Highlights',
        ];

        foreach ($pagePaths as $pageNum => $path) {
            EpaperPage::updateOrCreate(
                [
                    'epaper_edition_id' => $edition->id,
                    'page_number' => $pageNum,
                ],
                [
                    'page_title' => $pageTitles[$pageNum] ?? "Page {$pageNum}",
                    'page_image' => url("demo/epaper/page_{$pageNum}.jpg"),
                    'pdf_url' => $pdfUrl,
                ]
            );
        }

        return $edition;
    }
}
