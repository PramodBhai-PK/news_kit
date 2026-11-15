import 'package:flutter/material.dart';

/// =========================================================================
/// NEWSKIT WHITE-LABEL CONFIGURATION HUB (SINGLE-FILE BRANDING)
/// =========================================================================
/// You can completely rebrand, recolor, and configure this entire mobile app
/// by editing this single file. No need to touch any UI or screen files!
/// =========================================================================
class AppConfig {
  // -----------------------------------------------------------------------
  // 1. BRAND IDENTITY & TEXTS
  // -----------------------------------------------------------------------
  /// Full name of your news portal / organization
  static const String appName = 'NewsKit Portal';

  /// Short 2-3 letter initials for logo icon (e.g., 'NK', 'CNN', 'BBC', 'TOI')
  static const String appShortName = 'NK';

  /// Tagline displayed on splash and about screens
  static const String appTagline = 'Independent, Accurate & Fast Breaking News';

  /// App Version displayed in profile/settings
  static const String appVersion = '1.0.0';

  /// Support / Contact Email
  static const String supportEmail = 'editor@newskit.org';

  // -----------------------------------------------------------------------
  // 2. BACKEND API CONNECTION
  // -----------------------------------------------------------------------
  /// Base URL of your NewsKit Laravel Portal API
  /// - For Real Device Testing on same Wi-Fi: 'http://192.168.31.254:8000/api/v1'
  /// - For Localhost Emulator Testing: 'http://10.0.2.2:8000/api/v1'
  /// - For Production / Live Domain: 'https://your-domain.com/api/v1'
  static const String apiBaseUrl = 'http://192.168.31.254:8000/api/v1';

  // -----------------------------------------------------------------------
  // 3. BRAND THEME & COLORS
  // -----------------------------------------------------------------------
  /// Primary brand color (Used across AppBar, buttons, badges, breaking ticker)
  static const Color primaryColor = Color(0xFFE11D48); // NewsKit Crimson Red

  /// Accent vibrant color (Used for highlights, ratings, live icons)
  static const Color accentColor = Color(0xFFF59E0B);  // Warm Amber

  /// Success color (Used for verified badges)
  static const Color successColor = Color(0xFF10B981);

  /// Dark mode background slate
  static const Color darkBgColor = Color(0xFF0F172A);

  /// Dark mode card and surface slate
  static const Color darkSurfaceColor = Color(0xFF1E293B);

  /// Light mode background
  static const Color lightBgColor = Color(0xFFF8FAFC);

  /// Light mode card surface
  static const Color lightSurfaceColor = Colors.white;

  // -----------------------------------------------------------------------
  // 4. LOCALIZATION & LANGUAGES
  // -----------------------------------------------------------------------
  /// Default Language code ('en' for English, 'hi' for Hindi, 'ur' for Urdu, 'ar' for Arabic)
  static const String defaultLocale = 'en';

  // -----------------------------------------------------------------------
  // 5. SOCIAL & COMMUNITY LINKS
  // -----------------------------------------------------------------------
  /// WhatsApp Channel / Community URL
  static const String defaultWhatsAppChannel = 'https://whatsapp.com/channel/newskit';

  // -----------------------------------------------------------------------
  // 6. FEATURE TOGGLES (Turn features ON or OFF with True/False)
  // -----------------------------------------------------------------------
  /// Enable/Disable Breaking News Live Ticker
  static const bool enableBreakingNewsTicker = true;

  /// Enable/Disable Audio News Reader (Text-To-Speech)
  static const bool enableAudioTtsReader = true;

  /// Enable/Disable Hyperlocal District & City News Filter
  static const bool enableHyperlocalNews = true;

  /// Enable/Disable Citizen Journalism (Eyewitness tip reporting)
  static const bool enableCitizenJournalism = true;

  /// Enable/Disable Offline Bookmarks
  static const bool enableBookmarks = true;

  /// Enable/Disable Dark Mode option
  static const bool enableDarkMode = true;

  // -----------------------------------------------------------------------
  // 7. GOOGLE ADMOB MONETIZATION
  // -----------------------------------------------------------------------
  /// Set to true to show real Google AdMob ads
  static const bool enableAdMob = false;

  /// Google AdMob Banner Ad Unit IDs
  static const String adMobBannerAndroid = 'ca-app-pub-3940256099942544/6300978111'; // Google Test ID
  static const String adMobBannerIos = 'ca-app-pub-3940256099942544/2934735716';     // Google Test ID

  /// Google AdMob Interstitial Ad Unit IDs
  static const String adMobInterstitialAndroid = 'ca-app-pub-3940256099942544/1033173712'; // Google Test ID
  static const String adMobInterstitialIos = 'ca-app-pub-3940256099942544/4411468910';     // Google Test ID

  // -----------------------------------------------------------------------
  // 8. PUSH NOTIFICATIONS
  // -----------------------------------------------------------------------
  /// OneSignal App ID for Breaking News push notifications
  static const String oneSignalAppId = 'YOUR_ONESIGNAL_APP_ID_HERE';
}
