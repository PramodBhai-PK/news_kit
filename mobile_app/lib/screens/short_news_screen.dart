import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:share_plus/share_plus.dart';
import '../config/app_config.dart';
import '../models/article_model.dart';
import '../providers/bookmark_provider.dart';
import '../providers/news_provider.dart';
import '../services/tts_service.dart';
import '../widgets/custom_ad_card.dart';
import '../widgets/story_poster_dialog.dart';
import 'article_detail_screen.dart';

class ShortNewsScreen extends StatefulWidget {
  final int initialIndex;

  const ShortNewsScreen({super.key, this.initialIndex = 0});

  @override
  State<ShortNewsScreen> createState() => _ShortNewsScreenState();
}

class _ShortNewsScreenState extends State<ShortNewsScreen> {
  late PageController _pageController;
  final TtsService _tts = TtsService();
  int _currentIndex = 0;

  @override
  void initState() {
    super.initState();
    _pageController = PageController(initialPage: widget.initialIndex);
    _currentIndex = widget.initialIndex;
    _tts.init();

    WidgetsBinding.instance.addPostFrameCallback((_) {
      final news = Provider.of<NewsProvider>(context, listen: false);
      if (news.articles.isEmpty) {
        news.initHomeData();
      }
    });
  }

  @override
  void dispose() {
    _pageController.dispose();
    _tts.stop();
    super.dispose();
  }

  String _getShortSummary(ArticleModel article) {
    if (article.excerpt != null && article.excerpt!.trim().isNotEmpty) {
      return article.excerpt!.trim();
    }
    if (article.cleanText != null && article.cleanText!.trim().isNotEmpty) {
      final words = article.cleanText!.trim().split(RegExp(r'\s+'));
      if (words.length <= 60) return words.join(' ');
      return '${words.take(60).join(' ')}...';
    }
    return article.title;
  }

