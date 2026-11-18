import 'article_model.dart';

class AdvertisementModel {
  final int id;
  final String title;
  final String type; // 'image' or 'code'
  final String? imageUrl;
  final String? destinationUrl;
  final String? clickUrl;
  final String? htmlCode;
  final String placement; // 'header', 'in_feed', 'sidebar', etc.
  final int order;
  final int viewsCount;
  final int clicksCount;

  AdvertisementModel({
    required this.id,
    required this.title,
    required this.type,
    this.imageUrl,
    this.destinationUrl,
    this.clickUrl,
    this.htmlCode,
    this.placement = 'in_feed',
    this.order = 0,
    this.viewsCount = 0,
    this.clicksCount = 0,
  });

  bool get isImage => type == 'image' && imageUrl != null && imageUrl!.isNotEmpty;
  bool get isCode => type == 'code' && htmlCode != null && htmlCode!.isNotEmpty;

  factory AdvertisementModel.fromJson(Map<String, dynamic> json) {
    return AdvertisementModel(
      id: json['id'] ?? 0,
      title: json['title'] ?? 'Sponsored',
      type: json['type'] ?? 'image',
      imageUrl: ArticleModel.sanitizeUrl(json['image_url']),
      destinationUrl: json['destination_url'],
      clickUrl: json['click_url'],
      htmlCode: json['html_code'],
      placement: json['placement'] ?? 'in_feed',
      order: json['order'] ?? 0,
      viewsCount: json['views_count'] ?? 0,
      clicksCount: json['clicks_count'] ?? 0,
    );
  }

  Map<String, dynamic> toJson() => {
    'id': id,
    'title': title,
    'type': type,
    'image_url': imageUrl,
    'destination_url': destinationUrl,
    'click_url': clickUrl,
    'html_code': htmlCode,
    'placement': placement,
    'order': order,
    'views_count': viewsCount,
    'clicks_count': clicksCount,
  };
}

