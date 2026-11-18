import 'article_model.dart';

class EpaperPageModel {
  final int id;
  final int pageNumber;
  final String pageTitle;
  final String pageImage;
  final String? pdfUrl;

  EpaperPageModel({
    required this.id,
    required this.pageNumber,
    required this.pageTitle,
    required this.pageImage,
    this.pdfUrl,
  });

  factory EpaperPageModel.fromJson(Map<String, dynamic> json) {
    return EpaperPageModel(
      id: json['id'] ?? 0,
      pageNumber: json['page_number'] ?? 1,
      pageTitle: json['page_title'] ?? 'Page ${json['page_number'] ?? 1}',
      pageImage: ArticleModel.sanitizeUrl(json['page_image']) ?? '',
      pdfUrl: ArticleModel.sanitizeUrl(json['pdf_url']),
    );
  }

  Map<String, dynamic> toJson() => {
    'id': id,
    'page_number': pageNumber,
    'page_title': pageTitle,
    'page_image': pageImage,
    'pdf_url': pdfUrl,
  };
}

class EpaperEditionModel {
  final int id;
  final String title;
  final String slug;
  final String editionDate;
  final String formattedDate;
  final String coverImage;
  final String? pdfFile;
  final int totalPages;
  final int viewsCount;
  final int downloadsCount;
  final String? cityName;
  final List<EpaperPageModel> pages;

  EpaperEditionModel({
    required this.id,
    required this.title,
    required this.slug,
    required this.editionDate,
    required this.formattedDate,
    required this.coverImage,
    this.pdfFile,
    required this.totalPages,
    this.viewsCount = 0,
    this.downloadsCount = 0,
    this.cityName,
    this.pages = const [],
  });

  factory EpaperEditionModel.fromJson(Map<String, dynamic> json) {
    var rawPages = json['pages'] as List<dynamic>? ?? [];
    List<EpaperPageModel> parsedPages = rawPages
        .map((p) => EpaperPageModel.fromJson(p as Map<String, dynamic>))
        .toList();

    return EpaperEditionModel(
      id: json['id'] ?? 0,
      title: json['title'] ?? 'Digital E-Paper',
      slug: json['slug'] ?? '',
      editionDate: json['edition_date'] ?? '',
      formattedDate: json['formatted_date'] ?? '',
      coverImage: ArticleModel.sanitizeUrl(json['cover_image']) ?? '',
      pdfFile: ArticleModel.sanitizeUrl(json['pdf_file']),
      totalPages: json['total_pages'] ?? parsedPages.length,
      viewsCount: json['views_count'] ?? 0,
      downloadsCount: json['downloads_count'] ?? 0,
      cityName: json['city'] != null ? json['city']['name'] : null,
      pages: parsedPages,
    );
  }

  Map<String, dynamic> toJson() => {
    'id': id,
    'title': title,
    'slug': slug,
    'edition_date': editionDate,
    'formatted_date': formattedDate,
    'cover_image': coverImage,
    'pdf_file': pdfFile,
    'total_pages': totalPages,
    'views_count': viewsCount,
    'downloads_count': downloadsCount,
    'pages': pages.map((p) => p.toJson()).toList(),
  };
}
