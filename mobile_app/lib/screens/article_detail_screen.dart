import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/material.dart';
import 'package:flutter_html/flutter_html.dart';
import 'package:provider/provider.dart';
import 'package:share_plus/share_plus.dart';
import 'package:url_launcher/url_launcher.dart';
import 'package:youtube_player_flutter/youtube_player_flutter.dart';
import '../config/app_constants.dart';
import '../models/article_model.dart';
import '../providers/app_provider.dart';
import '../providers/bookmark_provider.dart';
import '../services/api_service.dart';
import '../services/tts_service.dart';
import '../widgets/article_card.dart';
import '../widgets/audio_player_bar.dart';
import 'comments_sheet.dart';

class ArticleDetailScreen extends StatefulWidget {
  final String slug;

  const ArticleDetailScreen({super.key, required this.slug});

  @override
  State<ArticleDetailScreen> createState() => _ArticleDetailScreenState();
}

class _ArticleDetailScreenState extends State<ArticleDetailScreen> {
  ArticleModel? _article;
  List<ArticleModel> _related = [];
  bool _isLoading = true;
  double _fontSizeScale = 1.0;
  YoutubePlayerController? _youtubeController;

  @override
  void initState() {
    super.initState();
    _fetchDetails();
  }

  Future<void> _fetchDetails() async {
    final api = Provider.of<AppProvider>(context, listen: false).apiService;
    try {
      final res = await api.fetchArticleDetail(widget.slug);
      if (mounted) {
        setState(() {
          _article = res['article'];
          _related = res['related'];
          _isLoading = false;
        });

        if (_article?.youtubeId != null && _article!.youtubeId!.isNotEmpty) {
          _youtubeController = YoutubePlayerController(
            initialVideoId: _article!.youtubeId!,
            flags: const YoutubePlayerFlags(autoPlay: false, mute: false),
          );
        }
      }
    } catch (e) {
      if (mounted) setState(() => _isLoading = false);
    }
  }

