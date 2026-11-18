import 'dart:convert';
import 'package:flutter/foundation.dart';
import 'package:http/http.dart' as http;
import '../config/app_config.dart';
import '../models/advertisement_model.dart';
import '../models/app_config_model.dart';
import '../models/article_model.dart';
import '../models/category_model.dart';
import '../models/comment_model.dart';
import '../models/location_model.dart';
import '../models/user_model.dart';
import 'auth_service.dart';

class ApiService {
  final String baseUrl;
  String currentLocale;

  ApiService({
    String? baseUrl,
    this.currentLocale = AppConfig.defaultLocale,
  }) : baseUrl = baseUrl ?? (kIsWeb ? '${Uri.base.origin}/api/v1' : AppConfig.apiBaseUrl);

  Future<Map<String, String>> _getHeaders({bool requireAuth = false}) async {
    final headers = {
      'Accept': 'application/json',
      'X-App-Locale': currentLocale,
    };

    if (requireAuth) {
      final token = await AuthService.getToken();
      if (token != null && token.isNotEmpty) {
        headers['Authorization'] = 'Bearer $token';
      }
    }

    return headers;
  }

  /// 1. Fetch App Configuration & Branding
  Future<AppConfigModel> fetchConfig() async {
    final response = await http.get(
      Uri.parse('$baseUrl/config'),
      headers: await _getHeaders(),
    );

    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      return AppConfigModel.fromJson(data['data']);
    }
    throw Exception('Failed to load app configuration: ${response.statusCode}');
  }

  /// 2. Fetch Articles with Filters
  Future<List<ArticleModel>> fetchArticles({
    int page = 1,
    int perPage = 12,
    String? categorySlug,
    String? citySlug,
    String? stateSlug,
    String? type,
    String? search,
    String? tag,
  }) async {
    final queryParams = {
      'page': page.toString(),
      'per_page': perPage.toString(),
    };

    if (categorySlug != null && categorySlug.isNotEmpty) {
      queryParams['category'] = categorySlug;
    }
    if (citySlug != null && citySlug.isNotEmpty) {
      queryParams['city'] = citySlug;
    }
    if (stateSlug != null && stateSlug.isNotEmpty) {
      queryParams['state'] = stateSlug;
    }
    if (type != null && type.isNotEmpty) {
      queryParams['type'] = type;
    }
    if (search != null && search.isNotEmpty) {
      queryParams['search'] = search;
    }
    if (tag != null && tag.isNotEmpty) {
      queryParams['tag'] = tag;
    }

    final uri = Uri.parse('$baseUrl/articles').replace(queryParameters: queryParams);
    final response = await http.get(uri, headers: await _getHeaders());

    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      final list = data['data'] as List<dynamic>? ?? [];
      return list.map((json) => ArticleModel.fromJson(json)).toList();
    }
    throw Exception('Failed to load articles');
  }

  /// 3. Fetch Breaking News
  Future<List<ArticleModel>> fetchBreakingNews() async {
    final response = await http.get(
      Uri.parse('$baseUrl/articles/breaking'),
      headers: await _getHeaders(),
    );

    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      final list = data['data'] as List<dynamic>? ?? [];
      return list.map((json) => ArticleModel.fromJson(json)).toList();
    }
    return [];
  }

  /// 4. Fetch Trending News
  Future<List<ArticleModel>> fetchTrendingNews() async {
    final response = await http.get(
      Uri.parse('$baseUrl/articles/trending'),
      headers: await _getHeaders(),
    );

    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      final list = data['data'] as List<dynamic>? ?? [];
      return list.map((json) => ArticleModel.fromJson(json)).toList();
    }
    return [];
  }

  /// 5. Fetch Featured News
  Future<List<ArticleModel>> fetchFeaturedNews() async {
    final response = await http.get(
      Uri.parse('$baseUrl/articles/featured'),
      headers: await _getHeaders(),
    );

    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      final list = data['data'] as List<dynamic>? ?? [];
      return list.map((json) => ArticleModel.fromJson(json)).toList();
    }
    return [];
  }

  /// 6. Fetch Video Bulletins
  Future<List<ArticleModel>> fetchVideos({int page = 1, int perPage = 10}) async {
    final uri = Uri.parse('$baseUrl/articles/videos').replace(queryParameters: {
      'page': page.toString(),
      'per_page': perPage.toString(),
    });
    final response = await http.get(uri, headers: await _getHeaders());

    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      final list = data['data'] as List<dynamic>? ?? [];
      return list.map((json) => ArticleModel.fromJson(json)).toList();
    }
    return [];
  }

  /// 7. Fetch Article Details & Related News
  Future<Map<String, dynamic>> fetchArticleDetail(String slug) async {
    final response = await http.get(
      Uri.parse('$baseUrl/articles/$slug'),
      headers: await _getHeaders(),
    );

    if (response.statusCode == 200) {
      final data = json.decode(response.body)['data'];
      final article = ArticleModel.fromJson(data['article']);
      final related = (data['related'] as List<dynamic>? ?? [])
          .map((json) => ArticleModel.fromJson(json))
          .toList();

      return {
        'article': article,
        'related': related,
      };
    }
    throw Exception('Article not found');
  }

  /// 8. Fetch Categories
  Future<List<CategoryModel>> fetchCategories() async {
    final response = await http.get(
      Uri.parse('$baseUrl/categories'),
      headers: await _getHeaders(),
    );

    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      final list = data['data'] as List<dynamic>? ?? [];
      return list.map((json) => CategoryModel.fromJson(json)).toList();
    }
    return [];
  }

  /// 9. Fetch Hyperlocal Locations (States & Cities)
  Future<List<StateModel>> fetchStates() async {
    final response = await http.get(
      Uri.parse('$baseUrl/locations/states'),
      headers: await _getHeaders(),
    );

    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      final list = data['data'] as List<dynamic>? ?? [];
      return list.map((json) => StateModel.fromJson(json)).toList();
    }
    return [];
  }

  Future<List<CityModel>> fetchCities({String? stateSlug}) async {
    final uri = Uri.parse('$baseUrl/locations/cities').replace(
      queryParameters: stateSlug != null ? {'state_slug': stateSlug} : null,
    );
    final response = await http.get(uri, headers: await _getHeaders());

    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      final list = data['data'] as List<dynamic>? ?? [];
      return list.map((json) => CityModel.fromJson(json)).toList();
    }
    return [];
  }

  /// 10. Fetch Comments & Post Comment
  Future<List<CommentModel>> fetchComments(String slug) async {
    final response = await http.get(
      Uri.parse('$baseUrl/articles/$slug/comments'),
      headers: await _getHeaders(),
    );

    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      final list = data['data'] as List<dynamic>? ?? [];
      return list.map((json) => CommentModel.fromJson(json)).toList();
    }
    return [];
  }

  Future<bool> postComment(String slug, String userName, String email, String comment) async {
    final response = await http.post(
      Uri.parse('$baseUrl/articles/$slug/comments'),
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
      },
      body: json.encode({
        'user_name': userName,
        'user_email': email,
        'comment': comment,
      }),
    );

    return response.statusCode == 201;
  }

  /// 11. Submit Citizen Journalism Tip
  Future<bool> submitCitizenTip({
    required String title,
    required String description,
    String? locationName,
    int? stateId,
    int? cityId,
    String? witnessName,
    String? witnessPhone,
    bool isAnonymous = false,
    List<int>? mediaBytes,
    String? mediaName,
  }) async {
    final request = http.MultipartRequest('POST', Uri.parse('$baseUrl/citizen-tips'));
    request.headers.addAll(await _getHeaders());

    request.fields['title'] = title;
    request.fields['description'] = description;
    if (locationName != null) request.fields['location_name'] = locationName;
    if (stateId != null) request.fields['state_id'] = stateId.toString();
    if (cityId != null) request.fields['city_id'] = cityId.toString();
    if (witnessName != null) request.fields['witness_name'] = witnessName;
    if (witnessPhone != null) request.fields['witness_phone'] = witnessPhone;
    request.fields['is_anonymous'] = isAnonymous ? '1' : '0';

    if (mediaBytes != null && mediaName != null) {
      request.files.add(http.MultipartFile.fromBytes('media', mediaBytes, filename: mediaName));
    }

    final streamedResponse = await request.send();
    return streamedResponse.statusCode == 201;
  }

  /// 12. Subscribe Newsletter
  Future<bool> subscribeNewsletter(String email) async {
    final response = await http.post(
      Uri.parse('$baseUrl/newsletter/subscribe'),
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
      },
      body: json.encode({'email': email}),
    );
    return response.statusCode == 200;
  }

  /// 13. Auth: Login & Register
  Future<Map<String, dynamic>> login(String email, String password, {String? deviceName}) async {
    final response = await http.post(
      Uri.parse('$baseUrl/auth/login'),
      headers: {'Accept': 'application/json', 'Content-Type': 'application/json'},
      body: json.encode({
        'email': email,
        'password': password,
        'device_name': deviceName ?? 'Flutter Mobile App',
      }),
    );

    final data = json.decode(response.body);
    if (response.statusCode == 200) {
      final token = data['data']['token'];
      final user = UserModel.fromJson(data['data']['user']);
      await AuthService.saveAuth(token, user);
      return {'success': true, 'user': user};
    }
    return {'success': false, 'message': data['message'] ?? 'Login failed'};
  }

  Future<Map<String, dynamic>> register(String name, String email, String password, {String? phone}) async {
    final response = await http.post(
      Uri.parse('$baseUrl/auth/register'),
      headers: {'Accept': 'application/json', 'Content-Type': 'application/json'},
      body: json.encode({
        'name': name,
        'email': email,
        'password': password,
        'phone': phone,
        'device_name': 'Flutter Mobile App',
      }),
    );

    final data = json.decode(response.body);
    if (response.statusCode == 201) {
      final token = data['data']['token'];
      final user = UserModel.fromJson(data['data']['user']);
      await AuthService.saveAuth(token, user);
      return {'success': true, 'user': user};
    }
    return {'success': false, 'message': data['message'] ?? 'Registration failed'};
  }

  /// 14. Advertisements & Sponsored Campaigns
  Future<List<AdvertisementModel>> fetchAdvertisements({String? placement}) async {
    try {
      final queryParams = placement != null ? {'placement': placement} : null;
      final uri = Uri.parse('$baseUrl/ads').replace(queryParameters: queryParams);
      final response = await http.get(uri, headers: await _getHeaders());

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        final list = data['data'] as List<dynamic>? ?? [];
        return list.map((json) => AdvertisementModel.fromJson(json)).toList();
      }
    } catch (e) {
      debugPrint('Error fetching advertisements: $e');
    }
    return [];
  }

  /// Record Ad Impression Analytics (Views Count)
  Future<bool> recordAdImpression(int id) async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/ads/$id/impression'),
        headers: await _getHeaders(),
      );
      return response.statusCode == 200;
    } catch (e) {
      debugPrint('Error recording ad impression: $e');
      return false;
    }
  }

  /// Record Ad Click Analytics (Clicks Count)
  Future<String?> recordAdClick(int id) async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/ads/$id/click'),
        headers: await _getHeaders(),
      );
      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        return data['data']?['destination_url'] as String?;
      }
    } catch (e) {
      debugPrint('Error recording ad click: $e');
    }
    return null;
  }
}

