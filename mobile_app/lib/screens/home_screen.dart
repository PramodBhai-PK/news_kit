import 'package:carousel_slider/carousel_slider.dart';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../models/article_model.dart';
import '../providers/app_provider.dart';
import '../providers/news_provider.dart';
import '../widgets/article_card.dart';
import '../widgets/breaking_news_ticker.dart';
import '../widgets/category_pill.dart';
import '../widgets/city_selector_sheet.dart';
import '../widgets/shimmer_loading.dart';
import 'citizen_tip_screen.dart';
import 'search_screen.dart';
import 'whatsapp_channel_screen.dart';

class HomeScreen extends StatefulWidget {
  const HomeScreen({super.key});

  @override
  State<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  final ScrollController _scrollController = ScrollController();

  @override
  void initState() {
    super.initState();
    _scrollController.addListener(_onScroll);
  }

  void _onScroll() {
    if (_scrollController.position.pixels >= _scrollController.position.maxScrollExtent - 200) {
      Provider.of<NewsProvider>(context, listen: false).loadMoreArticles();
    }
  }

  @override
  void dispose() {
    _scrollController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final newsProvider = Provider.of<NewsProvider>(context);
    final appProvider = Provider.of<AppProvider>(context);
    final isDark = Theme.of(context).brightness == Brightness.dark;

    return Scaffold(
      appBar: AppBar(
        titleSpacing: 16,
        title: Row(
          children: [
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
              decoration: BoxDecoration(
                color: const Color(0xFFE11D48),
                borderRadius: BorderRadius.circular(8),
              ),
              child: const Text(
                'NK',
                style: TextStyle(color: Colors.white, fontWeight: FontWeight.w900, fontSize: 14),
              ),
            ),
            const SizedBox(width: 8),
            const Text(
              'NEWSKIT',
              style: TextStyle(fontWeight: FontWeight.w900, letterSpacing: -0.5, fontSize: 18),
            ),
          ],
        ),
        actions: [
          // Hyperlocal City Selector Button
          TextButton.icon(
            style: TextButton.styleFrom(
              padding: const EdgeInsets.symmetric(horizontal: 10),
              backgroundColor: isDark ? const Color(0xFF1E293B) : const Color(0xFFF1F5F9),
              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
            ),
            icon: const Icon(Icons.location_on, size: 14, color: Color(0xFFE11D48)),
            label: Text(
              appProvider.selectedCityName ?? 'District',
              style: TextStyle(
                fontSize: 12,
                fontWeight: FontWeight.w700,
                color: isDark ? Colors.white : const Color(0xFF0F172A),
              ),
            ),
            onPressed: () {
              showModalBottomSheet(
                context: context,
                isScrollControlled: true,
                backgroundColor: Colors.transparent,
                builder: (_) => const CitySelectorSheet(),
              );
            },
          ),
          const SizedBox(width: 6),
          // Search Icon
          IconButton(
            icon: const Icon(Icons.search),
            onPressed: () {
              Navigator.push(
                context,
                MaterialPageRoute(builder: (_) => const SearchScreen()),
              );
            },
          ),
          // WhatsApp Channel Shortcut
          IconButton(
            icon: const Icon(Icons.chat_bubble_outline, color: Color(0xFF25D366)),
            onPressed: () {
              Navigator.push(
                context,
                MaterialPageRoute(builder: (_) => const WhatsAppChannelScreen()),
              );
            },
          ),
          const SizedBox(width: 6),
        ],
      ),
      floatingActionButton: FloatingActionButton.extended(
        backgroundColor: const Color(0xFFE11D48),
        icon: const Icon(Icons.campaign, color: Colors.white),
        label: const Text(
          'REPORT TIP',
          style: TextStyle(color: Colors.white, fontWeight: FontWeight.w900, letterSpacing: 0.5, fontSize: 11),
        ),
        onPressed: () {
          Navigator.push(
            context,
            MaterialPageRoute(builder: (_) => const CitizenTipScreen()),
          );
        },
      ),
      body: RefreshIndicator(
        color: const Color(0xFFE11D48),
        onRefresh: () => newsProvider.refresh(),
        child: newsProvider.isInitialLoading
            ? const ArticleListShimmer()
            : CustomScrollView(
                controller: _scrollController,
                slivers: [
                  // 1. Breaking News Ticker
                  if (newsProvider.breakingNews.isNotEmpty)
                    SliverToBoxAdapter(
                      child: BreakingNewsTicker(items: newsProvider.breakingNews),
                    ),

                  // 2. Featured Stories Carousel
                  if (newsProvider.featuredNews.isNotEmpty && newsProvider.activeCategorySlug == null) ...[
                    SliverToBoxAdapter(
                      child: Padding(
                        padding: const EdgeInsets.only(top: 8, bottom: 4),
                        child: CarouselSlider.builder(
                          itemCount: newsProvider.featuredNews.length,
                          options: CarouselOptions(
                            height: 230,
                            viewportFraction: 0.92,
                            enlargeCenterPage: true,
                            autoPlay: true,
                            autoPlayInterval: const Duration(seconds: 5),
                          ),
                          itemBuilder: (context, index, realIndex) {
                            return FeaturedArticleCard(article: newsProvider.featuredNews[index]);
                          },
                        ),
                      ),
                    ),
                  ],

                  // 3. Category Pills Row
                  SliverToBoxAdapter(
                    child: Padding(
                      padding: const EdgeInsets.symmetric(vertical: 8),
                      child: CategoryPillList(
                        categories: newsProvider.categories,
                        activeCategorySlug: newsProvider.activeCategorySlug,
                        onCategorySelected: (slug) {
                          newsProvider.filterByCategory(slug);
                        },
                      ),
                    ),
                  ),

                  // 4. Trending Stories Section Header (if no category selected)
                  if (newsProvider.trendingNews.isNotEmpty && newsProvider.activeCategorySlug == null) ...[
                    SliverToBoxAdapter(
                      child: Padding(
                        padding: const EdgeInsets.fromLTRB(16, 16, 16, 8),
                        child: Row(
                          children: const [
                            Icon(Icons.trending_up, color: Color(0xFFE11D48), size: 20),
                            SizedBox(width: 8),
                            Text(
                              'TRENDING STORIES',
                              style: TextStyle(fontSize: 15, fontWeight: FontWeight.w900, letterSpacing: 0.5),
                            ),
                          ],
                        ),
                      ),
                    ),
                    SliverToBoxAdapter(
                      child: SizedBox(
                        height: 190,
                        child: ListView.builder(
                          padding: const EdgeInsets.symmetric(horizontal: 16),
                          scrollDirection: Axis.horizontal,
                          itemCount: newsProvider.trendingNews.length,
                          itemBuilder: (context, index) {
                            final art = newsProvider.trendingNews[index];
                            return Container(
                              width: 260,
                              margin: const EdgeInsets.only(right: 12),
                              child: FeaturedArticleCard(article: art),
                            );
                          },
                        ),
                      ),
                    ),
                  ],

                  // 5. Main Feed Header
                  SliverToBoxAdapter(
                    child: Padding(
                      padding: const EdgeInsets.fromLTRB(16, 16, 16, 8),
                      child: Row(
                        children: [
                          const Icon(Icons.flash_on, color: Color(0xFFF59E0B), size: 18),
                          const SizedBox(width: 6),
                          Text(
                            newsProvider.activeCategorySlug != null
                                ? 'CATEGORY FEED'
                                : (appProvider.selectedCityName != null
                                    ? '${appProvider.selectedCityName!.toUpperCase()} HEADLINES'
                                    : 'LATEST STORIES'),
                            style: const TextStyle(fontSize: 14, fontWeight: FontWeight.w900, letterSpacing: 0.5),
                          ),
                        ],
                      ),
                    ),
                  ),

                  // 6. Articles List
                  if (newsProvider.articles.isEmpty)
                    const SliverFillRemaining(
                      hasScrollBody: false,
                      child: Center(
                        child: Padding(
                          padding: EdgeInsets.all(32),
                          child: Text(
                            'No articles found for this section.',
                            style: TextStyle(fontWeight: FontWeight.w600, color: Colors.grey),
                          ),
                        ),
                      ),
                    )
                  else
                    SliverList(
                      delegate: SliverChildBuilderDelegate(
                        (context, index) {
                          return ArticleListItem(article: newsProvider.articles[index]);
                        },
                        childCount: newsProvider.articles.length,
                      ),
                    ),

                  // 7. Loading more spinner
                  if (newsProvider.isLoadingMore)
                    const SliverToBoxAdapter(
                      child: Padding(
                        padding: EdgeInsets.all(24),
                        child: Center(child: CircularProgressIndicator()),
                      ),
                    ),

                  const SliverToBoxAdapter(child: SizedBox(height: 80)),
                ],
              ),
      ),
    );
  }
}

