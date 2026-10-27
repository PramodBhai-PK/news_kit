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
                'title' => 'ग्राउंड रिपोर्ट: दिल्ली मेट्रो के नए फेज 4 विस्तार का पहला ऑन-साइट वीडियो कवरेज',
                'excerpt' => 'दिल्ली मेट्रो के फेज 4 अंडरग्राउंड टनलिंग और नए एयरोसिटी-तुगलकाबाद कॉरिडोर का एक्सक्लूसिव ग्राउंड वीडियो जायजा।',
                'content' => '<p>दिल्ली मेट्रो रेल कॉर्पोरेशन (DMRC) ने फेज 4 के विस्तार कार्य को रिकॉर्ड गति से आगे बढ़ाया है। हमारी विशेष ग्राउंड रिपोर्ट में देखिए टनल बोरिंग मशीन (TBM) का लाइव ऑपरेशन और आगामी स्टेशनों की हाइ-टेक सुविधाएं।</p><p>विशेषज्ञों के अनुसार इस नए कॉरिडोर से रोजाना 5 लाख से अधिक यात्रियों का सफर 40 मिनट तक कम हो जाएगा। इस पूरी कवरेज को ऊपर दिए गए वीडियो प्लेयर में विस्तार से देखें।</p>',
                'featured_image' => 'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?w=1200&auto=format&fit=crop&q=80',
                'image_caption' => 'दिल्ली मेट्रो फेज 4 कॉरिडोर पर निर्माण कार्य - स्पेशल वीडियो रिपोर्ट',
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
                'title' => 'एक्सक्लूसिव वीडियो: इसरो के सेमी-क्रायोजेनिक इंजन का हाई-थ्रस्ट हॉट टेस्ट सफल',
                'excerpt' => 'भारतीय अंतरिक्ष अनुसंधान संगठन (ISRO) ने तमिलनाडु के महेंद्रगिरि स्थित प्रोपल्शन कॉम्प्लेक्स में क्रायोजेनिक इंजन का सफल वीडियो परीक्षण जारी किया।',
                'content' => '<p>इसरो ने भारी रॉकेट लॉन्च क्षमताओं को नया आयाम देने वाले सेमी-क्रायोजेनिक इंजन का 500 सेकंड की अवधि का हॉट टेस्ट सफलतापूर्वक संपन्न कर लिया है। यह इंजन भविष्य के गगनयान और भारी सैटेलाइट लॉन्च में गेम-चेंजर साबित होगा।</p><p>इंजन परीक्षण का हाई-डेफिनिशन वीडियो हमारे पाठकों के लिए सीधे स्ट्रीम किया जा रहा है।</p>',
                'featured_image' => 'https://images.unsplash.com/photo-1541185933-ef5d8ed016c2?w=1200&auto=format&fit=crop&q=80',
                'image_caption' => 'महेंद्रगिरि टेस्ट फैसिलिटी में इंजन का लाइव हॉट टेस्ट',
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
                'title' => 'वीडियो बुलेटिन: भारतीय टीम के ट्रेनिंग कैंप से सीधी तस्वीरें, विराट और रोहित की धमाकेदार बैटिंग प्रैक्टिस',
                'excerpt' => 'आगामी चैंपियनशिप से पहले भारतीय बल्लेबाजों का नेट्स में पसीना बहाते वीडियो सामने आया। देखिए पुल शॉट्स और स्ट्रेट ड्राइव्स का अनोखा नजारा।',
                'content' => '<p>भारतीय क्रिकेट टीम ने आज सुबह नरेंद्र मोदी स्टेडियम में सघन अभ्यास सत्र में भाग लिया। मुख्य कोच की देखरेख में बल्लेबाजों ने तेज गेंदबाजों और लेग स्पिनरों के खिलाफ आक्रामक तकनीक का अभ्यास किया।</p><p>विशेषज्ञों का मानना है कि वर्तमान फॉर्म को देखते हुए टीम का आत्मविश्वास सातवें आसमान पर है।</p>',
                'featured_image' => 'https://images.unsplash.com/photo-1540747913346-19e32dc3e97e?w=1200&auto=format&fit=crop&q=80',
                'image_caption' => 'नेट्स प्रैक्टिस के दौरान भारतीय टीम के दिग्गज बल्लेबाज',
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
                'title' => 'बाज़ार बुलेटिन: सेंसेक्स और निफ्टी ने बनाया नया रिकॉर्ड हाई, जानिए टॉप एक्सपर्ट्स का मार्केट वीडियो विश्लेषण',
                'excerpt' => 'घरेलू और विदेशी निवेशकों की भारी खरीदारी से शेयर बाजार में जबर्दस्त तेजी। प्रमुख सेक्टर्स के चार्ट्स पर विशेषज्ञ की राय।',
                'content' => '<p>शेयर बाज़ार में आज ऐतिहासिक तेजी देखने को मिली। आईटी और बैंकिंग शेयर्स में जोरदार खरीदारी दर्ज की गई। बाजार के दिग्गजों से समझिए आगामी हफ्तों के लिए कौन से सेक्टर्स रहेंगे फोकस में।</p>',
                'featured_image' => 'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=1200&auto=format&fit=crop&q=80',
                'image_caption' => 'शेयर बाजार में रिकॉर्ड उछाल - विशेष मार्केट वीडियो रिपोर्ट',
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

        // 4. Sample Newsletter Subscribers
        $subscribers = [
            ['email' => 'rahul.verma@gmail.com', 'is_active' => true, 'ip_address' => '127.0.0.1'],
            ['email' => 'priya.sharma@outlook.com', 'is_active' => true, 'ip_address' => '127.0.0.1'],
            ['email' => 'amit.kumar@yahoo.com', 'is_active' => true, 'ip_address' => '127.0.0.1'],
            ['email' => 'sunita.patel@rediffmail.com', 'is_active' => true, 'ip_address' => '127.0.0.1'],
            ['email' => 'vikas.mishra@hotmail.com', 'is_active' => false, 'ip_address' => '127.0.0.1'],
        ];

        foreach ($subscribers as $s) {
            Subscriber::updateOrCreate(['email' => $s['email']], $s);
        }
    }
}
