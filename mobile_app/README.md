# NewsKit - Flutter Mobile Application (Android & iOS)

A complete, high-performance Flutter mobile application for **NewsKit Portal**, featuring Material 3 UI, multi-language localization (English, Hindi, Arabic, Urdu with RTL), Audio News Reader (TTS), Hyperlocal District/City news filtering, Video Bulletins, Citizen Journalism reporting, and Offline Bookmarks.

---

## 🚀 60-Second White-Label Rebranding Guide

You can completely rebrand, recolor, and configure this entire mobile app by editing **one single file**:
📁 `lib/config/app_config.dart`

**No need to search through dozens of UI or screen files!** Everything is centralized into `AppConfig`:

```dart
class AppConfig {
  // 1. Brand Identity & Names
  static const String appName = 'MyNews Express';       // Your Brand Name
  static const String appShortName = 'MNE';             // Logo 2-3 letter initials
  static const String appTagline = 'Fastest News Live';  // Tagline
  static const String supportEmail = 'contact@domain.com';

  // 2. Connect Your Backend API
  static const String apiBaseUrl = 'https://your-domain.com/api/v1';

  // 3. Theme & Colors (Instant Color Palette Change)
  static const Color primaryColor = Color(0xFF1E40AF);   // Change to Royal Blue, Emerald, Crimson, etc.
  static const Color accentColor = Color(0xFFF59E0B);

  // 4. Feature Toggles (Turn Features ON/OFF)
  static const bool enableBreakingNewsTicker = true;
  static const bool enableAudioTtsReader     = true;
  static const bool enableHyperlocalNews     = true;
  static const bool enableCitizenJournalism  = true;
  static const bool enableBookmarks          = true;
  static const bool enableDarkMode           = true;

  // 5. AdMob Monetization & Push Notifications
  static const bool enableAdMob              = false;   // Set true to show ads
  static const String adMobBannerAndroid     = 'ca-app-pub-xxx/yyy';
  static const String oneSignalAppId         = 'YOUR_ONESIGNAL_APP_ID';
}
```

---

## 📱 Features

- **Multi-Language & RTL:** Native English, Hindi, Arabic, and Urdu with automatic Right-to-Left (RTL) layout.
- **Audio News Reader (TTS):** 1-tap Text-To-Speech engine allowing readers to listen to full articles on the go.
- **Hyperlocal City Filter:** Readers can select their district/city to get localized municipal updates and ground reports.
- **Breaking News Live Ticker:** Animated headline ticker with direct story navigation.
- **Digital Video Desk:** Integrated YouTube player and direct MP4 video player for video bulletins.
- **Offline Bookmarks:** Save articles locally to read without internet connection.
- **Citizen Journalism Desk:** Public reporting form with live camera/gallery photo upload and anonymous toggle.
- **WhatsApp Channel Card:** Built-in community widget with QR code and direct join button.
- **Dark Mode:** Seamless Light & Dark theme switching.
- **Google AdMob:** Ready slots for Banner and Interstitial monetization.
- **User Profile & Press Card:** Reporter/Editor credentials with live QR verification.

---

## 🛠️ Prerequisites

- **Flutter SDK:** Version `3.2.0` or higher ([Install Flutter](https://flutter.dev/docs/get-started/install))
- **Dart SDK:** Version `3.2.0` or higher
- **Android Studio** (for Android build) / **Xcode** (for iOS build)

---

## ⚙️ Step-by-Step Setup Guide

### 1. Connect to your NewsKit Laravel Backend
Open `lib/config/app_config.dart` and set your API base URL:

```dart
class AppConfig {
  // For Localhost Emulator testing:
  // static const String apiBaseUrl = 'http://10.0.2.2:8000/api/v1';

  // For Production / Staging:
  static const String apiBaseUrl = 'https://your-domain.com/api/v1';
  
  static const String appName = 'NewsKit Portal';
  ...
}
```

### 2. Install Dependencies
In your terminal, navigate to the `mobile_app` directory:

```bash
cd mobile_app
flutter pub get
```

### 3. Run the App in Debug Mode
Ensure your Android Emulator, iOS Simulator, or physical device is connected, then run:

```bash
flutter run
```

---

## 📦 Building for Production / App Stores

### Android Release Build (Google Play Store)

1. Generate an Android App Bundle (`.aab`):
```bash
flutter build appbundle --release
```
Your compiled app bundle will be located at:
`build/app/outputs/bundle/release/app-release.aab`

2. Generate a Standalone APK (`.apk`):
```bash
flutter build apk --release
```
Your compiled APK will be located at:
`build/app/outputs/flutter-apk/app-release.apk`

---

### iOS Release Build (Apple App Store)

```bash
flutter build ipa --release
```
Open `ios/Runner.xcworkspace` in **Xcode** to configure signing, provisioning profiles, and upload to App Store Connect.

---

## 🎨 Customizing Brand & Colors

To change the primary brand color, open `lib/config/app_config.dart` and update:

```dart
class AppConfig {
  static const Color primaryColor = Color(0xFFE11D48); // Change to your brand hex color
  static const Color accentColor  = Color(0xFFF59E0B);
  ...
}
```
All UI buttons, AppBars, active tabs, breaking tickers, and badges automatically inherit this color globally.

---

## 🔒 License & Support
This source code is licensed under the CodeCanyon Commercial License. For questions and setup support, please contact the author via your CodeCanyon purchase dashboard.

