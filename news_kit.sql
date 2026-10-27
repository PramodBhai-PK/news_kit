-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 27, 2026 at 05:41 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `news_kit`
--

-- --------------------------------------------------------

--
-- Table structure for table `advertisements`
--

CREATE TABLE `advertisements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `type` enum('image','code') NOT NULL DEFAULT 'image',
  `image_url` varchar(255) DEFAULT NULL,
  `destination_url` varchar(255) DEFAULT NULL,
  `html_code` longtext DEFAULT NULL,
  `placement` varchar(255) NOT NULL DEFAULT 'sidebar',
  `order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `views_count` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `clicks_count` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `advertisements`
--

INSERT INTO `advertisements` (`id`, `title`, `type`, `image_url`, `destination_url`, `html_code`, `placement`, `order`, `is_active`, `views_count`, `clicks_count`, `created_at`, `updated_at`) VALUES
(1, 'अमेज़न व फ्लिपकार्ट मेगा इलेक्ट्रॉनिक्स कार्निवल 2026', 'image', 'https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?w=1200&auto=format&fit=crop&q=80', 'https://amazon.in', NULL, 'header', 1, 1, 3173, 140, '2026-10-26 05:25:27', '2026-10-27 06:12:40'),
(2, 'Google AdSense (क्लाउड व AI रिस्पॉन्सिव बैनर)', 'code', NULL, NULL, '<div class=\"w-full p-4 rounded-2xl bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-900 dark:to-slate-950 border border-slate-200 dark:border-slate-800 text-center shadow-xs\">\n    <div class=\"flex items-center justify-between text-[10px] text-slate-400 font-bold mb-2\">\n        <span class=\"flex items-center gap-1\">\n            <svg class=\"w-3 h-3 fill-blue-500\" viewBox=\"0 0 24 24\"><path d=\"M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z\"/></svg>\n            Ads by Google\n        </span>\n        <span class=\"border border-slate-300 dark:border-slate-700 px-1 rounded text-[9px]\">AdSense</span>\n    </div>\n    <div class=\"py-2\">\n        <h5 class=\"font-extrabold text-sm text-slate-900 dark:text-white mb-1\">Google Cloud & AI Platform</h5>\n        <p class=\"text-xs text-slate-600 dark:text-slate-400 mb-3\">Build and scale intelligent web apps with $300 free cloud credits and Google Gemini API access.</p>\n        <a href=\"https://cloud.google.com\" target=\"_blank\" rel=\"noopener sponsored\" class=\"inline-block px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow transition-all\">\n            Start Free Trial →\n        </a>\n    </div>\n</div>', 'in_feed', 1, 1, 2393, 112, '2026-10-26 05:25:27', '2026-10-27 06:12:40'),
(3, 'सैमसंग गैलेक्सी AI अल्ट्रा - भारी छूट व नो-कॉस्ट EMI', 'image', 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=600&auto=format&fit=crop&q=80', 'https://samsung.com', NULL, 'sidebar', 1, 1, 1061, 65, '2026-10-26 05:25:27', '2026-10-27 06:12:40'),
(4, 'सोनी ब्राविया 4K स्मार्ट टीवी - एक्सक्लूसिव एक्सचेंज बोनस', 'image', 'https://images.unsplash.com/photo-1550745165-9bc0b252726f?w=600&auto=format&fit=crop&q=80', 'https://sony.co.in', NULL, 'sidebar', 2, 1, 921, 52, '2026-10-26 05:25:27', '2026-10-27 06:12:40');

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE `articles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `excerpt` text DEFAULT NULL,
  `content` longtext NOT NULL,
  `featured_image` varchar(255) DEFAULT NULL,
  `image_caption` varchar(255) DEFAULT NULL,
  `video_type` varchar(255) NOT NULL DEFAULT 'none',
  `video_url` text DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `state_id` bigint(20) UNSIGNED DEFAULT NULL,
  `city_id` bigint(20) UNSIGNED DEFAULT NULL,
  `location_name` varchar(100) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('published','draft','archived') NOT NULL DEFAULT 'published',
  `is_breaking` tinyint(1) NOT NULL DEFAULT 0,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_trending` tinyint(1) NOT NULL DEFAULT 0,
  `views_count` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `reading_time` int(11) NOT NULL DEFAULT 3,
  `published_at` timestamp NULL DEFAULT NULL,
  `custom_ad_title` varchar(255) DEFAULT NULL,
  `custom_ad_type` varchar(255) NOT NULL DEFAULT 'none',
  `custom_ad_image` varchar(255) DEFAULT NULL,
  `custom_ad_link` varchar(255) DEFAULT NULL,
  `custom_ad_code` longtext DEFAULT NULL,
  `custom_ad_views_count` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `custom_ad_clicks_count` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`id`, `title`, `slug`, `excerpt`, `content`, `featured_image`, `image_caption`, `video_type`, `video_url`, `category_id`, `state_id`, `city_id`, `location_name`, `user_id`, `status`, `is_breaking`, `is_featured`, `is_trending`, `views_count`, `reading_time`, `published_at`, `custom_ad_title`, `custom_ad_type`, `custom_ad_image`, `custom_ad_link`, `custom_ad_code`, `custom_ad_views_count`, `custom_ad_clicks_count`, `created_at`, `updated_at`) VALUES
