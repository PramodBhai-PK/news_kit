import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:url_launcher/url_launcher.dart';
import '../config/app_config.dart';
import '../providers/app_provider.dart';

class WhatsAppChannelScreen extends StatelessWidget {
  const WhatsAppChannelScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final config = Provider.of<AppProvider>(context).config;
    final channelUrl = config?.whatsAppChannelUrl ?? AppConfig.defaultWhatsAppChannel;
    final followers = config?.whatsAppFollowers ?? '250,000+';
    final qrUrl = config?.whatsAppQrUrl;

    return Scaffold(
      appBar: AppBar(
        title: const Text('WhatsApp Channel', style: TextStyle(fontWeight: FontWeight.w900)),
      ),
      body: Center(
        child: SingleChildScrollView(
          padding: const EdgeInsets.all(24),
          child: Container(
            padding: const EdgeInsets.all(24),
            decoration: BoxDecoration(
              color: const Color(0xFF0F172A), // Dark slate
              borderRadius: BorderRadius.circular(24),
              border: Border.all(color: const Color(0xFF25D366).withOpacity(0.3)),
              boxShadow: [
                BoxShadow(
                  color: Colors.black.withOpacity(0.2),
                  blurRadius: 20,
                  offset: const Offset(0, 8),
                ),
              ],
            ),
            child: Column(
              mainAxisSize: MainAxisSize.min,
              children: [
                // WhatsApp Icon Badge
                Container(
                  width: 64,
                  height: 64,
                  decoration: const BoxDecoration(
                    color: Color(0xFF25D366),
                    shape: BoxShape.circle,
                  ),
                  child: const Icon(Icons.chat_bubble, color: Colors.white, size: 36),
                ),
                const SizedBox(height: 16),
                const Text(
                  'Join Official WhatsApp Channel',
                  textAlign: TextAlign.center,
                  style: TextStyle(
                    color: Colors.white,
                    fontSize: 20,
                    fontWeight: FontWeight.w900,
                  ),
                ),
                const SizedBox(height: 6),
                Text(
                  'Get 24/7 breaking news, viral bulletins & alerts directly on WhatsApp.',
                  textAlign: TextAlign.center,
                  style: TextStyle(color: Colors.grey.shade400, fontSize: 13, height: 1.4),
                ),
                const SizedBox(height: 20),

                // Followers Counter
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                  decoration: BoxDecoration(
                    color: const Color(0xFF25D366).withOpacity(0.15),
                    borderRadius: BorderRadius.circular(20),
                    border: Border.all(color: const Color(0xFF25D366).withOpacity(0.3)),
                  ),
                  child: Row(
                    mainAxisSize: MainAxisSize.min,
                    children: [
                      const Icon(Icons.verified, color: Color(0xFF25D366), size: 16),
                      const SizedBox(width: 6),
                      Text(
                        '$followers Active Subscribers',
                        style: const TextStyle(
                          color: Color(0xFF25D366),
                          fontWeight: FontWeight.w800,
                          fontSize: 12,
                        ),
                      ),
                    ],
                  ),
                ),
                const SizedBox(height: 24),

                // QR Code
                if (qrUrl != null && qrUrl.isNotEmpty)
                  Container(
                    padding: const EdgeInsets.all(12),
                    decoration: BoxDecoration(
                      color: Colors.white,
                      borderRadius: BorderRadius.circular(16),
                    ),
                    child: CachedNetworkImage(
                      imageUrl: qrUrl,
                      width: 140,
                      height: 140,
                      fit: BoxFit.contain,
                    ),
                  )
                else
                  Container(
                    width: 140,
                    height: 140,
                    padding: const EdgeInsets.all(16),
                    decoration: BoxDecoration(
                      color: Colors.white,
                      borderRadius: BorderRadius.circular(16),
                    ),
                    child: const Icon(Icons.qr_code_2, size: 100, color: Color(0xFF0F172A)),
                  ),
                const SizedBox(height: 24),

                // Action Button
                SizedBox(
                  width: double.infinity,
                  height: 50,
                  child: ElevatedButton.icon(
                    style: ElevatedButton.styleFrom(
                      backgroundColor: const Color(0xFF25D366),
                      foregroundColor: Colors.white,
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(14)),
                    ),
                    icon: const Icon(Icons.open_in_new, size: 18),
                    label: const Text(
                      'JOIN CHANNEL NOW',
                      style: TextStyle(fontWeight: FontWeight.w900, letterSpacing: 0.5),
                    ),
                    onPressed: () {
                      launchUrl(Uri.parse(channelUrl), mode: LaunchMode.externalApplication);
                    },
                  ),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
