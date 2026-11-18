import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:url_launcher/url_launcher.dart';
import '../config/app_config.dart';
import '../providers/app_provider.dart';
import '../providers/auth_provider.dart';
import 'bookmarks_screen.dart';
import 'login_screen.dart';
import 'whatsapp_channel_screen.dart';

class ProfileScreen extends StatelessWidget {
  const ProfileScreen({super.key});

  void _showLanguageDialog(BuildContext context) {
    final appProvider = Provider.of<AppProvider>(context, listen: false);

    showDialog(
      context: context,
      builder: (_) => AlertDialog(
        title: const Text('Select Language', style: TextStyle(fontWeight: FontWeight.w900)),
        content: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            ListTile(
              title: const Text('English (Default)'),
              trailing: appProvider.currentLocale == 'en' ? const Icon(Icons.check, color: AppConfig.primaryColor) : null,
              onTap: () {
                appProvider.setLocale('en');
                Navigator.pop(context);
              },
            ),
            ListTile(
              title: const Text('Hindi'),
              trailing: appProvider.currentLocale == 'hi' ? const Icon(Icons.check, color: AppConfig.primaryColor) : null,
              onTap: () {
                appProvider.setLocale('hi');
                Navigator.pop(context);
              },
            ),
            ListTile(
              title: const Text('Urdu (RTL)'),
              trailing: appProvider.currentLocale == 'ur' ? const Icon(Icons.check, color: AppConfig.primaryColor) : null,
              onTap: () {
                appProvider.setLocale('ur');
                Navigator.pop(context);
              },
            ),
            ListTile(
              title: const Text('Arabic (RTL)'),
              trailing: appProvider.currentLocale == 'ar' ? const Icon(Icons.check, color: AppConfig.primaryColor) : null,
              onTap: () {
                appProvider.setLocale('ar');
                Navigator.pop(context);
              },
            ),
          ],
        ),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    final authProvider = Provider.of<AuthProvider>(context);
    final appProvider = Provider.of<AppProvider>(context);
    final isDark = Theme.of(context).brightness == Brightness.dark;
    final user = authProvider.currentUser;

    return Scaffold(
      appBar: AppBar(
        title: const Text('Settings & Profile', style: TextStyle(fontWeight: FontWeight.w900)),
      ),
      body: ListView(
        padding: const EdgeInsets.all(16),
        children: [
          // 1. User Header or Login Card
          if (authProvider.isLoggedIn && user != null) ...[
            Container(
              padding: const EdgeInsets.all(20),
              decoration: BoxDecoration(
                color: isDark ? const Color(0xFF1E293B) : Colors.white,
                borderRadius: BorderRadius.circular(20),
                border: Border.all(color: isDark ? const Color(0xFF334155) : const Color(0xFFE2E8F0)),
              ),
              child: Row(
                children: [
                  CircleAvatar(
                    radius: 30,
                    backgroundColor: AppConfig.primaryColor,
                    child: Text(
                      user.name.substring(0, 1).toUpperCase(),
                      style: const TextStyle(fontSize: 24, color: Colors.white, fontWeight: FontWeight.w900),
                    ),
                  ),
                  const SizedBox(width: 16),
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(user.name, style: const TextStyle(fontSize: 18, fontWeight: FontWeight.w800)),
                        const SizedBox(height: 2),
                        Text(user.email, style: const TextStyle(color: Colors.grey, fontSize: 12)),
                        const SizedBox(height: 6),
                        Container(
                          padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
                          decoration: BoxDecoration(
                            color: AppConfig.primaryColor.withOpacity(0.12),
                            borderRadius: BorderRadius.circular(6),
                          ),
                          child: Text(
                            user.role.toUpperCase(),
                            style: const TextStyle(
                              color: AppConfig.primaryColor,
                              fontSize: 10,
                              fontWeight: FontWeight.w800,
                            ),
                          ),
                        ),
                      ],
                    ),
                  ),
                ],
              ),
            ),
            // Digital Press Card if reporter
            if (user.pressCard != null) ...[
              const SizedBox(height: 16),
              Container(
                padding: const EdgeInsets.all(16),
                decoration: BoxDecoration(
                  gradient: const LinearGradient(
                    colors: [Color(0xFF0F172A), Color(0xFF1E1B4B)],
                  ),
                  borderRadius: BorderRadius.circular(20),
                  border: Border.all(color: const Color(0xFF6366F1).withOpacity(0.4)),
                ),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Row(
                      children: const [
                        Icon(Icons.badge, color: Color(0xFFF59E0B)),
                        SizedBox(width: 8),
                        Text(
                          'DIGITAL PRESS CARD',
                          style: TextStyle(color: Colors.white, fontWeight: FontWeight.w900, fontSize: 13),
                        ),
                      ],
                    ),
                    const SizedBox(height: 12),
                    Text(
                      'Press ID: ${user.pressCard!.code}',
                      style: const TextStyle(color: Colors.white, fontWeight: FontWeight.w700, fontSize: 14),
                    ),
                    if (user.pressCard!.designation != null)
                      Text(
                        'Designation: ${user.pressCard!.designation}',
                        style: const TextStyle(color: Colors.white70, fontSize: 12),
                      ),
                    const SizedBox(height: 12),
                    if (user.pressCard!.verifyUrl != null)
                      OutlinedButton.icon(
                        style: OutlinedButton.styleFrom(
                          foregroundColor: Colors.white,
                          side: const BorderSide(color: Colors.white70),
                        ),
                        icon: const Icon(Icons.verified, size: 16, color: Color(0xFF10B981)),
                        label: const Text('Verify Press Identity (Live URL)'),
                        onPressed: () {
                          launchUrl(Uri.parse(user.pressCard!.verifyUrl!));
                        },
                      ),
                  ],
                ),
              ),
            ],
          ] else ...[
            Container(
              padding: const EdgeInsets.all(20),
              decoration: BoxDecoration(
                color: isDark ? const Color(0xFF1E293B) : Colors.white,
                borderRadius: BorderRadius.circular(20),
                border: Border.all(color: isDark ? const Color(0xFF334155) : const Color(0xFFE2E8F0)),
              ),
              child: Column(
                children: [
                  const Icon(Icons.account_circle, size: 54, color: AppConfig.primaryColor),
                  const SizedBox(height: 12),
                  Text('Login to ${AppConfig.appName}', style: const TextStyle(fontSize: 16, fontWeight: FontWeight.w800)),
                  const SizedBox(height: 6),
                  const Text(
                    'Sync your reading preferences, comments, and journalist credentials across all devices.',
                    textAlign: TextAlign.center,
                    style: TextStyle(color: Colors.grey, fontSize: 12),
                  ),
                  const SizedBox(height: 16),
                  SizedBox(
                    width: double.infinity,
                    child: ElevatedButton(
                      style: ElevatedButton.styleFrom(
                        backgroundColor: AppConfig.primaryColor,
                        foregroundColor: Colors.white,
                        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                      ),
                      onPressed: () {
                        Navigator.push(context, MaterialPageRoute(builder: (_) => const LoginScreen()));
                      },
                      child: const Text('LOG IN / REGISTER', style: TextStyle(fontWeight: FontWeight.w800)),
                    ),
                  ),
                ],
              ),
            ),
          ],
          const SizedBox(height: 24),

          // 2. Preferences
          const Padding(
            padding: EdgeInsets.symmetric(horizontal: 8, vertical: 4),
            child: Text('PREFERENCES', style: TextStyle(fontSize: 12, fontWeight: FontWeight.w800, color: Colors.grey)),
          ),
          Card(
            child: Column(
              children: [
                SwitchListTile(
                  secondary: const Icon(Icons.dark_mode_outlined),
                  title: const Text('Dark Mode', style: TextStyle(fontWeight: FontWeight.w700)),
                  value: appProvider.isDarkMode,
                  onChanged: (val) => appProvider.toggleDarkMode(),
                ),
                const Divider(height: 1),
                ListTile(
                  leading: const Icon(Icons.bookmark_border),
                  title: const Text('Saved Bookmarks', style: TextStyle(fontWeight: FontWeight.w700)),
                  subtitle: const Text('Offline saved news articles'),
                  trailing: const Icon(Icons.chevron_right),
                  onTap: () {
                    Navigator.push(context, MaterialPageRoute(builder: (_) => const BookmarksScreen()));
                  },
                ),
                const Divider(height: 1),
                ListTile(
                  leading: const Icon(Icons.language),
                  title: const Text('Language', style: TextStyle(fontWeight: FontWeight.w700)),
                  subtitle: Text(appProvider.currentLocale.toUpperCase()),
                  trailing: const Icon(Icons.chevron_right),
                  onTap: () => _showLanguageDialog(context),
                ),
              ],
            ),
          ),
          const SizedBox(height: 16),

          // 3. Connect & Channels
          const Padding(
            padding: EdgeInsets.symmetric(horizontal: 8, vertical: 4),
            child: Text('COMMUNITY', style: TextStyle(fontSize: 12, fontWeight: FontWeight.w800, color: Colors.grey)),
          ),
          Card(
            child: Column(
              children: [
                ListTile(
                  leading: const Icon(Icons.chat_bubble_outline, color: Color(0xFF25D366)),
                  title: const Text('WhatsApp Channel', style: TextStyle(fontWeight: FontWeight.w700)),
                  subtitle: const Text('Get 24/7 breaking alerts'),
                  trailing: const Icon(Icons.chevron_right),
                  onTap: () {
                    Navigator.push(context, MaterialPageRoute(builder: (_) => const WhatsAppChannelScreen()));
                  },
                ),
              ],
            ),
          ),
          const SizedBox(height: 16),

          // 4. Logout (if logged in)
          if (authProvider.isLoggedIn)
            Card(
              child: ListTile(
                leading: const Icon(Icons.logout, color: Colors.red),
                title: const Text('Log Out', style: TextStyle(color: Colors.red, fontWeight: FontWeight.w700)),
                onTap: () => authProvider.logout(),
              ),
            ),
          const SizedBox(height: 32),

          // 5. App Footer
          Center(
            child: Text(
              '${AppConfig.appName} • Version ${AppConfig.appVersion}\nCommercial Edition',
              textAlign: TextAlign.center,
              style: const TextStyle(fontSize: 11, color: Colors.grey, height: 1.5),
            ),
          ),
          const SizedBox(height: 40),
        ],
      ),
    );
  }
}
