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
                'name' => 'सौरभ त्रिपाठी (प्रधान संपादक)',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'bio' => 'वरिष्ठ पत्रकार एवं राजनीतिक विश्लेषक। निष्पक्ष पत्रकारिता और ग्राउंड रिपोर्टिंग के 15+ वर्ष का अनुभव।',
                'avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150&auto=format&fit=crop&q=80',
            ]
        );

        $editor = User::firstOrCreate(
            ['email' => 'editor@newskit.com'],
            [
                'name' => 'नेहा कश्यप (वरिष्ठ संपादक)',
                'password' => Hash::make('password123'),
                'role' => 'editor',
                'bio' => 'टेक, विज्ञान एवं अंतरराष्ट्रीय मामलों की प्रमुख विशेषज्ञ।',
                'avatar' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?w=150&auto=format&fit=crop&q=80',
            ]
        );

        // 2. Categories
        $categoriesData = [
            [
                'name' => 'Politics',
                'hindi_name' => 'राजनीति',
                'slug' => 'politics',
                'color' => '#E11D48',
                'order' => 1,
                'description' => 'चुनावी हलचल, संसद की खबरें, राजनीतिक बयानबाजी और अंदरूनी सियासी विश्लेषण।'
            ],
            [
                'name' => 'National',
                'hindi_name' => 'देश',
                'slug' => 'national',
                'color' => '#2563EB',
                'order' => 2,
                'description' => 'देश के कोने-कोने की अहम घटनाएं, नीतियां और जनता से जुड़े बड़े मुद्दे।'
            ],
            [
                'name' => 'Technology',
                'hindi_name' => 'टेक व AI',
                'slug' => 'technology',
                'color' => '#7C3AED',
                'order' => 3,
                'description' => 'आर्टिफिशियल इंटेलिजेंस, नए स्मार्टफोन्स, गैजेट्स और तकनीकी दुनिया के बड़े बदलाव।'
            ],
            [
                'name' => 'Entertainment',
                'hindi_name' => 'मनोरंजन',
                'slug' => 'entertainment',
                'color' => '#DB2777',
                'order' => 4,
                'description' => 'बॉलीवुड, हॉलीवुड, ओटीटी रिलीज, बॉक्स ऑफिस और सेलेब्स की एक्सक्लूसिव बातचीत।'
            ],
            [
                'name' => 'Sports',
                'hindi_name' => 'खेल',
                'slug' => 'sports',
                'color' => '#059669',
                'order' => 5,
                'description' => 'क्रिकेट, फुटबॉल, ओलंपिक्स और खेल जगत की लाइव अपडेट्स व रिकॉर्ड्स।'
            ],
            [
                'name' => 'Business',
                'hindi_name' => 'व्यापार',
                'slug' => 'business',
                'color' => '#D97706',
                'order' => 6,
                'description' => 'शेयर बाजार, बजट, स्टार्टअप्स, महंगाई और आपकी जेब से जुड़े बड़े फैसले।'
            ],
            [
                'name' => 'Viral & Fact Check',
                'hindi_name' => 'वायरल सच',
                'slug' => 'viral-fact-check',
                'color' => '#EA580C',
                'order' => 7,
                'description' => 'सोशल मीडिया पर उड़ती अफवाहों की पड़ताल और असली सच।'
            ],
        ];

        $categories = [];
        foreach ($categoriesData as $cat) {
            $categories[$cat['slug']] = Category::create($cat);
        }

        // 3. Tags
        $tagsData = ['BreakingNews', 'Budget2026', 'ElectionWatch', 'AI Revolution', 'Cricket2026', 'CinemaExclusive', 'ISROMission', 'StartupsIndia'];
        $tags = [];
        foreach ($tagsData as $tagName) {
            $tags[$tagName] = Tag::create(['name' => $tagName]);
        }

        // 4. Articles
        $articles = [
            [
                'title' => 'संसद में नए डिजिटल पर्सनल डेटा प्रोटेक्शन कानून पर गरमा-गरम बहस, विपक्ष ने उठाए सवाल',
                'slug' => 'parliament-debate-digital-data-protection-bill',
                'category_id' => $categories['politics']->id,
                'user_id' => $admin->id,
                'excerpt' => 'संसद के विशेष सत्र में आज डिजिटल गोपनीयता और डेटा सुरक्षा को लेकर पक्ष और विपक्ष के बीच तीखी नोकझोंक देखने को मिली।',
                'content' => "<p><strong>नई दिल्ली:</strong> संसद के चालू सत्र में आज डिजिटल सुरक्षा और नागरिक अधिकारों को लेकर ऐतिहासिक बहस हुई। आईटी मंत्री ने सदन में विधेयक पेश करते हुए कहा कि नागरिकों के डिजिटल अधिकारों की सुरक्षा हमारी सर्वोच्च प्राथमिकता है।</p><p>विपक्षी सांसदों ने कानून के कुछ प्रावधानों पर असहमति जताते हुए समिति के पास भेजने की मांग की। विशेषज्ञों का मानना है कि इस कानून के लागू होने के बाद ग्लोबल टेक कंपनियों को भारतीय यूज़र्स के डेटा को लेकर कड़े नियमों का पालन करना होगा।</p><p>सदन की कार्यवाही के दौरान सोशल मीडिया प्लेटफॉर्म्स की जवाबदेही, डीपफेक टेक्नोलॉजी और एआई के दुरुपयोग पर भी चर्चा हुई। सरकार ने स्पष्ट किया कि किसी भी हाल में राष्ट्रीय सुरक्षा और यूज़र प्राइवेसी से समझौता नहीं किया जाएगा।</p>",
                'featured_image' => 'https://images.unsplash.com/photo-1541872703-74c5e44368f9?w=1000&auto=format&fit=crop&q=80',
                'image_caption' => 'संसद भवन में बजट सत्र के दौरान बहस का दृश्य (फाइल फोटो)',
                'status' => 'published',
                'is_breaking' => true,
                'is_featured' => true,
                'is_trending' => true,
                'views_count' => 14200,
                'reading_time' => 4,
                'published_at' => now()->subHours(2),
            ],
            [
                'title' => 'ISRO का नया कीर्तिमान: शुक्रयान मिशन की तैयारियां अंतिम चरण में, 2028 में लॉन्चिंग की उम्मीद',
                'slug' => 'isro-shukrayaan-mission-preparations-venus-launch',
                'category_id' => $categories['technology']->id,
                'user_id' => $editor->id,
                'excerpt' => 'चंद्रयान और गगनयान के बाद अब इसरो वीनस यानी शुक्र ग्रह के रहस्यों को उजागर करने के लिए पूरी तरह कमर कस चुका है।',
                'content' => "<p><strong>बेंगलुरु:</strong> भारतीय अंतरिक्ष अनुसंधान संगठन (ISRO) ने एक और ऐतिहासिक मील का पत्थर पार करने की तैयारी शुरू कर दी है। इसरो प्रमुख ने आज घोषणा की कि शुक्रयान (Shukrayaan-1) मिशन का प्राथमिक डिजाइन और पेलोड टेस्टिंग सफलतापूर्वक पूरी कर ली गई है।</p><p>शुक्र ग्रह का वायुमंडल बेहद घना और एसिडिक है। इस मिशन का मुख्य उद्देश्य ग्रह की उप-सतह, वायुमंडलीय रसायन विज्ञान और वहां मौजूद सल्फ्यूरिक एसिड के बादलों की जांच करना है।</p><p>वैज्ञानिकों का मानना है कि शुक्र ग्रह के अध्ययन से हमें पृथ्वी पर हो रहे जलवायु परिवर्तनों को बेहतर ढंग से समझने में मदद मिलेगी। इस मिशन में अंतरराष्ट्रीय अंतरिक्ष एजेंसियों के साथ भी सहयोग किया जा रहा है।</p>",
                'featured_image' => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?w=1000&auto=format&fit=crop&q=80',
                'image_caption' => 'इसरो का डीप स्पेस एक्सप्लोरेशन सेंटर (प्रतिनिधि तस्वीर)',
                'status' => 'published',
                'is_breaking' => true,
                'is_featured' => true,
                'is_trending' => true,
                'views_count' => 28900,
                'reading_time' => 5,
                'published_at' => now()->subHours(4),
            ],
            [
                'title' => 'टी20 वर्ल्ड चैंपियनशिप फाइनल: भारत ने ऑस्ट्रेलिया को रोमांचक मुकाबले में 5 रनों से हराया',
                'slug' => 't20-championship-final-india-beats-australia-thriller',
                'category_id' => $categories['sports']->id,
                'user_id' => $admin->id,
                'excerpt' => 'आखिरी ओवर में जीत के लिए चाहिए थे 12 रन, भारतीय तेज़ गेंदबाज़ की यॉर्कर गेंदों ने दिलाई ऐतिहासिक जीत।',
                'content' => "<p><strong>मेलबर्न:</strong> भारतीय क्रिकेट टीम ने एक बार फिर दुनिया में अपना परचम लहराया है। मेलबर्न क्रिकेट ग्राउंड पर खेले गए सांस रोक देने वाले फाइनल मुकाबले में भारत ने ऑस्ट्रेलिया को मात्र 5 रनों से परास्त किया।</p><p>मैच का पासा पलटा 19वें और 20वें ओवर में। कप्तान ने शानदार अर्धशतकीय पारी खेली और फील्डिंग में दो बेहतरीन कैच लपके। पूरे देश में इस ऐतिहासिक जीत का जश्न मनाया जा रहा है।</p><p>पुरस्कार वितरण समारोह में मैन ऑफ द मैच का खिताब भारतीय ऑलराउंडर को दिया गया जिन्होंने गेंद और बल्ले दोनों से मैच जिताऊ प्रदर्शन किया।</p>",
                'featured_image' => 'https://images.unsplash.com/photo-1540747913346-19e32dc3e97e?w=1000&auto=format&fit=crop&q=80',
                'image_caption' => 'जीत के बाद भारतीय टीम का जश्न (फाइल फोटो)',
                'status' => 'published',
                'is_breaking' => true,
                'is_featured' => false,
                'is_trending' => true,
                'views_count' => 45600,
                'reading_time' => 3,
                'published_at' => now()->subHours(6),
            ],
            [
                'title' => 'बॉलीवुड मेगास्टार की बहुप्रतीक्षित एक्शन थ्रिलर का टीज़र रिलीज़, 24 घंटे में बने कई रिकॉर्ड',
                'slug' => 'bollywood-megastar-action-thriller-teaser-records',
                'category_id' => $categories['entertainment']->id,
                'user_id' => $editor->id,
                'excerpt' => 'हाई-ऑक्टेन स्टंट्स और दमदार डायलॉग्स से भरपूर टीज़र ने सोशल मीडिया पर तहलका मचा दिया है।',
                'content' => "<p><strong>मुंबई:</strong> सिनेमा प्रेमियों का इंतज़ार आखिरकार खत्म हुआ। साल की सबसे बड़ी एक्शन थ्रिलर का आधिकारिक टीज़र यूट्यूब और इंस्टाग्राम पर रिलीज़ कर दिया गया है।</p><p>रिलीज़ के महज 12 घंटों के भीतर टीज़र को 50 मिलियन से अधिक व्यूज मिल चुके हैं। फिल्म में अंतरराष्ट्रीय स्तर के वीएफएक्स और स्टंट्स का इस्तेमाल किया गया है।</p><p>फिल्म निर्देशक ने मीडिया से बातचीत में बताया कि यह फिल्म पैन-इंडिया स्तर पर 5 भाषाओं में रिलीज की जाएगी।</p>",
                'featured_image' => 'https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?w=1000&auto=format&fit=crop&q=80',
                'image_caption' => 'सिनेमा हॉल और प्रीमियर स्क्रीनिंग का दृश्य',
                'status' => 'published',
                'is_breaking' => false,
                'is_featured' => false,
                'is_trending' => true,
                'views_count' => 19300,
                'reading_time' => 2,
                'published_at' => now()->subHours(8),
            ],
            [
                'title' => 'सेंसेक्स में 850 अंकों की तूफानी बढ़त, आईटी और बैंकिंग शेयर्स में जबरदस्त खरीदारी',
                'slug' => 'stock-market-rally-sensex-surges-850-points',
                'category_id' => $categories['business']->id,
                'user_id' => $admin->id,
                'excerpt' => 'वैश्विक बाजारों से मिले मजबूत संकेतों और विदेशी निवेशकों की वापसी से दलाल स्ट्रीट में छाई हरियाली।',
                'content' => "<p><strong>मुंबई:</strong> भारतीय शेयर बाजार के लिए आज का कारोबारी सत्र बेहद उत्साहवर्धक रहा। बॉम्बे स्टॉक एक्सचेंज (BSE) का संवेदी सूचकांक सेंसेक्स 850 अंक उछलकर नए शिखर पर पहुंच गया।</p><p>वहीं नेशनल स्टॉक एक्सचेंज (NSE) का निफ्टी भी 250 अंकों से अधिक की मजबूती के साथ बंद हुआ। ऑटो, आईटी और पीएसयू बैंक शेयरों में सबसे ज्यादा तेजी दर्ज की गई।</p><p>बाजार विशेषज्ञों का कहना है कि आने वाले तिमाही नतीजों को लेकर कॉरपोरेट सेक्टर में भारी सकारात्मकता है।</p>",
                'featured_image' => 'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=1000&auto=format&fit=crop&q=80',
                'image_caption' => 'शेयर बाजार में ट्रेडिंग फ्लोर का दृश्य',
                'status' => 'published',
                'is_breaking' => false,
                'is_featured' => false,
                'is_trending' => false,
                'views_count' => 11200,
                'reading_time' => 3,
                'published_at' => now()->subDay(),
            ],
            [
                'title' => 'फैक्ट चेक: क्या सरकार सभी नागरिकों को दे रही है फ्री लैपटॉप? जानिए सोशल मीडिया पर वायरल मैसेज का सच',
                'slug' => 'fact-check-free-laptop-scheme-viral-whatsapp-scam',
                'category_id' => $categories['viral-fact-check']->id,
                'user_id' => $editor->id,
                'excerpt' => 'व्हाट्सएप ग्रुप्स में एक लिंक तेजी से फॉरवर्ड किया जा रहा है जिसमें फ्री लैपटॉप का दावा है। हमारी पड़ताल में जानिए सच।',
                'content' => "<p><strong>नई दिल्ली:</strong> सोशल मीडिया प्लेटफॉर्म्स, खासकर व्हाट्सएप और टेलीग्राम पर पिछले कुछ दिनों से एक मैसेज वायरल हो रहा है जिसमें दावा किया जा रहा है कि केंद्र सरकार डिजिटल इंडिया अभियान के तहत सभी छात्रों को मुफ्त लैपटॉप बांट रही है।</p><p><strong>हमारी जांच में क्या निकला?</strong><br>NewsKit की फैक्ट चेक टीम ने जब भारत सरकार की आधिकारिक वेबसाइट्स (PIB और इलेक्ट्रॉनिक्स मंत्रालय) की जांच की तो ऐसी किसी योजना का कोई नोटिफिकेशन नहीं मिला।</p><p>वायरल मैसेज में दिया गया लिंक एक संदिग्ध फिशिंग वेबसाइट पर ले जाता है, जहां यूज़र्स से उनकी निजी जानकारी और बैंक डिटेल्स मांगी जाती हैं। सरकार की पीआईबी फैक्ट चेक यूनिट ने भी इसे पूरी तरह फर्जी और धोखाधड़ी करार दिया है।</p><p>कृपया किसी भी अनजान लिंक पर क्लिक न करें और न ही अपनी निजी जानकारी साझा करें।</p>",
                'featured_image' => 'https://images.unsplash.com/photo-1563986768609-322da13575f3?w=1000&auto=format&fit=crop&q=80',
                'image_caption' => 'फैक्ट चेक टीम द्वारा पड़ताल और विश्लेषण',
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
                'user_name' => 'राजेश कुमार शर्मा',
                'user_email' => 'rajesh.sharma@example.com',
                'comment' => 'बहुत ही सटीक और तथ्यात्मक विश्लेषण। NewsKit की निष्पक्ष पत्रकारिता की यही पहचान है।',
                'is_approved' => true,
            ]);
            Comment::create([
                'article_id' => $created->id,
                'user_name' => 'प्रिया सिंह',
                'user_email' => 'priya.singh@example.com',
                'comment' => 'यह ख़बर बहुत ज़रूरी थी, खासकर युवाओं के लिए। शानदार कवरेज!',
                'is_approved' => true,
            ]);
        }

        // 5. Default Settings in DB for Single-File / Single-Page Branding
        $defaultSettings = [
            'site_name' => ['value' => 'The LallanTop News', 'group' => 'branding', 'type' => 'text', 'label' => 'साइट का नाम (Site Name)'],
            'site_tagline' => ['value' => 'बेबाक, सटीक और सबसे तेज़ ख़बरें', 'group' => 'branding', 'type' => 'text', 'label' => 'टैगलाइन (Site Tagline)'],
            'site_short_name' => ['value' => 'LT', 'group' => 'branding', 'type' => 'text', 'label' => 'शॉर्ट नाम (Badge Name)'],
            'logo_url' => ['value' => '/images/logo.svg', 'group' => 'branding', 'type' => 'image', 'label' => 'मुख्य लोगो (Logo URL)'],
            'favicon_url' => ['value' => '/images/favicon.svg', 'group' => 'branding', 'type' => 'image', 'label' => 'फ़ेविकॉन (Favicon URL)'],
            'primary_color' => ['value' => '#E11D48', 'group' => 'branding', 'type' => 'color', 'label' => 'प्राइमरी थीम कलर (Primary Color)'],
            'accent_color' => ['value' => '#F59E0B', 'group' => 'branding', 'type' => 'color', 'label' => 'एक्सेंट / हाइलाइट कलर (Accent Color)'],
            'breaking_ticker_enabled' => ['value' => '1', 'group' => 'portal', 'type' => 'boolean', 'label' => 'ब्रेकिंग न्यूज़ टिकर ऑन/ऑफ'],
            'breaking_ticker_title' => ['value' => 'ताज़ा ख़बरें', 'group' => 'portal', 'type' => 'text', 'label' => 'टिकर का हेडिंग'],
            'contact_email' => ['value' => 'editor@lallantop.in', 'group' => 'general', 'type' => 'text', 'label' => 'सम्पर्क ईमेल'],
            'contact_phone' => ['value' => '+91 98765 43210', 'group' => 'general', 'type' => 'text', 'label' => 'सम्पर्क फ़ोन'],
            'footer_about' => ['value' => 'NewsKit Bharat का सबसे विश्वसनीय और निष्पक्ष डिजिटल न्यूज़ नेटवर्क है। राजनीति, तकनीक, खेल, सिनेमा और दुनिया की हर बड़ी ख़बर सबसे पहले यहाँ।', 'group' => 'general', 'type' => 'textarea', 'label' => 'फुटर में अबाउट टेक्स्ट'],
            'copyright_text' => ['value' => '© 2026 The LallanTop News Network. सर्वाधिकार सुरक्षित।', 'group' => 'general', 'type' => 'text', 'label' => 'कॉपीराइट नोटिस'],
            'social_twitter' => ['value' => 'https://twitter.com/lallantop', 'group' => 'social', 'type' => 'text', 'label' => 'X (Twitter) Link'],
            'social_facebook' => ['value' => 'https://facebook.com/lallantop', 'group' => 'social', 'type' => 'text', 'label' => 'Facebook Link'],
            'social_youtube' => ['value' => 'https://youtube.com/@lallantop', 'group' => 'social', 'type' => 'text', 'label' => 'YouTube Link'],
            'social_instagram' => ['value' => 'https://instagram.com/lallantop', 'group' => 'social', 'type' => 'text', 'label' => 'Instagram Link'],
            'social_whatsapp' => ['value' => 'https://whatsapp.com/channel/lallantop', 'group' => 'social', 'type' => 'text', 'label' => 'WhatsApp Channel Link'],
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

        // 6. States and Cities Hyperlocal Seed
        $this->call(StateAndCitySeeder::class);
    }
}
