import 'package:flutter/material.dart';
import '../models/article_model.dart';
import '../models/category_model.dart';
import '../services/api_service.dart';

class NewsProvider with ChangeNotifier {
  final ApiService apiService;

  List<ArticleModel> _breakingNews = [];
  List<ArticleModel> _featuredNews = [];
  List<ArticleModel> _trendingNews = [];
  List<ArticleModel> _articles = [];
  List<CategoryModel> _categories = [];
  List<ArticleModel> _videos = [];

  bool _isInitialLoading = true;
  bool _isLoadingMore = false;
  int _currentPage = 1;
  bool _hasMore = true;
  String? _activeCategorySlug;
  String? _activeCitySlug;

  List<ArticleModel> get breakingNews => _breakingNews;
  List<ArticleModel> get featuredNews => _featuredNews;
  List<ArticleModel> get trendingNews => _trendingNews;
  List<ArticleModel> get articles => _articles;
  List<CategoryModel> get categories => _categories;
  List<ArticleModel> get videos => _videos;

  bool get isInitialLoading => _isInitialLoading;
  bool get isLoadingMore => _isLoadingMore;
  bool get hasMore => _hasMore;
  String? get activeCategorySlug => _activeCategorySlug;
  String? get activeCitySlug => _activeCitySlug;

  NewsProvider({required this.apiService});

  Future<void> initHomeData({String? citySlug}) async {
    _isInitialLoading = true;
    _activeCitySlug = citySlug;
    _currentPage = 1;
    _hasMore = true;
    notifyListeners();

    try {
      final results = await Future.wait([
        apiService.fetchBreakingNews(),
        apiService.fetchFeaturedNews(),
        apiService.fetchTrendingNews(),
        apiService.fetchCategories(),
        apiService.fetchArticles(page: 1, citySlug: citySlug),
        apiService.fetchVideos(page: 1),
      ]);

      _breakingNews = results[0] as List<ArticleModel>;
      _featuredNews = results[1] as List<ArticleModel>;
      _trendingNews = results[2] as List<ArticleModel>;
      _categories = results[3] as List<CategoryModel>;
      _articles = results[4] as List<ArticleModel>;
      _videos = results[5] as List<ArticleModel>;
    } catch (e) {
      debugPrint('Error fetching home data: $e');
    }

    _isInitialLoading = false;
    notifyListeners();
  }

  Future<void> loadMoreArticles() async {
    if (_isLoadingMore || !_hasMore) return;

    _isLoadingMore = true;
    notifyListeners();

    try {
      final nextPage = _currentPage + 1;
      final newArticles = await apiService.fetchArticles(
        page: nextPage,
        categorySlug: _activeCategorySlug,
        citySlug: _activeCitySlug,
      );

      if (newArticles.isEmpty) {
        _hasMore = false;
      } else {
        _articles.addAll(newArticles);
        _currentPage = nextPage;
      }
    } catch (e) {
      debugPrint('Error loading more articles: $e');
    }

    _isLoadingMore = false;
    notifyListeners();
  }

  Future<void> filterByCategory(String? categorySlug) async {
    if (_activeCategorySlug == categorySlug) return;
    _activeCategorySlug = categorySlug;
    _isInitialLoading = true;
    _currentPage = 1;
    _hasMore = true;
    notifyListeners();

    try {
      _articles = await apiService.fetchArticles(
        page: 1,
        categorySlug: categorySlug,
        citySlug: _activeCitySlug,
      );
    } catch (e) {
      debugPrint('Error filtering by category: $e');
    }

    _isInitialLoading = false;
    notifyListeners();
  }

  Future<void> refresh() async {
    await initHomeData(citySlug: _activeCitySlug);
  }
}

