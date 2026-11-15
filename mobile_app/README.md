# NewsKit - Flutter Mobile Application (Android & iOS)

A complete, high-performance Flutter mobile application for **NewsKit Portal**, featuring Material 3 UI, multi-language localization (English, Hindi, Arabic, Urdu with RTL), Audio News Reader (TTS), Hyperlocal District/City news filtering, Video Bulletins, Citizen Journalism reporting, and Offline Bookmarks.

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

To change the primary brand color, open `lib/config/app_theme.dart`:

```dart
class AppTheme {
  static const Color primary = Color(0xFFE11D48); // Change to your brand color
  static const Color accent = Color(0xFFF59E0B);
  ...
}
```

---

## 🔒 License & Support
This source code is licensed under the CodeCanyon Commercial License. For questions and setup support, please contact the author via your CodeCanyon purchase dashboard.
