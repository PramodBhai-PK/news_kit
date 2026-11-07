<?php

/**
 * --------------------------------------------------------------------------
 * NEWSKIT SINGLE-FILE BRANDING & CONFIGURATION
 * --------------------------------------------------------------------------
 * Customize your entire News Portal and CMS branding, logo, colors,
 * and names from this single central configuration file.
 * 
 * Note: If values are updated from the Admin Panel (/admin/settings),
 * they will be persisted to the database and override these defaults.
 */

return [
    // Site Identity
    'site_name' => env('NEWSKIT_SITE_NAME', 'NewsKit Portal'),
    'site_tagline' => env('NEWSKIT_TAGLINE', 'Independent, Accurate & Fast Breaking News'),
    'site_short_name' => env('NEWSKIT_SHORT_NAME', 'NK'),
    
    // Logo and Favicon (Relative URL or SVG or external link)
    'logo_url' => env('NEWSKIT_LOGO_URL', '/images/logo.svg'),
    'logo_dark_url' => env('NEWSKIT_LOGO_DARK_URL', '/images/logo.svg'),
    'favicon_url' => env('NEWSKIT_FAVICON_URL', '/images/favicon.svg'),

    // Theme Colors (Used dynamically across portal and CMS)
    'primary_color' => env('NEWSKIT_PRIMARY_COLOR', '#E11D48'), // Bold crimson red
    'accent_color' => env('NEWSKIT_ACCENT_COLOR', '#F59E0B'),   // Warm vibrant amber
    'dark_bg' => '#0F172A',
    
    // Portal Features
    'breaking_ticker_enabled' => true,
    'breaking_ticker_title' => 'Breaking News',
    'audio_reader_enabled' => true,
    'comments_enabled' => true,
    'newsletter_enabled' => true,

    // Contact and About
    'contact_email' => 'editor@newskit.org',
    'contact_phone' => '+1 (555) 019-2834',
    'office_address' => 'New York, United States',
    'footer_about' => 'NewsKit is an independent digital news network delivering verified reporting, breaking news, politics, technology, sports, and investigative journalism.',
    'copyright_text' => '© ' . date('Y') . ' NewsKit Media Network. All rights reserved.',

    // Social Channels
    'social' => [
        'twitter' => 'https://twitter.com/newskit',
        'facebook' => 'https://facebook.com/newskit',
        'youtube' => 'https://youtube.com/@newskit',
        'instagram' => 'https://instagram.com/newskit',
        'whatsapp' => 'https://whatsapp.com/channel/newskit',
    ],

    // SEO Meta Defaults
    'meta_description' => 'Latest breaking news, in-depth analysis, world events, technology, business, and investigative journalism from NewsKit.',
    'meta_keywords' => 'news, breaking news, journalism, politics, technology, business, sports, world news',
];