(1, 'संसद में नए डिजिटल पर्सनल डेटा प्रोटेक्शन कानून पर गरमा-गरम बहस, विपक्ष ने उठाए सवाल', 'parliament-debate-digital-data-protection-bill', 'संसद के विशेष सत्र में आज डिजिटल गोपनीयता और डेटा सुरक्षा को लेकर पक्ष और विपक्ष के बीच तीखी नोकझोंक देखने को मिली।', '<p><strong>नई दिल्ली:</strong> संसद के चालू सत्र में आज डिजिटल सुरक्षा और नागरिक अधिकारों को लेकर ऐतिहासिक बहस हुई। आईटी मंत्री ने सदन में विधेयक पेश करते हुए कहा कि नागरिकों के डिजिटल अधिकारों की सुरक्षा हमारी सर्वोच्च प्राथमिकता है।</p><p>विपक्षी सांसदों ने कानून के कुछ प्रावधानों पर असहमति जताते हुए समिति के पास भेजने की मांग की। विशेषज्ञों का मानना है कि इस कानून के लागू होने के बाद ग्लोबल टेक कंपनियों को भारतीय यूज़र्स के डेटा को लेकर कड़े नियमों का पालन करना होगा।</p><p>सदन की कार्यवाही के दौरान सोशल मीडिया प्लेटफॉर्म्स की जवाबदेही, डीपफेक टेक्नोलॉजी और एआई के दुरुपयोग पर भी चर्चा हुई। सरकार ने स्पष्ट किया कि किसी भी हाल में राष्ट्रीय सुरक्षा और यूज़र प्राइवेसी से समझौता नहीं किया जाएगा।</p>', 'https://images.unsplash.com/photo-1541872703-74c5e44368f9?w=1000&auto=format&fit=crop&q=80', 'संसद भवन में बजट सत्र के दौरान बहस का दृश्य (फाइल फोटो)', 'none', NULL, 1, 3, 17, 'संसद मार्ग, नई दिल्ली', 1, 'published', 1, 1, 1, 14213, 4, '2026-10-25 22:21:21', NULL, 'none', NULL, NULL, NULL, 0, 1, '2026-10-26 00:21:21', '2026-10-27 00:06:02'),
(2, 'ISRO का नया कीर्तिमान: शुक्रयान मिशन की तैयारियां अंतिम चरण में, 2028 में लॉन्चिंग की उम्मीद', 'isro-shukrayaan-mission-preparations-venus-launch', 'चंद्रयान और गगनयान के बाद अब इसरो वीनस यानी शुक्र ग्रह के रहस्यों को उजागर करने के लिए पूरी तरह कमर कस चुका है।', '<p><strong>बेंगलुरु:</strong> भारतीय अंतरिक्ष अनुसंधान संगठन (ISRO) ने एक और ऐतिहासिक मील का पत्थर पार करने की तैयारी शुरू कर दी है। इसरो प्रमुख ने आज घोषणा की कि शुक्रयान (Shukrayaan-1) मिशन का प्राथमिक डिजाइन और पेलोड टेस्टिंग सफलतापूर्वक पूरी कर ली गई है।</p><p>शुक्र ग्रह का वायुमंडल बेहद घना और एसिडिक है। इस मिशन का मुख्य उद्देश्य ग्रह की उप-सतह, वायुमंडलीय रसायन विज्ञान और वहां मौजूद सल्फ्यूरिक एसिड के बादलों की जांच करना है।</p><p>वैज्ञानिकों का मानना है कि शुक्र ग्रह के अध्ययन से हमें पृथ्वी पर हो रहे जलवायु परिवर्तनों को बेहतर ढंग से समझने में मदद मिलेगी। इस मिशन में अंतरराष्ट्रीय अंतरिक्ष एजेंसियों के साथ भी सहयोग किया जा रहा है।</p>', 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?w=1000&auto=format&fit=crop&q=80', 'इसरो का डीप स्पेस एक्सप्लोरेशन सेंटर (प्रतिनिधि तस्वीर)', 'none', NULL, 3, 1, 1, 'हज़रतगंज, लखनऊ', 5, 'published', 1, 1, 1, 28905, 5, '2026-10-25 20:21:21', NULL, 'none', NULL, NULL, NULL, 0, 0, '2026-10-26 00:21:21', '2026-10-26 10:17:29'),
(3, 'टी20 वर्ल्ड चैंपियनशिप फाइनल: भारत ने ऑस्ट्रेलिया को रोमांचक मुकाबले में 5 रनों से हराया', 't20-championship-final-india-beats-australia-thriller', 'आखिरी ओवर में जीत के लिए चाहिए थे 12 रन, भारतीय तेज़ गेंदबाज़ की यॉर्कर गेंदों ने दिलाई ऐतिहासिक जीत।', '<p><strong>मेलबर्न:</strong> भारतीय क्रिकेट टीम ने एक बार फिर दुनिया में अपना परचम लहराया है। मेलबर्न क्रिकेट ग्राउंड पर खेले गए सांस रोक देने वाले फाइनल मुकाबले में भारत ने ऑस्ट्रेलिया को मात्र 5 रनों से परास्त किया।</p><p>मैच का पासा पलटा 19वें और 20वें ओवर में। कप्तान ने शानदार अर्धशतकीय पारी खेली और फील्डिंग में दो बेहतरीन कैच लपके। पूरे देश में इस ऐतिहासिक जीत का जश्न मनाया जा रहा है।</p><p>पुरस्कार वितरण समारोह में मैन ऑफ द मैच का खिताब भारतीय ऑलराउंडर को दिया गया जिन्होंने गेंद और बल्ले दोनों से मैच जिताऊ प्रदर्शन किया।</p>', 'https://images.unsplash.com/photo-1540747913346-19e32dc3e97e?w=1000&auto=format&fit=crop&q=80', 'जीत के बाद भारतीय टीम का जश्न (फाइल फोटो)', 'none', NULL, 5, 1, 3, 'दशाश्वमेध घाट, वाराणसी', 4, 'published', 1, 0, 1, 45610, 3, '2026-10-25 18:21:21', NULL, 'none', NULL, NULL, NULL, 0, 0, '2026-10-26 00:21:21', '2026-10-26 22:50:23'),
(4, 'बॉलीवुड मेगास्टार की बहुप्रतीक्षित एक्शन थ्रिलर का टीज़र रिलीज़, 24 घंटे में बने कई रिकॉर्ड', 'bollywood-megastar-action-thriller-teaser-records', 'हाई-ऑक्टेन स्टंट्स और दमदार डायलॉग्स से भरपूर टीज़र ने सोशल मीडिया पर तहलका मचा दिया है।', '<p><strong>मुंबई:</strong> सिनेमा प्रेमियों का इंतज़ार आखिरकार खत्म हुआ। साल की सबसे बड़ी एक्शन थ्रिलर का आधिकारिक टीज़र यूट्यूब और इंस्टाग्राम पर रिलीज़ कर दिया गया है।</p><p>रिलीज़ के महज 12 घंटों के भीतर टीज़र को 50 मिलियन से अधिक व्यूज मिल चुके हैं। फिल्म में अंतरराष्ट्रीय स्तर के वीएफएक्स और स्टंट्स का इस्तेमाल किया गया है।</p><p>फिल्म निर्देशक ने मीडिया से बातचीत में बताया कि यह फिल्म पैन-इंडिया स्तर पर 5 भाषाओं में रिलीज की जाएगी।</p>', 'https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?w=1000&auto=format&fit=crop&q=80', 'सिनेमा हॉल और प्रीमियर स्क्रीनिंग का दृश्य', 'none', NULL, 4, 2, 11, 'डाक बंगला चौराहा, पटना', 4, 'published', 0, 0, 1, 19300, 2, '2026-10-25 16:21:21', NULL, 'none', NULL, NULL, NULL, 0, 0, '2026-10-26 00:21:21', '2026-10-26 10:17:29'),
(5, 'सेंसेक्स में 850 अंकों की तूफानी बढ़त, आईटी और बैंकिंग शेयर्स में जबरदस्त खरीदारी', 'stock-market-rally-sensex-surges-850-points', 'वैश्विक बाजारों से मिले मजबूत संकेतों और विदेशी निवेशकों की वापसी से दलाल स्ट्रीट में छाई हरियाली।', '<p><strong>मुंबई:</strong> भारतीय शेयर बाजार के लिए आज का कारोबारी सत्र बेहद उत्साहवर्धक रहा। बॉम्बे स्टॉक एक्सचेंज (BSE) का संवेदी सूचकांक सेंसेक्स 850 अंक उछलकर नए शिखर पर पहुंच गया।</p><p>वहीं नेशनल स्टॉक एक्सचेंज (NSE) का निफ्टी भी 250 अंकों से अधिक की मजबूती के साथ बंद हुआ। ऑटो, आईटी और पीएसयू बैंक शेयरों में सबसे ज्यादा तेजी दर्ज की गई।</p><p>बाजार विशेषज्ञों का कहना है कि आने वाले तिमाही नतीजों को लेकर कॉरपोरेट सेक्टर में भारी सकारात्मकता है।</p>', 'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=1000&auto=format&fit=crop&q=80', 'शेयर बाजार में ट्रेडिंग फ्लोर का दृश्य', 'none', NULL, 6, 1, 1, 'गोमती नगर, लखनऊ', 1, 'published', 0, 0, 0, 11202, 3, '2026-10-25 00:21:21', NULL, 'none', NULL, NULL, NULL, 0, 0, '2026-10-26 00:21:21', '2026-10-26 10:17:29'),
(6, 'फैक्ट चेक: क्या सरकार सभी नागरिकों को दे रही है फ्री लैपटॉप? जानिए सोशल मीडिया पर वायरल मैसेज का सच', 'fact-check-free-laptop-scheme-viral-whatsapp-scam', 'व्हाट्सएप ग्रुप्स में एक लिंक तेजी से फॉरवर्ड किया जा रहा है जिसमें फ्री लैपटॉप का दावा है। हमारी पड़ताल में जानिए सच।', '<p><strong>नई दिल्ली:</strong> सोशल मीडिया प्लेटफॉर्म्स, खासकर व्हाट्सएप और टेलीग्राम पर पिछले कुछ दिनों से एक मैसेज वायरल हो रहा है जिसमें दावा किया जा रहा है कि केंद्र सरकार डिजिटल इंडिया अभियान के तहत सभी छात्रों को मुफ्त लैपटॉप बांट रही है।</p><p><strong>हमारी जांच में क्या निकला?</strong><br>NewsKit की फैक्ट चेक टीम ने जब भारत सरकार की आधिकारिक वेबसाइट्स (PIB और इलेक्ट्रॉनिक्स मंत्रालय) की जांच की तो ऐसी किसी योजना का कोई नोटिफिकेशन नहीं मिला।</p><p>वायरल मैसेज में दिया गया लिंक एक संदिग्ध फिशिंग वेबसाइट पर ले जाता है, जहां यूज़र्स से उनकी निजी जानकारी और बैंक डिटेल्स मांगी जाती हैं। सरकार की पीआईबी फैक्ट चेक यूनिट ने भी इसे पूरी तरह फर्जी और धोखाधड़ी करार दिया है।</p><p>कृपया किसी भी अनजान लिंक पर क्लिक न करें और न ही अपनी निजी जानकारी साझा करें।</p>', 'https://images.unsplash.com/photo-1563986768609-322da13575f3?w=1000&auto=format&fit=crop&q=80', 'फैक्ट चेक टीम द्वारा पड़ताल और विश्लेषण', 'none', NULL, 7, NULL, NULL, NULL, 2, 'published', 0, 0, 1, 34104, 3, '2026-10-25 00:21:21', NULL, 'none', NULL, NULL, NULL, 0, 0, '2026-10-26 00:21:21', '2026-10-26 22:49:02'),
(7, 'ChatGPT 5 और Google Gemini की नई पीढ़ी लॉन्च, रियल-टाइम वॉइस और विज़न में क्रांतिकारी बदलाव', 'chatgpt-5-and-gemini-next-gen-launch-features', 'आर्टिफिशियल इंटेलिजेंस की दुनिया में नया मोड़, अब इंसानों की तरह सहज बात करने में सक्षम नए एआई मॉडल्स।', '<p><strong>सैन फ्रांसिस्को:</strong> टेक जगत में आज एआई के नए युग की शुरुआत हुई। प्रमुख टेक कंपनियों ने अपने नए मल्टीमॉडल एआई सिस्टम पेश किए हैं जो इंसानी आवाज़ और लाइव वीडियो को बिना किसी देरी के समझ सकते हैं।</p><p>विशेषज्ञों के अनुसार, यह तकनीक कोडिंग, शिक्षा, स्वास्थ्य सेवा और व्यक्तिगत उत्पादकता में क्रांतिकारी बदलाव लाएगी।</p>', 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?w=1000&auto=format&fit=crop&q=80', NULL, 'none', NULL, 3, NULL, NULL, NULL, 5, 'published', 1, 1, 1, 31210, 1, '2026-10-26 00:40:56', 'विशेष ऑफर: फुल-स्टैक AI इंजीनियर सर्टिफिकेशन (50% फ्लैट छूट)', 'image', 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?w=600&auto=format&fit=crop&q=80', 'https://example.com/ai-masterclass', NULL, 0, 0, '2026-10-26 01:25:56', '2026-10-26 04:15:37'),
(8, 'वंदे भारत स्लीपर एक्सप्रेस के 10 नए रूट्स का ऐलान, दिल्ली से पटना और मुंबई का सफर होगा आसान', 'vande-bharat-sleeper-10-new-routes-announced', 'रेलवे मंत्रालय ने आज 10 नए हाई-स्पीड स्लीपर कॉरिडोर की घोषणा की, यात्रियों को मिलेंगी विश्वस्तरीय सुविधाएं।', '<p><strong>नई दिल्ली:</strong> भारतीय रेलवे ने लंबी दूरी के यात्रियों के लिए बड़ा तोहफा दिया है। अत्याधुनिक सुविधाओं से लैस वंदे भारत स्लीपर ट्रेनों के 10 नए रूट्स की समय सारणी जारी कर दी गई है।</p><p>ट्रेन में कवच सुरक्षा प्रणाली, बेहतर एयर-सस्पेंशन और आधुनिक केटरिंग व्यवस्था मौजूद है।</p>', 'https://images.unsplash.com/photo-1532105956626-9569c03602f6?w=1000&auto=format&fit=crop&q=80', NULL, 'none', NULL, 2, NULL, NULL, NULL, 5, 'published', 0, 1, 1, 24505, 4, '2026-10-26 00:25:56', 'आईआरसीटीसी स्पेशल टूर पार्टनर (Google AdSense)', 'code', NULL, NULL, '<div class=\"w-full p-4 rounded-2xl bg-gradient-to-r from-amber-500/10 to-orange-500/10 border border-amber-500/30 text-center\">\n    <div class=\"flex items-center justify-between text-[10px] text-amber-600 font-bold mb-1.5\">\n        <span>Google AdSense Partner</span>\n        <span class=\"bg-amber-500 text-white px-1.5 py-0.5 rounded text-[9px] font-black\">AD</span>\n    </div>\n    <h5 class=\"font-black text-sm text-slate-900 dark:text-white mb-1\">IRCTC देखो अपना देश: स्पेशल समर टूर पैकेजेस</h5>\n    <p class=\"text-xs text-slate-600 dark:text-slate-300 mb-3\">वंदे भारत स्लीपर और लग्जरी होटल्स बुकिंग पर 20% तक की भारी बचत।</p>\n    <a href=\"https://irctctourism.com\" target=\"_blank\" rel=\"noopener sponsored\" class=\"inline-block px-4 py-1.5 bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs rounded-xl shadow transition-colors\">\n        अभी सीट बुक करें →\n    </a>\n</div>', 2, 0, '2026-10-26 01:25:56', '2026-10-26 07:21:28'),
(9, 'आरबीआई ने ब्याज दरों पर लिया अहम फैसला, जानिए आपकी होम लोन और कार लोन ईएमआई पर क्या होगा असर', 'rbi-monetary-policy-repo-rate-home-loan-emi-impact', 'मौद्रिक नीति समिति (एमपीसी) की तीन दिवसीय बैठक के बाद गवर्नर ने आर्थिक वृद्धि और महंगाई पर दिया अहम बयान।', '<p><strong>मुंबई:</strong> भारतीय रिज़र्व बैंक (RBI) की मौद्रिक नीति समिति ने अर्थव्यवस्था में तरलता और मुद्रास्फीति के संतुलन को देखते हुए नीतिगत फैसलों का ऐलान किया।</p><p>बैंकिंग विशेषज्ञों का मानना है कि इससे मध्यम वर्ग और रियल एस्टेट सेक्टर को बड़ी राहत मिलेगी।</p>', 'https://images.unsplash.com/photo-1526304640581-d334cdbbf45e?w=1000&auto=format&fit=crop&q=80', NULL, 'none', NULL, 6, NULL, NULL, NULL, 1, 'published', 0, 1, 1, 18903, 3, '2026-10-25 22:25:56', NULL, 'none', NULL, NULL, NULL, 0, 0, '2026-10-26 01:25:56', '2026-10-26 02:22:30'),
(10, 'ऑस्कर 2027 के लिए भारत की आधिकारिक प्रविष्टि का ऐलान, स्वतंत्र सिनेमा की इस फिल्म ने मारी बाज़ी', 'oscars-2027-india-official-entry-announcement', 'फिल्म फेडरेशन ऑफ इंडिया की जूरी ने देश भर की 25 फिल्मों में से सर्वसम्मति से इस मार्मिक कहानी को चुना।', '<p><strong>चेन्नई:</strong> 99वें अकादमी पुरस्कारों (ऑस्कर) के लिए भारत की ओर से आधिकारिक तौर पर भेजी जाने वाली फिल्म के नाम का चयन कर लिया गया है।</p><p>फिल्म निर्देशक ने इस सम्मान पर खुशी जताते हुए दर्शकों और कलाकारों का आभार व्यक्त किया।</p>', 'https://images.unsplash.com/photo-1517604931442-7e0c8ed2963c?w=1000&auto=format&fit=crop&q=80', NULL, 'none', NULL, 4, NULL, NULL, NULL, 1, 'published', 0, 1, 1, 22403, 2, '2026-10-25 20:25:56', NULL, 'none', NULL, NULL, NULL, 0, 0, '2026-10-26 01:25:56', '2026-10-26 05:05:43'),
(11, 'रोहित शर्मा और विराट कोहली की टेस्ट क्रिकेट में वापसी पर मुख्य कोच का बड़ा बयान, जानिए क्या बोले', 'rohit-sharma-virat-kohli-test-cricket-head-coach-statement', 'आगामी पांच मैचों की बॉर्डर-गावस्कर टेस्ट सीरीज से पहले भारतीय टीम के रणनीति शिविर में अहम फैसले लिए गए।', '<p><strong>सिडनी:</strong> भारतीय क्रिकेट के दो दिग्गज बल्लेबाजों की फॉर्म और फिटनेस पर हेड कोच ने प्रेस कॉन्फ्रेंस में पत्रकारों के सवालों के बेबाक जवाब दिए।</p><p>कोच ने कहा कि दोनों खिलाड़ी टीम की रीढ़ हैं और उनके अनुभव का फायदा युवा खिलाड़ियों को मिल रहा है।</p>', 'https://images.unsplash.com/photo-1531415074868-036b107e7742?w=1000&auto=format&fit=crop&q=80', NULL, 'none', NULL, 5, NULL, NULL, NULL, 4, 'published', 1, 0, 1, 38703, 3, '2026-10-25 18:25:56', NULL, 'none', NULL, NULL, NULL, 0, 0, '2026-10-26 01:25:56', '2026-10-26 04:19:51'),
(12, 'डिजिटल शिक्षा को बढ़ावा देने के लिए सरकार ने शुरू की नई पहल', 'dajatal-shakashha-ka-bugdhhava-thana-ka-le-sarakara-na-shanpr-ka-naii-pahal', 'सरकार ने छात्रों को डिजिटल शिक्षा से जोड़ने के लिए एक नई पहल की घोषणा की है। इसके तहत ऑनलाइन पढ़ाई, डिजिटल लाइब्रेरी और आधुनिक शिक्षण संसाधनों को बढ़ावा देने पर जोर दिया जाएगा।', 'नई दिल्ली: देश में डिजिटल शिक्षा को अधिक सुलभ और प्रभावी बनाने के उद्देश्य से सरकार ने एक नई शैक्षणिक पहल की घोषणा की है। इस पहल का मुख्य लक्ष्य छात्रों को आधुनिक तकनीक के माध्यम से बेहतर शिक्षा उपलब्ध कराना और डिजिटल संसाधनों तक उनकी पहुँच बढ़ाना है।\r\n\r\nइस योजना के तहत स्कूलों में डिजिटल शिक्षण सामग्री, ऑनलाइन अध्ययन संसाधन और ई-लाइब्रेरी जैसी सुविधाओं को प्रोत्साहित करने पर ध्यान दिया जाएगा। इससे छात्रों को अपनी पढ़ाई के लिए अतिरिक्त संसाधन मिल सकेंगे और वे अपनी सुविधानुसार विभिन्न विषयों का अध्ययन कर सकेंगे।\r\n\r\nशिक्षा विशेषज्ञों का मानना है कि डिजिटल माध्यमों का सही उपयोग छात्रों की सीखने की क्षमता को बेहतर बनाने में मदद कर सकता है। विशेष रूप से ग्रामीण क्षेत्रों के विद्यार्थियों को गुणवत्तापूर्ण अध्ययन सामग्री उपलब्ध कराने में तकनीक महत्वपूर्ण भूमिका निभा सकती है।\r\n\r\nहालाँकि, इस पहल की सफलता के लिए इंटरनेट कनेक्टिविटी, डिजिटल उपकरणों की उपलब्धता और शिक्षकों को तकनीकी प्रशिक्षण देना भी आवश्यक होगा।\r\n\r\nनोट: यह केवल डेमो न्यूज़ है। इसमें दी गई पहल और घोषणा काल्पनिक हैं, इन्हें वास्तविक समाचार के रूप में प्रकाशित न करें।', '/storage/articles/CTpEaTvbZ1RnA8Lndatu61qHjsrDPKmFuo04BgxK.png', NULL, 'none', NULL, 3, NULL, NULL, NULL, 1, 'published', 1, 1, 1, 8, 1, '2026-10-26 04:23:54', NULL, 'none', NULL, NULL, NULL, 0, 0, '2026-10-26 04:23:54', '2026-10-26 22:48:30'),
(13, 'ग्राउंड रिपोर्ट: दिल्ली मेट्रो के नए फेज 4 विस्तार का पहला ऑन-साइट वीडियो कवरेज', 'ground-report-special-video-bulletin', 'दिल्ली मेट्रो के फेज 4 अंडरग्राउंड टनलिंग और नए एयरोसिटी-तुगलकाबाद कॉरिडोर का एक्सक्लूसिव ग्राउंड वीडियो जायजा।', '<p>दिल्ली मेट्रो रेल कॉर्पोरेशन (DMRC) ने फेज 4 के विस्तार कार्य को रिकॉर्ड गति से आगे बढ़ाया है। हमारी विशेष ग्राउंड रिपोर्ट में देखिए टनल बोरिंग मशीन (TBM) का लाइव ऑपरेशन और आगामी स्टेशनों की हाइ-टेक सुविधाएं।</p><p>विशेषज्ञों के अनुसार इस नए कॉरिडोर से रोजाना 5 लाख से अधिक यात्रियों का सफर 40 मिनट तक कम हो जाएगा। इस पूरी कवरेज को ऊपर दिए गए वीडियो प्लेयर में विस्तार से देखें।</p>', 'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?w=1200&auto=format&fit=crop&q=80', 'दिल्ली मेट्रो फेज 4 कॉरिडोर पर निर्माण कार्य - स्पेशल वीडियो रिपोर्ट', 'youtube', 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', 2, NULL, NULL, NULL, 4, 'published', 0, 1, 1, 14201, 3, '2026-10-26 04:40:57', NULL, 'none', NULL, NULL, NULL, 0, 0, '2026-10-26 04:39:43', '2026-10-26 08:27:54'),
(14, 'एक्सक्लूसिव वीडियो: इसरो के सेमी-क्रायोजेनिक इंजन का हाई-थ्रस्ट हॉट टेस्ट सफल', 'isro-next-gen-rocket-video-test', 'भारतीय अंतरिक्ष अनुसंधान संगठन (ISRO) ने तमिलनाडु के महेंद्रगिरि स्थित प्रोपल्शन कॉम्प्लेक्स में क्रायोजेनिक इंजन का सफल वीडियो परीक्षण जारी किया।', '<p>इसरो ने भारी रॉकेट लॉन्च क्षमताओं को नया आयाम देने वाले सेमी-क्रायोजेनिक इंजन का 500 सेकंड की अवधि का हॉट टेस्ट सफलतापूर्वक संपन्न कर लिया है। यह इंजन भविष्य के गगनयान और भारी सैटेलाइट लॉन्च में गेम-चेंजर साबित होगा।</p><p>इंजन परीक्षण का हाई-डेफिनिशन वीडियो हमारे पाठकों के लिए सीधे स्ट्रीम किया जा रहा है।</p>', 'https://images.unsplash.com/photo-1541185933-ef5d8ed016c2?w=1200&auto=format&fit=crop&q=80', 'महेंद्रगिरि टेस्ट फैसिलिटी में इंजन का लाइव हॉट टेस्ट', 'direct', 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4', 3, NULL, NULL, NULL, 4, 'published', 1, 0, 1, 28905, 2, '2026-10-26 02:40:57', NULL, 'none', NULL, NULL, NULL, 0, 0, '2026-10-26 04:39:43', '2026-10-26 06:56:23'),
(15, 'वीडियो बुलेटिन: भारतीय टीम के ट्रेनिंग कैंप से सीधी तस्वीरें, विराट और रोहित की धमाकेदार बैटिंग प्रैक्टिस', 'cricket-world-cup-highlights-video', 'आगामी चैंपियनशिप से पहले भारतीय बल्लेबाजों का नेट्स में पसीना बहाते वीडियो सामने आया। देखिए पुल शॉट्स और स्ट्रेट ड्राइव्स का अनोखा नजारा।', '<p>भारतीय क्रिकेट टीम ने आज सुबह नरेंद्र मोदी स्टेडियम में सघन अभ्यास सत्र में भाग लिया। मुख्य कोच की देखरेख में बल्लेबाजों ने तेज गेंदबाजों और लेग स्पिनरों के खिलाफ आक्रामक तकनीक का अभ्यास किया।</p><p>विशेषज्ञों का मानना है कि वर्तमान फॉर्म को देखते हुए टीम का आत्मविश्वास सातवें आसमान पर है।</p>', 'https://images.unsplash.com/photo-1540747913346-19e32dc3e97e?w=1200&auto=format&fit=crop&q=80', 'नेट्स प्रैक्टिस के दौरान भारतीय टीम के दिग्गज बल्लेबाज', 'youtube', 'https://www.youtube.com/watch?v=aqz-KE-bpKQ', 5, NULL, NULL, NULL, 1, 'published', 0, 0, 1, 35603, 3, '2026-10-26 00:40:57', NULL, 'none', NULL, NULL, NULL, 0, 0, '2026-10-26 04:39:43', '2026-10-26 22:48:03'),
(16, 'बाज़ार बुलेटिन: सेंसेक्स और निफ्टी ने बनाया नया रिकॉर्ड हाई, जानिए टॉप एक्सपर्ट्स का मार्केट वीडियो विश्लेषण', 'stock-market-bull-run-video-analysis', 'घरेलू और विदेशी निवेशकों की भारी खरीदारी से शेयर बाजार में जबर्दस्त तेजी। प्रमुख सेक्टर्स के चार्ट्स पर विशेषज्ञ की राय।', '<p>शेयर बाज़ार में आज ऐतिहासिक तेजी देखने को मिली। आईटी और बैंकिंग शेयर्स में जोरदार खरीदारी दर्ज की गई। बाजार के दिग्गजों से समझिए आगामी हफ्तों के लिए कौन से सेक्टर्स रहेंगे फोकस में।</p>', 'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=1200&auto=format&fit=crop&q=80', 'शेयर बाजार में रिकॉर्ड उछाल - विशेष मार्केट वीडियो रिपोर्ट', 'youtube', 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', 6, NULL, NULL, NULL, 1, 'published', 0, 0, 1, 19800, 2, '2026-10-25 22:40:57', NULL, 'none', NULL, NULL, NULL, 0, 0, '2026-10-26 06:40:57', '2026-10-26 06:40:57'),
(17, 'News from Viewer', 'news-from-viewer', 'Jo Ghatnaye huyi hai unka detailes', '<p>Jo Ghatnaye huyi hai unka detailes</p><p><strong>स्रोत (नागरिक पत्रकारिता डेस्क):</strong> विशेष नागरिक संवाददाता (पहचान गोपनीय)</p>', '/storage/uploads/citizen_tips/qZbYOMME37GBqJq1Ki5BI4ohvXkNc4hmgHFjznpW.png', NULL, 'none', NULL, 1, 1, 3, 'Cantt Station', 3, 'draft', 0, 0, 0, 0, 2, NULL, NULL, 'none', NULL, NULL, NULL, 0, 0, '2026-10-27 02:39:26', '2026-10-27 02:39:26');

-- --------------------------------------------------------

--
-- Table structure for table `article_tag`
--

CREATE TABLE `article_tag` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `article_id` bigint(20) UNSIGNED NOT NULL,
  `tag_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `article_tag`
--

INSERT INTO `article_tag` (`id`, `article_id`, `tag_id`) VALUES
(1, 1, 1),
(2, 1, 3),
(3, 2, 1),
(4, 2, 3),
(5, 3, 1),
(6, 3, 3),
(7, 4, 1),
(8, 4, 3),
(9, 5, 1),
(10, 5, 3),
(11, 6, 1),
(12, 6, 3),
(13, 12, 9),
(14, 12, 10);

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `hindi_name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `color` varchar(20) NOT NULL DEFAULT '#E11D48',
  `icon` varchar(255) DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `hindi_name`, `description`, `color`, `icon`, `order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Politics', 'politics', 'राजनीति', 'चुनावी हलचल, संसद की खबरें, राजनीतिक बयानबाजी और अंदरूनी सियासी विश्लेषण।', '#E11D48', NULL, 1, 1, '2026-10-26 00:21:20', '2026-10-26 00:21:20'),
(2, 'National', 'national', 'देश', 'देश के कोने-कोने की अहम घटनाएं, नीतियां और जनता से जुड़े बड़े मुद्दे।', '#2563EB', NULL, 2, 1, '2026-10-26 00:21:20', '2026-10-26 00:21:20'),
(3, 'Technology', 'technology', 'टेक व AI', 'आर्टिफिशियल इंटेलिजेंस, नए स्मार्टफोन्स, गैजेट्स और तकनीकी दुनिया के बड़े बदलाव।', '#7C3AED', NULL, 3, 1, '2026-10-26 00:21:20', '2026-10-26 00:21:20'),
(4, 'Entertainment', 'entertainment', 'मनोरंजन', 'बॉलीवुड, हॉलीवुड, ओटीटी रिलीज, बॉक्स ऑफिस और सेलेब्स की एक्सक्लूसिव बातचीत।', '#DB2777', NULL, 4, 1, '2026-10-26 00:21:20', '2026-10-26 00:21:20'),
(5, 'Sports', 'sports', 'खेल', 'क्रिकेट, फुटबॉल, ओलंपिक्स और खेल जगत की लाइव अपडेट्स व रिकॉर्ड्स।', '#059669', NULL, 5, 1, '2026-10-26 00:21:20', '2026-10-26 00:21:20'),
(6, 'Business', 'business', 'व्यापार', 'शेयर बाजार, बजट, स्टार्टअप्स, महंगाई और आपकी जेब से जुड़े बड़े फैसले।', '#D97706', NULL, 6, 1, '2026-10-26 00:21:20', '2026-10-26 00:21:20'),
(7, 'Viral & Fact Check', 'viral-fact-check', 'वायरल सच', 'सोशल मीडिया पर उड़ती अफवाहों की पड़ताल और असली सच।', '#EA580C', NULL, 7, 1, '2026-10-26 00:21:20', '2026-10-26 00:21:20');

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `state_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `name_en` varchar(255) DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`id`, `state_id`, `name`, `name_en`, `slug`, `order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'लखनऊ', 'Lucknow', 'lucknow', 1, 1, '2026-10-26 10:17:29', '2026-10-26 10:17:29'),
(2, 1, 'कानपुर', 'Kanpur', 'kanpur', 2, 1, '2026-10-26 10:17:29', '2026-10-26 10:17:29'),
(3, 1, 'वाराणसी', 'Varanasi', 'varanasi', 3, 1, '2026-10-26 10:17:29', '2026-10-26 10:17:29'),
(4, 1, 'प्रयागराज', 'Prayagraj', 'prayagraj', 4, 1, '2026-10-26 10:17:29', '2026-10-26 10:17:29'),
(5, 1, 'गोरखपुर', 'Gorakhpur', 'gorakhpur', 5, 1, '2026-10-26 10:17:29', '2026-10-26 10:17:29'),
(6, 1, 'नोएडा', 'Noida', 'noida', 6, 1, '2026-10-26 10:17:29', '2026-10-26 10:17:29'),
(7, 1, 'अयोध्या', 'Ayodhya', 'ayodhya', 7, 1, '2026-10-26 10:17:29', '2026-10-26 10:17:29'),
(8, 1, 'आगरा', 'Agra', 'agra', 8, 1, '2026-10-26 10:17:29', '2026-10-26 10:17:29'),
(9, 1, 'मेरठ', 'Meerut', 'meerut', 9, 1, '2026-10-26 10:17:29', '2026-10-26 10:17:29'),
(10, 1, 'बरेली', 'Bareilly', 'bareilly', 10, 1, '2026-10-26 10:17:29', '2026-10-26 10:17:29'),
(11, 2, 'पटना', 'Patna', 'patna', 1, 1, '2026-10-26 10:17:29', '2026-10-26 10:17:29'),
(12, 2, 'गया', 'Gaya', 'gaya', 2, 1, '2026-10-26 10:17:29', '2026-10-26 10:17:29'),
(13, 2, 'मुज़फ़्फ़रपुर', 'Muzaffarpur', 'muzaffarpur', 3, 1, '2026-10-26 10:17:29', '2026-10-26 10:17:29'),
(14, 2, 'भागलपुर', 'Bhagalpur', 'bhagalpur', 4, 1, '2026-10-26 10:17:29', '2026-10-26 10:17:29'),
(15, 2, 'दरभंगा', 'Darbhanga', 'darbhanga', 5, 1, '2026-10-26 10:17:29', '2026-10-26 10:17:29'),
(16, 2, 'पूर्णिया', 'Purnia', 'purnia', 6, 1, '2026-10-26 10:17:29', '2026-10-26 10:17:29'),
(17, 3, 'नई दिल्ली', 'New Delhi', 'new-delhi', 1, 1, '2026-10-26 10:17:29', '2026-10-26 10:17:29'),
(18, 3, 'दक्षिणी दिल्ली', 'South Delhi', 'south-delhi', 2, 1, '2026-10-26 10:17:29', '2026-10-26 10:17:29'),
(19, 3, 'गुरुग्राम', 'Gurugram', 'gurugram', 3, 1, '2026-10-26 10:17:29', '2026-10-26 10:17:29'),
(20, 3, 'गाज़ियाबाद', 'Ghaziabad', 'ghaziabad', 4, 1, '2026-10-26 10:17:29', '2026-10-26 10:17:29'),
(21, 3, 'फरीदाबाद', 'Faridabad', 'faridabad', 5, 1, '2026-10-26 10:17:29', '2026-10-26 10:17:29'),
(22, 4, 'भोपाल', 'Bhopal', 'bhopal', 1, 1, '2026-10-26 10:17:29', '2026-10-26 10:17:29'),
(23, 4, 'इंदौर', 'Indore', 'indore', 2, 1, '2026-10-26 10:17:29', '2026-10-26 10:17:29'),
(24, 4, 'ग्वालियर', 'Gwalior', 'gwalior', 3, 1, '2026-10-26 10:17:29', '2026-10-26 10:17:29'),
(25, 4, 'जबलपुर', 'Jabalpur', 'jabalpur', 4, 1, '2026-10-26 10:17:29', '2026-10-26 10:17:29'),
(26, 4, 'उज्जैन', 'Ujjain', 'ujjain', 5, 1, '2026-10-26 10:17:29', '2026-10-26 10:17:29'),
(27, 5, 'जयपुर', 'Jaipur', 'jaipur', 1, 1, '2026-10-26 10:17:29', '2026-10-26 10:17:29'),
(28, 5, 'जोधपुर', 'Jodhpur', 'jodhpur', 2, 1, '2026-10-26 10:17:29', '2026-10-26 10:17:29'),
(29, 5, 'उदयपुर', 'Udaipur', 'udaipur', 3, 1, '2026-10-26 10:17:29', '2026-10-26 10:17:29'),
(30, 5, 'कोटा', 'Kota', 'kota', 4, 1, '2026-10-26 10:17:29', '2026-10-26 10:17:29'),
(31, 5, 'बीकानेर', 'Bikaner', 'bikaner', 5, 1, '2026-10-26 10:17:29', '2026-10-26 10:17:29'),
(32, 6, 'मुंबई', 'Mumbai', 'mumbai', 1, 1, '2026-10-26 10:17:29', '2026-10-26 10:17:29'),
(33, 6, 'पुणे', 'Pune', 'pune', 2, 1, '2026-10-26 10:17:29', '2026-10-26 10:17:29'),
(34, 6, 'नागपुर', 'Nagpur', 'nagpur', 3, 1, '2026-10-26 10:17:29', '2026-10-26 10:17:29'),
(35, 6, 'ठाणे', 'Thane', 'thane', 4, 1, '2026-10-26 10:17:29', '2026-10-26 10:17:29'),
(36, 6, 'नासिक', 'Nashik', 'nashik', 5, 1, '2026-10-26 10:17:29', '2026-10-26 10:17:29'),
(37, 7, 'देहरादून', 'Dehradun', 'dehradun', 1, 1, '2026-10-26 10:17:29', '2026-10-26 10:17:29'),
(38, 7, 'हरिद्वार', 'Haridwar', 'haridwar', 2, 1, '2026-10-26 10:17:29', '2026-10-26 10:17:29'),
(39, 7, 'नैनीताल', 'Nainital', 'nainital', 3, 1, '2026-10-26 10:17:29', '2026-10-26 10:17:29'),
(40, 7, 'ऋषिकेश', 'Rishikesh', 'rishikesh', 4, 1, '2026-10-26 10:17:29', '2026-10-26 10:17:29'),
(41, 8, 'रांची', 'Ranchi', 'ranchi', 1, 1, '2026-10-26 10:17:29', '2026-10-26 10:17:29'),
(42, 8, 'जमशेदपुर', 'Jamshedpur', 'jamshedpur', 2, 1, '2026-10-26 10:17:29', '2026-10-26 10:17:29'),
(43, 8, 'धनबाद', 'Dhanbad', 'dhanbad', 3, 1, '2026-10-26 10:17:29', '2026-10-26 10:17:29');

-- --------------------------------------------------------

--
-- Table structure for table `citizen_tips`
--

CREATE TABLE `citizen_tips` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `location_name` varchar(255) DEFAULT NULL,
  `state_id` bigint(20) UNSIGNED DEFAULT NULL,
  `city_id` bigint(20) UNSIGNED DEFAULT NULL,
  `witness_name` varchar(255) DEFAULT NULL,
  `witness_phone` varchar(255) DEFAULT NULL,
  `witness_email` varchar(255) DEFAULT NULL,
  `is_anonymous` tinyint(1) NOT NULL DEFAULT 0,
  `media_path` varchar(255) DEFAULT NULL,
  `media_type` varchar(20) DEFAULT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'pending',
  `admin_notes` text DEFAULT NULL,
  `converted_article_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `citizen_tips`
--

INSERT INTO `citizen_tips` (`id`, `title`, `description`, `location_name`, `state_id`, `city_id`, `witness_name`, `witness_phone`, `witness_email`, `is_anonymous`, `media_path`, `media_type`, `status`, `admin_notes`, `converted_article_id`, `ip_address`, `created_at`, `updated_at`) VALUES
(1, 'News from Viewer', 'Jo Ghatnaye huyi hai unka detailes', 'Cantt Station', 1, 3, 'गोपनीय नागरिक', '+918318595770', 'pkyadav4488@gmail.com', 1, '/storage/uploads/citizen_tips/qZbYOMME37GBqJq1Ki5BI4ohvXkNc4hmgHFjznpW.png', 'image', 'converted_to_article', 'कन्वर्टेड बाय विकास वर्मा (डिजिटल प्रकाशक) ऑन 27 Oct, 2026 08:09 AM', 17, '127.0.0.1', '2026-10-27 02:31:37', '2026-10-27 02:39:26');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `article_id` bigint(20) UNSIGNED NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `is_approved` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `article_id`, `user_name`, `user_email`, `comment`, `is_approved`, `created_at`, `updated_at`) VALUES
(1, 1, 'राजेश कुमार शर्मा', 'rajesh.sharma@example.com', 'बहुत ही सटीक और तथ्यात्मक विश्लेषण। NewsKit की निष्पक्ष पत्रकारिता की यही पहचान है।', 1, '2026-10-26 00:21:21', '2026-10-26 00:21:21'),
(2, 1, 'प्रिया सिंह', 'priya.singh@example.com', 'यह ख़बर बहुत ज़रूरी थी, खासकर युवाओं के लिए। शानदार कवरेज!', 1, '2026-10-26 00:21:21', '2026-10-26 00:21:21'),
(3, 2, 'राजेश कुमार शर्मा', 'rajesh.sharma@example.com', 'बहुत ही सटीक और तथ्यात्मक विश्लेषण। NewsKit की निष्पक्ष पत्रकारिता की यही पहचान है।', 1, '2026-10-26 00:21:21', '2026-10-26 00:21:21'),
(4, 2, 'प्रिया सिंह', 'priya.singh@example.com', 'यह ख़बर बहुत ज़रूरी थी, खासकर युवाओं के लिए। शानदार कवरेज!', 1, '2026-10-26 00:21:21', '2026-10-26 00:21:21'),
(5, 3, 'राजेश कुमार शर्मा', 'rajesh.sharma@example.com', 'बहुत ही सटीक और तथ्यात्मक विश्लेषण। NewsKit की निष्पक्ष पत्रकारिता की यही पहचान है।', 1, '2026-10-26 00:21:21', '2026-10-26 00:21:21'),
(6, 3, 'प्रिया सिंह', 'priya.singh@example.com', 'यह ख़बर बहुत ज़रूरी थी, खासकर युवाओं के लिए। शानदार कवरेज!', 1, '2026-10-26 00:21:21', '2026-10-26 00:21:21'),
(7, 4, 'राजेश कुमार शर्मा', 'rajesh.sharma@example.com', 'बहुत ही सटीक और तथ्यात्मक विश्लेषण। NewsKit की निष्पक्ष पत्रकारिता की यही पहचान है।', 1, '2026-10-26 00:21:21', '2026-10-26 00:21:21'),
(8, 4, 'प्रिया सिंह', 'priya.singh@example.com', 'यह ख़बर बहुत ज़रूरी थी, खासकर युवाओं के लिए। शानदार कवरेज!', 1, '2026-10-26 00:21:21', '2026-10-26 00:21:21'),
(9, 5, 'राजेश कुमार शर्मा', 'rajesh.sharma@example.com', 'बहुत ही सटीक और तथ्यात्मक विश्लेषण। NewsKit की निष्पक्ष पत्रकारिता की यही पहचान है।', 1, '2026-10-26 00:21:21', '2026-10-26 00:21:21'),
(10, 5, 'प्रिया सिंह', 'priya.singh@example.com', 'यह ख़बर बहुत ज़रूरी थी, खासकर युवाओं के लिए। शानदार कवरेज!', 1, '2026-10-26 00:21:21', '2026-10-26 00:21:21'),
(11, 6, 'राजेश कुमार शर्मा', 'rajesh.sharma@example.com', 'बहुत ही सटीक और तथ्यात्मक विश्लेषण। NewsKit की निष्पक्ष पत्रकारिता की यही पहचान है।', 1, '2026-10-26 00:21:21', '2026-10-26 00:21:21'),
(12, 6, 'प्रिया सिंह', 'priya.singh@example.com', 'यह ख़बर बहुत ज़रूरी थी, खासकर युवाओं के लिए। शानदार कवरेज!', 1, '2026-10-26 00:21:21', '2026-10-26 00:21:21');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` varchar(255) NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` smallint(5) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_10_26_000001_create_news_cms_tables', 1),
(5, '2026_10_26_000002_create_advertisements_table', 2),
(6, '2026_10_26_000003_add_phone_and_status_to_users_table', 3),
(7, '2026_10_26_000004_create_subscribers_table', 4),
(8, '2026_10_26_000005_add_video_fields_to_articles_table', 4),
(9, '2026_10_26_000006_add_ad_counts_to_articles_table', 5),
(10, '2026_10_26_000007_create_states_and_cities_tables', 6),
(11, '2026_10_27_000008_add_press_id_card_fields_to_users_table', 7),
(12, '2026_10_27_000009_create_citizen_tips_table', 7);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('57fji4cIbuzcSORLa68gG6C7xwsw7AZhFLlz2ec4', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJ5aU9qN2U4blZZWlJFMXRyNUdDRnVZczdsOTNSMzZkTGRKOTlHb091IiwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjMsImxvY2FsZSI6ImhpIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9hZG1pblwvcHJvZmlsZSIsInJvdXRlIjoiYWRtaW4ucHJvZmlsZS5lZGl0In0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1793101399),
('GcwvvwXS8whMjCRemXJFVOdXeueNHT1vt66RHfLR', NULL, '127.0.0.1', 'curl/8.21.0', 'eyJfdG9rZW4iOiI2WHpmU2lyZHNIZE05SVJHUm1sMEhNVWRnZ0FHSVA2dFduRVZ5R1JCIiwibG9jYWxlIjoiaGkiLCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDAiLCJyb3V0ZSI6InBvcnRhbC5pbmRleCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1793101242);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` longtext DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'text',
  `group` varchar(255) NOT NULL DEFAULT 'general',
  `label` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `type`, `group`, `label`, `description`, `created_at`, `updated_at`) VALUES
(1, 'site_name', 'News Kit', 'text', 'branding', 'Site Name', NULL, '2026-10-26 00:21:21', '2026-10-26 01:15:20'),
(2, 'site_tagline', 'बेबाक, सटीक और सबसे तेज़ ख़बरें', 'text', 'branding', 'Site Tagline', NULL, '2026-10-26 00:21:21', '2026-10-26 01:15:20'),
(3, 'site_short_name', 'NK', 'text', 'branding', 'Site Short Name', NULL, '2026-10-26 00:21:21', '2026-10-26 01:15:20'),
(4, 'logo_url', '/storage/branding/85GtuGVU8TP39wEj5eHGmqNFB2mgthQEmYKbfU4q.png', 'text', 'branding', 'Logo Url', NULL, '2026-10-26 00:21:21', '2026-10-26 01:18:11'),
(5, 'favicon_url', '/images/favicon.svg', 'text', 'branding', 'Favicon Url', NULL, '2026-10-26 00:21:21', '2026-10-26 01:15:20'),
(6, 'primary_color', '#059669', 'color', 'branding', 'Primary Color', NULL, '2026-10-26 00:21:21', '2026-10-26 08:28:25'),
(7, 'accent_color', '#10B981', 'color', 'branding', 'Accent Color', NULL, '2026-10-26 00:21:21', '2026-10-26 08:28:25'),
(8, 'breaking_ticker_enabled', '1', 'text', 'portal', 'Breaking Ticker Enabled', NULL, '2026-10-26 00:21:21', '2026-10-26 01:15:20'),
(9, 'breaking_ticker_title', 'ताज़ा ख़बरें', 'text', 'portal', 'Breaking Ticker Title', NULL, '2026-10-26 00:21:21', '2026-10-26 01:15:20'),
(10, 'contact_email', 'editor@newskit.sss', 'text', 'general', 'Contact Email', NULL, '2026-10-26 00:21:21', '2026-10-26 01:15:20'),
(11, 'contact_phone', '+91 98765 43210', 'text', 'general', 'Contact Phone', NULL, '2026-10-26 00:21:21', '2026-10-26 01:15:20'),
(12, 'footer_about', 'NewsKit Bharat का सबसे विश्वसनीय और निष्पक्ष डिजिटल न्यूज़ नेटवर्क है। राजनीति, तकनीक, खेल, सिनेमा और दुनिया की हर बड़ी ख़बर सबसे पहले यहाँ।', 'textarea', 'general', 'Footer About', NULL, '2026-10-26 00:21:21', '2026-10-26 01:15:20'),
(13, 'copyright_text', '© 2026 News Kit Network. सर्वाधिकार सुरक्षित।', 'text', 'general', 'Copyright Text', NULL, '2026-10-26 00:21:21', '2026-10-26 01:15:20'),
(14, 'social_twitter', 'https://twitter.com/NewsKit', 'text', 'social', 'Social Twitter', NULL, '2026-10-26 00:21:21', '2026-10-26 01:15:20'),
(15, 'social_facebook', 'https://facebook.com/lallantop', 'text', 'social', 'Facebook Link', NULL, '2026-10-26 00:21:21', '2026-10-26 00:21:21'),
(16, 'social_youtube', 'https://youtube.com/@NewsKit', 'text', 'social', 'Social Youtube', NULL, '2026-10-26 00:21:21', '2026-10-26 01:15:20'),
(17, 'social_instagram', 'https://instagram.com/lallantop', 'text', 'social', 'Instagram Link', NULL, '2026-10-26 00:21:21', '2026-10-26 00:21:21'),
(18, 'social_whatsapp', 'https://whatsapp.com/channel/NewsKit', 'text', 'social', 'Social Whatsapp', NULL, '2026-10-26 00:21:21', '2026-10-26 01:15:20'),
(19, 'office_address', 'नई दिल्ली, भारत', 'text', 'general', 'Office Address', NULL, '2026-10-26 01:15:20', '2026-10-26 01:15:20');

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

CREATE TABLE `states` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `name_en` varchar(255) DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `code` varchar(10) DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `states`
--

INSERT INTO `states` (`id`, `name`, `name_en`, `slug`, `code`, `order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'उत्तर प्रदेश', 'Uttar Pradesh', 'uttar-pradesh', 'UP', 1, 1, '2026-10-26 10:17:29', '2026-10-26 10:17:29'),
(2, 'बिहार', 'Bihar', 'bihar', 'BR', 2, 1, '2026-10-26 10:17:29', '2026-10-26 10:17:29'),
(3, 'दिल्ली-NCR', 'Delhi-NCR', 'delhi-ncr', 'DL', 3, 1, '2026-10-26 10:17:29', '2026-10-26 10:17:29'),
(4, 'मध्य प्रदेश', 'Madhya Pradesh', 'madhya-pradesh', 'MP', 4, 1, '2026-10-26 10:17:29', '2026-10-26 10:17:29'),
(5, 'राजस्थान', 'Rajasthan', 'rajasthan', 'RJ', 5, 1, '2026-10-26 10:17:29', '2026-10-26 10:17:29'),
(6, 'महाराष्ट्र', 'Maharashtra', 'maharashtra', 'MH', 6, 1, '2026-10-26 10:17:29', '2026-10-26 10:17:29'),
(7, 'उत्तराखंड', 'Uttarakhand', 'uttarakhand', 'UK', 7, 1, '2026-10-26 10:17:29', '2026-10-26 10:17:29'),
(8, 'झारखंड', 'Jharkhand', 'jharkhand', 'JH', 8, 1, '2026-10-26 10:17:29', '2026-10-26 10:17:29');

-- --------------------------------------------------------

--
-- Table structure for table `subscribers`
--

CREATE TABLE `subscribers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `ip_address` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subscribers`
--

INSERT INTO `subscribers` (`id`, `email`, `is_active`, `ip_address`, `created_at`, `updated_at`) VALUES
(1, 'rahul.verma@gmail.com', 1, '127.0.0.1', '2026-10-26 04:39:43', '2026-10-26 04:39:43'),
(2, 'priya.sharma@outlook.com', 1, '127.0.0.1', '2026-10-26 04:39:43', '2026-10-26 04:39:43'),
(3, 'amit.kumar@yahoo.com', 1, '127.0.0.1', '2026-10-26 04:39:43', '2026-10-26 04:39:43'),
(4, 'sunita.patel@rediffmail.com', 1, '127.0.0.1', '2026-10-26 04:39:44', '2026-10-26 04:39:44'),
(5, 'vikas.mishra@hotmail.com', 1, '127.0.0.1', '2026-10-26 04:39:44', '2026-10-27 05:56:49'),
(6, 'curltester@newskit.in', 1, '127.0.0.1', '2026-10-26 04:42:27', '2026-10-26 04:42:27'),
(7, 'pkyadav4488@gmail.com', 1, '127.0.0.1', '2026-10-26 04:44:16', '2026-10-26 04:44:16');

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'BreakingNews', 'breakingnews', '2026-10-26 00:21:21', '2026-10-26 00:21:21'),
(2, 'Budget2026', 'budget2026', '2026-10-26 00:21:21', '2026-10-26 00:21:21'),
(3, 'ElectionWatch', 'electionwatch', '2026-10-26 00:21:21', '2026-10-26 00:21:21'),
(4, 'AI Revolution', 'ai-revolution', '2026-10-26 00:21:21', '2026-10-26 00:21:21'),
(5, 'Cricket2026', 'cricket2026', '2026-10-26 00:21:21', '2026-10-26 00:21:21'),
(6, 'CinemaExclusive', 'cinemaexclusive', '2026-10-26 00:21:21', '2026-10-26 00:21:21'),
(7, 'ISROMission', 'isromission', '2026-10-26 00:21:21', '2026-10-26 00:21:21'),
(8, 'StartupsIndia', 'startupsindia', '2026-10-26 00:21:21', '2026-10-26 00:21:21'),
(9, 'News world', 'news-world', '2026-10-26 04:23:54', '2026-10-26 04:23:54'),
(10, 'Top 10 tech AI', 'top-10-tech-ai', '2026-10-26 04:23:54', '2026-10-26 04:23:54');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'admin',
  `press_id_code` varchar(255) DEFAULT NULL,
  `designation` varchar(255) DEFAULT NULL,
  `blood_group` varchar(10) DEFAULT NULL,
  `bureau_location` varchar(255) DEFAULT NULL,
  `emergency_contact` varchar(255) DEFAULT NULL,
  `id_card_issued_at` date DEFAULT NULL,
  `id_card_expires_at` date DEFAULT NULL,
  `id_card_status` varchar(20) NOT NULL DEFAULT 'active',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `bio` text DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `email_verified_at`, `password`, `role`, `press_id_code`, `designation`, `blood_group`, `bureau_location`, `emergency_contact`, `id_card_issued_at`, `id_card_expires_at`, `id_card_status`, `is_active`, `bio`, `avatar`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'सौरभ त्रिपाठी (प्रधान संपादक)', 'admin@newskit.com', '+91 98765 43210', NULL, '$2y$12$VEYy0zJbIBiJWoEkfMANdeR0HBoYSerRVjNJuqsifcXqNqlVwUsky', 'admin', 'PRESS-HQ-2026-0001', 'प्रधान संपादक (Admin)', NULL, 'राष्ट्रीय समाचार ब्यूरो (National Bureau)', NULL, '2026-10-26', '2028-12-31', 'active', 1, 'संपादकीय नीति और राष्ट्रीय मुद्दों के विशेषज्ञ। 15+ वर्षों का निष्पक्ष पत्रकारिता अनुभव।', 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=200&auto=format&fit=crop&q=80', 'pTY3EZmNOLdZdYGfwuptZVzDwUKvaEtbhIZ2nIIIXyyJ6cPYvWYrCTLBTXpq', '2026-10-26 00:21:20', '2026-10-27 00:22:18'),
(2, 'नेहा कश्यप (वरिष्ठ संपादक)', 'editor@newskit.com', '+91 98765 43211', NULL, '$2y$12$i9KGkTNRFMcoK96NgH.AQeF5//NjoXRPm22ZMZ8/uo3qIS1BRGYUq', 'editor', 'PRESS-ED-2026-0002', 'वरिष्ठ संपादक (Editor)', NULL, 'राष्ट्रीय समाचार ब्यूरो (National Bureau)', NULL, '2026-10-26', '2028-12-31', 'active', 1, 'राजनीति और कूटनीति की गहरी समझ। देश-विदेश के राजनीतिक विश्लेषण में माहिर।', 'https://images.unsplash.com/photo-1580489944761-15a19d654956?w=200&auto=format&fit=crop&q=80', 'XwchUmUOi4q0TNOPIz3lQQf6ojn9YlVNwoVHR9fZHY6tU8Ny4MT7zRt5BUBg', '2026-10-26 00:21:20', '2026-10-27 00:22:18'),
(3, 'विकास वर्मा (डिजिटल प्रकाशक)', 'publisher@newskit.com', '+91 98765 43213', NULL, '$2y$12$4DqtE/4d4efaoBrzcknf4uopNAzeIMvoB.ietBBC8GXSKH.4emL1u', 'publisher', 'PRESS-PUB-2026-0003', 'डिजिटल प्रकाशक (Publisher)', NULL, 'राष्ट्रीय समाचार ब्यूरो (National Bureau)', NULL, '2026-10-26', '2028-12-31', 'active', 1, 'डिजिटल मीडिया हेड एवं कंटेंट डिस्ट्रीब्यूशन लीड। ऑडियंस रीच व पब्लिशिंग स्ट्रैटेजिस्ट।', 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200&auto=format&fit=crop&q=80', 'b0IE0ZNw0k5I8toR5a4ZJhq6RgGsyPcufVfgBKYNa31W4X6ZSP0i7q553Jwu', '2026-10-26 03:33:00', '2026-10-27 00:22:18'),
(4, 'अमित शर्मा (विशेष संवाददाता)', 'reporter@newskit.com', '+91 98765 43212', NULL, '$2y$12$2Mkn7udai4KNTP6hFo2fmeXvZ0HaV.LWl9HYgscB9ZmBFpsmUpOnK', 'reporter', 'PRESS-REP-2026-0004', 'न्यूज़ रिपोर्टर (Reporter)', NULL, 'राष्ट्रीय समाचार ब्यूरो (National Bureau)', NULL, '2026-10-26', '2028-12-31', 'active', 1, 'ज़मीनी रिपोर्टर, खेल व सिनेमा जगत की अंदरूनी ख़बरों पर पैनी नज़र।', 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=200&auto=format&fit=crop&q=80', '1JTq0UQj2Hz8NUFyzfcxlrQiMKx6T1BFsSYph1hpFM2OrWnqMKuodjn8lb9Q', '2026-10-26 03:33:01', '2026-10-27 00:22:18'),
(5, 'प्रिया राजदान (टेक व साइंस रिपोर्टर)', 'priya.reporter@newskit.com', '+91 98765 43214', NULL, '$2y$12$MRKTidVaeOlseGxkWJ2seOyqgrecy1r39f6Ly9VtLIv51hUlWkWK6', 'reporter', 'PRESS-REP-2026-0005', 'न्यूज़ रिपोर्टर (Reporter)', NULL, 'राष्ट्रीय समाचार ब्यूरो (National Bureau)', NULL, '2026-10-26', '2028-12-31', 'active', 1, 'AI, स्पेस मिशन (ISRO/NASA) और गैजेट्स पर विशेष कवरेज करने वाली टेक जर्नलिस्ट।', 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=200&auto=format&fit=crop&q=80', 'iYEzIMeQJG7kMF0fljMOll6RjlRXTSyqQ29LryYR85feFE7HY1J3BxIRo9rc', '2026-10-26 03:33:01', '2026-10-27 00:22:18');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `advertisements`
--
ALTER TABLE `advertisements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `articles_slug_unique` (`slug`),
  ADD KEY `articles_category_id_foreign` (`category_id`),
  ADD KEY `articles_user_id_foreign` (`user_id`),
  ADD KEY `articles_state_id_foreign` (`state_id`),
  ADD KEY `articles_city_id_foreign` (`city_id`);

--
-- Indexes for table `article_tag`
--
ALTER TABLE `article_tag`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `article_tag_article_id_tag_id_unique` (`article_id`,`tag_id`),
  ADD KEY `article_tag_tag_id_foreign` (`tag_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cities_slug_unique` (`slug`),
  ADD KEY `cities_state_id_foreign` (`state_id`);

--
-- Indexes for table `citizen_tips`
--
ALTER TABLE `citizen_tips`
  ADD PRIMARY KEY (`id`),
  ADD KEY `citizen_tips_state_id_foreign` (`state_id`),
  ADD KEY `citizen_tips_city_id_foreign` (`city_id`),
  ADD KEY `citizen_tips_converted_article_id_foreign` (`converted_article_id`),
  ADD KEY `citizen_tips_status_index` (`status`),
  ADD KEY `citizen_tips_created_at_index` (`created_at`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `comments_article_id_foreign` (`article_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  ADD KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_unique` (`key`);

--
-- Indexes for table `states`
--
ALTER TABLE `states`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `states_slug_unique` (`slug`);

--
-- Indexes for table `subscribers`
--
ALTER TABLE `subscribers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subscribers_email_unique` (`email`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tags_slug_unique` (`slug`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_press_id_code_unique` (`press_id_code`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `advertisements`
--
ALTER TABLE `advertisements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `articles`
--
ALTER TABLE `articles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `article_tag`
--
ALTER TABLE `article_tag`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `citizen_tips`
--
ALTER TABLE `citizen_tips`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `states`
--
ALTER TABLE `states`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `subscribers`
--
ALTER TABLE `subscribers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `articles_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `articles_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `articles_state_id_foreign` FOREIGN KEY (`state_id`) REFERENCES `states` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `articles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `article_tag`
--
ALTER TABLE `article_tag`
  ADD CONSTRAINT `article_tag_article_id_foreign` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `article_tag_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cities`
--
ALTER TABLE `cities`
  ADD CONSTRAINT `cities_state_id_foreign` FOREIGN KEY (`state_id`) REFERENCES `states` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `citizen_tips`
--
ALTER TABLE `citizen_tips`
  ADD CONSTRAINT `citizen_tips_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `citizen_tips_converted_article_id_foreign` FOREIGN KEY (`converted_article_id`) REFERENCES `articles` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `citizen_tips_state_id_foreign` FOREIGN KEY (`state_id`) REFERENCES `states` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_article_id_foreign` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
