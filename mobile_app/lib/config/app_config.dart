/// =========================================================================
/// NEWSKIT FLUTTER APP CONFIGURATION
/// =========================================================================
/// Buyers can easily customize their API endpoint, app name, branding,
/// and monetization keys from this single configuration file.
class AppConfig {
  /// Base URL of your NewsKit Laravel Portal API
  /// For Localhost Testing on Android Emulator use: http://10.0.2.2:8000/api/v1
  /// For Real Device / Production use: https://yourdomain.com/api/v1
  static const String apiBaseUrl = 'https://news-portal.example.com/api/v1';

  /// Application Name & Brand Tagline
  static const String appName = 'NewsKit Portal';
  static const String appTagline = 'Independent, Accurate & Fast Breaking News';
  static const String appVersion = '1.0.0';

  /// Default App Language ('en', 'hi', 'ur', 'ar')
  static const String defaultLocale = 'en';

  /// Feature Toggles
  static const bool enableBreakingNewsTicker = true;
  static const bool enableAudioTtsReader = true;
  static const bool enableHyperlocalNews = true;
  static const bool enableCitizenJournalism = true;
  static const bool enableBookmarks = true;
  static const bool enableDarkMode = true;

  /// WhatsApp Community / Channel Link
  static const String defaultWhatsAppChannel = 'https://whatsapp.com/channel/newskit';

  /// Google AdMob Configuration (Enter your AdMob Unit IDs)
  static const bool enableAdMob = false;
  static const String adMobBannerAndroid = 'ca-app-pub-3940256099942544/6300978111'; // Test ID
  static const String adMobBannerIos = 'ca-app-pub-3940256099942544/2934735716'; // Test ID
  static const String adMobInterstitialAndroid = 'ca-app-pub-3940256099942544/1033173712'; // Test ID
  static const String adMobInterstitialIos = 'ca-app-pub-3940256099942544/4411468910'; // Test ID

  /// Push Notifications (OneSignal App ID)
  static const String oneSignalAppId = 'YOUR_ONESIGNAL_APP_ID_HERE';
}
