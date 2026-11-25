import 'dart:typed_data';
import 'dart:ui' as ui;
import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/material.dart';
import 'package:flutter/rendering.dart';
import 'package:flutter/services.dart';
import 'package:share_plus/share_plus.dart';
import '../config/app_config.dart';
import '../models/article_model.dart';

class StoryPosterDialog extends StatefulWidget {
  final ArticleModel article;

  const StoryPosterDialog({super.key, required this.article});

  static Future<void> show(BuildContext context, ArticleModel article) {
    return showDialog(
      context: context,
      barrierDismissible: true,
      builder: (_) => StoryPosterDialog(article: article),
    );
  }

  @override
  State<StoryPosterDialog> createState() => _StoryPosterDialogState();
}

class _StoryPosterDialogState extends State<StoryPosterDialog> {
  final GlobalKey _posterKey = GlobalKey();
  bool _isGenerating = false;

  String _getArticleUrl() {
    if (widget.article.shareUrl != null && widget.article.shareUrl!.isNotEmpty) {
      return widget.article.shareUrl!;
    }
    final base = AppConfig.apiBaseUrl.replaceAll('/api/v1', '').replaceAll('/api', '');
    return '$base/article/${widget.article.slug}';
  }

  String _getDisplayUrl() {
    final url = _getArticleUrl();
    try {
      final uri = Uri.parse(url);
      final host = uri.host.replaceFirst('www.', '');
      final path = uri.path;
      final display = '$host$path';
      return display.length > 34 ? '${display.substring(0, 31)}...' : display;
    } catch (_) {
      return url.length > 34 ? '${url.substring(0, 31)}...' : url;
    }
  }

