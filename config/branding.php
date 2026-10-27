<?php

/**
 * --------------------------------------------------------------------------
 * NEWSKIT SINGLE-FILE BRANDING & CONFIGURATION
 * --------------------------------------------------------------------------
 * Yahan se aap pure CMS aur News Portal ki branding, logo, colors,
 * aur names ko ek hi jagah se customize kar sakte hain.
 * 
 * Note: Agar aap Admin Panel (/admin/settings) se change karte hain,
 * toh wo database me save ho jayega. Default values yahan se pick hoti hain.
 */

return [
    // Site Identity
    'site_name' => env('NEWSKIT_SITE_NAME', 'The LallanTop News'),
    'site_tagline' => env('NEWSKIT_TAGLINE', 'बेबाक, सटीक और सबसे तेज़ ख़बरें'),
    'site_short_name' => env('NEWSKIT_SHORT_NAME', 'LT'),
    
    // Logo and Favicon (Relative URL or SVG or external link)
    'logo_url' => env('NEWSKIT_LOGO_URL', '/images/logo.svg'),
    'logo_dark_url' => env('NEWSKIT_LOGO_DARK_URL', '/images/logo.svg'),
    'favicon_url' => env('NEWSKIT_FAVICON_URL', '/images/favicon.svg'),

    // Theme Colors (Used dynamically across portal and CMS)
    'primary_color' => env('NEWSKIT_PRIMARY_COLOR', '#E11D48'), // Bold crimson red (Lallantop vibe)
    'accent_color' => env('NEWSKIT_ACCENT_COLOR', '#F59E0B'),   // Warm vibrant amber/gold
    'dark_bg' => '#0F172A',
    
    // Portal Features
    'breaking_ticker_enabled' => true,
    'breaking_ticker_title' => 'ताज़ा ख़बरें',
    'audio_reader_enabled' => true,
    'comments_enabled' => true,
    'newsletter_enabled' => true,

    // Contact and About
    'contact_email' => 'editor@newskit.in',
    'contact_phone' => '+91 98765 43210',
    'office_address' => 'नई दिल्ली, भारत',
    'footer_about' => 'NewsKit Bharat का सबसे विश्वसनीय और निष्पक्ष डिजिटल न्यूज़ नेटवर्क है। राजनीति, तकनीक, खेल, सिनेमा और दुनिया की हर बड़ी ख़बर सबसे पहले यहाँ।',
    'copyright_text' => '© ' . date('Y') . ' The LallanTop News Network. सर्वाधिकार सुरक्षित।',

    // Social Channels
    'social' => [
        'twitter' => 'https://twitter.com/lallantop',
        'facebook' => 'https://facebook.com/lallantop',
        'youtube' => 'https://youtube.com/@lallantop',
        'instagram' => 'https://instagram.com/lallantop',
        'whatsapp' => 'https://whatsapp.com/channel/lallantop',
    ],

    // SEO Meta Defaults
    'meta_description' => 'देश और दुनिया की ताज़ा ख़बरें, चुनावी विश्लेषण, ग्राउंड रिपोर्ट्स, वायरल वीडियो और मनोरंजन समाचार हिंदी में।',
    'meta_keywords' => 'news, hindi news, lallantop, breaking news, politics, sports, entertainment',
];
