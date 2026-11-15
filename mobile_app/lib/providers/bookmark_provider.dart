import 'package:flutter/material.dart';
import '../models/article_model.dart';
import '../services/bookmark_service.dart';

class BookmarkProvider with ChangeNotifier {
  List<ArticleModel> _bookmarks = [];
  bool _isLoading = true;

  List<ArticleModel> get bookmarks => _bookmarks;
  bool get isLoading => _isLoading;

  BookmarkProvider() {
    loadBookmarks();
  }

  Future<void> loadBookmarks() async {
    _isLoading = true;
    notifyListeners();

    _bookmarks = await BookmarkService.getBookmarks();
    _isLoading = false;
    notifyListeners();
  }

  bool isBookmarked(int articleId) {
    return _bookmarks.any((item) => item.id == articleId);
  }

  Future<bool> toggleBookmark(ArticleModel article) async {
    final isAdded = await BookmarkService.toggleBookmark(article);
    await loadBookmarks();
    return isAdded;
  }
}
