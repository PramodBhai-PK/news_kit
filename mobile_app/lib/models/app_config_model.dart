import 'article_model.dart';

class AppConfigModel {
  final String appName;
  final String appTagline;
  final String shortName;
  final String logoUrl;
  final String faviconUrl;
  final String primaryColor;
  final String accentColor;
  final bool enableBreakingTicker;
  final bool enableAudioReader;
  final bool enableComments;
  final bool enableNewsletter;
  final bool enableHyperlocal;
  final bool enableCitizenJournalism;
  final String whatsAppChannelUrl;
  final String whatsAppFollowers;
  final String? whatsAppQrUrl;
  final Map<String, String> socialLinks;
  final String defaultLocale;
  final List<String> rtlLocales;
  final bool enableAdMob;

  AppConfigModel({
    required this.appName,
    required this.appTagline,
    required this.shortName,
    required this.logoUrl,
    required this.faviconUrl,
    required this.primaryColor,
    required this.accentColor,
    this.enableBreakingTicker = true,
    this.enableAudioReader = true,
    this.enableComments = true,
    this.enableNewsletter = true,
    this.enableHyperlocal = true,
    this.enableCitizenJournalism = true,
    this.whatsAppChannelUrl = '',
    this.whatsAppFollowers = '250,000+',
    this.whatsAppQrUrl,
    this.socialLinks = const {},
    this.defaultLocale = 'en',
    this.rtlLocales = const ['ar', 'ur'],
    this.enableAdMob = false,
  });

  factory AppConfigModel.fromJson(Map<String, dynamic> json) {
    final theme = json['theme'] ?? {};
    final features = json['features'] ?? {};
    final wa = json['whatsapp_channel'] ?? {};
    final social = json['social_links'] ?? {};
    final loc = json['localization'] ?? {};
    final monet = json['monetization'] ?? {};

    return AppConfigModel(
      appName: json['app_name'] ?? 'NewsKit Portal',
      appTagline: json['app_tagline'] ?? '',
      shortName: json['short_name'] ?? 'NK',
      logoUrl: ArticleModel.sanitizeUrl(json['logo_url']) ?? '',
      faviconUrl: ArticleModel.sanitizeUrl(json['favicon_url']) ?? '',
      primaryColor: theme['primary_color'] ?? '#E11D48',
      accentColor: theme['accent_color'] ?? '#F59E0B',
      enableBreakingTicker: features['breaking_ticker'] ?? true,
      enableAudioReader: features['audio_reader'] ?? true,
      enableComments: features['comments'] ?? true,
      enableNewsletter: features['newsletter'] ?? true,
      enableHyperlocal: features['hyperlocal'] ?? true,
      enableCitizenJournalism: features['citizen_journalism'] ?? true,
      whatsAppChannelUrl: wa['channel_url'] ?? '',
      whatsAppFollowers: wa['followers_count'] ?? '250,000+',
      whatsAppQrUrl: ArticleModel.sanitizeUrl(wa['qr_code_url']),
      socialLinks: Map<String, String>.from(social),
      defaultLocale: loc['default_locale'] ?? 'en',
      rtlLocales: (loc['rtl_locales'] as List<dynamic>?)?.map((e) => e.toString()).toList() ?? ['ar', 'ur'],
      enableAdMob: monet['admob_enabled'] ?? false,
    );
  }
}