  @override
  void dispose() {
    _youtubeController?.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final bookmarkProvider = Provider.of<BookmarkProvider>(context);
    final isDark = Theme.of(context).brightness == Brightness.dark;
    final isBookmarked = _article != null && bookmarkProvider.isBookmarked(_article!.id);

    return Scaffold(
      appBar: AppBar(
        title: Text(
          _article?.category?.name ?? 'Article',
          style: const TextStyle(fontSize: 16, fontWeight: FontWeight.w800),
        ),
        actions: [
          // Font Zoom Toggle
          IconButton(
            icon: const Icon(Icons.format_size),
            tooltip: 'Adjust text size',
            onPressed: () {
              setState(() {
                _fontSizeScale = _fontSizeScale == 1.0 ? 1.25 : (_fontSizeScale == 1.25 ? 1.45 : 1.0);
              });
            },
          ),
          // Bookmark Toggle
          if (_article != null)
            IconButton(
              icon: Icon(
                isBookmarked ? Icons.bookmark : Icons.bookmark_border,
                color: isBookmarked ? const Color(0xFFE11D48) : null,
              ),
              onPressed: () async {
                final added = await bookmarkProvider.toggleBookmark(_article!);
                if (mounted) {
                  ScaffoldMessenger.of(context).showSnackBar(
                    SnackBar(
                      content: Text(added ? 'Saved to Bookmarks' : 'Removed from Bookmarks'),
                      duration: const Duration(seconds: 2),
                    ),
                  );
                }
              },
            ),
          // Share
          if (_article != null)
            IconButton(
              icon: const Icon(Icons.share_outlined),
              onPressed: () {
                final shareText = '${_article!.title}\n\nRead more at: ${_article!.shareUrl}';
                Share.share(shareText, subject: _article!.title);
              },
            ),
        ],
      ),
      bottomNavigationBar: _article != null && _article!.cleanText != null
          ? Padding(
              padding: const EdgeInsets.all(12),
              child: AudioPlayerBar(
                title: _article!.title,
                text: _article!.cleanText!,
                slug: _article!.slug,
              ),
            )
          : null,
      body: _isLoading
          ? const Center(child: CircularProgressIndicator())
          : _article == null
              ? const Center(child: Text('Article not found'))
              : SingleChildScrollView(
                  padding: const EdgeInsets.only(bottom: 40),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      // 1. Featured Media: YouTube or Image
                      if (_youtubeController != null)
                        YoutubePlayer(
                          controller: _youtubeController!,
                          showVideoProgressIndicator: true,
                          progressIndicatorColor: const Color(0xFFE11D48),
                        )
                      else if (_article!.featuredImage != null)
                        AspectRatio(
                          aspectRatio: 16 / 9,
                          child: CachedNetworkImage(
                            imageUrl: _article!.featuredImage!,
                            fit: BoxFit.cover,
                            placeholder: (_, __) => Container(color: Colors.grey.shade300),
                            errorWidget: (_, __, ___) => Container(color: Colors.grey.shade400),
                          ),
                        ),

                      Padding(
                        padding: const EdgeInsets.all(16),
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            // 2. Tags & Category Row
                            Row(
                              children: [
                                if (_article!.category != null)
                                  Container(
                                    padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                                    decoration: BoxDecoration(
                                      color: const Color(0xFFE11D48),
                                      borderRadius: BorderRadius.circular(6),
                                    ),
                                    child: Text(
                                      _article!.category!.name.toUpperCase(),
                                      style: const TextStyle(
                                        color: Colors.white,
                                        fontSize: 10,
                                        fontWeight: FontWeight.w900,
                                      ),
                                    ),
                                  ),
                                const SizedBox(width: 8),
                                if (_article!.location?.badge != null)
                                  Container(
                                    padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                                    decoration: BoxDecoration(
                                      color: isDark ? const Color(0xFF334155) : const Color(0xFFE2E8F0),
                                      borderRadius: BorderRadius.circular(6),
                                    ),
                                    child: Text(
                                      _article!.location!.badge!,
                                      style: TextStyle(
                                        fontSize: 10,
                                        fontWeight: FontWeight.w700,
                                        color: isDark ? Colors.white70 : const Color(0xFF334155),
                                      ),
                                    ),
                                  ),
                                const Spacer(),
                                Text(
                                  '${_article!.readingTime} min read',
                                  style: const TextStyle(fontSize: 11, color: Colors.grey),
                                ),
                              ],
                            ),
                            const SizedBox(height: 12),

                            // 3. Title
                            Text(
                              _article!.title,
                              style: TextStyle(
                                fontSize: 22 * _fontSizeScale,
                                fontWeight: FontWeight.w900,
                                height: 1.3,
                              ),
                            ),
                            const SizedBox(height: 12),

                            // 4. Author & Published Date
                            Row(
                              children: [
                                CircleAvatar(
                                  radius: 18,
                                  backgroundColor: const Color(0xFFE11D48),
                                  child: Text(
                                    _article!.author?.name.substring(0, 1).toUpperCase() ?? 'N',
                                    style: const TextStyle(color: Colors.white, fontWeight: FontWeight.bold),
                                  ),
                                ),
                                const SizedBox(width: 10),
                                Column(
                                  crossAxisAlignment: CrossAxisAlignment.start,
                                  children: [
                                    Text(
                                      _article!.author?.name ?? 'Editorial Desk',
                                      style: const TextStyle(fontWeight: FontWeight.w700, fontSize: 13),
                                    ),
                                    Text(
                                      _article!.publishedAtHuman ?? '',
                                      style: const TextStyle(color: Colors.grey, fontSize: 11),
                                    ),
                                  ],
                                ),
                                const Spacer(),
                                // Audio Listen Button
                                if (_article!.cleanText != null)
                                  ElevatedButton.icon(
                                    style: ElevatedButton.styleFrom(
                                      backgroundColor: const Color(0xFF0F172A),
                                      foregroundColor: Colors.white,
                                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
                                      padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
                                    ),
                                    icon: const Icon(Icons.volume_up, size: 16, color: Color(0xFFF59E0B)),
                                    label: const Text('LISTEN', style: TextStyle(fontSize: 11, fontWeight: FontWeight.w800)),
                                    onPressed: () {
                                      TtsService().speak(_article!.cleanText!, _article!.slug);
                                    },
                                  ),
                              ],
                            ),
                            const Divider(height: 32),

                            // 5. Excerpt / Highlight
                            if (_article!.excerpt != null && _article!.excerpt!.isNotEmpty)
                              Container(
                                padding: const EdgeInsets.all(14),
                                margin: const EdgeInsets.only(bottom: 16),
                                decoration: BoxDecoration(
                                  color: const Color(0xFFE11D48).withOpacity(0.06),
                                  borderRadius: BorderRadius.circular(12),
                                  border: Border(left: BorderSide(color: const Color(0xFFE11D48), width: 4)),
                                ),
                                child: Text(
                                  _article!.excerpt!,
                                  style: TextStyle(
                                    fontSize: 15 * _fontSizeScale,
                                    fontWeight: FontWeight.w600,
                                    fontStyle: FontStyle.italic,
                                    height: 1.4,
                                  ),
                                ),
                              ),

                            // 6. Article HTML Body Content
                            if (_article!.content != null)
                              Html(
                                data: _article!.content!,
                                style: {
                                  'body': Style(
                                    fontSize: FontSize(16 * _fontSizeScale),
                                    lineHeight: const LineHeight(1.6),
                                    color: isDark ? const Color(0xFFE2E8F0) : const Color(0xFF1E293B),
                                    margin: Margins.zero,
                                    padding: HtmlPaddings.zero,
                                  ),
                                  'p': Style(marginBottom: Margins.all(12)),
                                  'h2': Style(fontSize: FontSize(20 * _fontSizeScale), fontWeight: FontWeight.w800),
                                  'h3': Style(fontSize: FontSize(18 * _fontSizeScale), fontWeight: FontWeight.w700),
                                },
                              ),

                            // 7. Custom In-Article Ad Banner
                            if (_article!.customAd != null && _article!.customAd!.image != null)
                              Container(
                                margin: const EdgeInsets.symmetric(vertical: 20),
                                child: ClipRRect(
                                  borderRadius: BorderRadius.circular(12),
                                  child: InkWell(
                                    onTap: () {
                                      if (_article!.customAd!.link != null) {
                                        launchUrl(Uri.parse(_article!.customAd!.link!));
                                      }
                                    },
                                    child: CachedNetworkImage(
                                      imageUrl: _article!.customAd!.image!,
                                      fit: BoxFit.cover,
                                    ),
                                  ),
                                ),
                              ),

                            const Divider(height: 40),

                            // 8. Comments Trigger Bar
                            InkWell(
                              onTap: () {
                                showModalBottomSheet(
                                  context: context,
                                  isScrollControlled: true,
                                  backgroundColor: Colors.transparent,
                                  builder: (_) => CommentsSheet(slug: _article!.slug),
                                );
                              },
                              child: Container(
                                padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 14),
                                decoration: BoxDecoration(
                                  color: isDark ? const Color(0xFF1E293B) : const Color(0xFFF1F5F9),
                                  borderRadius: BorderRadius.circular(14),
                                ),
                                child: Row(
                                  children: const [
                                    Icon(Icons.chat_bubble_outline, color: Color(0xFFE11D48)),
                                    SizedBox(width: 12),
                                    Text(
                                      'Reader Comments & Discussion',
                                      style: TextStyle(fontWeight: FontWeight.w700, fontSize: 14),
                                    ),
                                    Spacer(),
                                    Icon(Icons.chevron_right, color: Colors.grey),
                                  ],
                                ),
                              ),
                            ),

                            // 9. Related News Stories
                            if (_related.isNotEmpty) ...[
                              const SizedBox(height: 32),
                              const Text(
                                'RELATED STORIES',
                                style: TextStyle(fontSize: 16, fontWeight: FontWeight.w900, letterSpacing: 0.5),
                              ),
                              const SizedBox(height: 12),
                              ..._related.map((rel) => ArticleListItem(article: rel)),
                            ],
                          ],
                        ),
                      ),
                    ],
                  ),
                ),
    );
  }
}
