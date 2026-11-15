import '../config/app_config.dart';

class ArticleModel {
  final int id;
  final String title;
  final String slug;
  final String? excerpt;
  final String? content;
  final String? cleanText;
  final String? featuredImage;
  final String? imageCaption;
  final bool hasVideo;
  final String videoType;
  final String? videoUrl;
  final String? youtubeId;
  final String? youtubeEmbedUrl;
  final ArticleCategory? category;
  final ArticleLocation? location;
  final ArticleAuthor? author;
  final bool isBreaking;
  final bool isFeatured;
  final bool isTrending;
  final int viewsCount;
  final int readingTime;
  final String? publishedAt;
  final String? publishedAtHuman;
  final String? shareUrl;
  final ArticleCustomAd? customAd;
  final List<String> tags;

  ArticleModel({
    required this.id,
    required this.title,
    required this.slug,
    this.excerpt,
    this.content,
    this.cleanText,
    this.featuredImage,
    this.imageCaption,
    this.hasVideo = false,
    this.videoType = 'none',
    this.videoUrl,
    this.youtubeId,
    this.youtubeEmbedUrl,
    this.category,
    this.location,
    this.author,
    this.isBreaking = false,
    this.isFeatured = false,
    this.isTrending = false,
    this.viewsCount = 0,
    this.readingTime = 1,
    this.publishedAt,
    this.publishedAtHuman,
    this.shareUrl,
    this.customAd,
    this.tags = const [],
  });

  static String? sanitizeUrl(String? url) {
    if (url == null || url.isEmpty) return url;
    if (url.contains('localhost:8000') || url.contains('127.0.0.1:8000')) {
      try {
        final baseUri = Uri.parse(AppConfig.apiBaseUrl);
        final host = baseUri.host;
        final port = baseUri.port;
        final hostWithPort = port != 80 && port != 443 && port != 0 ? '$host:$port' : host;
        return url
            .replaceAll('localhost:8000', hostWithPort)
            .replaceAll('127.0.0.1:8000', hostWithPort);
      } catch (_) {
        return url;
      }
    }
    return url;
  }

  factory ArticleModel.fromJson(Map<String, dynamic> json) {
    return ArticleModel(
      id: json['id'] ?? 0,
      title: json['title'] ?? '',
      slug: json['slug'] ?? '',
      excerpt: json['excerpt'],
      content: json['content'],
      cleanText: json['clean_text'],
      featuredImage: sanitizeUrl(json['featured_image']),
      imageCaption: json['image_caption'],
      hasVideo: json['has_video'] ?? false,
      videoType: json['video_type'] ?? 'none',
      videoUrl: json['video_url'],
      youtubeId: json['youtube_id'],
      youtubeEmbedUrl: json['youtube_embed_url'],
      category: json['category'] != null ? ArticleCategory.fromJson(json['category']) : null,
      location: json['location'] != null ? ArticleLocation.fromJson(json['location']) : null,
      author: json['author'] != null ? ArticleAuthor.fromJson(json['author']) : null,
      isBreaking: json['is_breaking'] ?? false,
      isFeatured: json['is_featured'] ?? false,
      isTrending: json['is_trending'] ?? false,
      viewsCount: json['views_count'] ?? 0,
      readingTime: json['reading_time'] ?? 1,
      publishedAt: json['published_at'],
      publishedAtHuman: json['published_at_human'],
      shareUrl: sanitizeUrl(json['share_url']),
      customAd: json['custom_ad'] != null ? ArticleCustomAd.fromJson(json['custom_ad']) : null,
      tags: (json['tags'] as List<dynamic>?)
              ?.map((t) => t is Map ? (t['name'] ?? '').toString() : t.toString())
              .toList() ??
          [],
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'title': title,
      'slug': slug,
      'excerpt': excerpt,
      'content': content,
      'clean_text': cleanText,
      'featured_image': featuredImage,
      'image_caption': imageCaption,
      'has_video': hasVideo,
      'video_type': videoType,
      'video_url': videoUrl,
      'youtube_id': youtubeId,
      'youtube_embed_url': youtubeEmbedUrl,
      'category': category?.toJson(),
      'location': location?.toJson(),
      'author': author?.toJson(),
      'is_breaking': isBreaking,
      'is_featured': isFeatured,
      'is_trending': isTrending,
      'views_count': viewsCount,
      'reading_time': readingTime,
      'published_at': publishedAt,
      'published_at_human': publishedAtHuman,
      'share_url': shareUrl,
      'custom_ad': customAd?.toJson(),
      'tags': tags,
    };
  }
}

class ArticleCategory {
  final int id;
  final String name;
  final String slug;
  final String color;

  ArticleCategory({
    required this.id,
    required this.name,
    required this.slug,
    required this.color,
  });

  factory ArticleCategory.fromJson(Map<String, dynamic> json) {
    return ArticleCategory(
      id: json['id'] ?? 0,
      name: json['name'] ?? '',
      slug: json['slug'] ?? '',
      color: json['color'] ?? '#E11D48',
    );
  }

  Map<String, dynamic> toJson() => {
    'id': id,
    'name': name,
    'slug': slug,
    'color': color,
  };
}

class ArticleLocation {
  final String? badge;
  final String? state;
  final String? city;

  ArticleLocation({this.badge, this.state, this.city});

  factory ArticleLocation.fromJson(Map<String, dynamic> json) {
    return ArticleLocation(
      badge: json['badge'],
      state: json['state'],
      city: json['city'],
    );
  }

  Map<String, dynamic> toJson() => {
    'badge': badge,
    'state': state,
    'city': city,
  };
}

class ArticleAuthor {
  final int id;
  final String name;
  final String? designation;
  final String? avatar;

  ArticleAuthor({
    required this.id,
    required this.name,
    this.designation,
    this.avatar,
  });

  factory ArticleAuthor.fromJson(Map<String, dynamic> json) {
    return ArticleAuthor(
      id: json['id'] ?? 0,
      name: json['name'] ?? '',
      designation: json['designation'],
      avatar: ArticleModel.sanitizeUrl(json['avatar']),
    );
  }

  Map<String, dynamic> toJson() => {
    'id': id,
    'name': name,
    'designation': designation,
    'avatar': avatar,
  };
}

class ArticleCustomAd {
  final String type;
  final String? title;
  final String? image;
  final String? link;
  final String? htmlCode;

  ArticleCustomAd({
    required this.type,
    this.title,
    this.image,
    this.link,
    this.htmlCode,
  });

  factory ArticleCustomAd.fromJson(Map<String, dynamic> json) {
    return ArticleCustomAd(
      type: json['type'] ?? 'image',
      title: json['title'],
      image: json['image'],
      link: json['link'],
      htmlCode: json['html_code'],
    );
  }

  Map<String, dynamic> toJson() => {
    'type': type,
    'title': title,
    'image': image,
    'link': link,
    'html_code': htmlCode,
  };
}

