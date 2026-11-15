import 'dart:convert';
import 'package:shared_preferences/shared_preferences.dart';
import '../config/app_constants.dart';
import '../models/article_model.dart';

class BookmarkService {
  static Future<List<ArticleModel>> getBookmarks() async {
    final prefs = await SharedPreferences.getInstance();
    final jsonList = prefs.getStringList(AppConstants.keyBookmarks) ?? [];
    return jsonList.map((str) => ArticleModel.fromJson(json.decode(str))).toList();
  }

  static Future<bool> isBookmarked(int articleId) async {
    final list = await getBookmarks();
    return list.any((item) => item.id == articleId);
  }

  static Future<bool> toggleBookmark(ArticleModel article) async {
    final prefs = await SharedPreferences.getInstance();
    final list = await getBookmarks();
    final index = list.indexWhere((item) => item.id == article.id);

    if (index >= 0) {
      list.removeAt(index);
    } else {
      list.insert(0, article);
    }

    final encodedList = list.map((item) => json.encode(item.toJson())).toList();
    await prefs.setStringList(AppConstants.keyBookmarks, encodedList);

    return index < 0; // True if newly added, False if removed
  }
}