  Future<void> _sharePoster() async {
    setState(() => _isGenerating = true);

    try {
      await Future.delayed(const Duration(milliseconds: 100));

      final boundary = _posterKey.currentContext?.findRenderObject() as RenderRepaintBoundary?;
      if (boundary == null) {
        setState(() => _isGenerating = false);
        return;
      }

      final image = await boundary.toImage(pixelRatio: 2.5);
      final byteData = await image.toByteData(format: ui.ImageByteFormat.png);

      if (byteData != null) {
        final Uint8List pngBytes = byteData.buffer.asUint8List();
        final xFile = XFile.fromData(
          pngBytes,
          mimeType: 'image/png',
          name: '${widget.article.slug}_poster.png',
        );

        final articleUrl = _getArticleUrl();
        final shareText = '🗞️ *${widget.article.title}*\n\n'
            '${widget.article.excerpt != null && widget.article.excerpt!.isNotEmpty ? '${widget.article.excerpt}\n\n' : ''}'
            '👉 *Read the full detailed report here:*\n$articleUrl\n\n'
            '📲 Stay updated with ${AppConfig.appName}';

        await Share.shareXFiles(
          [xFile],
          text: shareText,
        );
      }
    } catch (e) {
      debugPrint('Error sharing poster: $e');
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Could not generate poster: $e')),
        );
      }
    } finally {
      if (mounted) setState(() => _isGenerating = false);
    }
  }

  Future<void> _copyArticleLink() async {
    final articleUrl = _getArticleUrl();
    await Clipboard.setData(ClipboardData(text: articleUrl));
    if (mounted) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('🔗 Article link copied to clipboard!'),
          duration: Duration(seconds: 2),
          backgroundColor: Color(0xFF10B981),
        ),
      );
    }
  }

  Future<void> _shareLinkOnly() async {
    final articleUrl = _getArticleUrl();
    final shareText = '🗞️ *${widget.article.title}*\n\n'
        '👉 Read the full story here:\n$articleUrl\n\n'
        'via ${AppConfig.appName}';
    await Share.share(shareText, subject: widget.article.title);
  }

  @override
  Widget build(BuildContext context) {
    final article = widget.article;

    return Dialog(
      backgroundColor: Colors.transparent,
      insetPadding: const EdgeInsets.symmetric(horizontal: 20, vertical: 24),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          // Close button at top right
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Row(
                children: const [
                  Icon(Icons.auto_awesome, color: Colors.amber, size: 18),
                  SizedBox(width: 6),
                  Text(
                    'WhatsApp / Story Poster',
                    style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 14),
                  ),
                ],
              ),
              IconButton(
                icon: const Icon(Icons.close, color: Colors.white70),
                onPressed: () => Navigator.pop(context),
              ),
            ],
          ),

          const SizedBox(height: 8),

          // Scaled Poster Container
          Flexible(
            child: SingleChildScrollView(
              child: Center(
                child: RepaintBoundary(
                  key: _posterKey,
                  child: Container(
                    width: 320,
                    decoration: BoxDecoration(
                      color: const Color(0xFF0F172A), // Dark slate
                      borderRadius: BorderRadius.circular(20),
                      border: Border.all(color: const Color(0xFF334155), width: 1.5),
                      boxShadow: [
                        BoxShadow(
                          color: Colors.black.withOpacity(0.5),
                          blurRadius: 24,
                          offset: const Offset(0, 8),
                        ),
                      ],
                    ),
                    clipBehavior: Clip.antiAlias,
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        // 1. Poster Top Brand Header
                        Container(
                          padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
                          decoration: const BoxDecoration(
                            color: Color(0xFF1E293B),
                            border: Border(bottom: BorderSide(color: Color(0xFF334155))),
                          ),
                          child: Row(
                            mainAxisAlignment: MainAxisAlignment.spaceBetween,
                            children: [
                              Row(
                                children: [
                                  Container(
                                    padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
                                    decoration: BoxDecoration(
                                      color: AppConfig.primaryColor,
                                      borderRadius: BorderRadius.circular(6),
                                    ),
                                    child: Text(
                                      AppConfig.appShortName,
                                      style: const TextStyle(
                                        color: Colors.white,
                                        fontWeight: FontWeight.w900,
                                        fontSize: 11,
                                      ),
                                    ),
                                  ),
                                  const SizedBox(width: 8),
                                  Text(
                                    AppConfig.appName.toUpperCase(),
                                    style: const TextStyle(
                                      color: Colors.white,
                                      fontWeight: FontWeight.w900,
                                      letterSpacing: 0.5,
                                      fontSize: 13,
                                    ),
                                  ),
                                  const SizedBox(width: 4),
                                  const Icon(Icons.verified, size: 14, color: Color(0xFF10B981)),
                                ],
                              ),
                              Text(
                                article.publishedAtHuman ?? 'JUST NOW',
                                style: const TextStyle(
                                  color: Color(0xFF94A3B8),
                                  fontSize: 10,
                                  fontWeight: FontWeight.w600,
                                ),
                              ),
                            ],
                          ),
                        ),

                        // 2. Featured Image
                        if (article.featuredImage != null)
                          Stack(
                            children: [
                              CachedNetworkImage(
                                imageUrl: article.featuredImage!,
                                width: double.infinity,
                                height: 180,
                                fit: BoxFit.cover,
                                errorWidget: (_, __, ___) => Container(
                                  height: 180,
                                  color: const Color(0xFF1E293B),
                                  child: const Icon(Icons.image, color: Colors.white24, size: 48),
                                ),
                              ),
                              Positioned(
                                bottom: 0,
                                left: 0,
                                right: 0,
                                child: Container(
                                  height: 40,
                                  decoration: BoxDecoration(
                                    gradient: LinearGradient(
                                      begin: Alignment.bottomCenter,
                                      end: Alignment.topCenter,
                                      colors: [
                                        const Color(0xFF0F172A),
                                        const Color(0xFF0F172A).withOpacity(0.0),
                                      ],
                                    ),
                                  ),
                                ),
                              ),
                              if (article.category != null)
                                Positioned(
                                  top: 12,
                                  left: 12,
                                  child: Container(
                                    padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                                    decoration: BoxDecoration(
                                      color: AppConfig.primaryColor,
                                      borderRadius: BorderRadius.circular(8),
                                      boxShadow: [
                                        BoxShadow(
                                          color: Colors.black.withOpacity(0.4),
                                          blurRadius: 6,
                                        ),
                                      ],
                                    ),
                                    child: Text(
                                      article.category!.name.toUpperCase(),
                                      style: const TextStyle(
                                        color: Colors.white,
                                        fontSize: 10,
                                        fontWeight: FontWeight.w800,
                                        letterSpacing: 0.5,
                                      ),
                                    ),
                                  ),
                                ),
                            ],
                          ),

                        // 3. Headline & Excerpt
                        Padding(
                          padding: const EdgeInsets.all(16),
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Text(
                                article.title,
                                maxLines: 3,
                                overflow: TextOverflow.ellipsis,
                                style: const TextStyle(
                                  color: Colors.white,
                                  fontSize: 16,
                                  fontWeight: FontWeight.w900,
                                  height: 1.35,
                                ),
                              ),
                              if (article.excerpt != null && article.excerpt!.isNotEmpty) ...[
                                const SizedBox(height: 8),
                                Text(
                                  article.excerpt!,
                                  maxLines: 2,
                                  overflow: TextOverflow.ellipsis,
                                  style: const TextStyle(
                                    color: Color(0xFFCBD5E1),
                                    fontSize: 12,
                                    height: 1.4,
                                  ),
                                ),
                              ],
                            ],
                          ),
                        ),

                        // 3.5 Read Full Story Link Badge (Visible on card graphic)
                        Container(
                          margin: const EdgeInsets.fromLTRB(16, 0, 16, 12),
                          padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
                          decoration: BoxDecoration(
                            color: const Color(0xFF1E293B),
                            borderRadius: BorderRadius.circular(10),
                            border: Border.all(color: const Color(0xFF38BDF8).withOpacity(0.4)),
                          ),
                          child: Row(
                            children: [
                              Container(
                                padding: const EdgeInsets.all(4),
                                decoration: BoxDecoration(
                                  color: const Color(0xFF38BDF8).withOpacity(0.15),
                                  borderRadius: BorderRadius.circular(6),
                                ),
                                child: const Icon(Icons.link_rounded, size: 14, color: Color(0xFF38BDF8)),
                              ),
                              const SizedBox(width: 8),
                              Expanded(
                                child: Column(
                                  crossAxisAlignment: CrossAxisAlignment.start,
                                  mainAxisSize: MainAxisSize.min,
                                  children: [
                                    const Text(
                                      'READ FULL STORY AT',
                                      style: TextStyle(
                                        color: Color(0xFF94A3B8),
                                        fontSize: 8,
                                        fontWeight: FontWeight.w900,
                                        letterSpacing: 0.5,
                                      ),
                                    ),
                                    Text(
                                      _getDisplayUrl(),
                                      maxLines: 1,
                                      overflow: TextOverflow.ellipsis,
                                      style: const TextStyle(
                                        color: Color(0xFF38BDF8),
                                        fontSize: 10.5,
                                        fontWeight: FontWeight.bold,
                                      ),
                                    ),
                                  ],
                                ),
                              ),
                              const Icon(Icons.arrow_forward_ios_rounded, size: 10, color: Color(0xFF94A3B8)),
                            ],
                          ),
                        ),

                        // 4. Footer Branding & QR Code Watermark
                        Container(
                          padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
                          decoration: const BoxDecoration(
                            color: Color(0xFF1E293B),
                            border: Border(top: BorderSide(color: Color(0xFF334155))),
                          ),
                          child: Row(
                            mainAxisAlignment: MainAxisAlignment.spaceBetween,
                            children: [
                              Row(
                                children: [
                                  Container(
                                    width: 32,
                                    height: 32,
                                    decoration: BoxDecoration(
                                      color: const Color(0xFF0F172A),
                                      borderRadius: BorderRadius.circular(8),
                                      border: Border.all(color: const Color(0xFF475569)),
                                    ),
                                    child: const Icon(Icons.qr_code_2, color: Colors.white, size: 20),
                                  ),
                                  const SizedBox(width: 10),
                                  Column(
                                    crossAxisAlignment: CrossAxisAlignment.start,
                                    children: const [
                                      Text(
                                        'GET LIVE UPDATES',
                                        style: TextStyle(
                                          color: Colors.white,
                                          fontWeight: FontWeight.w900,
                                          fontSize: 10,
                                          letterSpacing: 0.5,
                                        ),
                                      ),
                                      Text(
                                        'Download NewsKit Mobile App',
                                        style: TextStyle(
                                          color: Color(0xFF94A3B8),
                                          fontSize: 9,
                                        ),
                                      ),
                                    ],
                                  ),
                                ],
                              ),
                              Container(
                                padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                                decoration: BoxDecoration(
                                  color: const Color(0xFF25D366).withOpacity(0.2),
                                  borderRadius: BorderRadius.circular(8),
                                  border: Border.all(color: const Color(0xFF25D366).withOpacity(0.4)),
                                ),
                                child: Row(
                                  children: const [
                                    Icon(Icons.send_rounded, size: 10, color: Color(0xFF25D366)),
                                    SizedBox(width: 4),
                                    Text(
                                      'VERIFIED',
                                      style: TextStyle(
                                        color: Color(0xFF25D366),
                                        fontSize: 9,
                                        fontWeight: FontWeight.bold,
                                      ),
                                    ),
                                  ],
                                ),
                              ),
                            ],
                          ),
                        ),
                      ],
                    ),
                  ),
                ),
              ),
            ),
          ),

          const SizedBox(height: 12),

          // Article Link Preview Pill (Shows the link being attached)
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
            decoration: BoxDecoration(
              color: const Color(0xFF1E293B).withOpacity(0.95),
              borderRadius: BorderRadius.circular(12),
              border: Border.all(color: const Color(0xFF334155)),
            ),
            child: Row(
              children: [
                const Icon(Icons.link_rounded, size: 15, color: Color(0xFF38BDF8)),
                const SizedBox(width: 8),
                Expanded(
                  child: Text(
                    _getArticleUrl(),
                    maxLines: 1,
                    overflow: TextOverflow.ellipsis,
                    style: const TextStyle(
                      color: Color(0xFF38BDF8),
                      fontSize: 11,
                      fontFamily: 'monospace',
                    ),
                  ),
                ),
                const SizedBox(width: 6),
                InkWell(
                  onTap: _copyArticleLink,
                  borderRadius: BorderRadius.circular(6),
                  child: Padding(
                    padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 2),
                    child: Row(
                      mainAxisSize: MainAxisSize.min,
                      children: const [
                        Icon(Icons.copy_rounded, size: 12, color: Colors.white70),
                        SizedBox(width: 4),
                        Text(
                          'Copy',
                          style: TextStyle(color: Colors.white, fontSize: 10, fontWeight: FontWeight.bold),
                        ),
                      ],
                    ),
                  ),
                ),
              ],
            ),
          ),

          const SizedBox(height: 12),

          // Primary & Secondary Action Buttons
          Column(
            children: [
              SizedBox(
                width: double.infinity,
                child: ElevatedButton.icon(
                  style: ElevatedButton.styleFrom(
                    backgroundColor: const Color(0xFF25D366), // WhatsApp Green
                    foregroundColor: Colors.white,
                    padding: const EdgeInsets.symmetric(vertical: 13),
                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(14)),
                    elevation: 4,
                  ),
                  icon: _isGenerating
                      ? const SizedBox(
                          width: 18,
                          height: 18,
                          child: CircularProgressIndicator(strokeWidth: 2, color: Colors.white),
                        )
                      : const Icon(Icons.share, size: 18),
                  label: Text(
                    _isGenerating ? 'Generating...' : 'Share Card & Link (WhatsApp)',
                    style: const TextStyle(fontWeight: FontWeight.w900, fontSize: 13),
                  ),
                  onPressed: _isGenerating ? null : _sharePoster,
                ),
              ),
              const SizedBox(height: 8),
              Row(
                children: [
                  Expanded(
                    child: OutlinedButton.icon(
                      style: OutlinedButton.styleFrom(
                        foregroundColor: Colors.white,
                        side: const BorderSide(color: Color(0xFF475569)),
                        padding: const EdgeInsets.symmetric(vertical: 10),
                        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                      ),
                      icon: const Icon(Icons.copy, size: 14, color: Color(0xFF38BDF8)),
                      label: const Text(
                        'Copy Link',
                        style: TextStyle(fontSize: 12, fontWeight: FontWeight.bold),
                      ),
                      onPressed: _copyArticleLink,
                    ),
                  ),
                  const SizedBox(width: 8),
                  Expanded(
                    child: OutlinedButton.icon(
                      style: OutlinedButton.styleFrom(
                        foregroundColor: Colors.white,
                        side: const BorderSide(color: Color(0xFF475569)),
                        padding: const EdgeInsets.symmetric(vertical: 10),
                        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                      ),
                      icon: const Icon(Icons.send_rounded, size: 14, color: Color(0xFF10B981)),
                      label: const Text(
                        'Share Link Only',
                        style: TextStyle(fontSize: 12, fontWeight: FontWeight.bold),
                      ),
                      onPressed: _shareLinkOnly,
                    ),
                  ),
                ],
              ),
            ],
          ),
        ],
      ),
    );
  }
}

