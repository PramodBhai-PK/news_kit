import 'dart:math';
import 'package:flutter/material.dart';
import '../models/advertisement_model.dart';
import '../models/article_model.dart';
import '../models/category_model.dart';
import '../services/api_service.dart';

class NewsProvider with ChangeNotifier {
  ApiService _apiService;
  ApiService get apiService => _apiService;

  List<ArticleModel> _breakingNews = [];
  List<ArticleModel> _featuredNews = [];
  List<ArticleModel> _trendingNews = [];
  List<ArticleModel> _articles = [];
  List<CategoryModel> _categories = [];
  List<ArticleModel> _videos = [];
  List<AdvertisementModel> _advertisements = [];

  final Set<int> _recordedImpressionIds = {};

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
  List<AdvertisementModel> get advertisements => _advertisements;

  bool get isInitialLoading => _isInitialLoading;
  bool get isLoadingMore => _isLoadingMore;
  bool get hasMore => _hasMore;
  String? get activeCategorySlug => _activeCategorySlug;
  String? get activeCitySlug => _activeCitySlug;

  NewsProvider({required ApiService apiService}) : _apiService = apiService;

  void updateApiService(ApiService newService) {
    _apiService = newService;
  }

  Future<void> initHomeData({String? citySlug}) async {
    _isInitialLoading = true;
    _activeCitySlug = citySlug;
    _currentPage = 1;
    _hasMore = true;
    notifyListeners();

    try {
      final breakingFuture = _apiService.fetchBreakingNews().catchError((e) {
        debugPrint('Error loading breaking news: $e');
        return <ArticleModel>[];
      });
      final featuredFuture = _apiService.fetchFeaturedNews().catchError((e) {
        debugPrint('Error loading featured news: $e');
        return <ArticleModel>[];
      });
      final trendingFuture = _apiService.fetchTrendingNews().catchError((e) {
        debugPrint('Error loading trending news: $e');
        return <ArticleModel>[];
      });
      final categoriesFuture = _apiService.fetchCategories().catchError((e) {
        debugPrint('Error loading categories: $e');
        return <CategoryModel>[];
      });
      final articlesFuture = _apiService.fetchArticles(page: 1, citySlug: citySlug).catchError((e) {
        debugPrint('Error loading articles: $e');
        return <ArticleModel>[];
      });
      final videosFuture = _apiService.fetchVideos(page: 1).catchError((e) {
        debugPrint('Error loading videos: $e');
        return <ArticleModel>[];
      });
      final adsFuture = _apiService.fetchAdvertisements().catchError((e) {
        debugPrint('Error loading advertisements: $e');
        return <AdvertisementModel>[];
      });

      final results = await Future.wait([
        breakingFuture,
        featuredFuture,
        trendingFuture,
        categoriesFuture,
        articlesFuture,
        videosFuture,
        adsFuture,
      ]);

      _breakingNews = results[0] as List<ArticleModel>;
      _featuredNews = results[1] as List<ArticleModel>;
      _trendingNews = results[2] as List<ArticleModel>;
      _categories = results[3] as List<CategoryModel>;
      _articles = results[4] as List<ArticleModel>;
      _videos = results[5] as List<ArticleModel>;
      _advertisements = results[6] as List<AdvertisementModel>;
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

  /// Get a random or placement-targeted active advertisement
  AdvertisementModel? getRandomAd({String? placement, int? seed}) {
    if (_advertisements.isEmpty) return null;

    var candidates = placement != null
        ? _advertisements.where((a) => a.placement == placement).toList()
        : _advertisements;

    if (candidates.isEmpty) {
      candidates = _advertisements;
    }

    if (candidates.isEmpty) return null;

    if (seed != null) {
      return candidates[seed.abs() % candidates.length];
    }
    return candidates[Random().nextInt(candidates.length)];
  }

  /// Record ad impression only once per session/view
  void recordAdImpressionOnce(int adId) {
    if (!_recordedImpressionIds.contains(adId)) {
      _recordedImpressionIds.add(adId);
      _apiService.recordAdImpression(adId);
    }
  }
}