  @override
  Widget build(BuildContext context) {
    final news = Provider.of<NewsProvider>(context);
    final bookmarkProvider = Provider.of<BookmarkProvider>(context);
    final isDark = Theme.of(context).brightness == Brightness.dark;

    final articles = news.articles;
    if (articles.isEmpty) {
      return Scaffold(
        appBar: AppBar(
          title: const Text('⚡ NewsKit Shorts', style: TextStyle(fontWeight: FontWeight.w900)),
        ),
        body: const Center(child: CircularProgressIndicator()),
      );
    }

    final hasAds = news.advertisements.isNotEmpty;
    // Interleave ads every 5th item
    final totalCount = hasAds ? articles.length + (articles.length ~/ 4) : articles.length;

    return Scaffold(
      backgroundColor: isDark ? const Color(0xFF0F172A) : const Color(0xFFF8FAFC),
      body: Stack(
        children: [
          // 1. Vertical Full-Screen PageView
          PageView.builder(
            controller: _pageController,
            scrollDirection: Axis.vertical,
            itemCount: totalCount,
            onPageChanged: (index) {
              setState(() => _currentIndex = index);
              _tts.stop(); // Stop previous audio on swipe
            },
            itemBuilder: (context, index) {
              if (hasAds && (index + 1) % 5 == 0) {
                // Sponsored Fullscreen Card
                return Center(
                  child: SingleChildScrollView(
                    child: Padding(
                      padding: const EdgeInsets.symmetric(horizontal: 16),
                      child: Column(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          const SizedBox(height: 60),
                          CustomAdCard(
                            placement: 'in_feed',
                            seed: index + 50,
                            margin: EdgeInsets.zero,
                          ),
                          const SizedBox(height: 24),
                          Row(
                            mainAxisAlignment: MainAxisAlignment.center,
                            children: const [
                              Icon(Icons.keyboard_arrow_down, color: Colors.grey, size: 20),
                              SizedBox(width: 6),
                              Text(
                                'Swipe down for next story',
                                style: TextStyle(color: Colors.grey, fontSize: 12, fontWeight: FontWeight.w600),
                              ),
                            ],
                          ),
                        ],
                      ),
                    ),
                  ),
                );
              }

              final articleIndex = hasAds ? index - (index ~/ 5) : index;
              if (articleIndex >= articles.length) return const SizedBox.shrink();

              final article = articles[articleIndex];
              final isBookmarked = bookmarkProvider.isBookmarked(article.id);
              final summary = _getShortSummary(article);

              return SafeArea(
                child: Column(
                  children: [
                    const SizedBox(height: 54), // Spacing for floating top bar

                    // Top: Featured Image
                    Expanded(
                      flex: 42,
                      child: Stack(
                        fit: StackFit.expand,
                        children: [
                          if (article.featuredImage != null)
                            ClipRRect(
                              borderRadius: const BorderRadius.vertical(bottom: Radius.circular(24)),
                              child: CachedNetworkImage(
                                imageUrl: article.featuredImage!,
                                fit: BoxFit.cover,
                                errorWidget: (_, __, ___) => Container(
                                  color: isDark ? const Color(0xFF1E293B) : const Color(0xFFE2E8F0),
                                  child: const Icon(Icons.image, size: 48, color: Colors.grey),
                                ),
                              ),
                            )
                          else
                            Container(
                              color: isDark ? const Color(0xFF1E293B) : const Color(0xFFE2E8F0),
                              child: const Icon(Icons.newspaper, size: 48, color: Colors.grey),
                            ),

                          // Top gradient shadow
                          Positioned(
                            top: 0,
                            left: 0,
                            right: 0,
                            height: 60,
                            child: Container(
                              decoration: BoxDecoration(
                                gradient: LinearGradient(
                                  begin: Alignment.topCenter,
                                  end: Alignment.bottomCenter,
                                  colors: [Colors.black.withOpacity(0.5), Colors.transparent],
                                ),
                              ),
                            ),
                          ),

                          // Category & Time Pill
                          Positioned(
                            bottom: 12,
                            left: 16,
                            child: Row(
                              children: [
                                if (article.category != null)
                                  Container(
                                    padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                                    decoration: BoxDecoration(
                                      color: AppConfig.primaryColor,
                                      borderRadius: BorderRadius.circular(8),
                                      boxShadow: [
                                        BoxShadow(color: Colors.black.withOpacity(0.3), blurRadius: 6),
                                      ],
                                    ),
                                    child: Text(
                                      article.category!.name.toUpperCase(),
                                      style: const TextStyle(
                                        color: Colors.white,
                                        fontWeight: FontWeight.w900,
                                        fontSize: 10,
                                        letterSpacing: 0.5,
                                      ),
                                    ),
                                  ),
                                const SizedBox(width: 8),
                                Container(
                                  padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                                  decoration: BoxDecoration(
                                    color: Colors.black.withOpacity(0.6),
                                    borderRadius: BorderRadius.circular(8),
                                  ),
                                  child: Text(
                                    article.publishedAtHuman ?? 'JUST NOW',
                                    style: const TextStyle(color: Colors.white70, fontSize: 10, fontWeight: FontWeight.w600),
                                  ),
                                ),
                              ],
                            ),
                          ),
                        ],
                      ),
                    ),

                    // Bottom: Headline, 60-Word Story & Actions
                    Expanded(
                      flex: 58,
                      child: Padding(
                        padding: const EdgeInsets.fromLTRB(18, 14, 18, 12),
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            // Headline
                            Text(
                              article.title,
                              maxLines: 3,
                              overflow: TextOverflow.ellipsis,
                              style: TextStyle(
                                fontSize: 17,
                                fontWeight: FontWeight.w900,
                                height: 1.35,
                                letterSpacing: -0.3,
                                color: isDark ? Colors.white : const Color(0xFF0F172A),
                              ),
                            ),
                            const SizedBox(height: 10),

                            // 60-Word Story
                            Expanded(
                              child: SingleChildScrollView(
                                child: Text(
                                  summary,
                                  style: TextStyle(
                                    fontSize: 14,
                                    height: 1.55,
                                    color: isDark ? const Color(0xFFCBD5E1) : const Color(0xFF334155),
                                  ),
                                ),
                              ),
                            ),

                            const SizedBox(height: 8),

                            // Action Bar
                            Container(
                              padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                              decoration: BoxDecoration(
                                color: isDark ? const Color(0xFF1E293B) : const Color(0xFFF1F5F9),
                                borderRadius: BorderRadius.circular(16),
                                border: Border.all(
                                  color: isDark ? const Color(0xFF334155) : const Color(0xFFE2E8F0),
                                ),
                              ),
                              child: Row(
                                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                                children: [
                                  // TTS Audio Listen
                                  IconButton(
                                    icon: const Icon(Icons.volume_up_rounded, size: 22, color: AppConfig.primaryColor),
                                    tooltip: 'Listen to 60-word audio',
                                    onPressed: () {
                                      _tts.speak(summary, article.slug);
                                    },
                                  ),
                                  // WhatsApp Story Poster Maker
                                  IconButton(
                                    icon: const Icon(Icons.auto_awesome, size: 22, color: Color(0xFFF59E0B)),
                                    tooltip: 'Create WhatsApp Poster',
                                    onPressed: () => StoryPosterDialog.show(context, article),
                                  ),
                                  // Bookmark
                                  IconButton(
                                    icon: Icon(
                                      isBookmarked ? Icons.bookmark : Icons.bookmark_border,
                                      size: 22,
                                      color: isBookmarked ? AppConfig.primaryColor : (isDark ? Colors.white70 : Colors.black87),
                                    ),
                                    tooltip: 'Save article',
                                    onPressed: () => bookmarkProvider.toggleBookmark(article),
                                  ),
                                  // Share Link
                                  IconButton(
                                    icon: Icon(Icons.share_outlined, size: 22, color: isDark ? Colors.white70 : Colors.black87),
                                    tooltip: 'Share link',
                                    onPressed: () {
                                      Share.share('${article.title}\n\n${article.shareUrl ?? AppConfig.apiBaseUrl}');
                                    },
                                  ),
                                ],
                              ),
                            ),

                            const SizedBox(height: 10),

                            // Full Story Link
                            InkWell(
                              onTap: () {
                                Navigator.push(
                                  context,
                                  MaterialPageRoute(builder: (_) => ArticleDetailScreen(slug: article.slug)),
                                );
                              },
                              borderRadius: BorderRadius.circular(12),
                              child: Container(
                                padding: const EdgeInsets.symmetric(vertical: 10, horizontal: 14),
                                decoration: BoxDecoration(
                                  color: AppConfig.primaryColor.withOpacity(0.1),
                                  borderRadius: BorderRadius.circular(12),
                                  border: Border.all(color: AppConfig.primaryColor.withOpacity(0.3)),
                                ),
                                child: Row(
                                  mainAxisAlignment: MainAxisAlignment.center,
                                  children: const [
                                    Text(
                                      'Tap for Full Article & Discussion',
                                      style: TextStyle(
                                        color: AppConfig.primaryColor,
                                        fontWeight: FontWeight.w800,
                                        fontSize: 12,
                                      ),
                                    ),
                                    SizedBox(width: 6),
                                    Icon(Icons.arrow_forward_rounded, size: 14, color: AppConfig.primaryColor),
                                  ],
                                ),
                              ),
                            ),
                          ],
                        ),
                      ),
                    ),
                  ],
                ),
              );
            },
          ),

          // 2. Floating Top Header
          Positioned(
            top: 0,
            left: 0,
            right: 0,
            child: SafeArea(
              child: Container(
                padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                decoration: BoxDecoration(
                  gradient: LinearGradient(
                    begin: Alignment.topCenter,
                    end: Alignment.bottomCenter,
                    colors: [
                      (isDark ? const Color(0xFF0F172A) : Colors.white).withOpacity(0.95),
                      (isDark ? const Color(0xFF0F172A) : Colors.white).withOpacity(0.0),
                    ],
                  ),
                ),
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Row(
                      children: [
                        Container(
                          padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                          decoration: BoxDecoration(
                            gradient: const LinearGradient(
                              colors: [Color(0xFFE11D48), Color(0xFFF59E0B)],
                            ),
                            borderRadius: BorderRadius.circular(8),
                          ),
                          child: const Row(
                            children: [
                              Icon(Icons.bolt, color: Colors.white, size: 14),
                              SizedBox(width: 2),
                              Text(
                                'SHORTS',
                                style: TextStyle(color: Colors.white, fontWeight: FontWeight.w900, fontSize: 11),
                              ),
                            ],
                          ),
                        ),
                        const SizedBox(width: 8),
                        Text(
                          '60-Word Feed',
                          style: TextStyle(
                            fontSize: 14,
                            fontWeight: FontWeight.w900,
                            color: isDark ? Colors.white : const Color(0xFF0F172A),
                          ),
                        ),
                      ],
                    ),
                    IconButton(
                      icon: const Icon(Icons.refresh),
                      tooltip: 'Refresh Feed',
                      onPressed: () => news.refresh(),
                    ),
                  ],
                ),
              ),
            ),
          ),
        ],
      ),
    );
  }
}
