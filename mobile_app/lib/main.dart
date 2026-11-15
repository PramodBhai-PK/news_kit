import 'package:flutter/material.dart';
import 'package:flutter_localizations/flutter_localizations.dart';
import 'package:provider/provider.dart';
import 'config/app_config.dart';
import 'config/app_theme.dart';
import 'providers/app_provider.dart';
import 'providers/auth_provider.dart';
import 'providers/bookmark_provider.dart';
import 'providers/news_provider.dart';
import 'screens/splash_screen.dart';
import 'services/tts_service.dart';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();

  // Initialize Speech Synthesis for Audio News Reader
  await TtsService().init();

  runApp(const NewsKitApp());
}

class NewsKitApp extends StatelessWidget {
  const NewsKitApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MultiProvider(
      providers: [
        ChangeNotifierProvider(create: (_) => AppProvider()),
        ChangeNotifierProxyProvider<AppProvider, NewsProvider>(
          create: (ctx) => NewsProvider(apiService: ctx.read<AppProvider>().apiService),
          update: (ctx, app, previous) {
            if (previous != null) {
              previous.updateApiService(app.apiService);
              return previous;
            }
            return NewsProvider(apiService: app.apiService);
          },
        ),
        ChangeNotifierProxyProvider<AppProvider, AuthProvider>(
          create: (ctx) => AuthProvider(apiService: ctx.read<AppProvider>().apiService),
          update: (ctx, app, previous) {
            if (previous != null) {
              previous.updateApiService(app.apiService);
              return previous;
            }
            return AuthProvider(apiService: app.apiService);
          },
        ),
        ChangeNotifierProvider(create: (_) => BookmarkProvider()),
      ],
      child: Consumer<AppProvider>(
        builder: (context, appProvider, child) {
          return MaterialApp(
            title: AppConfig.appName,
            debugShowCheckedModeBanner: false,
            theme: AppTheme.lightTheme,
            darkTheme: AppTheme.darkTheme,
            themeMode: appProvider.isDarkMode ? ThemeMode.dark : ThemeMode.light,
            locale: Locale(appProvider.currentLocale),
            supportedLocales: const [
              Locale('en', 'US'),
              Locale('hi', 'IN'),
              Locale('ur', 'PK'),
              Locale('ar', 'AE'),
            ],
            localizationsDelegates: const [
              GlobalMaterialLocalizations.delegate,
              GlobalWidgetsLocalizations.delegate,
              GlobalCupertinoLocalizations.delegate,
            ],
            home: const SplashScreen(),
          );
        },
      ),
    );
  }
}

