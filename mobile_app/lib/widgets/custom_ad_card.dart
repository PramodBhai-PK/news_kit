import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/material.dart';
import 'package:flutter_widget_from_html/flutter_widget_from_html.dart';
import 'package:provider/provider.dart';
import 'package:url_launcher/url_launcher.dart';
import '../config/app_config.dart';
import '../models/advertisement_model.dart';
import '../providers/news_provider.dart';
import 'shimmer_loading.dart';

class CustomAdCard extends StatefulWidget {
  final AdvertisementModel? ad;
  final String? placement;
  final int? seed;
  final EdgeInsetsGeometry margin;
  final bool compact;

  const CustomAdCard({
    super.key,
    this.ad,
    this.placement,
    this.seed,
    this.margin = const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
    this.compact = false,
  });

  @override
  State<CustomAdCard> createState() => _CustomAdCardState();
}

class _CustomAdCardState extends State<CustomAdCard> {
  AdvertisementModel? _resolvedAd;

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      _initAndTrackAd();
    });
  }

  void _initAndTrackAd() {
    final news = Provider.of<NewsProvider>(context, listen: false);
    final ad = widget.ad ?? news.getRandomAd(placement: widget.placement, seed: widget.seed);
    if (ad != null && mounted) {
      setState(() {
        _resolvedAd = ad;
      });
      news.recordAdImpressionOnce(ad.id);
    }
  }

  Future<void> _handleAdTap() async {
    if (_resolvedAd == null) return;

    final news = Provider.of<NewsProvider>(context, listen: false);
    news.apiService.recordAdClick(_resolvedAd!.id);

    final targetUrl = _resolvedAd!.destinationUrl ?? _resolvedAd!.clickUrl;
    if (targetUrl != null && targetUrl.isNotEmpty) {
      try {
        final uri = Uri.parse(targetUrl);
        await launchUrl(uri, mode: LaunchMode.externalApplication);
      } catch (e) {
        debugPrint('Could not launch ad URL: $e');
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    final news = Provider.of<NewsProvider>(context);
    final ad = _resolvedAd ?? widget.ad ?? news.getRandomAd(placement: widget.placement, seed: widget.seed);

    if (ad == null) {
      return const SizedBox.shrink();
    }

    final isDark = Theme.of(context).brightness == Brightness.dark;

    return Container(
      margin: widget.margin,
      decoration: BoxDecoration(
        color: isDark ? const Color(0xFF1E293B) : Colors.white,
        borderRadius: BorderRadius.circular(16),
        border: Border.all(
          color: isDark ? const Color(0xFF334155) : const Color(0xFFE2E8F0),
        ),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(isDark ? 0.25 : 0.05),
            blurRadius: 10,
            offset: const Offset(0, 3),
          ),
        ],
      ),
      clipBehavior: Clip.antiAlias,
      child: Material(
        color: Colors.transparent,
        child: InkWell(
          onTap: _handleAdTap,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            mainAxisSize: MainAxisSize.min,
            children: [
              // Header tag
              Padding(
                padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Row(
                      children: [
                        Container(
                          padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 2),
                          decoration: BoxDecoration(
                            color: isDark ? const Color(0xFF1E293B) : const Color(0xFFF1F5F9),
                            borderRadius: BorderRadius.circular(6),
                            border: Border.all(
                              color: isDark ? const Color(0xFF334155) : const Color(0xFFCBD5E1),
                            ),
                          ),
                          child: Row(
                            children: [
                              Icon(
                                Icons.campaign_rounded,
                                size: 12,
                                color: AppConfig.primaryColor,
                              ),
                              const SizedBox(width: 4),
                              Text(
                                'SPONSORED',
                                style: TextStyle(
                                  fontSize: 9,
                                  fontWeight: FontWeight.w800,
                                  letterSpacing: 0.5,
                                  color: isDark ? const Color(0xFFCBD5E1) : const Color(0xFF475569),
                                ),
                              ),
                            ],
                          ),
                        ),
                      ],
                    ),
                    Icon(
                      Icons.open_in_new,
                      size: 13,
                      color: isDark ? const Color(0xFF94A3B8) : const Color(0xFF64748B),
                    ),
                  ],
                ),
              ),

              // Image Ad
              if (ad.isImage) ...[
                ClipRRect(
                  borderRadius: const BorderRadius.vertical(bottom: Radius.circular(15)),
                  child: Stack(
                    children: [
                      CachedNetworkImage(
                        imageUrl: ad.imageUrl!,
                        width: double.infinity,
                        height: widget.compact ? 120 : 160,
                        fit: BoxFit.cover,
                        placeholder: (_, __) => ShimmerLoading(
                          width: double.infinity,
                          height: widget.compact ? 120 : 160,
                          borderRadius: 0,
                        ),
                        errorWidget: (_, __, ___) => Container(
                          height: 120,
                          color: isDark ? const Color(0xFF0F172A) : const Color(0xFFF1F5F9),
                          alignment: Alignment.center,
                          child: const Icon(Icons.broken_image, color: Color(0xFF64748B)),
                        ),
                      ),
                      Positioned(
                        bottom: 0,
                        left: 0,
                        right: 0,
                        child: Container(
                          padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
                          decoration: BoxDecoration(
                            gradient: LinearGradient(
                              begin: Alignment.bottomCenter,
                              end: Alignment.topCenter,
                              colors: [
                                Colors.black.withOpacity(0.85),
                                Colors.transparent,
                              ],
                            ),
                          ),
                          child: Row(
                            children: [
                              Expanded(
                                child: Text(
                                  ad.title,
                                  maxLines: 1,
                                  overflow: TextOverflow.ellipsis,
                                  style: const TextStyle(
                                    color: Colors.white,
                                    fontSize: 12,
                                    fontWeight: FontWeight.w700,
                                  ),
                                ),
                              ),
                              const SizedBox(width: 8),
                              Container(
                                padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                                decoration: BoxDecoration(
                                  color: AppConfig.primaryColor,
                                  borderRadius: BorderRadius.circular(12),
                                ),
                                child: const Text(
                                  'Visit',
                                  style: TextStyle(
                                    color: Colors.white,
                                    fontSize: 11,
                                    fontWeight: FontWeight.bold,
                                  ),
                                ),
                              ),
                            ],
                          ),
                        ),
                      ),
                    ],
                  ),
                ),
              ]
              // Code / HTML Ad
              else if (ad.isCode) ...[
                Padding(
                  padding: const EdgeInsets.all(12),
                  child: HtmlWidget(
                    ad.htmlCode!,
                    onTapUrl: (url) {
                      _handleAdTap();
                      return true;
                    },
                    textStyle: TextStyle(
                      fontSize: 12,
                      color: isDark ? const Color(0xFFE2E8F0) : const Color(0xFF1E293B),
                    ),
                  ),
                ),
              ],
            ],
          ),
        ),
      ),
    );
  }
}
