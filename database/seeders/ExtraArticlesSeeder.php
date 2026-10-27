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
                'title' => 'ChatGPT 5 और Google Gemini की नई पीढ़ी लॉन्च, रियल-टाइम वॉइस और विज़न में क्रांतिकारी बदलाव',
                'slug' => 'chatgpt-5-and-gemini-next-gen-launch-features',
                'category_id' => $cats['technology']->id ?? 3,
                'user_id' => $admin->id,
                'excerpt' => 'आर्टिफिशियल इंटेलिजेंस की दुनिया में नया मोड़, अब इंसानों की तरह सहज बात करने में सक्षम नए एआई मॉडल्स।',
                'content' => '<p><strong>सैन फ्रांसिस्को:</strong> टेक जगत में आज एआई के नए युग की शुरुआत हुई। प्रमुख टेक कंपनियों ने अपने नए मल्टीमॉडल एआई सिस्टम पेश किए हैं जो इंसानी आवाज़ और लाइव वीडियो को बिना किसी देरी के समझ सकते हैं।</p><p>विशेषज्ञों के अनुसार, यह तकनीक कोडिंग, शिक्षा, स्वास्थ्य सेवा और व्यक्तिगत उत्पादकता में क्रांतिकारी बदलाव लाएगी।</p>',
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
                'title' => 'वंदे भारत स्लीपर एक्सप्रेस के 10 नए रूट्स का ऐलान, दिल्ली से पटना और मुंबई का सफर होगा आसान',
                'slug' => 'vande-bharat-sleeper-10-new-routes-announced',
                'category_id' => $cats['national']->id ?? 2,
                'user_id' => $admin->id,
                'excerpt' => 'रेलवे मंत्रालय ने आज 10 नए हाई-स्पीड स्लीपर कॉरिडोर की घोषणा की, यात्रियों को मिलेंगी विश्वस्तरीय सुविधाएं।',
                'content' => '<p><strong>नई दिल्ली:</strong> भारतीय रेलवे ने लंबी दूरी के यात्रियों के लिए बड़ा तोहफा दिया है। अत्याधुनिक सुविधाओं से लैस वंदे भारत स्लीपर ट्रेनों के 10 नए रूट्स की समय सारणी जारी कर दी गई है।</p><p>ट्रेन में कवच सुरक्षा प्रणाली, बेहतर एयर-सस्पेंशन और आधुनिक केटरिंग व्यवस्था मौजूद है।</p>',
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
                'title' => 'आरबीआई ने ब्याज दरों पर लिया अहम फैसला, जानिए आपकी होम लोन और कार लोन ईएमआई पर क्या होगा असर',
                'slug' => 'rbi-monetary-policy-repo-rate-home-loan-emi-impact',
                'category_id' => $cats['business']->id ?? 6,
                'user_id' => $admin->id,
                'excerpt' => 'मौद्रिक नीति समिति (एमपीसी) की तीन दिवसीय बैठक के बाद गवर्नर ने आर्थिक वृद्धि और महंगाई पर दिया अहम बयान।',
                'content' => '<p><strong>मुंबई:</strong> भारतीय रिज़र्व बैंक (RBI) की मौद्रिक नीति समिति ने अर्थव्यवस्था में तरलता और मुद्रास्फीति के संतुलन को देखते हुए नीतिगत फैसलों का ऐलान किया।</p><p>बैंकिंग विशेषज्ञों का मानना है कि इससे मध्यम वर्ग और रियल एस्टेट सेक्टर को बड़ी राहत मिलेगी।</p>',
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
                'title' => 'ऑस्कर 2027 के लिए भारत की आधिकारिक प्रविष्टि का ऐलान, स्वतंत्र सिनेमा की इस फिल्म ने मारी बाज़ी',
                'slug' => 'oscars-2027-india-official-entry-announcement',
                'category_id' => $cats['entertainment']->id ?? 4,
                'user_id' => $admin->id,
                'excerpt' => 'फिल्म फेडरेशन ऑफ इंडिया की जूरी ने देश भर की 25 फिल्मों में से सर्वसम्मति से इस मार्मिक कहानी को चुना।',
                'content' => '<p><strong>चेन्नई:</strong> 99वें अकादमी पुरस्कारों (ऑस्कर) के लिए भारत की ओर से आधिकारिक तौर पर भेजी जाने वाली फिल्म के नाम का चयन कर लिया गया है।</p><p>फिल्म निर्देशक ने इस सम्मान पर खुशी जताते हुए दर्शकों और कलाकारों का आभार व्यक्त किया।</p>',
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
                'title' => 'रोहित शर्मा और विराट कोहली की टेस्ट क्रिकेट में वापसी पर मुख्य कोच का बड़ा बयान, जानिए क्या बोले',
                'slug' => 'rohit-sharma-virat-kohli-test-cricket-head-coach-statement',
                'category_id' => $cats['sports']->id ?? 5,
                'user_id' => $admin->id,
                'excerpt' => 'आगामी पांच मैचों की बॉर्डर-गावस्कर टेस्ट सीरीज से पहले भारतीय टीम के रणनीति शिविर में अहम फैसले लिए गए।',
                'content' => '<p><strong>सिडनी:</strong> भारतीय क्रिकेट के दो दिग्गज बल्लेबाजों की फॉर्म और फिटनेस पर हेड कोच ने प्रेस कॉन्फ्रेंस में पत्रकारों के सवालों के बेबाक जवाब दिए।</p><p>कोच ने कहा कि दोनों खिलाड़ी टीम की रीढ़ हैं और उनके अनुभव का फायदा युवा खिलाड़ियों को मिल रहा है।</p>',
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
