import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../config/app_config.dart';
import '../models/article_model.dart';
import '../models/category_model.dart';
import '../providers/app_provider.dart';
import '../providers/news_provider.dart';
import '../widgets/article_card.dart';
import '../widgets/custom_ad_card.dart';

class CategoryNewsScreen extends StatefulWidget {
  const CategoryNewsScreen({super.key});

  @override
  State<CategoryNewsScreen> createState() => _CategoryNewsScreenState();
}

class _CategoryNewsScreenState extends State<CategoryNewsScreen> {
  CategoryModel? _selectedCategory;
  List<ArticleModel> _categoryArticles = [];
  bool _isLoading = false;

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      final news = Provider.of<NewsProvider>(context, listen: false);
      if (news.categories.isEmpty) {
        news.initHomeData();
      }
    });
  }

  Future<void> _loadCategoryArticles(CategoryModel category) async {
    setState(() {
      _selectedCategory = category;
      _isLoading = true;
    });

    final api = Provider.of<AppProvider>(context, listen: false).apiService;
    try {
      final articles = await api.fetchArticles(categorySlug: category.slug);
      if (mounted) {
        setState(() {
          _categoryArticles = articles;
          _isLoading = false;
        });
      }
    } catch (e) {
      if (mounted) setState(() => _isLoading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    final categories = Provider.of<NewsProvider>(context).categories;
    final isDark = Theme.of(context).brightness == Brightness.dark;

    return Scaffold(
      appBar: AppBar(
        title: Text(
          _selectedCategory != null ? _selectedCategory!.name : 'Explore Categories',
          style: const TextStyle(fontWeight: FontWeight.w900),
        ),
        leading: _selectedCategory != null
            ? IconButton(
                icon: const Icon(Icons.arrow_back),
                onPressed: () => setState(() => _selectedCategory = null),
              )
            : null,
      ),
      body: _selectedCategory == null
          ? categories.isEmpty
              ? Center(
                  child: Padding(
                    padding: const EdgeInsets.all(32),
                    child: Column(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        const Icon(Icons.grid_view_outlined, size: 54, color: Colors.grey),
                        const SizedBox(height: 12),
                        const Text(
                          'Loading Categories...',
                          style: TextStyle(fontWeight: FontWeight.w700, fontSize: 16),
                        ),
                        const SizedBox(height: 16),
                        ElevatedButton.icon(
                          style: ElevatedButton.styleFrom(
                            backgroundColor: AppConfig.primaryColor,
                            foregroundColor: Colors.white,
                            shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
                          ),
                          icon: const Icon(Icons.refresh, size: 16),
                          label: const Text('Refresh'),
                          onPressed: () {
                            Provider.of<NewsProvider>(context, listen: false).initHomeData();
                          },
                        ),
                      ],
                    ),
                  ),
                )
              : GridView.builder(
              padding: const EdgeInsets.all(16),
              gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
                crossAxisCount: 2,
                crossAxisSpacing: 12,
                mainAxisSpacing: 12,
                childAspectRatio: 1.4,
              ),
              itemCount: categories.length,
              itemBuilder: (context, index) {
                final cat = categories[index];
                final hexColor = cat.color.replaceAll('#', '');
                final color = Color(int.parse('FF$hexColor', radix: 16));

                return InkWell(
                  onTap: () => _loadCategoryArticles(cat),
                  borderRadius: BorderRadius.circular(16),
                  child: Container(
                    padding: const EdgeInsets.all(16),
                    decoration: BoxDecoration(
                      color: isDark ? const Color(0xFF1E293B) : Colors.white,
                      borderRadius: BorderRadius.circular(16),
                      border: Border.all(
                        color: isDark ? const Color(0xFF334155) : const Color(0xFFE2E8F0),
                      ),
                      boxShadow: [
                        BoxShadow(
                          color: Colors.black.withOpacity(0.04),
                          blurRadius: 8,
                          offset: const Offset(0, 2),
                        ),
                      ],
                    ),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        Container(
                          width: 36,
                          height: 36,
                          decoration: BoxDecoration(
                            color: color.withOpacity(0.15),
                            borderRadius: BorderRadius.circular(10),
                          ),
                          child: Icon(Icons.category_outlined, color: color, size: 20),
                        ),
                        Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(
                              cat.name,
                              maxLines: 1,
                              overflow: TextOverflow.ellipsis,
                              style: const TextStyle(fontSize: 15, fontWeight: FontWeight.w800),
                            ),
                            const SizedBox(height: 2),
                            Text(
                              '${cat.articlesCount} stories',
                              style: TextStyle(
                                fontSize: 11,
                                color: isDark ? Colors.grey.shade400 : Colors.grey.shade500,
                              ),
                            ),
                          ],
                        ),
                      ],
                    ),
                  ),
                );
              },
            )
          : _isLoading
              ? const Center(child: CircularProgressIndicator())
              : _categoryArticles.isEmpty
                  ? const Center(child: Text('No articles found in this category.'))
                  : Builder(
                      builder: (context) {
                        final news = Provider.of<NewsProvider>(context, listen: false);
                        final hasAds = news.advertisements.isNotEmpty;
                        final totalCount = hasAds
                            ? _categoryArticles.length + (_categoryArticles.length ~/ 4)
                            : _categoryArticles.length;

                        return ListView.builder(
                          padding: const EdgeInsets.symmetric(vertical: 8),
                          itemCount: totalCount,
                          itemBuilder: (context, index) {
                            if (hasAds && (index + 1) % 5 == 0) {
                              return CustomAdCard(
                                placement: 'in_feed',
                                seed: index + 20,
                                margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                              );
                            }
                            final articleIndex = hasAds ? index - (index ~/ 5) : index;
                            if (articleIndex < _categoryArticles.length) {
                              return ArticleListItem(article: _categoryArticles[articleIndex]);
                            }
                            return const SizedBox.shrink();
                          },
                        );
                      },
                    ),
    );
  }
}

